<x-layout-crew>
    <v-container>
        <h1 class="mb-2 mt-5 text-center mb-5 red--text">Zdravo, kapetane!</h1>
        <p class='text-center mb-0'>Kliknite na ime stanice za više detalja</p>
        <h3 class='text-center red--text'>Prijatna plovidba</h3>
        <v-row>
            <v-col cols="12">
                <select-date 
                :dates="{{json_encode($dates)}}"
                baseUrl="{{route('crew.homepage')}}"
                >
                </select-date>
            </v-col>
        </v-row>
        
        @foreach ($excursions as $excursionData)
            <v-row class='px-6'>
                <v-col cols='12'>
                    <h3 class="blue--text mb-1">Izlet: {{$excursionData['excursionName']}}</h3>
                    
                    @if($excursionData['reservations']->count())
                    <table class="stripped-table mb-5">
                        <tr class="red--text">
                            <th>Stanica</th>
                            <th>Broj rezervacija</th>
                            <th>Broj putnika</th>
                        </tr>
                            @foreach ($excursionData['reservations'] as $stationName => $reservationsOfTheStation)
                            <tr>
                                <td><a @if($reservationsOfTheStation->count()) href="{{route('crew.excursion-station.details', ['excursion' => $excursionData['excursionId'], 'station' => $reservationsOfTheStation[0]->station_id])}}" @endif>{{$stationName}}</a></td>
                                <td>{{$reservationsOfTheStation->count()}}</td>
                                <td><strong>{{$reservationsOfTheStation->sum('seats') + $reservationsOfTheStation->sum('child_seats')}}</strong> &nbsp; ({{$reservationsOfTheStation->sum('seats')}} odraslih i {{$reservationsOfTheStation->sum('child_seats')}} djece)</td>
                            </tr>
                            @endforeach
                        </table>
                    @else
                        <h3 class="error--text">Za ovaj izlet nema rezervacija</h3>
                    @endif
                </v-col>
            </v-row>
        @endforeach
    </v-container>
</x-layout-crew>