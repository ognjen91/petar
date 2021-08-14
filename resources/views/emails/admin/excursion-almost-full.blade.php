@component('mail::message')
# Upozorenje: Izlet je skoro popunjen
## izlet: {{$excursion->name}}
### Ukupno mjesta: {{$excursion->total_seats}}
### Trenutno slobodno mjesta: {{$excursion->freeSeats}}

#### Srdačan pozdrav,<br>
Osam Marketing
@endcomponent
