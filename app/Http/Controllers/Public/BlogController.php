<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Agency;
use App\Models\BlogPost;
use App\Models\BlogCategory;
use App\Models\Setting;

class BlogController extends Controller
{
    public function index()
    {
        if (Setting::get('blog_enabled', 'true') !== 'true') {
            abort(404);
        }

        $agency = Agency::first();
        $theme = request('preview_theme') ?: session('preview_theme') ?: ($agency->active_theme ?? 'ethos_earth');

        $posts = BlogPost::with('category')
            ->published()
            ->latest('published_at')
            ->paginate(9);

        $categories = BlogCategory::where('is_active', true)->get();

        return view("themes.{$theme}.public.blog", compact('posts', 'categories', 'agency'));
    }

    public function category(string $slug)
    {
        if (Setting::get('blog_enabled', 'true') !== 'true') {
            abort(404);
        }

        $agency = Agency::first();
        $theme = request('preview_theme') ?: session('preview_theme') ?: ($agency->active_theme ?? 'ethos_earth');

        $category = BlogCategory::where('slug', $slug)->where('is_active', true)->firstOrFail();

        $posts = BlogPost::with('category')
            ->where('blog_category_id', $category->id)
            ->published()
            ->latest('published_at')
            ->paginate(9);

        $categories = BlogCategory::where('is_active', true)->get();

        return view("themes.{$theme}.public.blog", compact('posts', 'categories', 'category', 'agency'));
    }

    public function show(string $slug)
    {
        if (Setting::get('blog_enabled', 'true') !== 'true') {
            abort(404);
        }

        $agency = Agency::first();
        $theme = request('preview_theme') ?: session('preview_theme') ?: ($agency->active_theme ?? 'ethos_earth');

        $post = BlogPost::with('category')->where('slug', $slug)->published()->firstOrFail();
        $recent = BlogPost::where('id', '!=', $post->id)->published()->latest('published_at')->take(3)->get();

        return view("themes.{$theme}.public.blog-post", compact('post', 'recent', 'agency'));
    }
}
