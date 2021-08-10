@component('mail::message')
# Upozorenje: Izlet je skoro popunjen
## izlet: {{$excursion->name}}
### Ukupno mjesta: {{$excursion->total_seats}}
### Trenutno slobodno mjesta: {{$excursion->free_seats}}

#### Srdačan pozdrav,<br>
Osam Marketing
@endcomponent
