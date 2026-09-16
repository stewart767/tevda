<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'payment_reference',
        'invoice_id',
        'member_id',
        'amount',
        'currency',
        'payment_method',
        'transaction_reference',
        'status',
        'paid_at',
        'proof_of_payment_path',
        'verified_by',
        'verified_at',
        'notes',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'paid_at' => 'datetime',
        'verified_at' => 'datetime',
    ];

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }

    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    public function verifier()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    public function receipt()
    {
        return $this->hasOne(Receipt::class);
    }

    public static function generatePaymentReference(): string
    {
        $year = date('Y');
        $count = self::whereYear('created_at', $year)->count() + 1;
        return sprintf('PAY-TEVDA-%s-%05d', $year, $count);
    }
}
