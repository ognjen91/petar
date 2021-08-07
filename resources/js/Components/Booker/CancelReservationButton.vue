<template>
    <div>
        <!-- CONFIRM DIALOG -->
        <v-dialog
        v-model="showConfirmDialog"
        width="500"
        >
            <template v-slot:activator>
                <v-btn
                elevation="2"
                large
                color='red'
                class='white--text'
                @click='showConfirmDialog = true'
                >
                <slot></slot>
                </v-btn>
            </template>

            <v-card>
            <v-card-title class="text-h5 red lighten-2 mb-5 white--text">
            Otkazivanje rezervacije
            </v-card-title>

            <v-card-text>
            {{!reservationCanceled? confirmText : successText}}
            </v-card-text>

            <v-divider></v-divider>

            <v-card-actions>
            <v-spacer></v-spacer>
            <v-btn
            color="primary"
            text
            @click="cancelReservation"
            v-if="!reservationCanceled"
            >
            I accept
            </v-btn>
            </v-card-actions>
            </v-card>
        </v-dialog>


        <ErrorDialog
        :show="showErrorDialog"
        :error='error'
        @closeErrorDialog="showSuccessDialog = false"
        />
    </div>
</template>
<script>
import ErrorDialog from './ErrorDialog'
import axios from 'axios'
export default {
    components : {
        ErrorDialog
    },

    props : {
        reservationId : {
            Type : Number,
            required : true
        },
        excursionType : {
            Type : String,
            required : false,
            default : 'regular',
            validator: val => ['regular', 'private'].includes(val)
        }
    },

    data(){
        return {
            showConfirmDialog : false,
            reservationCanceled : false,
            confirmText : 'Da li ste sigurni da želite da otkažete rezervaciju?',
            successText : 'Rezervacija je uspješno otkazana. Stranica će se osvježiti.',
            showErrorDialog : false,
            error : ""
        }
    },

    methods : {
        cancelReservation(){

            axios.post(`/cancel-reservation/${this.reservationId}`, {
                excursion_type : this.excursionType
            })
            .then(({data}) => {
                this.reservationCanceled = true
                setTimeout(()=>{
                    location.reload();
                }, 3000)
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
    }
}
</script>