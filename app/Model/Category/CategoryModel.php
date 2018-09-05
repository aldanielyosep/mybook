<?php
    namespace App\Model\Category;

    use DB; 
    use Illuminate\Database\Eloquent\Model;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\Cache;
    
    class CategoryModel extends Model
    {
        public function categoryLists($request)
        {   
            $query1 = DB::connection('mysql')
                ->table(DB::Raw('mcategory T1'))
                ->selectRaw("T1.category_id, T1.category_name, T1.created_date, T1.parent_id, '' as parent_category")
                ->where('status', '=', 'ACTIVE')
                ->where(DB::Raw('T1.parent_id'), '=', 0);
            $query2 = DB::connection('mysql')
                ->table(DB::Raw('mcategory T2'))
                ->selectRaw('T2.category_id, T2.category_name, T2.created_date, T2.parent_id, T3.category_name')
                ->leftjoin(DB::Raw('mcategory T3'), DB::Raw('T3.category_id'), '=', DB::Raw('T2.parent_id'))
                ->where(DB::Raw('T2.status'), '=', 'ACTIVE')
                ->where(DB::Raw('T2.parent_id'), '!=', 0);
            $total = $query1->count()+$query2->count();
            if($total < 1){
                $status = false;
                $message = 'No record';
            }else{
                $status = true;
                $message = 'Success';
            }
            $record['status'] = $status;
            $record['message_response'] = $message;
            $record['data'] = $query1->union($query2)->get();

            return $record;
        }  

        public function createCategorys($request)
        {   
            $status = false;
            $parent_id = false;
            $category_name = false;
            $msg = 'Failed';
            if($request->has('parent_id')){
                $parent_id = true;
                $msg = 'parent_id';
                if($request->parent_id == ''){
                    $request->parent_id = 0;
                }
                
                if($request->has('category_name') && $request->category_name != ''){
                    $category_name = true;
                    $msg = 'category_name';
                }else{
                    if($request->has('category_name') != ''){
                        $msg = 'category_name must be filled with characters';
                    }else{
                        $msg = 'category_name is mandatory';
                    }
                    $category_name = false;
                }
            }else{
                $parent_id = false;
                $msg = 'parent_id is mandatory';
            }
            if($category_name == true && $parent_id == true){
                $status = true;
                $msg = 'Success';

                $query = DB::connection('mysql')
                ->table('mcategory')
                ->select(DB::Raw('count(category_id) as jumlah'))
                ->where('category_id', '=', $request->parent_id)
                ->first();
                if($query->jumlah > 0 || $request->parent_id == ''){
                    $query = DB::connection('mysql')
                        ->table(DB::Raw('mcategory'))
                        ->insertGetId(
                            [ 
                                'parent_id' => $request->parent_id,
                                'category_name' => $request->category_name
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
                    $msg = 'parent_id '.$request->parent_id.' not found or not ""';
                }
            }
            $record['status'] = $status;
            $record['message_response'] = $msg;

            return $record;
        }  

        public function updateCategorys($request)
        {   
            $status = false;
            $category_id = false;
            $category_name = false;
            $msg = 'Failed';
            if($request->has('category_id') && $request->category_id != ''){
                $category_id = true;
                $msg = 'category_id';                
                if($request->has('category_name') && $request->category_name != ''){
                    $category_name = true;
                    $msg = 'category_name';
                }else{
                    if($request->has('category_name') != ''){
                        $msg = 'category_name must be filled with characters';
                    }else{
                        $msg = 'category_name is mandatory';
                    }
                    $category_name = false;
                }
            }else{
                $category_id = false;
                $msg = 'category_id is mandatory';
            }
            if($category_name == true && $category_id == true){
                $status = true;
                $msg = 'Success';

                $query = DB::connection('mysql')
                ->table('mcategory')
                ->select(DB::Raw('count(category_id) as jumlah'))
                ->where('category_name', '=', $request->category_name)
                ->first();
                if($query->jumlah > 0){
                    $status = false;
                    $msg = 'Category already exist';
                }else{
                    $query = DB::connection('mysql')
                        ->table(DB::Raw('mcategory'))
                        ->where('category_id', $request->category_id)
                        ->update(['category_name' => $request->category_name]);
                    if($query > 0){
                        $status = true;
                        $msg = 'Success';
                    }else{
                        $status = false;
                        $msg = 'Failed';
                    }
                }
            }
            $record['status'] = $status;
            $record['message_response'] = $msg;

            return $record;
        }   

        public function deleteCategorys($request)
        {   
            $status = false;
            $category_id = false;
            $msg = 'Failed';
            if($request->has('category_id') && $request->category_id != ''){
                $category_id = true;
                $msg = 'category_id';    
            }else{
                $category_id = false;
                $msg = 'category_id is mandatory';
            }
            if($category_id == true){
                $status = true;
                $msg = 'Success';

                $query = DB::connection('mysql')
                ->table('mcategory')
                ->select(DB::Raw('count(category_id) as jumlah'))
                ->where('category_id', '=', $request->category_id)
                ->first();
                if($query->jumlah > 0){
                    $query = DB::connection('mysql')
                        ->table(DB::Raw('mcategory'))
                        ->where('category_id', $request->category_id)
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
                    $msg = 'category id '.$request->category_id.' not found';
                }
            }
            $record['status'] = $status;
            $record['message_response'] = $msg;

            return $record;
        }  
    }