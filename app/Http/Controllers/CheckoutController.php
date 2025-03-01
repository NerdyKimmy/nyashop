<?php

namespace App\Http\Controllers;

use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use App\Helpers\Cart;
use App\Models\CartItem;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class CheckoutController extends Controller
{
    public function checkout(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = $request->user();

        \Stripe\Stripe::setApiKey(getenv('STRIPE_SECRET_KEY'));

        [$products, $cartItems] = Cart::getProductsAndCartItems();

        $orderItems = [];
        $lineItems  = [];
        $totalPrice = 0;

        foreach ($products as $product) {
            $quantity = $cartItems[$product->id]['quantity'];
            $totalPrice += $product->price * $quantity;
            $lineItems[] = [
                'price_data' => [
                    'currency'     => 'uah',
                    'product_data' => [
                        'name' => $product->title,
                        // 'images' => [$product->image]
                    ],
                    'unit_amount'  => $product->price * 100,
                ],
                'quantity' => $quantity,
            ];
            $orderItems[] = [
                'product_id' => $product->id,
                'quantity'   => $quantity,
                'unit_price' => $product->price,
            ];
        }

        // Передаємо email користувача для створення/прив'язки Stripe Customer
        $session = \Stripe\Checkout\Session::create([
            'line_items'     => $lineItems,
            'mode'           => 'payment',
            'success_url'    => route('checkout.success', [], true) . '?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url'     => route('checkout.failure', [], true),
            'customer_email' => $user->email,
        ]);

        // Створюємо Order
        $order = Order::create([
            'total_price' => $totalPrice,
            'status'      => OrderStatus::Unpaid,
            'created_by'  => $user->id,
            'updated_by'  => $user->id,
        ]);

        // Створюємо Order Items
        foreach ($orderItems as $orderItem) {
            $orderItem['order_id'] = $order->id;
            OrderItem::create($orderItem);
        }

        // Створюємо Payment
        Payment::create([
            'order_id'   => $order->id,
            'amount'     => $totalPrice,
            'status'     => PaymentStatus::Pending,
            'type'       => 'cc',
            'created_by' => $user->id,
            'updated_by' => $user->id,
            'session_id' => $session->id,
        ]);

        // Очищуємо корзину
        CartItem::where('user_id', $user->id)->delete();

        return redirect($session->url);
    }

    public function success(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = $request->user();
        \Stripe\Stripe::setApiKey(getenv('STRIPE_SECRET_KEY'));

        try {
            $session_id = $request->get('session_id');
            $session    = \Stripe\Checkout\Session::retrieve($session_id);
            if (!$session) {
                return view('checkout.failure', ['message' => 'Invalid Session ID']);
            }

            $payment = Payment::query()
                ->where('session_id', $session_id)
                ->whereIn('status', [PaymentStatus::Pending, PaymentStatus::Paid])
                ->first();
            if (!$payment) {
                throw new NotFoundHttpException();
            }
            if ($payment->status === PaymentStatus::Pending->value) {
                $this->updateOrderAndSession($payment);
            }
            // Якщо поле customer містить валідний ID, то отримуємо клієнта, інакше залишаємо $customer як null
            if (isset($session->customer) && is_string($session->customer) && !empty($session->customer)) {
                $customer = \Stripe\Customer::retrieve($session->customer);
            } else {
                $customer = null;
            }
            return view('checkout.success', compact('customer'));
        } catch (NotFoundHttpException $e) {
            throw $e;
        } catch (\Exception $e) {
            return view('checkout.failure', ['message' => $e->getMessage()]);
        }
    }

    public function failure(Request $request)
    {
        return view('checkout.failure', ['message' => ""]);
    }

    public function checkoutOrder(Order $order, Request $request)
    {
        \Stripe\Stripe::setApiKey(getenv('STRIPE_SECRET_KEY'));

        $lineItems = [];
        foreach ($order->items as $item) {
            $lineItems[] = [
                'price_data' => [
                    'currency'     => 'uah',
                    'product_data' => [
                        'name' => $item->product->title,
                    ],
                    'unit_amount'  => $item->unit_price * 100,
                ],
                'quantity' => $item->quantity,
            ];
        }

        // Передаємо email користувача для створення/прив'язки Stripe Customer
        $session = \Stripe\Checkout\Session::create([
            'line_items'     => $lineItems,
            'mode'           => 'payment',
            'success_url'    => route('checkout.success', [], true) . '?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url'     => route('checkout.failure', [], true),
            'customer_email' => $request->user()->email,
        ]);

        // Якщо Payment не створений, створюємо новий, інакше оновлюємо session_id
        if (!$order->payment) {
            $order->payment()->create([
                'session_id' => $session->id,
                'amount'     => $order->total_price,
                'status'     => \App\Enums\PaymentStatus::Pending,
                'type'       => 'cc',
                'created_by' => $request->user()->id,
                'updated_by' => $request->user()->id,
            ]);
        } else {
            $order->payment->update([
                'session_id' => $session->id,
            ]);
        }

        return redirect($session->url);
    }

    public function webhook()
    {
        \Stripe\Stripe::setApiKey(getenv('STRIPE_SECRET_KEY'));

        $endpoint_secret = 'whsec_9e29e6c3bcd5422dedd5cab8df9e924e14d5f36dc285c284e8f23d6beaf11c2c';

        $payload    = @file_get_contents('php://input');
        $sig_header = $_SERVER['HTTP_STRIPE_SIGNATURE'];
        $event      = null;

        try {
            $event = \Stripe\Webhook::constructEvent(
                $payload, $sig_header, $endpoint_secret
            );
        } catch (\UnexpectedValueException $e) {
            // Invalid payload
            return response('', 401);
        } catch (\Stripe\Exception\SignatureVerificationException $e) {
            // Invalid signature
            return response('', 402);
        }

        // Обробляємо подію
        switch ($event->type) {
            case 'checkout.session.completed':
                $paymentIntent = $event->data->object;
                $sessionId   = $paymentIntent['id'];

                $payment = Payment::query()
                    ->where('session_id', $sessionId)
                    ->where('status', PaymentStatus::Pending)
                    ->first();
                if ($payment) {
                    $this->updateOrderAndSession($payment);
                }
                break;
            default:
                echo 'Received unknown event type ' . $event->type;
        }

        return response('', 200);
    }

    private function updateOrderAndSession(Payment $payment)
    {
        $payment->status = PaymentStatus::Paid;
        $payment->update();

        $order = $payment->order;
        $order->status = OrderStatus::Paid;
        $order->update();
    }
}
