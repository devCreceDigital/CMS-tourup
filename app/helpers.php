<?php

use App\Models\MediaImage;

if (!function_exists('generate_unique_slug')) {
    function generate_unique_slug(string $modelClass, string $name, ?int $ignoreId = null, string $column = 'slug'): string
    {
        $slug = Str::slug($name);
        $original = $slug;
        $counter = 1;

        while (true) {
            $query = $modelClass::where($column, $slug);
            if ($ignoreId) {
                $query->where('id', '!=', $ignoreId);
            }
            if (!$query->exists()) {
                break;
            }
            $slug = $original . '-' . $counter;
            $counter++;
        }

        return $slug;
    }
}

if (!function_exists('theme_image')) {
    function theme_image(string $key, string $default = ''): string
    {
        $media = MediaImage::where('key', $key)->first();

        if ($media) {
            return asset('storage/' . $media->file_path);
        }

        if ($default && file_exists(public_path($default))) {
            return asset($default);
        }

        return $default ? asset($default) : '';
    }
}
