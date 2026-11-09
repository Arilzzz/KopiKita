<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class dataMenu extends Model
{
    protected $table = 'data_menus';

    protected $guarded = ['id'];

    public function kategori()
    {
        return $this->belongsTo(Kategori::class, 'kategori_id');
    }

    public function transaksiDetails()
    {
        return $this->hasMany(TransaksiDetail::class, 'data_menu_id');
    }

    /**
     * Accessor for $menu->nama to point to nama_menu
     */
    public function getNamaAttribute(): ?string
    {
        return $this->nama_menu;
    }

    /**
     * Accessor for $menu->price to point to harga
     */
    public function getPriceAttribute(): float|int|null
    {
        return $this->harga;
    }
}

// Class alias for case-sensitivity consistency
if (!class_exists('App\Models\DataMenu')) {
    class_alias(dataMenu::class, 'App\Models\DataMenu');
}
