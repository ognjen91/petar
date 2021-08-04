<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;

class PrivateExcursionReservationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'title' => $this->title,
            // 'title' => Carbon::parse($this->start)->format('H:i') . "-" . Carbon::parse($this->end)->format('H:i'),
            // 'title' => $this->title . "(od " . Carbon::parse($this->start)->format('H:i') . " do " . Carbon::parse($this->end)->format('H:i') . ")",
            'start' => Carbon::parse($this->start)->timestamp,
            'end' => Carbon::parse($this->end)->timestamp,
            'formatedStartTime' => Carbon::parse($this->start)->format('H:i'),
            'formatedEndTime' => Carbon::parse($this->end)->format('H:i'),
            'color' => 'blue',
        ];
    }
}
