<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'product_title', 'product_description', 'price','image','status'
    ];
    //
    public function cart()
    {
        return $this->belongsTo('App\Cart');
    }
}
