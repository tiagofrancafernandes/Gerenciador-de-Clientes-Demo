<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Helpers\ConfigHandler;
use Illuminate\Support\Facades\Auth;

class IpCheckMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Request  $request
     * @param  Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (auth()->check()) {
            $approved = [];

            $ips = ConfigHandler::get('ips');

            if ($ips !== null) {
                $ips = json_decode($ips, true);

                foreach ($ips as $row) {
                    $approved[] = $row['ip'];
                }

                if (!in_array($request->ip(), $approved, true) && auth()->user()->is_office_login_only === 1) {
                    flash('Sorry, the system cannot be accessed from your location.')->warning();

                    Auth::guard()->logout();

                    return redirect()->route('login');
                }
            }
        }

        return $next($request);
    }
}
