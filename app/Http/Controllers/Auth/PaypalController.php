<?php

namespace App\Http\Controllers\Auth;

use App\Models\Account;
use App\Models\DonatePaypal;
use Illuminate\Http\Request;
use App\Models\UserPaypalDonate;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Crypt;
use Srmklive\PayPal\Services\PayPal as PayPalClient;

class PaypalController extends Controller
{
    public function paypal(Request $request)
    {
        if (session()->token() !== request()->session()->token()) {
            return '';
        }

        $points = DonatePaypal::find($request->donate);

        $provider = new PayPalClient();
        $provider->setApiCredentials(config('paypal'));
        $paypalToken = $provider->getAccessToken();

        $response = $provider->createOrder([
            'intent' => 'CAPTURE',
            'application_context' => [
                'shipping' => 0.00,
                'currency_code' => 'EUR',
                'user_action' => 'PAY_NOW',
                'business' => '',
                'product_name' => $points->name . ' - ' . $points->description,
                'product_description' => $points->description,
                'return_url' => route('paypal-checkout.success'),
                'cancel_url' => route('paypal-checkout.cancel'),
                'description' => $points->description,
            ],
            'purchase_units' => [
                [
                    'amount' => [
                        'currency_code' => 'EUR',
                        'value' => $points->amount,
                        'description' => $points->description,
                    ],
                    'description' => $points->description,
                ],
            ],
        ]);

        $secret = Crypt::encrypt($points['id']);
        $account = Crypt::encrypt($request->account);

        try {
            if (isset($response['id']) && $response['id'] != null) {
                foreach ($response['links'] as $link) {
                    if ($link['rel'] === 'approve') {
                        session()->put('secret', $secret);
                        session()->put('idx', $account);

                        return redirect()->away($link['href']);
                    }
                }
            } else {
                return redirect()->route('paypal-checkout.cancel');
            }
        } catch (\Exception $e) {
            dd($e->getMessage());
        }
    }

    public function success(Request $request)
    {
        $provider = new PayPalClient();
        $provider->setApiCredentials(config('paypal'));

        $paypalToken = $provider->getAccessToken();

        $response = $provider->capturePaymentOrder($request->token);

        $curl = curl_init("https://api.paypal.com/v1/checkout/orders/{$request->token}");

        curl_setopt($curl, CURLOPT_POST, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $paypalToken['access_token'],
            'Accept: application/json',
            'Content-Type: application/json',
        ]);

        // only available in live
        // $curlResponse = curl_exec($curl);
        // $result = json_decode($curlResponse);

        // if ($result->id != $request->token && $result->status != 'COMPLETED') {
        //     return '';
        // }

        fwrite(fopen(storage_path('logs/paypal.log'), 'a'), auth()->id() . PHP_EOL);
        fwrite(fopen(storage_path('logs/paypal.log'), 'a'), json_encode($response) . PHP_EOL);
        // fwrite(fopen(storage_path('logs/paypal.log'), 'a'), json_encode($result) . PHP_EOL);


        if($request->PayerID && $request->token) {
            return DB::transaction(function () use ($request, $response) {
                $idx = Crypt::decrypt(session()->get('idx'));
                $secret = Crypt::decrypt(session()->get('secret'));

                $userPaypalDonate = UserPaypalDonate::create([
                    'user_id' => auth()->id(),
                    'account' => $idx,
                    'donate_paypal_id' => $secret,
                    'transaction_id' => $response['purchase_units'][0]['payments']['captures'][0]['id'],
                    'reference_number' => $request->token . '_' . $response['payer']['payer_id'],
                    'status' => $response['purchase_units'][0]['payments']['captures'][0]['status'],
                    'paypal_response' => json_encode($response),
                ]);

                $userPaypalDonate->refresh()->load(['user', 'user.account', 'donatePaypal']);

                $account = Account::find($idx);
                $amount = $userPaypalDonate->donatePaypal->equivalent_donate_points;
                $currentCash = $account->cash;
                $userAccount = $account->account;
                $userModifiedCash = intval($currentCash) + intval($amount);

                $db = DB::connection('ACCOUNT_DBF');
                $stmt = $db->getPdo()->prepare('EXEC dbo.usp_UpdateCashInfo ?, ?');
                $stmt->bindParam(1, $userAccount);
                $stmt->bindParam(2, $userModifiedCash);
                $stmt->execute();

                return redirect()->route('me.dashboard')->with('success', 'Transaction success.');
            });

        } else {
            return redirect()->route('paypal-checkout.cancel');
        }
    }

    public function cancel(Request $request)
    {
        return redirect()->route('donates');
    }
}
