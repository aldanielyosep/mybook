<?php

namespace App\Http\Controllers\Category;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\Category\CategoryModel;
use DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

class CategoryController extends Controller
{     
    public function listCategory(Request $request){
        $data = new CategoryModel();
        $record = $data->categoryLists($request);
        if(isset($record))
        {
            $res['status'] = $record['status'];
            $res['message'] = $record['message_response'];
            $res['data'] = $record['data'];
        }
        return response($res);        
    }
    
    public function createCategory(Request $request){
        $data = new CategoryModel();
        $record = $data->createCategorys($request);
        if(isset($record))
        {
            $res['status'] = $record['status'];
            $res['message'] = $record['message_response'];
        }
        return response($res);        
    }
    
    public function updateCategory(Request $request){
        $data = new CategoryModel();
        $record = $data->updateCategorys($request);
        if(isset($record))
        {
            $res['status'] = $record['status'];
            $res['message'] = $record['message_response'];
        }
        return response($res);        
    }
    
    public function deleteCategory(Request $request){
        $data = new CategoryModel();
        $record = $data->deleteCategorys($request);
        if(isset($record))
        {
            $res['status'] = $record['status'];
            $res['message'] = $record['message_response'];
        }
        return response($res);        
    }
}