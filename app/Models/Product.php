<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table = 'Products';
    protected $primaryKey = 'ProductNumber';

    // N -> 1
    public function category(){
        return $this->belongsTo(Category::class,'CategoryID','CategoryID');
    }
}
