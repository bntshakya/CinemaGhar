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
use HTTP_Request2;
use HTTP_Request2_Exception;

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
        return view('user::show', ['paymentMethods' => $paymentMethods]);
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

    public function viewcards()
    {
        return view('user::addCard');
    }

    public function addCards(Request $request)
    {
        $user = \Auth::user();
        $stripeCustomerId = $user->StripeCustomerId;
        $stripe = new \Stripe\StripeClient(env('STRIPE_SECRET_KEY'));
        $abc = $stripe->customers->createSource($stripeCustomerId, ['source' => 'tok_visa']);

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

    public function viewPaymentMethods()
    {
        // dd(env('HELLO_HERO'));

        $customer = Auth::user();
        $customerId = $customer->StripeCustomerId;
        $stripe = new \Stripe\StripeClient(env('STRIPE_SECRET_KEY'));
        $paymentMethods = $stripe->paymentMethods->all([
            'customer' => $customerId,
            'type' => 'card',
        ]);
        if (CustomerCard::where('CustomerId', $customerId)->where('isDefault', 1)->exists()) {
            $defaultMethod = CustomerCard::where('CustomerId', $customerId)->where('isDefault', 1)->first();
            $defaultMethodCard = $stripe->customers->retrievePaymentMethod(
                $customerId,
                $defaultMethod->CardId,
            );
        } else {
            $defaultMethodCard = false;
        }
        return view('user::CustomerCards', ['paymentMethods' => $paymentMethods, 'defaultMethod' => $defaultMethodCard]);
    }

    public function setCard(Request $request)
    {
        $cardId = $request->input('paymentMethod');
        $customerId = Auth::user()->StripeCustomerId;
        // dd(CustomerCard::all());
        $CustomerCardDefault = CustomerCard::where('CustomerId', $customerId)
            ->where('CardId', $cardId)
            ->first();
        if ($CustomerCardDefault) {
            CustomerCard::where('CustomerId', $customerId)->update(['isDefault' => false]);
            $CustomerCardDefault->update(['isDefault' => true]);
        } else {
            return redirect()->back()->with('status', 'Card doesnt exist');
        }
        return redirect()->back()->with('status', 'Default card set successfully.');

    }

    public function viewSubscriptions()
    {
        $customerId = Auth::user()->StripeCustomerId;
        $SubscriptionExists = CustomerSubscription::where('customerId', $customerId)->exists();
        return view('user::SubscriptionPage', ['SubscriptionExists' => $SubscriptionExists]);
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


    public function selectSubscriptionMethod()
    {
        return view('user::subscribe');
    }

    public function deleteSubscription()
    {
        $stripe = new \Stripe\StripeClient(env('STRIPE_SECRET_KEY'));
        $customerId = $customerId = Auth::user()->StripeCustomerId;
        $ifexists = CustomerSubscription::where('customerId', $customerId)->exists();
        if ($ifexists) {
            $subscriptionId = CustomerSubscription::where('customerId', $customerId)->first('subscriptionId');
            $stripe->subscriptions->cancel($subscriptionId->subscriptionId);
            CustomerSubscription::where('customerId', $customerId)->delete();
        }
        return redirect()->route('User.Subscriptions')->with(['msg', 'successfully updated subscription']);
    }

    public function selectPayment()
    {
        $stripe = new \Stripe\StripeClient(env('STRIPE_SECRET_KEY'));
        $customer = Auth::user();
        $customerId = $customer->StripeCustomerId;
        $paymentMethods = $stripe->paymentMethods->all([
            'customer' => $customerId,
            'type' => 'card',
        ]);
        return view('user::PaymentPage', ['paymentMethods' => $paymentMethods]);
    }

    public function cancelBookedTickets(Request $request)
    {
        $id = $request['CancelId'];
        TicketBooking::find($id)->delete();
        return redirect()->back()->with(['msg' => 'successfully deleted bookings']);
    }

    public function verifyOtp()
    {
        $request = new HTTP_Request2();
        $request->setUrl('https://vv5ywm.api.infobip.com/2fa/2/applications');
        $request->setMethod(HTTP_Request2::METHOD_POST);
        $request->setConfig(array(
            'follow_redirects' => TRUE
        ));
        $request->setHeader(array(
            'Authorization' => 'App ' . env('INFOBIP_API_KEY'),
            'Content-Type' => 'application/json',
            'Accept' => 'application/json'
        ));
        $request->setBody('{"name":"2fa test application","enabled":true,"configuration":{"pinAttempts":10,"allowMultiplePinVerifications":true,"pinTimeToLive":"15m","verifyPinLimit":"1/3s","sendPinPerApplicationLimit":"100/1d","sendPinPerPhoneNumberLimit":"10/1d"}}');
        try {
            $response = $request->send();
            if ($response->getStatus() == 200) {
                echo $response->getBody();
            } else {
                return $response->getBody();
            }
        } catch (HTTP_Request2_Exception $e) {
            echo 'Error: ' . $e->getMessage();
        }
    }

    public function otpMsgTemplate(Request $request)
    {
        $appId = $request->input('appId');
        // dd($appId);
        $request = new HTTP_Request2();
        $request->setUrl("https://vv5ywm.api.infobip.com/2fa/2/applications/{$appId}/messages");
        // dd($request,$appId);
        $request->setMethod(HTTP_Request2::METHOD_POST);
        $request->setConfig(array(
            'follow_redirects' => TRUE
        ));
        $request->setHeader(array(
            'Authorization' => 'App ' . env('INFOBIP_API_KEY'),
            'Content-Type' => 'application/json',
            'Accept' => 'application/json'
        ));
        $request->setBody('{"pinType":"NUMERIC","messageText":"Your OTP code is {{pin}}","pinLength":4,"senderId":"ServiceSMS"}');
        try {
            $response = $request->send();
            if ($response->getStatus() == 200) {
                echo $response->getBody();
            } else {
                echo 'Unexpected HTTP status: ' . $response->getStatus() . ' ' .
                    $response->getReasonPhrase();
            }
        } catch (HTTP_Request2_Exception $e) {
            echo 'Error: ' . $e->getMessage();
        }


    }

    public function otpMsgDeliver(Request $request)
    {
        $applicationId = $request->input('appId');
        $messageId = $request->input('msgId');
        $request = new HTTP_Request2();
        $request->setUrl('https://vv5ywm.api.infobip.com/2fa/2/pin');
        $request->setMethod(HTTP_Request2::METHOD_POST);
        $request->setConfig(array(
            'follow_redirects' => TRUE
        ));
        $request->setHeader(array(
            'Authorization' => 'App ' . env('INFOBIP_API_KEY'),
            'Content-Type' => 'application/json',
            'Accept' => 'application/json'
        ));
        $request->setBody('{"applicationId":"' . $applicationId . '","messageId":"' . $messageId . '","from":"ServiceSMS","to":"9779840528745"}');
        try {
            $response = $request->send();
            if ($response->getStatus() == 200) {
                echo $response->getBody();
            } else {
                echo 'Unexpected HTTP status: ' . $response->getStatus() . ' ' .
                    $response->getReasonPhrase();
            }
        } catch (HTTP_Request2_Exception $e) {
            echo 'Error: ' . $e->getMessage();
        }
    }

    public function otpVerify(Request $request)
    {
        $pinId = $request->input('pinId');
        $pinCode = $request->input('pinCode');
        $request = new HTTP_Request2();
        $request->setUrl("https://vv5ywm.api.infobip.com/2fa/2/pin/{$pinId}/verify");
        $request->setMethod(HTTP_Request2::METHOD_POST);
        $request->setConfig(array(
            'follow_redirects' => TRUE
        ));
        $request->setHeader(array(
            'Authorization' => 'App ' . env('INFOBIP_API_KEY'),
            'Content-Type' => 'application/json',
            'Accept' => 'application/json'
        ));
        $request->setBody("{\"pin\":\"{$pinCode}\"}");
        try {
            $response = $request->send();
            if ($response->getStatus() == 200) {
                echo $response->getBody();
            } else {
                echo 'Unexpected HTTP status: ' . $response->getStatus() . ' ' . $response->getBody() .
                    $response->getReasonPhrase();
            }
        } catch (HTTP_Request2_Exception $e) {
            echo 'Error: ' . $e->getMessage();
        }
    }

    public function otpResend(Request $request)
    {
        $pinId = $request->input('pinId');
        $request = new HTTP_Request2();
        $request->setUrl("https://vv5ywm.api.infobip.com/2fa/2/pin/{$pinId}/resend");
        $request->setMethod(HTTP_Request2::METHOD_POST);
        $request->setConfig(array(
            'follow_redirects' => TRUE
        ));
        $request->setHeader(array(
            'Authorization' => 'App ' . env('INFOBIP_API_KEY'),
            'Content-Type' => 'application/json',
            'Accept' => 'application/json'
        ));
        try {
            $response = $request->send();
            if ($response->getStatus() == 200) {
                echo $response->getBody();
            } else {
                echo 'Unexpected HTTP status: ' . $response->getStatus() . ' ' . $response->getBody() .
                    $response->getReasonPhrase();
            }
        } catch (HTTP_Request2_Exception $e) {
            echo 'Error: ' . $e->getMessage();
        }
    }
}
