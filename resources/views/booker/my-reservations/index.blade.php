<x-layout-booker>
    
    <div class="container">

        <div class='my-3'>
            <h1 class='text-center blue--text'>Moje rezevacije</h1>
            
            @if($excursionType)
            <h2 class='text-center blue--text'>Izlet {{$excursionType->name}}</h2>
            @else
            <h2 class='text-center blue--text'>Svi izleti</h2>
            @endif
            
            @if($startDate)
            <h2 class='text-center blue--text'>Period @if($startDate) od {{$startDate}} @endif @if($endDate) do {{$endDate}} @endif</h2>
            @endif
        </div>

        <div class="mb-2">
            <booker-redirect-params-select 
            :excursion-types="{{json_encode($excursionTypes)}}"
            initial-start-date="{{$startDate}}"
            initial-end-date="{{$endDate}}"
            >
            </booker-redirect-params-select>
        </div>
            
        
        @if($reservations->total())
            <table id="my-reservations">
                <tr>
                    @if(!$excursionType)<th>Izlet</th>@endif
                    <th>Datum</th>
                    <th>Mjesta</th>
                    <th>Naplaćeno</th>
                    <th>Detalji</th>
                </tr>
                @foreach ($reservations as $reservation)
                    <tr @class(['red--text' => !$reservation->active])>
                        @if(!$excursionType)<td><a href="{{route('my-reservations.show', $reservation->id)}}" @class(['red--text' => !$reservation->active])>{{$reservation->excursion->excursionType->name}}</a></td>@endif
                        <td>{{\Carbon\Carbon::parse($reservation->excursion->departure)->format('d.m.Y')}}</td>
                        <td>{{$reservation->seats}}</td>
                        <td>{{$reservation->price}}</td>
                        <td>
                            <v-btn
                            elevation="2"
                            small
                            >
                                <a href="{{route('my-reservations.show', $reservation->id)}}">Detalji</a>
                            </v-btn>
                        </td>
                    </tr>
                @endforeach
                <tr>
                    @if(!$excursionType)<td></td>@endif
                    <td>Ukupno <br> <strong>{{$reservations->total()}}</strong></td>
                    <td>Ukupno <br> <strong>{{$totalSeats}}</strong></td>
                    <td>Ukupno <br> <strong>{{$totalPrice}}</strong></td>
                </tr>
            </table>
        @else
            <h3 class="red--text text-center">Nema rezervacija za zadate parametre</h3>
        @endif

        <div class="d-flex justify-center text-center">
            {{ $reservations->links() }}
        </div>
        
    </div>
    </x-layout-booker>