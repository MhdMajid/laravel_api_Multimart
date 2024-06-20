<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Shopping_cart;
use App\Models\Item;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\Payment;
use App\Models\User;
use App\Models\Payment_detail;
use App\Http\Traits\RespondsWithHttpStatus;
use Illuminate\Support\Facades\DB;
use App\Models\Order;
class PaymentController extends Controller
{
    use RespondsWithHttpStatus;
    public function createPayment(){
       
            try {
                
                DB::beginTransaction();
                $payments = User::find(Auth::user()->id)->Payments;
            
                foreach ($payments as $payment) {
                    
                    $orders = $payment->Orders;
                    
                   if( $orders == null){
                    Payment_detail::where('payment_id',$payment->id)->delete();
                    Payment::where('id',$payment->id)->delete();
                   }
                }
            $data = Shopping_cart::where('user_id',Auth::user()->id)->get();
            $items = [];
            
            foreach($data as $item_id){
                $item = Item::where('id',$item_id->item_id)->first();
                array_push($items, $item);
            }
            if(count($items) == 0){
                return $this->failure('There is no items');
            }
            $amount = 0;
            
            foreach ($items as $item) {
                $discount = $item->is_discount;
                if($discount){
                    
                    $newPrice = ($item->price * $item->discount) / 100 ;
                    $amount = $newPrice + $amount;
                }else{
                    
                   $amount = $item->price + $amount;
                }

                
            }
            
            $data = Payment::create([
                'user_id'=>Auth::user()->id,
                'payment_amount'=>$amount,
                'payment_date'=>Carbon::now(),
                'delevery_cost'=>1000.0,
            ]);
            
            foreach ($items as $item) {
                Payment_detail::create([
                    'payment_id'=>$data->id,
                    'item_id'=>$item->id,
                    'user_id'=>Auth::user()->id,
                ]);
            }
            Order::where('user_id',Auth::user()->id)->where('payment_id',null)->update([
                'payment_id'=>$data->id,
            ]);
            $details = Payment_detail::where('payment_id',$data->id)->get();
            $allData = [];
            $allData['payment'] = $data;
            $allData['Details'] = $details;
            DB::commit();
            return $this->success(
                'The address has been created successfully',
                $allData,
            );
            } catch (\Throwable $e) {
                DB::rollBack();
                return $this->failure($e->getMessage());
            }
    }

    
    
}
