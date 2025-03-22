<?php

namespace App\Http\Controllers\Auth;

use App\Models\DonatePaypal;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Mail\PaypalDonateNotifyAdministrator;
use Illuminate\Support\Facades\Mail;

class DonatePaypalController extends Controller
{
    public function __invoke(Request $request)
    {
        if ($request->user()) {
            $user = $request->user();
            DonatePaypal::create([
                'tx' => $request->tx,
                'amount' => $request->amt,
                'item_name' => $request->item_name,
                'item_number' => 1,
                'payment_status' => $request->st,
                'memebr_id' => $user->ID,
            ]);

            $cash = $request->amt * 100 + $request->amt * 100 * 0.15;
            Mail::to(config('app.admin_email'))
                ->later(now()->addSeconds(1), new PaypalDonateNotifyAdministrator([
                    'account' => $user->ID,
                    'amount' => $request->amt,
                    'cash' => $cash,
                    'orderId' => $request->tx,
                ]));

            return response()->json(['success' => true, 'message' => 'Donation successfully sent thank you for your support']);
        }

        return response()->json(['success' => false, 'message' => 'User not found']);
    }
}
