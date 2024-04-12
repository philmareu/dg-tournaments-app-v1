<?php

namespace DGTournaments\Repositories;

use Carbon\Carbon;
use DGTournaments\Models\Post;
use DGTournaments\Models\PostCategory;
use Illuminate\Support\Facades\DB;

class BlogRepository extends EloquentRepository {

    protected $category;

    public function __construct(Post $model, PostCategory $category)
    {
        parent::__construct($model);
        $this->category = $category;
    }

    public function getRecent($limit = 4)
    {
        return $this->model->orderBy('posted_at', 'desc')
            ->wherePublished(1)
            ->where('posted_at', '<', Carbon::now())
            ->limit($limit)
            ->get();
    }

    public function getPaginated($limit = 10)
    {
        return $this->model->orderBy('posted_at', 'desc')
            ->with('category')
            ->where('posted_at', '<', Carbon::now())
            ->wherePublished(1)
            ->paginate($limit);
    }

    public function getDates()
    {
        $dates = $this->model->wherePublished(1)
            ->get(['posted_at']);

        return $dates;
    }

    public function getPostForPage($year, $month, $day, $slug)
    {
        return $this->model
            ->wherePublished(1)
            ->where('posted_at', 'LIKE', "$year-$month-$day%")
            ->whereSlug($slug)
            ->first();
    }

    public function getPostsByCategory($category)
    {
        $cat = $this->category->whereSlug($category)->first();

        $postIds = DB::table('post_post_category')
            ->where('post_category_id', $cat->id)
            ->lists('post_id');

        return $this->model->whereIn('id', $postIds)->get();
    }

    public function getPostsByMonth($year, $month)
    {
        return $this->model
            ->wherePublished(1)
            ->where('posted_at', 'LIKE', "$year-$month-%")
            ->get();
    }

    public function getForPreview($id)
    {
        return $this->model->find($id);
    }
}