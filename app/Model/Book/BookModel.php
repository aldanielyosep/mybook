<?php
    namespace App\Model\Book;

    use DB; 
    use Illuminate\Database\Eloquent\Model;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\Cache;
    
    class BookModel extends Model
    {
        public function bookLists($request)
        {   
            $query = DB::connection('mysql')
                ->table(DB::Raw('mbook T1'))
                ->selectRaw("CASE WHEN T2.parent_id = 0 THEN T2.category_name ELSE T3.category_name END as 'category'
                , CASE WHEN T2.parent_id = 0 THEN '' ELSE T2.category_name END as 'sub_kategori', T1.*")
                ->join(DB::Raw('mcategory T2'), DB::Raw('T2.category_id'), '=', DB::Raw('T1.category_id'))
                ->leftjoin(DB::Raw('mcategory T3'), DB::Raw('T3.category_id'), '=', DB::Raw('T2.parent_id'));
            $total = $query->count();
            if($total < 1){
                $status = false;
                $message = 'No record';
            }else{
                $status = true;
                $message = 'Success';
            }
            $record['status'] = $status;
            $record['message_response'] = $message;
            $record['data'] = $query->get();

            return $record;
        }  

        public function createBooks($request)
        {   
            $status = false;
            $param = false;
            $msg = 'Failed';
            if($request->has('number_of_pages') && $request->number_of_pages != '' && $request->has('published_date') && $request->published_date != '' && $request->has('isbn') && $request->isbn != '' && $request->has('language') && $request->language != '' && $request->has('publisher') && $request->publisher != '' && $request->has('writer') && $request->writer != '' && $request->has('title') && $request->title != '' && $request->has('description') && $request->description != '' && $request->has('price') && $request->price != '' && $request->has('category_id') && $request->category_id != ''){
                $param = true;
                $msg = 'param';
            }else{
                $param = false;
                $msg = 'all parameter is mandatory';
            }
            if($param == true){
                $pages = $request->number_of_pages;
                $published_date = $request->published_date;
                $isbn = $request->isbn;
                $language = $request->language;
                $publisher = $request->publisher;
                $writer = $request->writer;
                $title = $request->title;
                $description = $request->description;
                $price = $request->price;
                $category_id = $request->category_id;

                $status = true;
                $msg = 'Success';
                $queryCheck = DB::connection('mysql')
                    ->table('mcategory')
                    ->select(DB::Raw('count(0) as jumlah'), DB::Raw('category_name'))
                    ->where('category_id', '=', $category_id)
                    ->groupBy(DB::Raw('category_name'))
                    ->first();
                $query = DB::connection('mysql')
                    ->table('mbook')
                    ->select(DB::Raw('count(0) as jumlah'))
                    ->where('category_id', '=', $category_id)
                    ->where('title', '=', $title)
                    ->first();
                if($queryCheck->jumlah > 0){
                    if($query->jumlah < 1){
                        $query = DB::connection('mysql')
                            ->table(DB::Raw('mbook'))
                            ->insertGetId(
                                [ 
                                    'number_of_pages' => $pages,
                                    'published_date' => $published_date,
                                    'isbn' => $isbn,
                                    'language' => $language,
                                    'publisher' => $publisher,
                                    'writer' => $writer,
                                    'title' => $title,
                                    'description' => $description,
                                    'price' => $price,
                                    'category_id' => $category_id,
                                ]
                            );
                        if($query > 0){
                            $status = true;
                            $msg = 'Success';
                        }else{
                            $status = false;
                            $msg = 'Failed';
                        }
                    }else{
                        $status = false;
                        $msg = $title.' already exist for category '.$queryCheck->category_name;
                    }
                }else{
                    $status = false;
                    $msg = 'category_id '.$category_id.' not found';
                }
            }
            $record['status'] = $status;
            $record['message_response'] = $msg;

            return $record;
        }  

        public function updateBooks($request)
        {   
            $status = false;
            $book_id_flag = false;
            $category_id_flag = false;
            $msg = 'Failed';
            if($request->has('book_id') && $request->book_id != ''){
                $book_id_flag = true;
                $msg = 'book_id'; 
            }else{
                $book_id_flag = false;
                $msg = 'book_id is mandatory';
            }
            if($book_id_flag == true){
                $status = true;
                $msg = 'Success';

                $query = DB::connection('mysql')
                ->table('mbook')
                ->select(DB::Raw('count(0) as jumlah'))
                ->where('book_id', '=', $request->book_id)
                ->first();
                if($query->jumlah < 1){
                    $status = false;
                    $msg = 'Book id '.$request->book_id.' not found';
                }else{
                    $book_id = $request->book_id;
                    if(($request->has('number_of_pages') && $request->number_of_pages != '') || ($request->has('published_date') && $request->published_date != '') || ($request->has('isbn') && $request->isbn != '') || ($request->has('language') && $request->language != '') || ($request->has('publisher') && $request->publisher != '') || ($request->has('writer') && $request->writer != '') || ($request->has('title') && $request->title != '') || ($request->has('description') && $request->description != '') || ($request->has('price') && $request->price != '') || ($request->has('category_id') && $request->category_id != '')){
                        $number_of_pages = array();
                        $published_date = array();
                        $isbn = array();
                        $language = array();
                        $publisher = array();
                        $writer = array();
                        $title = array();
                        $description = array();
                        $price = array();
                        $category_id = array();
                        if($request->has('number_of_pages')){
                            $number_of_pages = array('number_of_pages' => $request->number_of_pages);
                        }
                        if($request->has('published_date')){
                            $published_date = array('published_date' => $request->published_date);
                        }
                        if($request->has('isbn')){
                            $isbn = array('isbn' => $request->isbn);
                        }
                        if($request->has('language')){
                            $language = array('language' => $request->language);
                        }
                        if($request->has('publisher')){
                            $publisher = array('publisher' => $request->publisher);
                        }
                        if($request->has('writer')){
                            $writer = array('writer' => $request->writer);
                        }
                        if($request->has('title')){
                            $title = array('title' => $request->title);
                        }
                        if($request->has('description')){
                            $description = array('description' => $request->description);
                        }
                        if($request->has('price')){
                            $price = array('price' => $request->price);
                        }
                        if($request->has('category_id')){
                            $category_id = array('category_id' => $request->category_id);
                            $query = DB::connection('mysql')
                            ->table('mcategory')
                            ->select(DB::Raw('count(0) as jumlah'))
                            ->where('category_id', '=', $request->category_id)
                            ->first();
                            if($query->jumlah < 1){
                                $status = false;
                                $msg = 'category id '.$request->category_id.' not found';
                                $record['status'] = $status;
                                $record['message_response'] = $msg;
                    
                                return $record;
                            }
                        }
                        
                        $data = array_merge($number_of_pages, $published_date, $isbn, $language, $publisher, $writer, $title, $description, $price, $category_id);
                        $query = DB::connection('mysql')
                            ->table(DB::Raw('mbook'))
                            ->where('book_id', '=', $book_id)
                            ->update($data);
                        if($query > 0){
                            $status = true;
                            $msg = 'Success';
                        }else{
                            $status = false;
                            $msg = 'No update data';
                        }
                    }else{
                        $status = false;
                        $msg = 'No update data';
                    }
                }
            }
            $record['status'] = $status;
            $record['message_response'] = $msg;

            return $record;
        }   

        public function deleteBooks($request)
        {   
            $status = false;
            $book_id = false;
            $msg = 'Failed';
            if($request->has('book_id') && $request->book_id != ''){
                $book_id = true;
                $msg = 'book_id';    
            }else{
                $book_id = false;
                $msg = 'book_id is mandatory';
            }
            if($book_id == true){
                $status = true;
                $msg = 'Success';

                $query = DB::connection('mysql')
                ->table('mbook')
                ->select(DB::Raw('count(0) as jumlah'))
                ->where('book_id', '=', $request->book_id)
                ->first();
                if($query->jumlah > 0){
                    $query = DB::connection('mysql')
                        ->table(DB::Raw('mbook'))
                        ->where('book_id', $request->book_id)
                        ->delete();
                    if($query > 0){
                        $status = true;
                        $msg = 'Success';
                    }else{
                        $status = false;
                        $msg = 'Failed';
                    }
                }else{
                    $status = false;
                    $msg = 'book_id '.$request->book_id.' not found';
                }
            }
            $record['status'] = $status;
            $record['message_response'] = $msg;

            return $record;
        }  
    }