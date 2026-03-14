<?php

namespace App\Http\Repository;

use App\Models\Category;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;

class CategoryRepository
{
    public static function findBySlug(string $slug)
    {
        return Cache::remember('category_slug_' . $slug, Carbon::now()->addHours(1), function () use ($slug) {
            return Category::where('slug', $slug)->first();
        });
    }
}
