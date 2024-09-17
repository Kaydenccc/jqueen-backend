<?php

namespace App\Http\Middleware;

use App\Models\UserAdmin;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class ApiAuthMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {

        $token = $request->header('Authorization');
        $authenticate = true;
        if(!$token) {
            $authenticate = false;
        }
        //AMBIL DATA ADMIN
         $admin = UserAdmin::where('token', $token)->first();

         if(!$admin) {
            $authenticate= false;
         } else {
            Auth::login($admin); 
         }

        // JIKA ADMIN ADA LANJUTKAN KE PROSES SELANJUTNYA
         if($authenticate){
             return $next($request);
         } else {
            return response()->json([
                "errors"=>[
                    "message"=>[
                        'Unauthorized.'
                    ]
                ]
            ])->setStatusCode(401);
         }
    }
}
