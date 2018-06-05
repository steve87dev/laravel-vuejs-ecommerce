<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    //
    public function products()
    {
        return $this->hasOne('App\Product','id','product_id');
    }
}
