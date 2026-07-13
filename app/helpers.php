<?php

use App\Models\MediaImage;

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
