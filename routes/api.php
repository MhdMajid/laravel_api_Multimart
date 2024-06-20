<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\ItemController;
use App\Http\Controllers\Api\AdminController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\CartController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\PaymentController;
use App\Http\Controllers\Api\SellerController;
use App\Http\Controllers\Api\RatingController;
use App\Http\Controllers\Api\PropertyController;



/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/


Route::post('/auth/register', [UserController::class, 'createUser']); ///
Route::post('/auth/login', [UserController::class, 'loginUser']);//

Route::group(['middleware' => ['auth:sanctum']], function() {



    Route::middleware(['is_admin'])->group(function () {

        Route::middleware(['is_superadmin'])->group(function () {
            
            // user
            Route::get('/user/getAllUser'        , [UserController::class, 'getAllUser']);//
            Route::post('/user/creaTUserByAdmin' , [UserController::class, 'creaTUserByAdmin']);//
            Route::post('/user/updateUserByAdmin', [UserController::class, 'updateUserByAdmin']);//
            Route::post('/user/deleteuser'       , [UserController::class, 'deleteuser']);//
            Route::post('/user/getUserByadmin'   , [UserController::class, 'getUserByadmin']);//

        });


        //Items
        Route::post('/item/addItem', [ItemController::class, 'addItem']);//
        Route::post('/item/updateItem', [ItemController::class, 'updateItem']);//
        Route::post('/item/deleteItem', [ItemController::class, 'deleteItem']);//
        Route::post('/item/addProperty', [ItemController::class, 'addPropertyToItem']);//
        Route::post('/user/addAddress', [UserController::class, 'addAddress']);//
        Route::get('/user/getAddresses', [UserController::class, 'getAddresses']);//
        Route::post('/user/deleteAddress', [UserController::class, 'deleteAddress']);//

        //categories
        Route::post('/category/create', [CategoryController::class, 'addCategory']);//
        Route::post('/category/update', [CategoryController::class, 'updateCategory']);//
        Route::post('/category/delete', [CategoryController::class, 'deleteCategory']);//

        //payments
        Route::get('/order/getAllOrders', [OrderController::class, 'getAllOrders']);//
        Route::post('/order/deleteOrder', [OrderController::class, 'deleteOrder']);//


        
    });

    Route::middleware(['Shop'])->group(function () {
        
        // has Shop
        
        
        
    });

    
    Route::get('/auth/logout', [UserController::class, 'logout']);//
    Route::get('/adminauth/logout', [AdminController::class, 'Adminlogout']);//
    
    
    
    
    
    
    //payments
    Route::get('/payment/createPayment', [PaymentController::class, 'createPayment']);//
    Route::post('/order/createOrder', [OrderController::class, 'createOrder']);//
    Route::get('/order/getOrdersByUser', [OrderController::class, 'getOrdersByUser']);//

    
    //cart
    Route::post('/cart/addToCart', [CartController::class, 'addToCart']);///
    Route::post('/cart/deleteFromCart', [CartController::class, 'deleteFromCart']);//
    Route::get('/cart/deleteCart', [CartController::class, 'deleteCart']);//
    Route::get('/cart/getCart', [CartController::class, 'getCart']);//
    Route::post('/cart/countCart', [CartController::class, 'countCart']);//
    Route::get('/item/cartItemCount', [ItemController::class, 'cartItemCount']);//
    
    //wish list
    Route::get('/item/getWishList', [ItemController::class, 'getWishList']);//
    Route::post('/item/addToWishList', [ItemController::class, 'addToWishList']);//
    Route::post('/item/deleteFromWishList', [ItemController::class, 'deleteFromWishList']);//
    Route::get('/item/wishlistCount', [ItemController::class, 'wishlistCount']);//

    // seller
    Route::post('/seller/addSeller'   , [SellerController::class, 'addSeller']);//
    Route::post('/seller/deleteseller', [SellerController::class, 'deleteSeller']); //
    Route::get('/seller/getAllSellerById', [SellerController::class, 'getAllSellerById']); // 
    
    // user
    Route::get('/user/getUserById' , [UserController::class, 'getUserById']);//
    Route::get('/user/checkUser'   , [UserController::class, 'checkUser']);//
    Route::get('/user/checkHasShop', [UserController::class, 'checkHasShop']);//
    Route::post('/user/updateUser' , [UserController::class, 'updateUser']);//
    
    
    // rating
    Route::post('/rating/addrating'      , [RatingController::class, 'addrating']);//
    Route::post('/rating/updateRating'   , [RatingController::class, 'updateRating']);//
    Route::post('/rating/deleteRating'   , [RatingController::class, 'deleteRating']);//
    Route::post('/rating/userRating'   , [RatingController::class, 'userRating']);//

    // Property
    Route::post('/property/addColor', [PropertyController::class, 'addColor']);//
    Route::post('/property/addSize', [PropertyController::class, 'addSize']);//
    Route::post('/property/editColor', [PropertyController::class, 'editColor']);//
    Route::post('/property/editSize', [PropertyController::class, 'editSize']);//
    Route::post('/property/deleteColor', [PropertyController::class, 'deleteColor']);//
    Route::post('/property/deletesize', [PropertyController::class, 'deletesize']);//
    Route::post('/property/editQuantity', [PropertyController::class, 'editQuantity']);//
    


});

    // Property
    Route::post('/property/getProperty', [PropertyController::class, 'getProperty']);//


        Route::post('/item/getItem', [ItemController::class, 'getItem']);//

        Route::get('/seller/getseller'   , [SellerController::class, 'getAllseller']);//
        Route::post('/seller/getoneseller',[SellerController::class, 'getoneseller']);//    

// ----------
    //
    
       //payment

Route::post('/category/getCategoriesByProduct', [CategoryController::class, 'getCategoriesByProduct']);//

Route::get('/category/getAllCategories', [CategoryController::class, 'getAllCategories']);//

Route::post('/item/getProperties', [ItemController::class, 'getProperties']);//

Route::post('/item/getItemsRandomly', [ItemController::class, 'getItemsRandomly']);//

Route::post('/item/getItemsByCategory', [ItemController::class, 'getItemsByCategory']);//

Route::get('/item/getAllItems', [ItemController::class, 'getAllItems']);//


// -------------
Route::post('/adminauth/register', [AdminController::class, 'createAdmin']);//
Route::post('/adminauth/login', [AdminController::class, 'loginAdmin']);//
Route::post('/adminauth/getadresses', [UserController::class, 'getAddress']);
Route::post('/adminauth/getcatigories', [ItemController::class, 'getProduct']);
Route::post('/item/getBestSeller',[ItemController::class, 'getBestSeller']);
Route::post('/item/getNewItems',[ItemController::class, 'getNewItems']);
Route::post('/item/getPopuler', [ItemController::class, 'getPopuler']);



