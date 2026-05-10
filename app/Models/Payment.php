<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $table = 'payment';
    protected $primaryKey = 'payment_id';
    protected $fillable = ['order_id', 'payment_method', 'amount', 'status', 'transaction_id', 'paid_at'];
    protected $casts = ['paid_at' => 'datetime'];

    public function order() { return $this->belongsTo(Order::class, 'order_id', 'order_id'); }
}
