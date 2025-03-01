<?php

namespace App\Models;

use App\Enums\OrderStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use App\Models\User;

class Order extends Model
{
    use HasFactory;

    protected $fillable = ['status', 'total_price', 'created_by', 'updated_by'];

    public function isPaid()
    {
        return $this->status !== OrderStatus::Unpaid->value;
    }

    public function isUnpaid()
    {
        return $this->status === OrderStatus::Unpaid->value;
    }

    public function isProcessing()
    {
        return $this->status === OrderStatus::Processing->value;
    }

    public function isShipped()
    {
        return $this->status === OrderStatus::Shipped->value;
    }

    public function isCompleted()
    {
        return $this->status === OrderStatus::Completed->value;
    }

    public function isCancelled()
    {
        return $this->status === OrderStatus::Cancelled->value;
    }

    public function payment(): HasOne
    {
        return $this->hasOne(Payment::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }
}
