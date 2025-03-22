<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that's loaded on the first page visit.
     *
     * @see https://inertiajs.com/server-side-setup#root-template
     *
     * @var string
     */
    protected $rootView = 'layouts.app';

    /**
     * Determines the current asset version.
     *
     * @see https://inertiajs.com/asset-versioning
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

       
    function isPortOpen($host, $port) {
        $connection = @fsockopen($host, $port, $errno, $errstr, 2); // 2-second timeout
    
        if (is_resource($connection)) {
            fclose($connection);
            return true; // Port is open
        } else {
            return false; // Port is closed or not running
        }
    }

    /**
     * Define the props that are shared by default.
     *
     * @see https://inertiajs.com/shared-data
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        $isMaintenance = $this->isPortOpen("127.0.0.1", "28000");
        $isLoggedIn = auth()->check();
        return array_merge(parent::share($request), [
            'isLoggedIn' => $isLoggedIn,
            'isAdmin' => $isLoggedIn && in_array(auth()->user()->email, config('admin.administrators')),
            'state' => $isMaintenance,

        ]);
    }
}
