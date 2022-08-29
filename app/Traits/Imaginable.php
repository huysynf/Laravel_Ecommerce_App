<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Facades\Storage;
use Image;

trait Imaginable
{
    public function images()
    {
        return $this->morphMany(\App\Models\Image::class, 'imageable');
    }

    /**
     * @return Attribute
     */
    public function imagePath(): Attribute
    {
        return Attribute::make(
            get: fn() => asset(self::IMAGE_SHOW_PATH. $this?->images?->first()?->url)
        );
    }

    /**
     * @param $imageUrl
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function syncImage($imageUrl)
    {
        $this->destroyImage();
        return $this->images()->create(['url' => $imageUrl]);
    }

    /**
     * @return int
     */
    public function destroyImage(): int
    {
        return $this->images()->delete();
    }
}
