<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


use App\Models\Review;


class ReviewController extends Controller
{
    public function createReview(Request $request)
    {
        $validate = Validator::make($request->all(),[ 
            'body'=>'required',
        ]);
        try{
            $data = Shop::create([
                'user_id'=>Auth::user()->id,
                'body'=>$request->body
            ]);
                 return $this->success('created Success',$data);
     
             }catch(Exception $e){
                 return $this->failure($e->getMessage());
             }
    }

    public function updateReview(Request $request)
    {
        $validate = Validator::make($request->all(),[ 
            'state'=>'required',
        ]);
        try{
            $data = Shop::create([
                'state'=>$request->state
            ]);
                 return $this->success('updated Success',$data);
     
             }catch(Exception $e){
                 return $this->failure($e->getMessage());
             }
    }

    public function getReviewByUser(Request $request)
    {
        try{
            $data = Shop::where('user_id', Auth::user()->id)->get();
                 return $this->success('Success',$data);
     
             }catch(Exception $e){
                 return $this->failure($e->getMessage());
             }
    }

    public function getReview(Request $request)
    {
        try{
            $data = Shop::get();
                 return $this->success('Success',$data);
     
             }catch(Exception $e){
                 return $this->failure($e->getMessage());
             }
    }


}
