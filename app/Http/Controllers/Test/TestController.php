<?php

namespace App\Http\Controllers\Test;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;

class TestController extends Controller
{	
    public  function connection()
    {
        /*
        **** for datatables, adding records count and draw to json*****
        */    
        DB::connection()->getPdo();
        if(DB::connection()->getDatabaseName()){
			$res['code'] = 200;
			$res['message'] = 'Success';
			$res['data'] ='Yes! Successfully connected to the DB: ' . DB::connection()->getDatabaseName();
        }
		else
		{
			$res['code'] = 404;
			$res['message'] = trans('message.error_404');
		}

        return response($res);
    }
}
