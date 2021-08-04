<x-layout-booker>

    <div class="container">

        <div class='my-3'>
             <h1 class='red--text'>Detalji rezevacije</h1>
            <p>Kreirano: {{\Carbon\Carbon::parse($reservation->created_at)->format('d.m.Y. H:i:s')}}</p>

             <h2 class='black--text'>Od: <span class="red--text">{{\Carbon\Carbon::parse($reservation->start)->format('d.m.Y. H:i')}}</span></h2>
             <h2 class='black--text'>Do: <span class="red--text">{{\Carbon\Carbon::parse($reservation->end)->format('d.m.Y. H:i')}}</span></h2>


             @if($reservation->isInFuture)
                 @if($reservation->active)
                    <cancel-reservation-button
                    excursion-type="private"
                    class='my-5'
                    :reservation-id="{{$reservation->id}}"
                    ></cancel-reservation-button>
                @else
                    <h2 class='red--text'><u>Rezervacija je otkazana</u></h2>
                        @if($reservation->cancelation_time)
                            <p class="mb-0">Otkazano: {{\Carbon\Carbon::parse($reservation->cancelation_time)->format('d.m.Y. H:i:s')}}</p>
                        @endif
                @endif
            @else
                @if($reservation->active)
                    <h2 class='green--text'>Rezervacija je izvršena</h2>
                    <p class="mb-0">Otkazano: {{\Carbon\Carbon::parse($reservation->cancelation_time)->format('d.m.Y. H:i:s')}}</p>
                @else
                    <h2 class='danger--text'>Rezervacija je otkazana</h2>
                @endif
             @endif

            @if($reservation->message)
                <h2 class='red--text'>Poruka</h2>
                <p>
                    {!! nl2br(e($reservation->message)) !!}
                </p>
            @endif

            <div class="py-5">
                <a href="{{route('my-reservations.private.index')}}" class="blue--text my-5 text-h5">
                    Nazad na rezervacije
               </a>
            </div>
             
    
        </div>
    </div>
</x-layout-booker>