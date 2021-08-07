<template>
    <v-container class="grey lighten-5">
        <v-row class='mt-4'>
            <v-col cols="12" class="">
                
                <p class='text-center blue--text mb-0'>Booking Mama &copy;</p>
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

        <!-- SEATS, CHILD SEATS, PRICE -->
        <v-row>
            <v-col cols="12" class='flex flex-column justify-center items-center'>
                <div class="mb-3">
                    <label for="seats"><strong>Broj mjesta</strong></label><br>
                    <input id="seats" type="number" placeholder="Broj mjesta" v-model="seats" min="1">
                </div>
                <div class="mb-3">
                    <label for="childSeats"><strong>Broj mjesta za djecu</strong></label><br>
                    <input id="childSeats" type="number" placeholder="Broj mjesta za djecu" v-model="childSeats" min="0">
                </div>
                <div class="">
                    <label for="price"><strong>Naplaćeni iznos</strong></label><br>
                    <input id="price" type="number" placeholder="iznos u evrima" v-model="price" min="0" step="0.1">
                </div>
             </v-col>
        </v-row>

        <!-- ======EXCURSION SELECT====== -->
        <v-row>
            <!-- MAIN DIRECTION -->
            <v-col cols="12">
                <ExcursionsOnTheDateWithEnoughSeats 
                :excursions="excursionsOnTheDateWithEnoughSeats"
                @excursionSelected="proceedExcursionSelected"
                :selected-excursion-id="selectedExcursionId"
                />
             </v-col>

            <!-- CONNECTED DIRECTION, IF EXIST -->
            <v-col cols="12">
                <h3 class="danger-text">Molimo odaberite povratak</h3>

                <ExcursionsOnTheDateWithEnoughSeats
                v-if="connectedTypesExist" 
                :excursions="connectedExcursionsOnTheDateWithEnoughSeats"
                @excursionSelected="proceedConnectedExcursionSelected"
                :selected-excursion-id="selectedConnectedExcursionId"
                />
             </v-col>


        </v-row>

        <!-- ======MESSAGE====== -->
        <v-row>
            <v-col cols="12">
                <v-textarea
                outlined
                name="input-7-4"
                label="Dodatna poruka"
                v-model="message"
                >
                </v-textarea>            
        </v-col>
        </v-row>

        <!-- BTNS -->
        <v-row>
            <v-col cols="12">
            <!-- IF ALL REQUIRED PARAMS ARE SELECTED -->
                <v-btn
                color="green"
                class="white--text"
                elevation="2"
                x-large
                style="width: 100%;"
                @click="book"
                v-if='price && seats && selectedExcursionId && selectedStationId && canProceedAccordingToConnectedExcursionRequirement'
                >
                Bukiraj izlet
                </v-btn>

            <!-- IF ALL REQUIRED PARAMS ARE NOT SELECTED -->
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
        @closeErrorDialog="showErrorDialog = false"
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
            connectedExcursionsOnTheDate : [],
            connectedTypesExist : false,
            selectedExcursionId : null,
            selectedConnectedExcursionId : null,
            seats : 1,
            childSeats : 0,
            price : null,
            message : "",
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
        connectedExcursionsOnTheDateWithEnoughSeats(){
            if(!this.connectedTypesExist) return []
            return this.connectedExcursionsOnTheDate.filter(exc => +exc.freeSeats >= +this.seats)
        },
        selectedExcursionTypesStations(){
            if(!this.selectedExcursionType) return []
            return this.selectedExcursionType.stations
        },

        canProceedAccordingToConnectedExcursionRequirement(){
            if(!this.connectedTypesExist) return true
            if(this.connectedTypesExist) return !!this.selectedConnectedExcursionId
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
        proceedConnectedExcursionSelected(excursion){
            this.selectedConnectedExcursionId = excursion.id
        },
        proceedStationSelected(station){
            this.selectedStationId = station.id
        },

        // CHECK AVAILABLE TIMES (POST REQUEST)
        checkAvailableTimes(){
            axios.post('check-excursions-on-date', {
                selectedExcursionTypeId : this.selectedExcursionType.id,
                selectedDate : this.selectedDate,
            }).then(({data}) => {
                this.excursionsOnTheDate = data.excursionsOnTheDate
                this.connectedExcursionsOnTheDate = data.connectedExcursionsOnTheDate,
                this.connectedTypesExist = data.connectedTypesExist
            })
            .catch((error) => {
                this.excursionsOnTheDate = []
                this.connectedExcursionsOnTheDate = []
            })
        },

        book(){
            let bookingData = {
                selectedExcursionId : this.selectedExcursionId,
                station : this.selectedStationId,
                seats : this.seats,
                child_seats : this.childSeats,
                price : this.price,
                message : this.message
            }
            if(this.selectedConnectedExcursionId) bookingData['selectedConnectedExcursionId'] = this.selectedConnectedExcursionId

            axios.post('book', bookingData).then(({data}) => {
                this.showSuccessDialog = true
                this.excursionsOnTheDate = []
                this.connectedExcursionsOnTheDate = []
                this.selectedExcursionId = null
                this.selectedConnectedExcursionId = null

                setTimeout(()=>{
                    location.reload();
                }, 4000)
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
            // if(newVal.length){
                this.selectedExcursionId = null
                this.selectedConnectedExcursionId = null
            // } 
        }
    }
}
</script>