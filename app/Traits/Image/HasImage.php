<?php

namespace App\Traits\Image;

trait HasImage
{
    private function saveImage(
        ?string $prefix,
        string $name,
        \Illuminate\Http\UploadedFile $image,
        ?int $other,
        string $directory,
    ): string {
        $name = str_replace(
            [' ', '%', ':'],
            '',
            $prefix.'-'.$name.($other ? '-'.$other : '')
        ).'.'.$image->extension();

        $storageLocation = 'public/'.$directory;
        $publicLocation = 'storage/'.$directory;

        $image->storeAs($storageLocation, $name);

        return $publicLocation.'/'.$name;
    }
}
