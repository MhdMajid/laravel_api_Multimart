<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Item;
use App\Models\Shopping_cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use App\Models\Payment;
use App\Models\Address;
use App\Models\order_item;


use App\Http\Traits\RespondsWithHttpStatus;
use Illuminate\Support\Facades\DB;
use App\Models\User;



class OrderController extends Controller
{
    use RespondsWithHttpStatus;
    public function createOrder(Request $request){
       
            $validatedItem= Validator::make($request->all(), 
            [
                'last_name'=>'required',
                'first_name'=>'required',
                'phone'=>'required',
                'adress'=>'required',
                'city'=>'required',
                // 'payment_id'=>'required',
            ]);
            if($validatedItem->fails()){
                // $validtionMessages = $validateItemaddItem->errors();
                    return $this->failure('validation Error',401);
            }
            try {
            DB::beginTransaction();


            $cartItems= Shopping_cart::where('user_id',Auth::user()->id)->get();
            $itemIds=   $cartItems->pluck('item_id');
            $payment_amount=0;
            foreach ($itemIds as $itemId) {
              $item= Item::where('id',$itemId)->first();
              $itemPrice= $item==null?0:$item->price;
              $payment_amount+=$itemPrice;
            }
           
            
            $payment = Payment::create([
                'payment_amount'=>$payment_amount,
                'delevery_cost'=>0,
                'payment_date'=>Carbon::now(),
                'user_id'=>Auth::user()->id
            ]);
            $address = Address::create([
                'last_name'=>$request->last_name,
                'first_name'=>$request->first_name,
                'adress'=>$request->adress ,
                'phone'=>$request->phone,
                'city'=>$request->city,
                'user_id'=>Auth::user()->id,
                "details"=>$request->details
            ]);
            $totalAmount = $payment->payment_amount + $payment->delevery_cost;
            $order = Order::create([
               'user_id'=>Auth::user()->id,
               'address_id'=>$address->id,
               'order_date'=> Carbon::now(),
               'order_ship_date'=>Carbon::now(),
               'payment_id'=> $payment->id,
               'total_price'=>$totalAmount,
            ]);
            $orderItems=[];

            foreach ($itemIds  as $itemId) {

                $orderItems[]=[
                    'item_id'=>$itemId,
                    'quantity'=>1,
                    'order_id'=>$order->id,
                    'created_at'=>Carbon::now(),
                    'updated_at'=>Carbon::now(),
                ];

            }
            $order_items = order_item::insert($orderItems);

            Shopping_cart::where('user_id',Auth::user()->id)->delete();
            DB::commit();
            return $this->success(
                'The order have  been created successfully',
                $order,
            );
            } catch (\Throwable $e) {
                DB::rollBack();
                return $this->failure($e->getMessage());
            }
    }

    public function getAllOrders(){
       
        try{
            
            $data = Order::get();
            return $this->success(
                'The Orders has been obtained successfully',
                $data,
            );
        }catch(\Throwable $e){
            
            return $this->failure($e->getMessage());

        }
    }

    public function getOrdersByUser(){
        try{
            $data = Order::where('user_id',Auth::user()->id)->get();
            
            return $this->success(
                'The orders have been obtained successfully',
                $data,
            );
        }catch(\Throwable $e){
            return $this->failure($e->getMessage());

        }
    }

    // public function deleteOrder(Request $request){
    //     $validateCategory= Validator::make($request->all(), 
    //         [
                
    //             'id'=>'required',
                
    //         ]);
    //         if($validateCategory->fails()){
    //             // $validtionMessages = $validateCategory->errors();
    //                 return $this->failure('validation Error',403);
    //         }
    //     try{
            
    //         $order_id=$request->id;
    //         Order::where('id',$order_id)->delete();
    //         return $this->success(
    //             'The seller has been deleted successfully',
            
    //         );

    //     }catch(\Throwable $e){
    //         // return $this->failure('There is error in creating the product, try again');
    //         return $this->failure($e->getMessage());

    //     }
    // }


}
