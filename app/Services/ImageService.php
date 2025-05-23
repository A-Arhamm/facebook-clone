<?php

namespace App\Services;

use Intervention\Image\Facades\Image;

class ImageService
{
    public function updateImage($model, $request)
    {
        $image = Image::make($request->file('image'));

        if (!empty($model->image)) {
            $currentImage = public_path() . $model->image;

            if (file_exists($currentImage) && $currentImage != public_path() . '/images/user-placeholder.png') {
                unlink($currentImage);
            }
        }

        $file = $request->file('image');
        $ext = $file->getClientOriginalExtension();

        if ($request->width) {
            $image->crop(
                $request->width,
                $request->height,
                $request->left,
                $request->top
            );
        }

        $name = time() . '.' . $ext;
        $image->save(public_path() . '/images/' . $name);
        $model->image = '/images/' . $name;

        return $model;
    }
}