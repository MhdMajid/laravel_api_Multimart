<?php

namespace App\Http\Controllers\Api;

use App\Models\Item;
use App\Models\rating;
use App\Http\Controllers\Controller;
use App\Http\Traits\RespondsWithHttpStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class RatingController extends Controller

{
    use RespondsWithHttpStatus;
  public function addrating(Request $request ){
    $validate= Validator::make($request->all(), 
        [
            'item_id' => 'required',
            'rating'  => 'required',
        ]);
        if($validate->fails()){
                return $this->failure('validation Error',403);
        }
        try{
            $rate = new rating();
            $rate->user_id = Auth::user()->id;
            $rate->item_id = $request->item_id;
            $rate->rating = $request->rating;
            $rate->save();
            return $this->success( $rate,
                'rating created successfully'
            );
        }catch(\Throwable $e){
            return $this->failure($e->getMessage());
        }
  }

  public function updateRating(Request $request ){
    $validate= Validator::make($request->all(), 
        [
            'item_id' => 'required',
            'rating'  => 'required',
        ]);
        if($validate->fails()){
                return $this->failure('validation Error',403);
        }
        try{

            $data = rating::where('user_id',Auth::user()->id)->update([
                'rating' => $request->rating,
            ]);

            return $this->success(
                'rating updated successfully'
            );
        }catch(\Throwable $e){
            return $this->failure($e->getMessage());
        }
  }



  public function userRating(Request $request ){
    $validate= Validator::make($request->all(), 
        [
            'item_id' => 'required',
        ]);
        if($validate->fails()){
                return $this->failure('validation Error',403);
        }
        try{

            $rating =rating::where('item_id',$request->item_id)
            ->where('user_id',Auth::user()->id)->orderByDesc('created_at')
            ->get();
         return $this->success(
                'user rating ',
                $rating,
            );
           
        }catch(\Throwable $e){
            return $this->failure($e->getMessage());
        }
  }


  public function deleteRating(Request $request){
    $validateCategory= Validator::make($request->all(), 
        ['item_id'=>'required',]);
        if($validateCategory->fails()){
                return $this->failure('validation Error',403);
        }
    try{
        rating::where('item_id',$request->item_id)->where('user_id',Auth::user()->id)->delete();
        return $this->success(
            'The rating has been deleted successfully',
        );
    }catch(\Throwable $e){
            return $this->failure($e->getMessage());
        }
        
    }







    
    
    
}