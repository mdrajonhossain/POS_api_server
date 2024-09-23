<?php

namespace App\Http\Controllers;

use App\Models\User;

use Illuminate\Support\Facades\Auth;

use Laravel\Sanctum\PersonalAccessToken;

use Illuminate\Http\Request;
use App\Models\Catagory;
use App\Models\Product;
use App\Models\brand;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;





class userController extends Controller{


    // user log_out
    public function logout(Request $request) {
        try {
            $token = $request->bearerToken();
            if (!$token) {
                return response()->json(['error' => 'Unauthorized', 'status' => false], 401);
            }
    
            $accessToken = PersonalAccessToken::where('token', hash('sha256', $token))->first();
            if (!$accessToken) {
                return response()->json(['error' => 'Unauthorized', 'status' => false], 401);
            }
    
            Auth::login($accessToken->tokenable);
            $accessToken->delete();
    
            return response()->json(['status' => true, 'message' => 'User logged out successfully'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'An error occurred', 'status' => false, 'message' => $e->getMessage()], 500);
        }
    }


    //    
    public function userregister(Request $request){
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);
        // Check if validation fails
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }
        // Create the user
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);
        if($user){
            return response()->json([
                'status' => true,
                'message' => 'User registered successfully', 
                'user' => $user], 201);
        }else{
            return response()->json([
                'status' => false,
                'message' => 'User registered successfully', 
                'user' => $user], 201);
        }
        // Return success response
        
    }


    public function userLogin(Request $request){        
        $credentials = $request->only('email', 'password');
        
        if (Auth::attempt($credentials)) {
            $user = Auth::user();            
            $accessToken = $user->createToken('myToken')->plainTextToken;            
            list($id, $token) = explode('|', $accessToken);
            
            return response()->json([
                'User_data' => $user,
                'message' => 'success',
                'status' => true,                
                'access_token' => $token
            ], 200);
        }

        return response()->json([
            'message' => 'Unauthorized',
            'status' => false
        ], 401);
    }



    public function userCheck(Request $request){      
      
        $user = $request->attributes->get('user');

        if (!$user->id) {
            return response()->json([
                'error' => 'Unauthorized',
                'status' => false,
                'message' => 'failed'
            ], 401);
        }

        return response()->json([            
            'status' => true,
            'message' => 'Success',
            'data' => $user
        ], 200);

    }

  
    // get catagory
    public function get_category(Request $request){
        $user = $request->attributes->get('user');
    
        if (!$user) {
            return response()->json([
                'error' => 'Unauthorized',
                'status' => false,
                'message' => 'Failed'
            ], 401);
        }
    
        $categories = Catagory::all();
    
        return response()->json([
            'status' => true,            
            'catagory' => $categories
        ], 200);
    }



    // add catagory 
    public function add_category(Request $request){    
        
        $user = $request->user();
        $request->validate([
            'category_name' => 'required|string|max:255'
        ]);

        if (!$user) {
            return response()->json(['error' => 'Unauthorized', 'status' => false, 'message' => 'Failed'], 401);
        }

        try {        
            $category = new Catagory();
            $category->cata_name = $request->category_name;
            $category->save();
            
            return response()->json(['status' => true, 'data' => $category], 201);
        } catch (\Exception $e) {        
            return response()->json(['status' => false, 'message' => 'Failed to create category'], 500);
        }

    }


    public function categoryUpdate(Request $request){            
        $user = $request->user();
                
        $validatedData = $request->validate([
            'id' => 'required|integer',
            'category_name' => 'required|string|max:255',
        ]);

        if (!$user) {
            return response()->json(['error' => 'Unauthorized', 'status' => false, 'message' => 'Failed'], 401);
        }

        try {        
            $categ = Catagory::find($request->id);
            $categ->cata_name = $validatedData['category_name'];
            $categ->save();
            
            return response()->json(['status' => true, 'data' => $categ], 201);
        } catch (\Exception $e) {        
            return response()->json(['status' => false, 'message' => 'Failed to create category'], 500);
        }

    }



    public function catagoryDelete(Request $request){
        $user = $request->attributes->get('user');          
        $Cat = Catagory::find($request->id);        
        if ($Cat) {
            $Cat->delete();
            $expensesall = Catagory::all();
            return response()->json(['status' => true, 'Deleted' =>$Cat, "all_data" => $expensesall], 200);
        } else {
            return response()->json(['status' => false], 200);
        }
        return response()->json(['status' => false], 200);        
    }




