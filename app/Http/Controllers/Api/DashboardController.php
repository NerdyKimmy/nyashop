<?php

namespace App\Http\Controllers\Api;

use App\Enums\AddressType;
use App\Enums\OrderStatus;
use App\Http\Controllers\Controller;
use App\Http\Resources\Dashboard\OrderResource;
use App\Models\Customer;
use App\Models\Order;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function activeCustomers()
    {
        return Customer::count();
    }

    public function activeProducts()
    {
        return Product::count();
    }

    public function paidOrders()
    {
        $fromDate = $this->getFromDate();
        $query = Order::query()->whereIn('status', [
            OrderStatus::Paid->value,
            OrderStatus::Shipped->value,
            OrderStatus::Completed->value
        ]);

        if ($fromDate) {
            $query->where('created_at', '>', $fromDate);
        }

        return $query->count();
    }

    public function totalIncome()
    {
        $fromDate = $this->getFromDate();
        $query = Order::query()->where('status', OrderStatus::Paid->value);

        if ($fromDate) {
            $query->where('created_at', '>', $fromDate);
        }
        return $query->sum('total_price');
    }

    public function ordersByCountry()
    {
        $fromDate = $this->getFromDate();
        $query = Order::query()
            ->select(['c.name', DB::raw('count(orders.id) as count')])
            ->join('users', 'created_by', '=', 'users.id')
            ->join('customer_addresses AS a', 'users.id', '=', 'a.customer_id')
            ->join('countries AS c', 'a.country_code', '=', 'c.code')
            ->where('status', OrderStatus::Paid->value)
            ->where('a.type', AddressType::Billing->value)
            ->groupBy('c.name')
        ;

        if ($fromDate) {
            $query->where('orders.created_at', '>', $fromDate);
        }

        return $query->get();
    }

    public function latestCustomers()
    {
        return Customer::query()
            ->select(['id', 'first_name', 'last_name', 'u.email', 'phone', 'u.created_at'])
            ->join('users AS u', 'u.id', '=', 'customers.user_id')
            ->where('status', CustomerStatus::Active->value)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
    }

    public function latestOrders()
    {
        return OrderResource::collection(
            Order::query()
                ->select(['o.id', 'o.total_price', 'o.created_at', DB::raw('COUNT(oi.id) AS items'),
                    'c.user_id', 'c.first_name', 'c.last_name'])
                ->from('orders AS o')
                ->join('order_items AS oi', 'oi.order_id', '=', 'o.id')
                ->join('customers AS c', 'c.user_id', '=', 'o.created_by')
                ->where('o.status', OrderStatus::Paid->value)
                ->limit(10)
                ->orderBy('o.created_at', 'desc')
                ->groupBy('o.id', 'o.total_price', 'o.created_at', 'c.user_id', 'c.first_name', 'c.last_name')
                ->get()
        );
    }

    private function getFromDate()
    {
        $request = \request();
        $paramDate = $request->get('d');
        $array = [
            '1d' => Carbon::now()->subDays(1),
            '1k' => Carbon::now()->subDays(7),
            '2k' => Carbon::now()->subDays(14),
            '1m' => Carbon::now()->subDays(30),
            '3m' => Carbon::now()->subDays(60),
            '6m' => Carbon::now()->subDays(180),
        ];

        return $array[$paramDate] ?? null;
    }
    public function ordersByDay()
    {
        $fromDate = $this->getFromDate();

        $query = Order::query()
            ->select([
                DB::raw('DATE(created_at) as date'),
                DB::raw('COUNT(*) as count')
            ])
            ->whereIn('status', [
                OrderStatus::Paid->value,
                OrderStatus::Shipped->value,
                OrderStatus::Completed->value
            ])
            ->groupBy(DB::raw('DATE(created_at)'))
            ->orderBy(DB::raw('DATE(created_at)'));

        if ($fromDate) {
            $query->where('created_at', '>', $fromDate);
        }

        return $query->get();
    }
}
