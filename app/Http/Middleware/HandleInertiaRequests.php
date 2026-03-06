<?php

namespace App\Http\Middleware;

use App\Helpers\MenuHelper;
use Illuminate\Http\Request;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    protected $rootView = 'app';

    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        $auth = $request->user();
        $menuGroups = [];
        $currentPath = '';
        if ($auth) {
            $menuGroups = MenuHelper::getMenuGroups();
            $currentPath = ltrim(parse_url($request->url(), PHP_URL_PATH) ?? '', '/');
        }

        return [
            ...parent::share($request),
            'name' => config('app.name'),
            'auth' => [
                'user' => $auth,
            ],
            'menuGroups' => $menuGroups,
            'currentPath' => $currentPath,
            'logout_url' => route('logout'),
            'csrf_token' => csrf_token(),
        ];
    }
}
