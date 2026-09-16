<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_number',
        'control_number',
        'member_id',
        'user_id',
        'amount',
        'currency',
        'purpose',
        'status',
        'due_date',
        'notes',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'due_date' => 'date',
    ];

    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public static function generateInvoiceNumber(): string
    {
        $year = date('Y');
        $count = self::whereYear('created_at', $year)->count() + 1;
        return sprintf('TEVDA-INV-%s-%05d', $year, $count);
    }

    /**
     * Generate unique 12-digit standard Control Number (prefix 99401).
     */
    public static function generateControlNumber(): string
    {
        do {
            $randomDigits = mt_rand(1000000, 9999999);
            $controlNumber = '99401' . $randomDigits;
        } while (self::where('control_number', $controlNumber)->exists());

        return $controlNumber;
    }
}
