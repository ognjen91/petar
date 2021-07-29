<x-layout-booker-pdf>
    <div class=" mx-auto px-3">
        <div class="row">

            {{-- DATA, TITLES --}}
            <div class="col-12" style='margin: 20px 0;'>
                
                <h1 class='text-center'>@if($booker) Buker {{$booker->name}} @else Svi Bukeri @endif</h1>

                {{-- Excursion type --}}
                @if($excursionType)
                  <h2 class='text-center'>Izlet: {{$excursionType->name}}</h2>
                @else
                 <h2 class='text-center'>Svi izleti</h2>
                @endif

                {{-- Period range --}}
                @if($formatedStartDate || $formatedEndDate)
                  <h2 class='text-center'>Rezervacije - period @if($formatedStartDate) od {{$formatedStartDate}} @endif @if($formatedStartDate) do {{$formatedStartDate}} @endif</h2>
                @else
                  <h2 class='text-center'>Sve rezervacije</h2>
                @endif

            </div>

            {{-- RESERVATIONS TABLE --}}
            <div class="col-12 flex justify-center">
                <table class="table-auto" style="width=100%;">
                    <thead>
                        <tr>
                            @if(!$excursionType)<th style="text-align: left; padding-right: 55px;">Izlet</th>@endif
                            @if(!$booker)<th style="text-align: left; padding-right: 55px;">Buker</th>@endif
                            <th style="text-align: left; padding-right: 55px;">Datum</th>
                            <th style="text-align: left; padding-right: 55px;">Broj putnika</th>
                            <th style="text-align: left; padding-right: 55px;">Naplacena cijena [&euro; ]</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($reservations as $reservation)
                            <tr>
                                @if(!$excursionType)<td style="text-align: left; padding-right: 55px; padding-top:5px;">{{$reservation->excursion->excursionType->name}}</td>@endif
                                @if(!$booker)<td>{{$reservation->booker->name}}</td>@endif
                                <td>{{\Carbon\Carbon::parse($reservation->excursion->departure)->format('d.m.Y.')}}</td>
                                <td>{{$reservation->seats}}</td>
                                <td>{{$reservation->price}}</td>
                            </tr>
                            @endforeach
                            <tr>
                                <td style="text-align: left; padding-right: 55px; padding-top: 20px;"></td>
                                <td></td>
                                <td>Ukupno: <strong>{{$totalSeats}}</strong></td>
                                <td>Ukupno: <strong>{{$totalPrice}} &euro;</strong></td>
                            </tr>
                    </tbody>
                </table>
            </div>


        </div>
    </div>
</x-layout-booker-pdf>