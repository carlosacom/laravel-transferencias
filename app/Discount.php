<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Discount extends Model
{
    protected $table = 'discounts';

    public function reseller()
    {
        return $this->belongsTo('App\Reseller','reseller_id');
    }
    public function user()
    {
        return $this->belongsTo('App\User', 'user_id');
    }
}
