<x-layout-booker>
    
    <div class="container">

        <div class='my-3'>
            {{-- TITLE & BASIC INFO --}}
            <h1 class='text-center blue--text'>Moje rezevacije privatnih izleta</h1>
            @if($startDate || $endDate)
                <h2 class='text-center blue--text'>Period @if($startDate) od {{$startDate}} @endif @if($endDate) do {{$endDate}} @endif</h2>
            @endif
        </div>

        {{-- SELECT PARAMS --}}
        <div class="mb-2">
            <booker-redirect-params-select
            class='mb-5' 
            base-url="/moje-rezervacije/privatni"
            :show-excursion-types-select="false"
            initial-start-date="{{$startDate}}"
            initial-end-date="{{$endDate}}"
            >
            </booker-redirect-params-select>
        </div>
            
        {{-- THE TABLE --}}
        @if($reservations->total())
            <table id="my-reservations">
                <tr>
                    <th>Početak</th>
                    <th>Kraj</th>
                    <th>Naplaćeno</th>
                    <th>Detalji</th>
                </tr>
                @foreach ($reservations as $reservation)
                    <tr @class(['red--text' => !$reservation->active])>
                        <td>{{\Carbon\Carbon::parse($reservation->start)->format('d.m.Y')}}</td>
                        <td>{{\Carbon\Carbon::parse($reservation->end)->format('d.m.Y')}}</td>
                        <td>{{$reservation->price}}</td>
                        <td>
                            <v-btn
                            elevation="2"
                            small
                            >
                                <a href="{{route('my-reservations.private.show', $reservation->id)}}">Detalji</a>
                            </v-btn>
                        </td>
                    </tr>
                @endforeach
                <tr>
                    <td>Ukupno <br> <strong>{{$reservations->total()}}</strong></td>
                    <td></td>
                    <td>Ukupno <br> <strong>{{$totalPrice}} &euro;</strong></td>
                    <td></td>
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