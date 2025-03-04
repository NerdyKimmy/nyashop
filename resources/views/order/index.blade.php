<?php
/** @var \Illuminate\Database\Eloquent\Collection $orders */
?>

<x-app-layout>
    <div class="container mx-auto lg:w-2/3 p-5">
        <h1 class="text-3xl font-bold mb-2">My Orders</h1>
        <div class="bg-white rounded-lg p-3 overflow-x-auto">
            <table class="table-auto w-full">
                <thead>
                <tr class="border-b-2">
                    <th class="text-left p-2">Order #</th>
                    <th class="text-left p-2">Date</th>
                    <th class="text-left p-2">Status</th>
                    <th class="text-left p-2">SubTotal</th>
                    <th class="text-left p-2">Items</th>
                    <th class="text-left p-2">Actions</th>
                </tr>
                </thead>
                <tbody>
                @foreach($orders as $order)
                    @php
                        // Визначаємо колір (CSS-клас) залежно від статусу
                        switch ($order->status) {
                            case 'unpaid':
                                $statusClass = 'bg-gray-400';
                                break;
                            case 'paid':
                                $statusClass = 'bg-emerald-400';
                                break;
                            case 'shipped':
                                $statusClass = 'bg-orange-400';
                                break;
                            case 'completed':
                                $statusClass = 'bg-emerald-400';
                                break;
                            case 'cancelled':
                                $statusClass = 'bg-red-400';
                                break;
                            default:
                                $statusClass = 'bg-gray-400';
                                break;
                        }
                    @endphp

                    <tr class="border-b">
                        <td class="py-1 px-2">
                            <a href="{{ route('order.view', $order) }}"
                               class="text-pink-400 hover:text-pink-300">
                                #{{ $order->id }}
                            </a>
                        </td>
                        <td class="py-1 px-2 whitespace-nowrap">
                            {{ $order->created_at }}
                        </td>
                        <td class="py-1 px-2">
                            <small class="text-white py-1 px-2 rounded {{ $statusClass }}">
                                {{ $order->status }}
                            </small>
                        </td>
                        <td class="py-1 px-2">
                            ₴{{ $order->total_price }}
                        </td>
                        <td class="py-1 px-2 whitespace-nowrap">
                            {{ $order->items()->count() }} item(s)
                        </td>
                        <td class="py-1 px-2 w-[100px]">
                            @if ($order->status === 'unpaid')
                                <form action="{{ route('cart.checkout-order', $order) }}" method="POST">
                                    @csrf
                                    <button class="flex items-center py-1 px-2 bg-pink-300 hover:bg-pink-400 text-white rounded">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5"
                                             fill="none" viewBox="0 0 24 24"
                                             stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                  d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                                        </svg>
                                        <span class="ml-1">Pay</span>
                                    </button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-3">
            {{ $orders->links() }}
        </div>
    </div>
</x-app-layout>
