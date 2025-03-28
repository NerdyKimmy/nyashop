<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;

class OrderResource extends JsonResource
{
    public static $wrap = false;

    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        // Якщо у вас PHP 8+, можна зразу:
        $user     = $this->user;
        $customer = $user?->customer;
        $shipping = $customer?->shippingAddress;
        $billing  = $customer?->billingAddress;

        return [
            'id'          => $this->id,
            'status'      => $this->status,
            'total_price' => $this->total_price,
            'items'       => $this->items->map(fn($item) => [
                'id'         => $item->id,
                'unit_price' => $item->unit_price,
                'quantity'   => $item->quantity,
                'product'    => [
                    'id'    => $item->product->id,
                    'slug'  => $item->product->slug,
                    'title' => $item->product->title,
                    'image' => $item->product->image,
                ]
            ]),
            'customer' => $customer ? [
                'id'         => $user->id,
                'email'      => $user->email,
                'first_name' => $customer->first_name,
                'last_name'  => $customer->last_name,
                'phone'      => $customer->phone,
                'shippingAddress' => $shipping ? [
                    'id'       => $shipping->id,
                    'address1' => $shipping->address1,
                    'address2' => $shipping->address2,
                    'city'     => $shipping->city,
                    'state'    => $shipping->state,
                    'zipcode'  => $shipping->zipcode,
                    'country'  => $shipping->country?->name, // Тут теж можуть бути null-значення
                ] : null,
                'billingAddress' => $billing ? [
                    'id'       => $billing->id,
                    'address1' => $billing->address1,
                    'address2' => $billing->address2,
                    'city'     => $billing->city,
                    'state'    => $billing->state,
                    'zipcode'  => $billing->zipcode,
                    'country'  => $billing->country?->name,
                ] : null,
            ] : null,
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at->format('Y-m-d H:i:s'),
        ];
    }
}
