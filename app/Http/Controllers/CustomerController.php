<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Plan;
use App\Models\User;

use Illuminate\Support\Facades\Auth;
use Laravel\Cashier\Cashier;
use \Stripe\Stripe;
class CustomerController extends Controller
{
    //
    public function __construct() 
    {

        $this->stripe = new \Stripe\StripeClient(env('STRIPE_SECRET'));
    }



    public function customer(Request $resquest)
    {
        
        $user = Auth::user();
            $this->stripe->customers->create([
            'name' => $user->name,
            'id'   => $user->id,
            'email'=> $user->email
            ]);
    }

    public function showcustomer()
    {   
      
               
        $all=$this->stripe->customers->all();  

        return response()->json([$all],200);
    }



}
