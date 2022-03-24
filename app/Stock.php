<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
   protected $fillable = [
       'user_id', 
       'product_id'
   ];

   public function user()
   {
     return $this->belongsTo('App\User');
   }

   public function product()
   {
     return $this->hasOne('App\Product');
   }
}
