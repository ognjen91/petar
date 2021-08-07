<x-layout-booker-pdf>
    <div class=" mx-auto px-3">
        <div class="row">

            {{-- DATA, TITLES --}}
            <div class="col-12" style='margin: 20px 0;'>
                
                <h1 class='text-center'>@if($booker) Buker {{$booker->name}} @else Svi Bukeri @endif</h1>
                <h1 class='text-center' style='color: red;'>Redovni izleti</h1>

                {{-- Excursion type --}}
                @if($excursionType)
                  <h2 class='text-center'>Izlet: {{$excursionType->name}}</h2>
                @else
                 <h2 class='text-center'>Svi izleti</h2>
                @endif

                {{-- Period range --}}
                @if($formatedStartDate || $formatedEndDate)
                  <h2 class='text-center'>Rezervacije - period @if($formatedStartDate) od {{$formatedStartDate}} @endif @if($formatedEndDate) do {{$formatedEndDate}} @endif</h2>
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
                            <th style="text-align: left; padding-right: 55px;">Vrijeme</th>
                            <th style="text-align: left; padding-right: 55px;">Mjesta</th>
                            <th style="text-align: left; padding-right: 55px;">Naplaceno [&euro; ]</th>
                            <th style="text-align: left; padding-right: 55px;">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($reservations as $reservation)
                            @continue($reservation->isReturnWayDirectionReservation)

                            <tr @if(!$reservation->active) style="color : red;" @endif>
                                @if(!$excursionType)<td style="text-align: left; padding-right: 55px; padding-top:5px;">{{$reservation->excursion->excursionType->name}}</td>@endif
                                @if(!$booker)<td>{{$reservation->booker->name}}</td>@endif
                                <td>{{\Carbon\Carbon::parse($reservation->excursion->departure)->format('d.m.Y.')}}</td>
                                <td>{{\Carbon\Carbon::parse($reservation->excursion->departure)->format('H:i')}}</td>
                                <td>{{$reservation->seats}}</td>
                                <td>{{$reservation->price}}</td>
                                <td>{{$reservation->active? (\Carbon\Carbon::parse($reservation->excursion->departure) < \Carbon\Carbon::now()? 'Aktivno' : 'Izvršeno') : 'Otkazano'}}</td>
                            </tr>
                            @endforeach
                            <tr>
                                @if(!$excursionType)<td></td>@endif
                                @if(!$booker) <td></td> @endif
                                <td style="text-align: left; padding-right: 55px; padding-top: 20px;"></td>
                                <td></td>
                                <td>Ukupno: <br><strong>{{$totalSeats}} mjesta</strong></td>
                                <td>Ukupno: <br><strong>{{$totalPrice}} &euro;</strong></td>
                                <td></td>
                            </tr>
                    </tbody>
                </table>
            </div>


            {{-- ===========ADDITIONAL, BY STATIONS IF DATES ARE THE SAME======== --}}
            @if($sholudShowStationsRecapTable)

            <table style="margin-top: 90px;">
                <tr>
                    <th>Stanica</th>
                    <th>Odraslih</th>
                    <th>Djece</th>
                    <th>Ukupno putnika</th>
                </tr>
                @foreach ($reservationsByStation as $reservationData)
                    <tr>
                        <td>{{$reservationData['stationName']}}</td>
                        <td>{{$reservationData['seats']}}</td>
                        <td>{{$reservationData['childSeats']}}</td>
                        <td>{{$reservationData['seats'] + $reservationData['childSeats']}}</td>
                    </tr>
                @endforeach
                {{-- <tr>
                    <td></td>
                    <td>Ukupno <br> <strong>{{totalRecapData['totalSeats']}}</strong></td>
                    <td>Ukupno <br> <strong>{{totalRecapData['totalChildSeats']}}</strong></td>
                    <td>Ukupno <br> <strong>{{totalRecapData['totalSeats'] + totalRecapData['totalChildSeats']}}</strong></td>
                </tr> --}}
                </table>

            @endif


            <div style="margin-top:50px; text-align:center;"><small>Izvještaj generisan: {{\Carbon\Carbon::now()->format('d.m.Y. H:i')}}</small></div>


        </div>
    </div>


    <style>
    table {
    font-family: arial, sans-serif;
    border-collapse: collapse;
    width: 100%;
    }

    td, th {
    border: 1px solid #dddddd;
    text-align: left;
    padding: 8px;
    }

    tr:nth-child(even) {
    background-color: #dddddd;
    }
    </style>
</x-layout-booker-pdf>