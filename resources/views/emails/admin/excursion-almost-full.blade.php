@component('mail::message')
# Upozorenje: Izlet je skoro popunjen
## izlet: {{$excursion->name}} {{\Carbon\Carbon::parse($excursion->departure)->format('d.m.Y.')}}
### Ukupno mjesta: {{$excursion->total_seats}}
### Trenutno slobodno mjesta: {{$excursion->freeSeats}}

#### Srdačan pozdrav,<br>
Osam Marketing
@endcomponent
