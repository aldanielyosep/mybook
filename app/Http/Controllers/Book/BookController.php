<?php

namespace App\Http\Controllers\Book;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\Book\BookModel;
use DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

class BookController extends Controller
{     
    public function listBook(Request $request){
        $data = new BookModel();
        $record = $data->bookLists($request);
        if(isset($record))
        {
            $res['status'] = $record['status'];
            $res['message'] = $record['message_response'];
            $res['data'] = $record['data'];
        }
        return response($res);        
    }
    
    public function createBook(Request $request){
        $data = new BookModel();
        $record = $data->createBooks($request);
        if(isset($record))
        {
            $res['status'] = $record['status'];
            $res['message'] = $record['message_response'];
        }
        return response($res);        
    }
    
    public function updateBook(Request $request){
        $data = new BookModel();
        $record = $data->updateBooks($request);
        if(isset($record))
        {
            $res['status'] = $record['status'];
            $res['message'] = $record['message_response'];
        }
        return response($res);        
    }
    
    public function deleteBook(Request $request){
        $data = new BookModel();
        $record = $data->deleteBooks($request);
        if(isset($record))
        {
            $res['status'] = $record['status'];
            $res['message'] = $record['message_response'];
        }
        return response($res);        
    }
}