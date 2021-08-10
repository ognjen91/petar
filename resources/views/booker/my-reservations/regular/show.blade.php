<x-layout-booker>

    <div class="container">

        <div class='my-3'>
            
            {{-- BASIC INFO --}}
             <div class="mb-2">
                 <h1 class='red--text'><u>Detalji rezevacije</u></h1>
                 <h2 class='black--text'>Izlet: <span class="red--text">{{$reservation->excursion->excursionType->name}}</span></h2>
                 <p>Kreirano: {{\Carbon\Carbon::parse($reservation->created_at)->format('d.m.Y. H:i:s')}}</p>
                 
                 <h2 class='black--text'>Polazak: <span class="red--text">{{\Carbon\Carbon::parse($reservation->excursion->departure)->format('d.m.Y. H:i')}}</span></h2>
                 <h2 class='black--text'>Stanica: <span class="red--text">{{$reservation->station->name}}</span></h2>
                 <h2 class='black--text'>Broj rezervisanih mjesta: <span class="red--text">{{$reservation->seats}}</span></h2>
                 <h2 class='black--text'>Naplaćeno: <span class="red--text">{{$reservation->price}} &euro;</span></h2>
            </div>
                 
            {{-- IF RESERVATION HAS CONNECTED RETURN WAY DIRECTION, DISPLAY IT'S DETAILS AS WELL --}}
            @if($returnDirectionReservation)
            <div class="my-4">
                <v-divider></v-divider>
                
                @if(!$returnDirectionReservation->isCanceled)
                    <h4 class='red--text'><u>Rezevisani povratni smjer</u></h4>
                    <h5 class='black--text'>Polazak: <span class="red--text">{{\Carbon\Carbon::parse($returnDirectionReservation->excursion->departure)->format('d.m.Y. H:i')}}</span></h5>
                @else
                    <h4 class='red--text'><u>Rezevisani povratni smjer je otkazan</u></h4>
                    <h5 class='black--text'>Otkazano: <span class="red--text">{{\Carbon\Carbon::parse($reservation->cancelation_time)->format('d.m.Y. H:i')}}</span></h5>
                @endif
                <v-divider></v-divider>
            </div>
             @endif

             @if($reservation->isInFuture)
                 {{-- FUTURE: IF SI NOT CANCELED --}}
                 @if(!$reservation->isCanceled)
                     <h4 class="red--text">Otkazivanje rezervacije</h4>
                    <cancel-reservation-button
                    excursion-type="regular"
                    class='my-5'
                    :reservation-id="{{$reservation->id}}"
                    >
                    @if($returnDirectionReservation)
                         @if(!$returnDirectionReservation->isCanceled)
                         Otkažite oba smjera rezervacije
                         @else
                         Otkažite rezervaciju                        
                         @endif
                    @else
                         Otkažite rezervaciju
                    @endif
                    </cancel-reservation-button>
                @else
                     {{-- FUTURE : IF IS CANCELED --}}
                    <h2 class='red--text'><u>Rezervacija je otkazana</u></h2>
                        @if($reservation->cancelation_time)
                            <p class="mb-0">Otkazano: {{\Carbon\Carbon::parse($reservation->cancelation_time)->format('d.m.Y. H:i:s')}}</p>
                        @endif
                @endif
            @else
                {{-- PAST: IF NOT CANCELED --}}
                @if(!$reservation->isCanceled)
                    <h2 class='green--text'>Rezervacija je izvršena</h2>
                    @else
                    {{-- PAST: IF CANCELED --}}
                    <h2 class='danger--text'>Rezervacija je otkazana</h2>
                    <p class="mb-0">Otkazano: {{\Carbon\Carbon::parse($reservation->cancelation_time)->format('d.m.Y. H:i:s')}}</p>
                @endif
             @endif

            {{-- =========CANCEL RETURN WAY ONLY========== --}}
            @if($returnDirectionReservation)
                @if($returnDirectionReservation->active)
                        <h4 class="red--text">Otkazivanje povratnog smjera</h4>

                        <cancel-reservation-button
                        excursion-type="regular"
                        class='my-5'
                        :reservation-id="{{$returnDirectionReservation->id}}"
                        >
                        Otkažite samo povratni smjer
                        </cancel-reservation-button>
                @endif
            @endif

            {{-- ADDITIONAL MESSAGE --}}
             @if($reservation->message)
                <h2 class='red--text'>Poruka</h2>
                <p>
                    {!! nl2br(e($reservation->message)) !!}
                </p>
            @endif

            {{-- BACK BTN --}}
            <div class="py-5">
                <a href="{{route('my-reservations.regular.index')}}" class="blue--text my-5 text-h5">
                    Nazad na rezervacije
               </a>
            </div>
             
    
        </div>
    </div>
</x-layout-booker>