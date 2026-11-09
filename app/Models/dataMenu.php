<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class dataMenu extends Model
{
    protected $guarded = ['id'];

    public function kategori()
    {
        return $this->belongsTo(Kategori::class, 'kategori_id');
    }

    public function transaksiDetails()
    {
        return $this->hasMany(TransaksiDetail::class, 'data_menu_id');
    }

}
