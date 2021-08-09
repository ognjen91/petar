<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExcursionType extends Model
{
    use HasFactory;
    
    protected $fillable = ['type', 'slug', 'name', 'crew_can_see'];

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

    public function connectedExcursionTypes(){
        return $this->belongsToMany(ExcursionType::class, 'connected_excursion_types', 'direction_1', 'direction_2');
    }
}
