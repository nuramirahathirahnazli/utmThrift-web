<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

use App\Models\Item;
use App\Models\Order;
use App\Models\Itemscart;


class ToyyibPayController extends Controller
{
    public function createBill(Request $request)
    {
        Log::info('Raw incoming request:', $request->all());

        $validated = $request->validate([
            'amount' => 'required|numeric|min:100',
            'email' => 'required|email',
            'name' => 'required|string',
            'phone' => 'required|string',
            'description' => 'required|string',
            'order_id' => 'required|exists:orders,id',
        ]);

        try {
            $data = [
                'userSecretKey'          => env('TOYYIBPAY_KEY'),
                'categoryCode'           => env('TOYYIBPAY_CATEGORY'),
                'billName'               => 'UTMThrift Item Purchase',
                'billDescription'        => $validated['description'],
                'billPriceSetting'       => 1,
                'billPayorInfo'          => 1,
                'billAmount'             => $validated['amount'],
                'billReturnUrl'          => env('TOYYIBPAY_RETURN_URL', 'http://localhost:8000/payment-success'),
                'billCallbackUrl'        => env('TOYYIBPAY_CALLBACK_URL', 'http://localhost:8000/api/payment-callback'),
                'billExternalReferenceNo'=> 'UTMTHRIFT' . uniqid(),
                'billTo'                 => $validated['name'],
                'billEmail'              => $validated['email'],
                'billPhone'              => $validated['phone'],
            ];

            \Log::info('Sending to ToyyibPay:', $data);

            $response = Http::asForm()->post('https://dev.toyyibpay.com/index.php/api/createBill', $data);

            \Log::info('ToyyibPay Response:', [
                'status' => $response->status(),
                'body' => $response->body()
            ]);

            $responseBody = $response->json();

            if ($response->successful() && isset($responseBody[0]['BillCode'])) {
                $billCode = $responseBody[0]['BillCode'];

                $order = Order::find($validated['order_id']);
                $order->bill_code = $billCode;
                $order->save();

                return response()->json([
                    'success' => true,
                    'bill_url' => env('TOYYIBPAY_BILL_BASE_URL') . $billCode,

                ]);
            }

            \Log::error('ToyyibPay Error: ' . $response->body());

            return response()->json([
                'success' => false,
                'error' => 'Invalid response from ToyyibPay API.',
                'details' => $response->body()
            ], 502);
        } catch (\Exception $e) {
            Log::error('ToyyibPay Exception: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'error' => 'Something went wrong while creating bill.'
            ], 500);
        }
    }

    public function paymentSuccess(Request $request)
    {
        Log::info('Payment Success Received:', $request->all());

        $statusId = $request->query('status_id');
        $billCode = $request->query('billcode');
        $orderId = $request->query('order_id');
        $transactionId = $request->query('transaction_id');
        $msg = $request->query('msg');

        $order = Order::where('bill_code', $billCode)->first();

        if (!$order) {
            Log::error("Payment success: Order not found for BillCode $billCode");
            abort(404, 'Order not found');
        }

        DB::beginTransaction();

        try {
            // Update order status
            switch ($statusId) {
                case 1:
                    $order->order_status = 'completed';
                    break;
                case 2:
                    $order->order_status = 'failed';
                    break;
                default:
                    $order->order_status = 'pending';
                    break;
            }
            $order->save();

            // Update item stock & status if success
            if ($statusId == 1) {
                $item = Item::find($order->item_id);
                if ($item) {
                    $item->quantity -= $order->quantity;
                    if ($item->quantity <= 0) {
                        $item->status = 'sold';
                    }
                    $item->save();
                }

                // Remove item from buyer's cart
                Itemscart::where('user_id', $order->buyer_id)
                    ->where('item_id', $order->item_id)
                    ->delete();
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Payment success update failed: ' . $e->getMessage());
            return response()->json(['error' => 'Payment succeeded, but order update failed.'], 500);
        }

        $access_token = session('access_token') ?? '';

        return view('users.buyers.payment.success', [
            'statusId' => $statusId,
            'billCode' => $billCode,
            'orderId' => $orderId,
            'transactionId' => $transactionId,
            'msg' => $msg,
            'paymentMethod' => $order->payment_method ?? 'N/A',
            'timestamp' => $order->updated_at ?? now(),
            'itemName' => $order->item_name ?? 'Item Name',
            'quantity' => $order->quantity ?? 1,
            'access_token' => $access_token,
        ]);
    }

    public function paymentCallback(Request $request)
    {
        $billCode = $request->billcode;
        $status = $request->status;

        $order = Order::where('bill_code', $billCode)->first();
        Log::info("Payment callback received", $request->all());

        if (!$order) {
            Log::error("Callback failed: Order not found for BillCode $billCode");
            return response('Order not found', 404);
        }

        DB::beginTransaction();

        try {
            switch ($status) {
                case 1:
                    $order->order_status = 'completed'; // previously: 'paid'
                    break;
                case 2:
                    $order->order_status = 'failed';
                    break;
                default:
                    $order->order_status = 'pending';
                    break;
            }

            $order->save();

            if ($status == 1) {
                // Update item stock & status
                $item = \App\Models\Item::find($order->item_id);
                if ($item) {
                    $item->quantity -= $order->quantity;
                    if ($item->quantity <= 0) {
                        $item->status = 'sold';
                    }
                    $item->save();
                }

                // Remove item from cart
                \App\Models\Itemscart::where('user_id', $order->buyer_id)
                    ->where('item_id', $order->item_id)
                    ->delete();
            }

            DB::commit();
            return response('Callback handled', 200);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Callback handling failed: ' . $e->getMessage());
            return response('Callback processing failed', 500);
        }
    }

    

}


