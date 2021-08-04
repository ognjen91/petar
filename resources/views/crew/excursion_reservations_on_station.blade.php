<x-layout-crew>
    <v-container>
        <h1 class="mb-2 mt-5 text-center mb-1 red--text">Izlet {{$excursion->name}}</h1>
        <h2 class="mb-2 text-center mb-5 blue--text">Stajalište <strong>{{$station->name}}</strong></h2>
        <v-row>
            <v-col cols="12">
                <table class="stripped-table class='mb-5">
                    <tr>
                      <th>Odraslih</th>
                      <th>Djece</th>
                      <th>Poruka</th>
                    </tr>
                    @foreach ($reservations as $reservation)
                    <tr>
                      <td>{{$reservation->seats}}</td>
                      <td>{{$reservation->child_seats}}</td>
                      <td>{{$reservation->message}}</td>
                    </tr>
                    @endforeach
                  </table>

                  <a href="{{route('crew.homepage')}}" class='red--text my-5'>Nazad na prikaz svih stajališta</a>
            </v-col>
        </v-row>
    </v-container>
</x-layout-crew>