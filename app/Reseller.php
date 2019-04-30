<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Reseller extends Model
{
    protected $table = 'resellers';
    
    protected $hidden = [
        'password'
    ];

    public function discounts()
    {
        return $this->hasMany('App\Discount');
    }
    public function discount_actual()
    {
        return $this->hasOne('App\Discount')->orderBy('id','DESC');
    }
}
