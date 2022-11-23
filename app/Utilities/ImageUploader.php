<?php


namespace App\Utilities;


use Illuminate\Http\UploadedFile;

class ImageUploader
{
    public static function upload( UploadedFile $file ): string
    {
        $filename = $file->hashName();
        $file->move( storage_path( 'uploads' ), $filename );

        return $filename;
    }

    public static function update( UploadedFile $file, $filename, string $folder ): ?string
    {
        $generated_name = static::generateName();
        // TODO: Remove file from storage before adding new one.

        return null;
    }

    private static function generateName(): string
    {
        return time();
    }

    public static function getFileURI( string $filename, string $folder ): string
    {
        return env( 'DO_URL' ) . "/$folder/$filename";
    }
}