// get product 
    public function get_product(Request $request){
        $user = $request->attributes->get('user');
    
        if (!$user) {
            return response()->json(['error' => 'Unauthorized', 'status' => false, 'message' => 'Failed'], 401);
        }    
        
        $catagory = Catagory::all();
        $product = Product::all();
        $brand = brand::all();

        return response()->json([
            'status' => true,
            'product' => $product,
            'catagory'=>$catagory,
            'brand' => $brand
        ], 200);
    }



// add product 
    public function add_product(Request $request){    
        
        $user = $request->user();

        $request->validate([
            'category_id' => 'required|integer|max:255',
            'name' => 'required|string|max:255',
            'brand_id' => 'required|integer|max:255',
            'purchase_price' => 'required|numeric',
            'selling_price' => 'required|numeric',
            'discount' => 'required|numeric',
            'stock' => 'required|numeric'
        ]);

        if (!$user) {
            return response()->json(['error' => 'Unauthorized', 'status' => false, 'message' => 'Failed'], 401);
        }

        try {   
            function generateUniqueRandomNumber($prefix = 'P', $length = 5) {
                $uniqueId = uniqid();            
                $randomNumber = substr($uniqueId, -$length);
                $randomNumber = str_pad($randomNumber, $length, '0', STR_PAD_LEFT);                
                $uniqueNumber = $prefix . $randomNumber;
                return $uniqueNumber;
            }
            $newUniqueRandomNumber = generateUniqueRandomNumber('P', 5);
            
            $product = new Product();
            $product->category_id = $request->category_id;
            $product->name = $request->name;
            $product->product_code = $newUniqueRandomNumber;
            $product->brand_id = $request->brand_id;
            $product->purchase_price = $request->purchase_price;
            $product->selling_price = $request->selling_price;
            $product->discount = $request->discount;
            $product->stock = $request->stock;
            $product->save();
            
            return response()->json(['status' => true, 'product' => $product], 201);
        } catch (\Exception $e) {        
            return response()->json(['status' => false, 'message' => 'Already have data'], 500);
        }
    }


    public function productDelete(Request $request){
        $user = $request->attributes->get('user');          
        $items = Product::find($request->id);        
        if ($items) {
            $items->delete();
            $itemsall = Product::all();
            return response()->json(['status' => true, 'Deleted' =>$items, "all_data" => $itemsall], 200);
        } else {
            return response()->json(['status' => false], 200);
        }
        return response()->json(['status' => false], 200);        
    }



    public function get_brand(Request $request){      
      
        $user = $request->attributes->get('user');
        $brand = brand::all();

        if (!$user->id) {
            return response()->json([
                'error' => 'Unauthorized',
                'status' => false,
                'message' => 'failed'
            ], 401);
        }

        return response()->json([            
            'status' => true,            
            'data' => $brand
        ], 200);

    }


    // add brand
    public function add_brand(Request $request){    
        
        $user = $request->user();
        $request->validate([            
            'brand_name' => 'required|string|max:255',
        ]);

        if (!$user) {
            return response()->json(['error' => 'Unauthorized', 'status' => false, 'message' => 'Failed'], 401);
        }

        try {        
            $brand = new brand();
            $brand->brand_name = $request->brand_name;            
            $brand->save();
            
            return response()->json(['status' => true, 'brand' => $brand], 201);
        } catch (\Exception $e) {        
            return response()->json(['status' => false, 'message' => 'Already have data'], 500);
        }
    }




      // userlist
      public function userlist(Request $request){    
        try {                
            $user = $request->user();       

            if (!$user) {
                return response()->json(['error' => 'Unauthorized', 'status' => false, 'message' => 'Failed'], 401);
            }

            $user = User::all();
            
            return response()->json(['status' => true, 'user' => $user], 201);
        } catch (\Exception $e) {        
            return response()->json(['status' => false, 'message' => 'Already have data'], 500);
        }
    }



    // user type change
    public function user_typechange(Request $request){    
        try {        
        
            $user = $request->user();                   
            if (!$user) {
                return response()->json(['error' => 'Unauthorized', 'status' => false, 'message' => 'Failed'], 401);
            }

            $User = User::find($request->id);            
            $User->is_type = ($User->is_type === "is_admin") ? "is_user" : "is_admin";            
            $User->save();
            
            return response()->json(['status' => true, 'user' => $User], 201);
        } catch (\Exception $e) {        
            return response()->json(['status' => false, 'message' => 'Already have data'], 500);
        }
    }










}