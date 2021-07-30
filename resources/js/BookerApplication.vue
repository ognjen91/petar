<template>
    <v-container class="grey lighten-5">
        <v-row class='mt-4'>
            <v-col cols="12" class="">
                <h1 class='text-center blue--text'>Petar Boats Booking</h1>
            </v-col>
        </v-row>

        <!-- SELECT EXCURSION -->
        <ExcursionTypeSelect 
        :excursion-types="excursionTypes" 
        @excursionTypeSelected="proceedSelectedExcursion"
        />

        <!-- SELECT STATION -->
        <StationSelect
        v-if="this.selectedExcursionType"
        :stations="selectedExcursionTypesStations"
        @stationSelected="proceedStationSelected"
        />

        <!-- SELECT DATE -->
        <DatePicker
        :initial-date="selectedDate"
        @dateSelected="proceedDateSelected"
        />

        <!-- SEATS -->
        <v-row>
            <v-col cols="12" class='flex flex-column justify-center items-center'>
                <div class="mb-3">
                    <label for="seats"><strong>Broj mjesta</strong></label><br>
                    <input id="seats" type="number" placeholder="Broj mjesta" v-model="seats" min="1">
                </div>
                <div class="">
                    <label for="price"><strong>Naplaćeni iznos</strong></label><br>
                    <input id="price" type="number" placeholder="iznos u evrima" v-model="price" min="0" step="0.1">
                </div>
             </v-col>
        </v-row>


        <v-row>
            <v-col cols="12">
                <ExcursionsOnTheDateWithEnoughSeats 
                :excursions="excursionsOnTheDateWithEnoughSeats"
                @excursionSelected="proceedExcursionSelected"
                :selected-excursion-id="selectedExcursionId"
                />
             </v-col>
        </v-row>

        <v-row>
            <v-col cols="12">
                <v-btn
                color="green"
                class="white--text"
                elevation="2"
                x-large
                style="width: 100%;"
                @click="book"
                v-if='price && seats && selectedExcursionId && selectedStationId '
                >
                Bukiraj izlet
                </v-btn>

                <v-btn
                color="red lighten-2"
                class="white--text"
                elevation="2"
                x-large
                style="width: 100%;"
                v-else
                >
                Molimo odaberite parametre
                </v-btn>

             </v-col>
        </v-row>

        <SuccessDialog 
        :show="showSuccessDialog" 
        @closeSuccessDialog="showSuccessDialog = false"
        />
        <ErrorDialog
        :show="showErrorDialog"
        :error='error'
        @closeErrorDialog="showSuccessDialog = false"
        />


    </v-container>
</template>
<script>
import ExcursionTypeSelect from './Components/Booker/ExcursionTypeSelect'
import StationSelect from './Components/Booker/StationSelect'
import DatePicker from './Components/Booker/DatePicker'
import ExcursionsOnTheDateWithEnoughSeats from './Components/Booker/ExcursionsOnTheDateWithEnoughSeats'
import SuccessDialog from './Components/Booker/SuccessDialog'
import ErrorDialog from './Components/Booker/ErrorDialog'
import axios from 'axios'

export default {
    components : {
        ExcursionTypeSelect,
        StationSelect,
        DatePicker,
        ExcursionsOnTheDateWithEnoughSeats,
        SuccessDialog,
        ErrorDialog
    },
    props : {
        excursionTypes : {
            Type : Array,
            required : false,
            default : () => []
        }
    },

    data(){
        return{
            selectedExcursionType : null,
            selectedStationId : null,
            selectedDate : (new Date(Date.now() - (new Date()).getTimezoneOffset() * 60000)).toISOString().substr(0, 10),
            excursionsOnTheDate : [],
            seats : 1,
            price : null,
            selectedExcursionId : null,
            showBookBtn : false,
            showSuccessDialog : false,
            showErrorDialog : false,
            error : ""
        }
    },

    computed : {
        excursionsOnTheDateWithEnoughSeats(){
            return this.excursionsOnTheDate.filter(exc => +exc.freeSeats >= +this.seats)
        },
        selectedExcursionTypesStations(){
            if(!this.selectedExcursionType) return []
            return this.selectedExcursionType.stations
        }
    },

    methods : {
        proceedSelectedExcursion(selectedTypeObject){
            this.selectedExcursionType = selectedTypeObject
            if(this.selectedDate && this.seats) this.checkAvailableTimes()
        },
        proceedDateSelected(selectedDate){
            this.selectedDate = selectedDate
            if(this.selectedExcursionType && this.seats) this.checkAvailableTimes()
        },
        proceedExcursionSelected(excursion){
            this.selectedExcursionId = excursion.id
        },

        proceedStationSelected(station){
            this.selectedStationId = station.id
        },

        // CHECK AVAILABLE TIMES (POST REQUEST)
        checkAvailableTimes(){
            axios.post('check-excursions-on-date', {
                selectedExcursionTypeId : this.selectedExcursionType.id,
                selectedDate : this.selectedDate
            }).then(({data}) => {
                this.excursionsOnTheDate = data.excursionsOnTheDate
            })
            .catch((error) => {
            })
        },

        book(){
            axios.post('book', {
                selectedExcursionId : this.selectedExcursionId,
                station : this.selectedStationId,
                seats : this.seats,
                price : this.price
            }).then(({data}) => {
                this.showSuccessDialog = true
            })
            .catch((error) => {
                if(error.response.status === 501){
                    this.showErrorDialog = true
                    this.error = error.response.data.status
                } else {
                    alert('Došlo je do greške')
                }
            })
        }
    },

    watch : {
        selectedExcursionId(newVal){
            this.showBookBtn = !!newVal
        },
        excursionsOnTheDateWithEnoughSeats(newVal){
            if(newVal.length) this.selectedExcursionId = false
        }
    }
}
</script>