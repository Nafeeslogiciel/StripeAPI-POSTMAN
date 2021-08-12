<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Plan;
use App\Models\User;

use Illuminate\Support\Facades\Auth;
use Laravel\Cashier\Cashier;
use \Stripe\Stripe;
class SubscriptionController extends Controller
{
    //
     

    public function __construct() 
    {

        $this->stripe = new \Stripe\StripeClient(env('STRIPE_SECRET'));
    }

    public function create(Request $request,$id)
    {
       
       $plan = Plan::findOrFail($id);
         
        $user = Auth::user();
        $paymentMethod=$this->stripe->paymentMethods->create([
                        'type' => 'card',
                                'card' => [
                                        'number' => '4242424242424242',
                                        'exp_month' => 8,
                                        'exp_year' => 2022,
                                        'cvc' => '314',
                                     ],
                                    ]);
       
        $user->createOrGetStripeCustomer();

        $user->updateDefaultPaymentMethod($paymentMethod);
        
        $user->newSubscription('default',$plan->stripe_plan)
            ->create($paymentMethod, [
                'email' => $user->email,
            ]);
        
        return response()->json(['success', 'Your plan subscribed successfully'],200);
    }



    public function storePlan(Request $request)
    {   
        $data = $request->all();

        $data['slug'] = strtolower($data['name']);
        $price = $data['cost'] *100; 

        //create stripe product
        $stripeProduct = $this->stripe->products->create([
            'name' => $data['name'],
        ]);
        
        //Stripe Plan Creation
        $stripePlanCreation = $this->stripe->plans->create([
            'amount' => $price,
            'currency' => 'inr',
            'interval' => 'month', //  it can be day,week,month or year
            'product' => $stripeProduct->id,
        ]);

        $data['stripe_plan'] = $stripePlanCreation->id;

        Plan::create($data);

        echo 'plan has been created';
    }

    



}
