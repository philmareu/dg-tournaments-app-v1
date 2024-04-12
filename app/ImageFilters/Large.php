<?php

namespace DGTournaments\ImageFilters;


use Intervention\Image\Filters\FilterInterface;

class Large implements FilterInterface
{

    /**
     * Applies filter to given image
     *
     * @param  \Intervention\Image\Image $image
     * @return \Intervention\Image\Image
     */
    public function applyFilter(\Intervention\Image\Image $image)
    {
        return $image->resize(1200, null, function ($constraint) {
            $constraint->aspectRatio();
        });
    }
}