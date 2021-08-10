<x-layout-booker-pdf>
    <div class=" mx-auto px-3">
        <div class="row">

            {{-- DATA, TITLES --}}
            <div class="col-12" style='margin: 20px 0;'>
                
                <h1 class='text-center'>@if($booker) Buker {{$booker->name}} @else Svi Bukeri @endif</h1>
                <h1 class='text-center' style='color: red;'>Privatni izleti</h1>

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
                            <th style="text-align: left; padding-right: 55px;">Naslov</th>
                            @if(!$booker)<th style="text-align: left; padding-right: 55px;">Buker</th>@endif
                            <th style="text-align: left; padding-right: 55px;">Početak</th>
                            <th style="text-align: left; padding-right: 55px;">Kraj</th>
                            <th style="text-align: left; padding-right: 55px;">Naplaceno [&euro; ]</th>
                            <th style="text-align: left; padding-right: 55px;">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($reservations as $reservation)
                            <tr @if($reservation->isCanceled) style="color : red;" @endif>
                                <td>{{$reservation->title}}</td>
                                @if(!$booker)<td>{{$reservation->booker->name}}</td>@endif
                                <td>{{\Carbon\Carbon::parse($reservation->start)->format('d.m.Y. H:i')}}</td>
                                <td>{{\Carbon\Carbon::parse($reservation->end)->format('d.m.Y. H:i')}}</td>
                                <td>{{$reservation->price}}</td>
                                <td>{{!$reservation->isCanceled? (\Carbon\Carbon::parse($reservation->start) < \Carbon\Carbon::now()? 'Aktivno' : 'Izvršeno') : 'Otkazano'}}</td>
                            </tr>
                            @endforeach
                            <tr>
                                <td></td>
                                @if(!$booker) <td></td> @endif
                                <td style="text-align: left; padding-right: 55px; padding-top: 20px;"></td>
                                <td></td>
                                <td>Ukupno: <br><strong>{{$totalPrice}} &euro;</strong></td>
                                <td></td>
                            </tr>
                    </tbody>
                </table>
            </div>


            <div style="margin-top:50px; text-align:center;"><small>Izvještaj generisan: {{\Carbon\Carbon::now()->format('d.m.Y. H:i')}}</small></div>


        </div>
    </div>
</x-layout-booker-pdf>