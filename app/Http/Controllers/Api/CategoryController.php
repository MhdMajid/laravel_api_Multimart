<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Traits\RespondsWithHttpStatus;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    use RespondsWithHttpStatus;

    public function getAllCategories(){
       
        try{
            
            $data = Category::get();
            return $this->success(
                'The Categories has been obtained successfully',
                $data,
            );
        }catch(\Throwable $e){
            // return $this->failure('There is error in creating the product, try again');
            return $this->failure($e->getMessage());

        }
    }


    public function addCategory(Request $request){
        $validateCategory= Validator::make($request->all(), 
            [
                'name' => 'required',
                
            ]);
            if($validateCategory->fails()){
                // $validtionMessages = $validateCategory->errors();
                    return $this->failure('validation Error in product id',403);
            }
        try{
            
            $name = $request->name;
           
            $data = Category::create([
                'name'=>$name,
                
            ]);
            return $this->success(
                'The category has been created successfully',
                $data,
            );
        }catch(\Throwable $e){
            // return $this->failure('There is error in creating the Category, try again');
            return $this->failure($e->getMessage());

        }
    }

    public function updateCategory(Request $request){
        $validateCategory= Validator::make($request->all(), 
            [
                // 'product_id' => 'required|exists:products,id',
                'id'=>'required|exists:categories,id',
                'name'=>'required'

                
            ]);
            if($validateCategory->fails()){
                // $validtionMessages = $validateCategory->errors();
                    return $this->failure('validation Error',403);
            }
        try{
            $category_id = $request->id;
            $product_id = $request->product_id;
            $name = $request->name;
             Category::where('id',$category_id)->update([
                'name'=>$name,
                // 'product_id'=>$product_id,
            ]);
            $data = Category::find($category_id);
            return $this->success(
                'The category has been updated successfully',
                // $data,
            );
        }catch(\Throwable $e){
            // return $this->failure('There is error in creating the Category, try again');
            return $this->failure($e->getMessage());

        }
    }

    public function deleteCategory(Request $request){
        $validateCategory= Validator::make($request->all(), 
            [
                
                'id'=>'required|exists:categories,id',
                
            ]);
            if($validateCategory->fails()){
                // $validtionMessages = $validateCategory->errors();
                    return $this->failure('validation Error',403);
            }
        try{
            $category_id=$request->id;
            Category::where('id',$category_id)->delete();
            return $this->success(
                'The category has been deleted successfully',
            
            );
        }catch(\Throwable $e){
            // return $this->failure('There is error in creating the product, try again');
            return $this->failure($e->getMessage());

        }
    }
}
