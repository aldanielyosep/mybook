<?php

namespace App\Http\Middleware;

use Closure;

class AuthHeader
{
    public function handle($request, Closure $next)
    {
		$headerAuth = $request->header('Authorization');
        
        if(!empty($headerAuth)){
            if($headerAuth == 'A.Daniel.Yosep-GDIS'){ 
                $res['status'] = true;
                $res['message'] = 'Success';
                $res['data']['headerAuth'] = $headerAuth;

                // return response($res);
            }else{
                $res['status'] = false;
                $res['message'] = 'Access Denied. Invalid Authorization';
                $res['data']['headerAuth'] = $headerAuth;
                return response($res);
            }           
        }else{
            $rest['status'] = false;
            $rest['message'] = 'Authorization is misssing';
            return $rest;
        }

        return $next($request);
    }
}
