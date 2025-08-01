<?php

namespace App\Providers;

use App\Models\Sys\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\ServiceProvider;

class ViewServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        View::composer('*', function ($view) {
            $subdomain = Request::getHost();
            $currentPath = Request::segment(1);

            // Ambil App berdasarkan subdomain
            $app = App::where('subdomain', $subdomain)->first();

            $menus = [];
            $page_title = null;
            $page_icon = null;

            if ($app && Auth::check()) {
                // Ambil role user pertama
                $roleId = Auth::user()->getRoleIds()->first();

                // Ambil menu yang bisa diakses user
                $menus = $app->menus()
                    ->whereHas('menu_permission.roles', function ($query) use ($roleId) {
                        $query->where('uuid', $roleId);
                    })
                    ->whereNull('parent')
                    ->get();

                // Coba cocokkan menu aktif berdasarkan URL
                $active = $app->menus()
                    ->where('url', $currentPath)
                    ->first();

                if ($active) {
                    $page_title = $active->title;
                    $page_icon = $active->icon;
                }
            }

            $view->with([
                'app' => $app,
                'menus' => $menus,
                'page_title' => $page_title ?? 'Tidak Diketahui',
                'page_icon' => $page_icon ?? 'bx bx-question-mark',
            ]);
        });
    }
}
