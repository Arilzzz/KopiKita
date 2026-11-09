<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransaksiDetail extends Model
{
    protected $table = 'transaksi_details';

    protected $guarded = ['id'];

    public function transaksi()
    {
        return $this->belongsTo(Transaksi::class, 'transaksi_id');
    }

    public function dataMenu()
    {
        return $this->belongsTo(dataMenu::class, 'data_menu_id');
    }

    public function menu()
    {
        return $this->belongsTo(dataMenu::class, 'data_menu_id');
    }

    /**
     * Accessor for quantity to point to jumlah
     */
    public function getQuantityAttribute(): ?int
    {
        return $this->jumlah;
    }

    /**
     * Accessor for price to point to harga
     */
    public function getPriceAttribute(): float|int|null
    {
        return $this->harga;
    }

    /**
     * Accessor for subtotal
     */
    public function getSubtotalAttribute(): float|int
    {
        return ($this->jumlah ?? 0) * ($this->harga ?? 0);
    }
}
