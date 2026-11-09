<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    protected $table = 'transaksis';

    protected $guarded = ['id'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function details()
    {
        return $this->hasMany(TransaksiDetail::class, 'transaksi_id');
    }

    public function transaksiDetails()
    {
        return $this->hasMany(TransaksiDetail::class, 'transaksi_id');
    }

    /**
     * Accessor for invoice to point to invoice_number
     */
    public function getInvoiceAttribute(): ?string
    {
        return $this->invoice_number;
    }

    /**
     * Accessor for total to point to totalharga
     */
    public function getTotalAttribute(): float|int|null
    {
        return $this->totalharga;
    }

    /**
     * Accessor for paid to point to uang_bayar
     */
    public function getPaidAttribute(): float|int|null
    {
        return $this->uang_bayar;
    }

    /**
     * Accessor for change to point to kembalian
     */
    public function getChangeAttribute(): float|int|null
    {
        return $this->kembalian;
    }
}
