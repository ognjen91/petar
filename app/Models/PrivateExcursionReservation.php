<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class PrivateExcursionReservation extends Model
{
    use HasFactory;
    protected $fillable = ['title', 'start', 'end', 'message', 'price', 'booker_id', 'active', 'cancelation_time',];

    /**
     * CASTS
     */

    protected $casts = [
        'start' => 'datetime',
        'end' => 'datetime',
        'cancelation_time' => 'datetime'
    ];

    /**
     * RELATIONSHIPS
     */

    public function booker(){
        return $this->belongsTo(User::class, 'booker_id');
    }

    /**
     * SCOPES
     */

    public function scopeActive($query)
    {
        return $query->where('active', 1);
    }

    /**
     * ACCESSORS
     */

    public function getIsInFutureAttribute(){
        return Carbon::parse($this->start) > Carbon::now();
    }

    public function getIsCanceledAttribute(){
        return !!$this->cancelation_time;
    }
}
