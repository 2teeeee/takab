<?php

namespace App\Providers;

use App\Models\Category;
use App\Models\Page;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Paginator::useBootstrapFive();
        view()->share('menuCategories', Category::orderBy('id')->get());
        view()->share('footerAbout', Page::find(2));
    }
}
