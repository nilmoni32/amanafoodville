<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Zone extends Model
{
    /**
     * @var array
     */
    protected $fillable = [ 'district_id', 'name', 'status' ];

    public function district(){
        return $this->belongsTo(District::class);        
    }
}
