<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\purchase;
use App\Models\Supplier;
use App\Models\Product;
use App\Models\Catagory;
use App\Models\Customer;
use App\Models\sales;
use App\Models\expenses;
use App\Models\User;
// use App\Models\purchase_details;
use App\Models\purchase_details;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class IncomController extends Controller{
    
    //       
    public function income(Request $request){   
      
        $user = $request->attributes->get('user');
        $expenses = expenses::all();
        $purchase = purchase::all();
        $sales = sales::all();


        function sum_by_date($array, $key) {
            $result = [];
            foreach ($array as $item) {
                $date = date('Y-m-d', strtotime($item['created_at']));
                
                if (isset($result[$date])) {
                    $result[$date] += $item[$key];
                } else {
                    $result[$date] = $item[$key];
                }
            }
            return $result;
        }


        $purchases_sum = sum_by_date($purchase, 'total_price');
        $sales_sum = sum_by_date($sales, 'total_price');
        $expenses_sum = sum_by_date($expenses, 'amount');

        $final_table = [];
        $all_dates = array_unique(array_merge(array_keys($purchases_sum), array_keys($sales_sum), array_keys($expenses_sum)));

        foreach ($all_dates as $date) {
            $purchase_total = isset($purchases_sum[$date]) ? $purchases_sum[$date] : 0;
            $sales_total = isset($sales_sum[$date]) ? $sales_sum[$date] : 0;
            $expenses_total = isset($expenses_sum[$date]) ? $expenses_sum[$date] : 0;

            $final_table[] = [
                'created_at' => $date,
                'purchase_total' => $purchase_total,
                'sales_total' =>  $sales_total,
                'expenses' =>  $expenses_total,
                'net_amount' => $sales_total - ($purchase_total + $expenses_total)
            ];
        }
        
        
$today = date('Y-m-d');
$totalNetAmount = array_reduce($final_table, function($carry, $item) use ($today) {
    if ($item['created_at'] === $today) {
        return $carry + $item['net_amount'];
    }
    return $carry;
}, 0);




$month = date('Y-m');
$totalmonth = array_reduce($final_table, function($a, $item) use ($month) {    
    $itemMonth = substr($item['created_at'], 0, 7); 
    if ($itemMonth == $month) {
        return $a + $item['net_amount'];
    }
    return $a;
}, 0);




$year = date('Y');
$totalYear = array_reduce($final_table, function($sum, $item) use ($year) {    
    // Extract the year from 'created_at'
    $itemYear = date('Y', strtotime($item['created_at'])); 
    
    if ($itemYear == $year) {
        return $sum + (float)$item['net_amount']; // Ensure net_amount is numeric
    }
    return $sum;
}, 0);


$today = date('Y-m-d');
$startOfWeek = date('Y-m-d', strtotime('monday this week'));
$endOfWeek = date('Y-m-d', strtotime('sunday this week'));

$weekly = array_reduce($final_table, function($carry, $item) use ($startOfWeek, $endOfWeek) {
    if ($item['created_at'] >= $startOfWeek && $item['created_at'] <= $endOfWeek) {
        return $carry + $item['net_amount'];
    }
    return $carry;
}, 0);


             
        if (!$user->id) {
            return response()->json([
                'error' => 'Unauthorized',
                'status' => false,
                'message' => 'failed'
            ], 401);
        }

        return response()->json([            
            'status' => true,   
            "weekly"=>$weekly,
            "Year" => $totalYear,
            "month" => $totalmonth,
            "tody"       => $totalNetAmount,
            "allcounter" => $final_table
        ], 200);

    }





    public function dashcounter(Request $request){   
      
        $user = $request->attributes->get('user');
        $expenses = expenses::all();
        $purchase = purchase::all();
        $sales = sales::all();


        function sum_by_date($array, $key) {
            $result = [];
            foreach ($array as $item) {
                $date = date('Y-m-d', strtotime($item['created_at']));
                
                if (isset($result[$date])) {
                    $result[$date] += $item[$key];
                } else {
                    $result[$date] = $item[$key];
                }
            }
            return $result;
        }


        $purchases_sum = sum_by_date($purchase, 'total_price');
        $sales_sum = sum_by_date($sales, 'total_price');
        $expenses_sum = sum_by_date($expenses, 'amount');

        $final_table = [];
        $all_dates = array_unique(array_merge(array_keys($purchases_sum), array_keys($sales_sum), array_keys($expenses_sum)));

        foreach ($all_dates as $date) {
            $purchase_total = isset($purchases_sum[$date]) ? $purchases_sum[$date] : 0;
            $sales_total = isset($sales_sum[$date]) ? $sales_sum[$date] : 0;
            $expenses_total = isset($expenses_sum[$date]) ? $expenses_sum[$date] : 0;

            $final_table[] = [
                'created_at' => $date,
                'purchase_total' => $purchase_total,
                'sales_total' =>  $sales_total,
                'expenses' =>  $expenses_total,
                'net_amount' => $sales_total - ($purchase_total + $expenses_total)
            ];
        }
        
        
$today = date('Y-m-d');
$totalNetAmount = array_reduce($final_table, function($carry, $item) use ($today) {
    if ($item['created_at'] === $today) {
        return $carry + $item['net_amount'];
    }
    return $carry;
}, 0);




$month = date('Y-m');
$totalmonth = array_reduce($final_table, function($a, $item) use ($month) {    
    $itemMonth = substr($item['created_at'], 0, 7); 
    if ($itemMonth == $month) {
        return $a + $item['net_amount'];
    }
    return $a;
}, 0);




$year = date('Y');
$totalYear = array_reduce($final_table, function($sum, $item) use ($year) {    
    // Extract the year from 'created_at'
    $itemYear = date('Y', strtotime($item['created_at'])); 
    
    if ($itemYear == $year) {
        return $sum + (float)$item['net_amount'];
    }
    return $sum;
}, 0);


$today = date('Y-m-d');
$startOfWeek = date('Y-m-d', strtotime('monday this week'));
$endOfWeek = date('Y-m-d', strtotime('sunday this week'));

$weekly = array_reduce($final_table, function($carry, $item) use ($startOfWeek, $endOfWeek) {
    if ($item['created_at'] >= $startOfWeek && $item['created_at'] <= $endOfWeek) {
        return $carry + $item['net_amount'];
    }
    return $carry;
}, 0);


             
        if (!$user->id) {
            return response()->json([
                'error' => 'Unauthorized',
                'status' => false,
                'message' => 'failed'
            ], 401);
        }



    $allcounter = [
    ["routeName" => 0, "title" => "Today", "counter" => "Tk. ".$totalNetAmount],
    ["routeName" => 0, "title" => "Weekly", "counter" => "Tk. ".$weekly],
    ["routeName" => 0, "title" => "Monthly", "counter" => "Tk. ".$totalmonth],
    ["routeName" => 0, "title" => "Yearly", "counter" => "Tk. ".$totalYear], 
    ["routeName" => 8, "title" => "Sales", "counter" => "Counter: ".sales::count()],
    ["routeName" => 4, "title" => "Category", "counter" => "Counter: ".Catagory::count()],
    ["routeName" => 5, "title" => "Product", "counter" => "Counter: ".Product::count()],
    ["routeName" => 7, "title" => "Purchase", "counter" => "Counter: ".purchase::count()],
    ["routeName" => 6, "title" => "Expenses", "counter" => "Counter: ".expenses::count()],
    ["routeName" => 1, "title" => "Customer", "counter" => "Counter: ".Customer::count()],
    ["routeName" => 2, "title" => "Supplier", "counter" => "Counter: ".Supplier::count()],
    ["routeName" => 10, "title" => "User", "counter" => "Counter: ".User::count()],
];


        return response()->json([            
            'status' => true,
            "data" => $allcounter
        ], 200);

    }






}