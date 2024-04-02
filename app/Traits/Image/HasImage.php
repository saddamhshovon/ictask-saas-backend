<?php

namespace App\Traits\Image;

trait HasImage
{
    private function saveImage(
        ?string $prefix,
        string $name,
        \Illuminate\Http\UploadedFile $image,
        ?int $other,
        string $storageLocation,
        string $publicLocation,
    ): array {
        $name = str_replace(
            [' ', '%', ':'],
            '',
            $prefix.'-'.$name.($other ? '-'.$other : '')
        ).'.'.$image->extension();

        $image->storeAs($storageLocation, $name);

        return [
            'storage' => $storageLocation.'/'.$name,
            'public' => $publicLocation.'/'.$name,
        ];
    }
}
