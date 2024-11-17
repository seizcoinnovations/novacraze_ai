<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class SubVendorAccessCheckpost
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle($request, Closure $next)
    {
        // check vendor and user account status
        if((getsubVendorUid() and !session('loggedBySuperAdmin') and ((getUserAuthInfo('subvendor_status') != 1) or (getUserAuthInfo('status') != 1)))) {
            
            Auth::logout();
            $request->session()->invalidate();
            if ($request->ajax()) {
                return __apiResponse([
                    'message' => __tr('Subvendor/User account is not in active state'),
                    'auth_info' => getUserAuthInfo(5),
                ], 11);
            }
            // return redirect()->route('landing_page');

            
        }
        // check if user has permissions to access area
        if ((hassubVendorAccess() === false)) {
            if ($request->ajax()) {
                return __apiResponse([
                    'message' => __tr('Restricted Area'),
                    'auth_info' => getUserAuthInfo(5),
                ], 11);
            }
            return redirect()->route('home');
        }

        return $next($request);
    }
}
