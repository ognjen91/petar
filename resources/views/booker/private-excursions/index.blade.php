<x-layout-booker>
    <v-container>
        <v-row>
            <v-col cols='12'>
                <h1 class="mb-2 mt-5 text-center">Privatne ture</h1>
                <h2 class="mb-5 text-center">Napravite novu rezervaciju privatne ture klikom <a href="{{route('private-excursions.create')}}" class='red--text'>ovdje</a></h2>
                <private-excursions-calendar :excursions="{{json_encode($futurePrivateExcursions)}}"></private-excursions-calendar>
            </v-col>
        </v-row>
    </v-container>
</x-layout-booker>