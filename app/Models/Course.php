<?php

namespace DGTournaments\Models;

use DGTournaments\Models\Activity;
use DGTournaments\Models\DataSource;
use DGTournaments\Models\Tournament;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Course extends Model
{
    use SoftDeletes;

    protected $casts = [
        'length' => 'integer',
        'latitude' => 'float',
        'longitude' => 'float'
    ];

    protected $fillable = [
        'name',
        'slug',
        'address',
        'address_2',
        'city',
        'state_province',
        'country',
        'description',
        'directions',
        'length',
        'latitude',
        'longitude'
    ];

    public function tournaments()
    {
        return $this->belongsToMany(Tournament::class, 'tournaments_course', 'course_id');
    }

    public function activities()
    {
        return $this->morphMany(Activity::class, 'resource')->latest();
    }

    public function dataSource()
    {
        return $this->belongsTo(DataSource::class);
    }
}
