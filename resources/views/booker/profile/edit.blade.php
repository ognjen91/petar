<x-layout-booker>
    <v-container class='edit-profile'>

        
        <v-row>
            <v-col cols="12">
                
                <h1 class='red--text text-center my-5'>Promjena podataka</h1>
                @if(session('success'))
                 <h2 class="green--text text-center my-5">{{session('success')}}</h2>
                @endif
                @if(session('errors'))
                    <ul style="list-style: none">
                        @foreach ($errors->all() as $error)
                             <li class='red--text text-center mb-2'>{{ $error }}</li>
                        @endforeach
                    </ul>
                @endif


                    <form action="{{route('booker.profile.update')}}" method="POST" class='py-5'>
                            <div class="row flex flex-column items-center">

                            @csrf
                            <input type="hidden" name="_method" value="PATCH">
                            
                            <v-col cols="10" md='8'>
                                <label for="name" class='blue--text'>Ime i prezime</label><br>
                                <input required type="text" id="name" name="name" value="{{!session('errors')? $name : old('name')}}">
                            </v-col>
                            
                            <v-col cols="10" md='8'>
                                <label for="email" class='blue--text'>Email</label><br>
                                <input required type="email" id="email" name="email" value="{{!session('errors')? $email : old('email')}}">
                            </v-col>
                            
                            <v-col cols="10" md='8'>
                                <label for="password" class='blue--text'>Stara lozinka</label><br>
                                <input required type="password" id="password" name="password" value="">
                            </v-col>
                            
                            <v-col cols="10" md='8'>
                                <label for="new_password" class='blue--text'>Nova lozinka</label><br>
                                <input required type="password" id="new_password" name="new_password" value="" min="8">
                            </v-col>
                            
                            <v-col cols="10" md='8'>
                                <label for="new_password_confirmation" class='blue--text'>Potvrdite novu lozinku</label><br>
                                <input required type="password" id="new_password_confirmation" name="new_password_confirmation" value="" min="8">
                            </v-col>
                            
                            
                            <button type='submit' class='blue-text col-4'>Izmjenite podatke</button>
                            </div>
                    </form>
                </v-col>
            </v-row>

    </v-container>

</x-layout-booker>