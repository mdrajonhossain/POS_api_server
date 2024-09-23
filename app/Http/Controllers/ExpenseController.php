<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\expenses;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class ExpenseController extends Controller{
    //


    public function get_expenses(Request $request){      
      
        $user = $request->attributes->get('user');
        $expenses = expenses::all();

        if (!$user->id) {
            return response()->json([
                'error' => 'Unauthorized',
                'status' => false,
                'message' => 'failed'
            ], 401);
        }

        return response()->json([            
            'status' => true,            
            'data' => $expenses
        ], 200);

    }



    public function add_expenses(Request $request){
        $user = $request->user();
    
        // Validate incoming request data
        $request->validate([
            'description' => 'required|string|max:255',            
            'amount' => 'required|numeric',
        ]);
    
        // Check authorization
        if (!$user) {
            return response()->json(['error' => 'Unauthorized', 'status' => false, 'message' => 'Unauthorized request'], 401);
        }
    
        try {
            // Create a new supplier
            $expenses = new expenses();
            $expenses->description = $request->description;
            $expenses->amount = $request->amount;
            $expenses->save();
    
            // Return success response
            return response()->json(['status' => true, 'data' => $expenses], 201);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => 'Failed to create customer'], 500);
        }
    }



    public function expensesUpdate(Request $request){
        $user = $request->user();
            
        $request->validate([
            'id' => 'required|integer',
            'description' => 'required|string|max:255',            
            'amount' => 'required|numeric',
        ]);
            
        if (!$user) {
            return response()->json(['error' => 'Unauthorized', 'status' => false, 'message' => 'Unauthorized request'], 401);
        }
    
        try {
            $expenses = expenses::find($request->id);  
            $expenses->description = $request->description;
            $expenses->amount = $request->amount;
            $expenses->save();
                
            return response()->json(['status' => true, 'data' => $expenses], 201);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => 'Failed to create customer'], 500);
        }
    }




    public function expensesDelete(Request $request){
        $user = $request->attributes->get('user');          
        $expens = expenses::find($request->id);        
        if ($expens) {
            $expens->delete();
            $expensesall = expenses::all();
            return response()->json(['status' => true, 'Deleted_salese' =>$expens, "all_data" => $expensesall], 200);
        } else {
            return response()->json(['status' => false], 200);
        }
        return response()->json(['status' => false], 200);        
    }


















}
