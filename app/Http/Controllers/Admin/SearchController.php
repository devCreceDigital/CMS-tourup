<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Trip;
use App\Models\Traveler;
use App\Models\BlogPost;
use App\Models\Faq;
use App\Models\Program;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $query = $request->get('q', '');

        if (strlen(trim($query)) < 2) {
            return view('admin.search.index', [
                'query' => $query,
                'results' => [],
                'total' => 0,
            ]);
        }

        $results = [];
        $total = 0;

        $trips = Trip::where('name', 'like', "%{$query}%")
            ->orWhere('reference', 'like', "%{$query}%")
            ->get();
        if ($trips->count() > 0) {
            $results[] = [
                'section' => 'Viajes',
                'icon' => 'fa-suitcase',
                'items' => $trips->map(function ($t) {
                    return [
                        'title' => $t->name . ' (' . $t->reference . ')',
                        'url' => route('admin.trips.edit', $t->id),
                    ];
                }),
            ];
            $total += $trips->count();
        }

        $travelers = Traveler::where('full_name', 'like', "%{$query}%")
            ->orWhere('dni', 'like', "%{$query}%")
            ->get();
        if ($travelers->count() > 0) {
            $results[] = [
                'section' => 'Viajeros',
                'icon' => 'fa-users',
                'items' => $travelers->map(function ($t) {
                    return [
                        'title' => $t->full_name . ' (DNI: ' . $t->dni . ')',
                        'url' => route('admin.travelers.show', $t->id),
                    ];
                }),
            ];
            $total += $travelers->count();
        }

        $posts = BlogPost::where('title', 'like', "%{$query}%")->get();
        if ($posts->count() > 0) {
            $results[] = [
                'section' => 'Blog',
                'icon' => 'fa-newspaper',
                'items' => $posts->map(function ($p) {
                    return [
                        'title' => $p->title,
                        'url' => route('admin.blog-posts.edit', $p->id),
                    ];
                }),
            ];
            $total += $posts->count();
        }

        $faqs = Faq::where('question', 'like', "%{$query}%")
            ->orWhere('answer', 'like', "%{$query}%")
            ->get();
        if ($faqs->count() > 0) {
            $results[] = [
                'section' => 'FAQ',
                'icon' => 'fa-question-circle',
                'items' => $faqs->map(function ($f) {
                    return [
                        'title' => $f->question,
                        'url' => route('admin.faqs.index'),
                    ];
                }),
            ];
            $total += $faqs->count();
        }

        $programs = Program::where('name', 'like', "%{$query}%")->get();
        if ($programs->count() > 0) {
            $results[] = [
                'section' => 'Programas',
                'icon' => 'fa-layer-group',
                'items' => $programs->map(function ($p) {
                    return [
                        'title' => $p->name,
                        'url' => route('admin.programs.edit', $p->id),
                    ];
                }),
            ];
            $total += $programs->count();
        }

        return view('admin.search.index', compact('query', 'results', 'total'));
    }
}
