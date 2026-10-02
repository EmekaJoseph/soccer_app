<?php

namespace App\Support;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;

/**
 * Resizes uploaded images and stores them on the public "uploads" disk.
 * Returned paths are relative to public/, which is what the database stores.
 */
class ImageUploader
{
    public function store(UploadedFile $file, string $folder, int $maxSize = 200): string
    {
        $image = (new ImageManager(new Driver))->read($file->getRealPath());
        $image->scaleDown(width: $maxSize, height: $maxSize);

        $path = $folder.'/'.Str::lower((string) Str::ulid()).'.webp';
        Storage::disk('uploads')->put($path, (string) $image->toWebp(85));

        return $path;
    }

    public function delete(?string $path): void
    {
        // Only ever delete files inside the upload folders, never arbitrary public/ files.
        if ($path && ! str_contains($path, '..') && str_contains($path, '/')) {
            Storage::disk('uploads')->delete($path);
        }
    }

    /** Store a new image (if one was sent) and remove the one it replaces. */
    public function replace(?UploadedFile $file, ?string $current, string $folder, int $maxSize = 200): ?string
    {
        if (! $file) {
            return $current;
        }

        $path = $this->store($file, $folder, $maxSize);
        $this->delete($current);

        return $path;
    }
}
