<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class District extends Model
{
    
    /**
     * @var array
    */
    protected $fillable = ['name', 'status'];

    public function zones(){
        return $this->hasMany(Zone::class);
    }
}
