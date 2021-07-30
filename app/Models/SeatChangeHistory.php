<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SeatChangeHistory extends Model
{
    use HasFactory;
    protected $fillable = ['excursion_id', 'seats_old_value', 'seats_new_value'];

    /**
     *RELATIONSHIPS
     */

     public function excursion(){
         return $this->belongsTo(Excursion::class);
     }
}
