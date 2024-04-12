<?php
/**
 * Created by PhpStorm.
 * User: philmareu
 * Date: 11/28/17
 * Time: 11:08 AM
 */

namespace DGTournaments\ImageFilters;


use Intervention\Image\Filters\FilterInterface;

class Small implements FilterInterface
{
    /**
     * Applies filter to given image
     *
     * @param  \Intervention\Image\Image $image
     * @return \Intervention\Image\Image
     */
    public function applyFilter(\Intervention\Image\Image $image)
    {
        return $image->resize(600, null, function ($constraint) {
            $constraint->aspectRatio();
        });
    }
}