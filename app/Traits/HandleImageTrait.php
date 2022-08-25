<?php

namespace App\Traits;

use Image;

trait HandleImageTrait
{
    protected string $path = 'upload/';

    /**
     * @param $request
     * @return mixed
     */
    public function verify($request)
    {
        return $request->has('image');
    }

    /**
     * @param $request
     * @return string|void
     */
    public function saveImage($request)
    {
        if($this->verify($request))
        {
            $image = $request->file('image');
            $name = time() . '.' . $image->getClientOriginalExtension();
            Image::make($image)->resize(300, 300)->save($this->path . $name);
            return $name;
        }
    }

    /**
     * @param $request
     * @param $currentImage
     * @return mixed|string|null
     */
    public function updateImage($request, $currentImage): mixed
    {
        if($this->verify($request))
        {
            $this->deleteImage($currentImage);

            return $this->saveImage($request);
        }

        return $currentImage;
    }

    /**
     * @param $imageName
     * @return void
     */
    public function deleteImage($imageName): void
    {
        if($imageName && file_exists($this->path .$imageName))
        {
            unlink($this->path .$imageName);
        }
    }
}
