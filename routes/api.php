<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\userController;
use App\Http\Controllers\CustomerController;

use App\Http\Controllers\SupplierController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\SalesController;
use App\Http\Controllers\IncomController;



/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });
 
 

Route::post('/userRegister', [userController::class, 'userregister']);
Route::post('/userLogin', [userController::class, 'userLogin']);

Route::post('/logout', [userController::class, 'logout']);



Route::middleware(['checktoken', 'admin_user'])->group(function () {  
    
    Route::get('/userlist', [userController::class, 'userlist']);
    Route::post('/user_typechange', [userController::class, 'user_typechange']);
    
    
    Route::get('/userCheck', [userController::class, 'userCheck']);

    Route::get('/get_categories', [UserController::class, 'get_category']);
    Route::post('/add_category', [UserController::class, 'add_category']);
    Route::post('/catagoryDelete', [UserController::class, 'catagoryDelete']);
    Route::post('/categoryUpdate', [UserController::class, 'categoryUpdate']);
    

    Route::get('/get_product', [UserController::class, 'get_product']);
    Route::post('/add_product', [UserController::class, 'add_product']);
    Route::post('/productDelete', [UserController::class, 'productDelete']);
    
    Route::get('/get_brand', [UserController::class, 'get_brand']);
    Route::post('/add_brand', [UserController::class, 'add_brand']);

    Route::get('/get_customer', [CustomerController::class, 'get_customer']);
    Route::post('/addcustomer', [CustomerController::class, 'storecustomer']);
    Route::post('/customerEdit', [CustomerController::class, 'updateCustomer']);
    Route::post('/customer_delete', [CustomerController::class, 'customer_delete']);
    
    Route::get('/get_supplier', [SupplierController::class, 'get_supplier']);
    Route::post('/addsupplier', [SupplierController::class, 'addsupplier']);
    Route::post('/supplierUpdate', [SupplierController::class, 'supplierUpdate']);
    Route::post('/supplier_delete', [SupplierController::class, 'supplier_delete']);
    
    
    Route::get('/get_expenses', [ExpenseController::class, 'get_expenses']);
    Route::post('/add_expenses', [ExpenseController::class, 'add_expenses']);
    Route::post('/expensesDelete', [ExpenseController::class, 'expensesDelete']);
    Route::post('/expensesUpdate', [ExpenseController::class, 'expensesUpdate']);
    
    
    Route::get('/get_supplier', [PurchaseController::class, 'get_suplyer']);
    
    // purchase table in list for purchase list
    Route::get('/get_purchase', [PurchaseController::class, 'get_purchase']);
    Route::post('/purchase_in_getItems', [PurchaseController::class, 'purchase_in_getItems']);
    Route::post('/add_purchase', [PurchaseController::class, 'add_purchase']);

    Route::post('/purchaseDelete', [PurchaseController::class, 'purchaseDelete']);

    // purchase_in_product select for product list
    Route::get('/purchase_in_product_select', [PurchaseController::class, 'purchase_in_product_list']);
    
    
    Route::post('/purchaseDetails_in_items_list', [PurchaseController::class, 'purchaseDetails_in_items_list']);

    Route::post('/add_purchaseDetails_in_items_list', [PurchaseController::class, 'add_purchaseDetails_in_items_list']);
    
    // purchaseDetails_in_items_delete
    Route::post('/purchaseDetails_in_items_delete', [PurchaseController::class, 'purchaseDetails_in_items_delete']);
    
    // purchaseDetails_in_items quantity edit
    Route::post('/purchaseDetails_in_items_edit', [PurchaseController::class, 'purchaseDetails_in_items_edit']);
    
    Route::post('/purchase_in_items_save', [PurchaseController::class, 'purchase_in_items_save']);
    
    
    
    Route::post('/add_sales', [SalesController::class, 'add_sales']);
    Route::post('/get_sales', [SalesController::class, 'getsales']);
    Route::post('/salesdelete', [SalesController::class, 'salesdelete']);
    Route::post('/add_sales_in_items', [SalesController::class, 'add_sales_in_items']);
    Route::post('/sales_in_product_quanityUpdate', [SalesController::class, 'sales_in_product_quanityUpdate']);
    
    Route::post('/add_sales_in_items_delete', [SalesController::class, 'add_sales_in_items_delete']);
    Route::post('/add_sales_in_items_edit', [SalesController::class, 'add_sales_in_items_edit']);
    Route::post('/getsales_details', [SalesController::class, 'getsalesdetails']);
               
    Route::post('/final_sales_in_items_save', [SalesController::class, 'final_sales_in_items_save']);


    Route::get('/dashcounter', [IncomController::class, 'dashcounter']);
    Route::get('/income', [IncomController::class, 'income']);

});