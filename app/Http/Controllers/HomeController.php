<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Doctor;
use App\Models\Hospital;
use App\Models\News;
use App\Models\Package;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        return view('home.index', [
            'hospitals' => Hospital::query()->where('is_active', true)->orderBy('sort_order')->take(6)->get(),
            'departments' => Department::query()->where('is_active', true)->orderByDesc('is_featured')->orderBy('sort_order')->take(8)->get(),
            'doctors' => Doctor::query()->with(['departments', 'hospitals'])->where('is_active', true)->where('is_featured', true)->orderBy('sort_order')->take(10)->get(),
            'packages' => Package::query()->where('is_active', true)->where('is_featured', true)->orderBy('sort_order')->take(4)->get(),
            'news' => News::query()->where('is_published', true)->whereNotNull('published_at')->where('published_at', '<=', now())->latest('published_at')->take(4)->get(),
            'diagnosticUrl' => \App\Models\SiteSetting::get('diagnostics_url', '#'),
        ]);
    }

    public function search(Request $request): View
    {
        $validated = $request->validate([
            'q' => ['nullable', 'string', 'max:100'],
            'type' => ['nullable', 'in:all,doctor,department,hospital,package,news'],
        ]);

        $term = trim($validated['q'] ?? '');
        $type = $validated['type'] ?? 'all';
        $results = collect();

        if ($term !== '') {
            if (in_array($type, ['all', 'doctor'], true)) {
                $results = $results->concat(Doctor::query()->where('is_active', true)->where('name', 'like', "%{$term}%")->limit(20)->get()->map(fn ($item) => ['type' => 'Doctor', 'title' => $item->name, 'description' => $item->designation, 'url' => '#']));
            }
            if (in_array($type, ['all', 'department'], true)) {
                $results = $results->concat(Department::query()->where('is_active', true)->where('name', 'like', "%{$term}%")->limit(20)->get()->map(fn ($item) => ['type' => 'Department', 'title' => $item->name, 'description' => $item->short_description, 'url' => '#']));
            }
            if (in_array($type, ['all', 'hospital'], true)) {
                $results = $results->concat(Hospital::query()->where('is_active', true)->where('name', 'like', "%{$term}%")->limit(20)->get()->map(fn ($item) => ['type' => 'Hospital', 'title' => $item->name, 'description' => $item->address, 'url' => '#']));
            }
            if (in_array($type, ['all', 'package'], true)) {
                $results = $results->concat(Package::query()->where('is_active', true)->where('name', 'like', "%{$term}%")->limit(20)->get()->map(fn ($item) => ['type' => 'Package', 'title' => $item->name, 'description' => $item->short_description, 'url' => '#']));
            }
            if (in_array($type, ['all', 'news'], true)) {
                $results = $results->concat(News::query()->where('is_published', true)->where('title', 'like', "%{$term}%")->limit(20)->get()->map(fn ($item) => ['type' => 'News', 'title' => $item->title, 'description' => $item->excerpt, 'url' => '#']));
            }
        }

        return view('search.index', compact('term', 'type', 'results'));
    }

    public function switchLanguage(string $locale)
    {
        abort_unless(in_array($locale, ['en', 'bn'], true), 404);
        session(['locale' => $locale]);
        app()->setLocale($locale);
        return redirect()->back();
    }
}
