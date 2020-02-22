<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class message extends Model
{
    public $fillable = ['message','from','to']; 
    
    public function user()
    {
        return $this->belongsTo(user::class);
    }
}
