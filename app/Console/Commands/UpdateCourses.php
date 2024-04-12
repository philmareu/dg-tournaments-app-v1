<?php

namespace DGTournaments\Console\Commands;

use DGTournaments\Models\Course;
use DGTournaments\Models\DataSource;
use DGTournaments\Services\API\CourseApi;
use DGTournaments\Services\API\Payloads\CourseDataPayload;
use DGTournaments\Services\API\Responses\CoursesResponse;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class UpdateCourses extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'courses:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update courses from data sources';
    
    protected $course;
    
    protected $dataSource;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(Course $course, DataSource $dataSource)
    {
        parent::__construct();
        $this->course = $course;
        $this->dataSource = $dataSource;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->dataSource->whereType('course')->get()->each(function(DataSource $dataSource) {
            $this->info('Data Source: ' . $dataSource->title);
            $freshListOfCourses = $this->getCourses($dataSource)->getPayloads();
            $existingCourses = $dataSource->courses;

            $this->info($freshListOfCourses->count() . ' total');
            $this->info('Removing unlisted courses');
            $this->removeUnlisted($existingCourses, $freshListOfCourses);

            $this->info('Updating existing courses');
            $this->updateExisting($freshListOfCourses);

            $this->info('Creating new courses');
            $this->createNew($dataSource, $freshListOfCourses);
        });
    }

    /**
     * @param DataSource $dataSource
     */
    private function getCourses(DataSource $dataSource) : CoursesResponse
    {
        if (Cache::has($dataSource->slug . '.api.courses')) {
            dump('From cache ... ');
            $courses = Cache::get($dataSource->slug . '.api.courses');
        } else {
            $courses = CourseApi::make($dataSource)->getCourses();
            Cache::put($dataSource->slug . '.api.courses', $courses, 120);
        }

        return $courses;
    }

    /**
     * @param $existingCourses
     * @param $freshListOfCourses
     */
    private function removeUnlisted($existingCourses, $freshListOfCourses)
    {
        $existingCourses->filter(function (Course $course) use ($freshListOfCourses) {
            return $freshListOfCourses->where('id', $course->data_source_course_id)->isEmpty();
        })->each(function (Course $course) {
            $course->delete();
        });
    }

    /**
     * @param DataSource $dataSource
     * @param $freshListOfCourses
     */
    private function updateExisting(Collection $freshListOfCourses)
    {
        $currentCourses = $this->course
            ->whereIn('data_source_course_id', $freshListOfCourses->pluck('id'))
            ->withTrashed()
            ->get();

        $currentCourses->each(function(Course $currentCourse) use ($freshListOfCourses) {

            $apiCourse = $freshListOfCourses->where('id', $currentCourse->data_source_course_id)->first();

            if(! is_null($apiCourse))
            {
                $currentCourse->restore();
                $currentCourse->update(
                    array_merge(
                        [
                            'slug' => str_slug($apiCourse->get('name'))
                        ],
                        $apiCourse->all()
                    )
                );
            }
        });
    }

    /**
     * @param DataSource $dataSource
     * @param $freshListOfCourses
     */
    private function createNew(DataSource $dataSource, $freshListOfCourses)
    {
        $freshListOfCourses->filter(function (CourseDataPayload $course) {
            $existing = $this->course->where('data_source_course_id', $course->get('id'))
                ->first();

            return is_null($existing);
        })->each(function (CourseDataPayload $course) use ($dataSource) {

            $newCourse = new Course(
                array_merge(
                    [
                        'slug' => str_slug($course->get('name'))
                    ],
                    $course->toArray()
                )
            );

            $newCourse->data_source_course_id = $course->get('id');
            $newCourse->dataSource()->associate($dataSource);
            $newCourse->save();
        });
    }
    
}
