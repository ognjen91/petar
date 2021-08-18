<x-layout-booker>
    
    <div class="container">

        <div class='my-3'>
            {{-- TITLE & BASIC INFO --}}
            <h1 class='text-center blue--text'>Moje rezevacije redovnih izleta</h1>
            
            @if($excursionType)
            <h2 class='text-center blue--text'>Izlet {{$excursionType->name}}</h2>
            @else
            <h2 class='text-center blue--text'>Svi izleti</h2>
            @endif
            
            @if($startDate || $endDate)
            <h2 class='text-center blue--text'>Period @if($startDate) od {{$startDate}} @endif @if($endDate) do {{$endDate}} @endif</h2>
            @endif
        </div>

        <div class="mb-2">
            <booker-redirect-params-select 
            class='mb-5' 
            base-url="/moje-rezervacije/redovni"
            :excursion-types="{{json_encode($excursionTypes)}}"
            initial-start-date="{{$startDate}}"
            initial-end-date="{{$endDate}}"
            >
            </booker-redirect-params-select>
        </div>
            
        
        @if($reservations->count())
            {{-- THE TABLE --}}
            <table id="my-reservations">
                <tr>
                    @if(!$excursionType)<th>Izlet</th>@endif
                    <th>Datum</th>
                    <th>Mjesta</th>
                    <th>Naplaćeno</th>
                    <th>Detalji</th>
                </tr>
                @foreach ($reservations as $reservation)
                    {{-- @continue(!$reservation->excursion) --}}
                    {{-- @if(!$reservation->excursion) {{dd($reservation)}} @endif --}}
                    {{-- DISPLAY ONLY IF IT IS NOT RETURN WAY RESEVATION! --}}
                    @continue($reservation->isReturnWayDirectionReservation)

                    {{-- ROW DATA DISPLAY --}}
                    <tr @class(['red--text' => $reservation->isCanceled])>
                        @if(!$excursionType)<td><a href="{{route('my-reservations.regular.show', $reservation->id)}}" @class(['red--text' => $reservation->isCanceled])>{{$reservation->excursion? $reservation->excursion->excursionType->name : '(izbrisan izlet)'}}</a></td>@endif
                        <td>{{$reservation->excursion? \Carbon\Carbon::parse($reservation->excursion->departure)->format('d.m.Y') : '(izbrisan izlet)'}}</td>
                        <td>{{$reservation->seats}}</td>
                        <td>{{$reservation->price}}</td>
                        <td>
                            <v-btn
                            elevation="2"
                            small
                            >
                                <a href="{{route('my-reservations.regular.show', $reservation->id)}}">Detalji</a>
                            </v-btn>
                        </td>
                    </tr>
                @endforeach
                <tr>
                    @if(!$excursionType)<td></td>@endif
                    <td>Ukupno <br> <strong>{{$reservations->count()}}</strong></td>
                    <td>Ukupno <br> <strong>{{$totalSeats}}</strong></td>
                    <td>Ukupno <br> <strong>{{$totalPrice}}</strong></td>
                </tr>
            </table>
        @else
            <h3 class="red--text text-center">Nema rezervacija za zadate parametre</h3>
        @endif

        {{-- LINKS --}}
        {{-- <div class="d-flex justify-center text-center">
            {{ $reservations->links() }}
        </div> --}}
        
    </div>
    </x-layout-booker>