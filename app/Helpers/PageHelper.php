<?php

namespace App\Helpers;

use App\Models\Sys\App;
use App\Models\Sys\Menu;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Route;

class PageHelper
{
    public static function info($params = '')
    {
        // Ambil nama route aktif
        $routeName = Route::currentRouteName();
        if (!$routeName) return [];

        $segments = array_map('trim', explode('|', $routeName));

        // --- Cari nama aplikasi ---
        $appKey = $segments[0] ?? null;
        $app = null;

        if ($appKey) {
            $app = Cache::remember("app_name_{$appKey}", 3600, function () use ($appKey) {
                return App::where('name', $appKey)->first();
            });
        }

        $breadcrumbs = [];

        // Tambahkan breadcrumb aplikasi
        if ($app) {
            $breadcrumbs[] = [
                'label' => $app->name,
                'url' => route($app->name . ' | Home'),
            ];
        }

        // --- Cari nama menu (modul) ---
        $menuKey = $segments[1] ?? null;
        $menu = null;

        if ($app && $menuKey) {
            $menu = Cache::remember("menu_title_{$app->id}_{$menuKey}", 3600, function () use ($app, $menuKey) {
                return Menu::where('app_id', $app->id)
                    ->where(function ($q) use ($menuKey) {
                        $q->where('title', $menuKey) // kalau kebetulan sudah sama
                          ->orWhere('url', Str::lower($menuKey)); // atau cocok dengan url
                    })
                    ->first();
            });
        }

        if ($menu) {
            // Kalau ada segmen ketiga → menu jadi link
            if (isset($segments[2])) {
                $breadcrumbs[] = [
                    'label' => $menu->title,
                    'url' => route($app->name . ' | ' . $menuKey),
                ];
            } else {
                // Kalau cuma sampai modul → modul aktif
                $breadcrumbs[] = [
                    'label' => $menu->title,
                    'active' => true,
                ];
            }
        }

        // --- Halaman detail (Show/Edit) ---
        if (isset($segments[2])) {
            $label = $segments[2];

            // Kalau ada parameter model, pakai field name
            if ($params) {
                $label = $params;
            }

            $breadcrumbs[] = [
                'label' => $label,
                'active' => true,
            ];
        }

        return [
            'breadcrumbs' => $breadcrumbs,
            'page_icon' => $menu->icon,
            'page_title' => $menu->title
        ];
    }
}
