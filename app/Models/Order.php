<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $table = 'Orders';
    protected $primaryKey = 'OrderNumber';

    public function products()
    {
        return $this->belongsToMany(Product::class,'Order_Details','OrderNumber','ProductNumber')
                    ->withPivot('QuotedPrice','QuantityOrdered');
    }
}
