<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Order; // ✅ IMPORTANT: add this import

class Payment extends Model
{
    protected $table = 'payment';
    protected $primaryKey = 'payment_id';

    public $timestamps = true; // ✅ add if your table has created_at / updated_at

    protected $fillable = [
        'order_id',
        'payment_method',   // cod | aba | acleda
        'amount',
        'status',           // pending | awaiting_payment | paid | failed | refunded
        'transaction_id',
        'qr_reference',
        'merchant_id',
        'paid_at',
        'confirmed_at',
    ];

    protected $casts = [
        'paid_at'      => 'datetime',
        'confirmed_at' => 'datetime',
    ];

    /**
     * Relationship: Payment belongs to Order
     */
    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id', 'order_id');
    }

    /**
     * Human-readable payment method label
     */
    public function getMethodLabelAttribute(): string
    {
        return match ($this->payment_method ?? '') {
            'aba'    => 'ABA Bank QR',
            'acleda' => 'ACLEDA Bank QR',
            'cod'    => 'Cash on Delivery',
            default  => ucfirst(str_replace('_', ' ', $this->payment_method ?? '')),
        };
    }

    public function isQrPayment(): bool
    {
        return in_array($this->payment_method, ['aba', 'acleda']);
    }

    public function isPaid(): bool
    {
        return $this->status === 'paid';
    }

    public function isAwaitingPayment(): bool
    {
        return $this->status === 'awaiting_payment';
    }
}