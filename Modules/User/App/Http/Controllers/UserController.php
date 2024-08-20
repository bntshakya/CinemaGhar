<?php

namespace Modules\User\App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\CustomerCard;
use App\Models\CustomerSubscription;
use App\Models\TicketBooking;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Auth;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('user::index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('user::create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        //
    }

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        $stripe = new \Stripe\StripeClient(env('STRIPE_SECRET_KEY'));
        $customer = Auth::user();
        $customerId = $customer->StripeCustomerId;
        $paymentMethods = $stripe->paymentMethods->all([
            'customer' => $customerId,
            'type' => 'card',
        ]);
        return view('user::show',['paymentMethods' => $paymentMethods]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        return view('user::edit');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id): RedirectResponse
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        //
    }

    public function viewcards(){
        return view('user::addCard');
    }

    public function addCards(Request $request){
        $user = \Auth::user();
        $stripeCustomerId = $user->StripeCustomerId;
        $stripe = new \Stripe\StripeClient(env('STRIPE_SECRET_KEY'));
        $abc = $stripe->customers->createSource($stripeCustomerId,['source'=>'tok_visa']);
        
    }

    public function payment(Request $request)
    {
        \Stripe\Stripe::setApiKey(env('STRIPE_SECRET_KEY'));

        $stripe = new \Stripe\StripeClient(env('STRIPE_SECRET_KEY'));
        // $user = Auth::user();
        // // dd($user);
        // $stripeCustomerId = $user->StripeCustomerId;
        // $abc = $stripe->paymentMethods->all([
        //     'customer' => $stripeCustomerId,
        //     'type' => 'card',
        // ]);

        // dd($abc);
        $stripe->paymentMethods->attach(
            'pm_card_mastercard_debit',
            ['customer' => 'cus_QZ0Yq0WQDFCg9D']
        );
    //     $paymentMethod = \Stripe\PaymentMethod::create([
    //         'type' => 'card',
    //         ['source'=>'tok_amex']
    //     ]);

    //     // Retrieve the customer
    //     $customer = \Stripe\Customer::retrieve('cus_QZ0Yq0WQDFCg9D');

    //     // Attach the Payment Method to the Customer
    //     $paymentMethod->attach([
    //         'customer' => $customer->id,
    //     ]);
    }

    public function viewPaymentMethods(){
        $customer = Auth::user();
        $customerId = $customer->StripeCustomerId;
        $stripe = new \Stripe\StripeClient(env('STRIPE_SECRET_KEY'));
        $paymentMethods = $stripe->paymentMethods->all([
            'customer' => $customerId,
            'type' => 'card',
        ]);
        if(CustomerCard::where('CustomerId', $customerId)->where('isDefault', 1)->exists()){
            $defaultMethod = CustomerCard::where('CustomerId',$customerId)->where('isDefault',1)->first();
            $defaultMethodCard = $stripe->customers->retrievePaymentMethod(
                $customerId,
                $defaultMethod->CardId,
            );
        }else{
            $defaultMethodCard = false;
        }
        return view('user::CustomerCards',['paymentMethods'=>$paymentMethods,'defaultMethod'=>$defaultMethodCard]);
    }

    public function setCard(Request $request){
        $cardId = $request->input('paymentMethod');
        $customerId = Auth::user()->StripeCustomerId;
        // dd(CustomerCard::all());
        $CustomerCardDefault = CustomerCard::where('CustomerId', $customerId)
            ->where('CardId', $cardId)
            ->first();
        if ($CustomerCardDefault) {
            CustomerCard::where('CustomerId',$customerId)->update(['isDefault'=>false]);
            $CustomerCardDefault->update(['isDefault' => true]);
        }else{
            return redirect()->back()->with('status', 'Card doesnt exist');
        }
        return redirect()->back()->with('status', 'Default card set successfully.');

    }

    public function viewSubscriptions(){
        $customerId = Auth::user()->StripeCustomerId;
        $SubscriptionExists = CustomerSubscription::where('customerId',$customerId)->exists();
        return view('user::SubscriptionPage',['SubscriptionExists'=>$SubscriptionExists]);
    }

    public function selectSubscription()
    {
        $stripe = new \Stripe\StripeClient(env('STRIPE_SECRET_KEY'));
        $productId = env('STRIPE_PRO_PRODUCT_ID');
        $priceId = env('STRIPE_PRO_PRICE_ID');
        $customerId = Auth::user()->StripeCustomerId;
        $defaultMethod = CustomerCard::where('CustomerId', $customerId)->where('isDefault', 1)->first();
        $customer = CustomerSubscription::where('customerId', $customerId)->first();

        // Check if the customer does not already have a subscription
        if (is_null($customer)) {
            $subscription = $stripe->subscriptions->create([
                'customer' => $customerId,
                'items' => [
                    [
                        'price' => $priceId,
                    ]
                ],
                'collection_method' => 'charge_automatically',
                'default_payment_method' => $defaultMethod->CardId,
            ]);
            CustomerSubscription::create(['customerId' => $customerId, 'subscriptionId' => $subscription->id]);
            return redirect()->route('User.Subscriptions')->with(['msg', 'Successfully created subscription']);
        } else {
            return redirect()->route('User.Subscriptions')->with(['msg', 'Subscription already exists']);
        }
    }


    public function selectSubscriptionMethod(){
        return view('user::subscribe');
    }

    public function deleteSubscription(){
        $stripe = new \Stripe\StripeClient(env('STRIPE_SECRET_KEY'));
        $customerId = $customerId = Auth::user()->StripeCustomerId;
        $ifexists = CustomerSubscription::where('customerId',$customerId)->exists();
        if($ifexists){
            $subscriptionId = CustomerSubscription::where('customerId',$customerId)->first('subscriptionId');
            $stripe->subscriptions->cancel($subscriptionId->subscriptionId);
            CustomerSubscription::where('customerId',$customerId)->delete();
        }
        return redirect()->route('User.Subscriptions')->with(['msg', 'successfully updated subscription']);
    }

    public function selectPayment(){
        $stripe = new \Stripe\StripeClient(env('STRIPE_SECRET_KEY'));
        $customer = Auth::user();
        $customerId = $customer->StripeCustomerId;
        $paymentMethods = $stripe->paymentMethods->all([
            'customer' => $customerId,
            'type' => 'card',
        ]);
        return view('user::PaymentPage',['paymentMethods'=>$paymentMethods]);
    }

    public function cancelBookedTickets(Request $request){
        $id = $request['CancelId'];
        TicketBooking::find($id)->delete();
        return redirect()->back()->with(['msg' => 'successfully deleted bookings']);
    }

}
