<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExcursionType extends Model
{
    use HasFactory;
    
    protected $fillable = ['type', 'slug', 'name'];

    /**
     * RELATIONSHIPS
     */

    public function excursions()
    {
        return $this->hasMany(Excursion::class);
    }
        
    public function stations()
    {
        return $this->belongsToMany(Station::class, 'excursion_type_station');
    }
}
