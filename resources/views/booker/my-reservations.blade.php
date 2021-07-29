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
            
        
        @if($reservations->count())
            <table id="my-reservations">
                <tr>
                    @if(!$excursionType)<th>Izlet</th>@endif
                    <th>Datum</th>
                    <th>Mjesta</th>
                    <th>Naplaćeno</th>
                </tr>
                @foreach ($reservations as $reservation)
                    <tr>
                        @if(!$excursionType)<td>{{$reservation->excursion->excursionType->name}}</td>@endif
                        <td>{{\Carbon\Carbon::parse($reservation->excursion->departure)->format('d.m.Y')}}</td>
                        <td>{{$reservation->seats}}</td>
                        <td>{{$reservation->price}}</td>
                    </tr>
                @endforeach
                <tr>
                    @if(!$excursionType)<td></td>@endif
                    <td></td>
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