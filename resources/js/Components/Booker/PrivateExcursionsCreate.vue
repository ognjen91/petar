<template>
    <v-row>
        <v-col cols="12">
                <!-- TITLE -->
                <p class="mt-6 mb-0 blue--text"><strong>Naslov</strong></p>
                <v-text-field
                v-model="title"
                placeholder="Naslov"
                >
                </v-text-field>

                <!-- PRICE -->
                <p class="mt-6 mb-0 blue--text"><strong>Cijena</strong></p>
                <v-text-field
                v-model="price"
                placeholder="Cijena"
                type="number"
                >
                </v-text-field>

                <!-- START -->
                <Datetime
                type="datetime"
                v-model="start"
                input-id="startDate"
                :class="{'not-selected' : !start}"
                :min-datetime="min"
                >
                    <label for="startDate" slot="before">
                        <span v-if="!start">Kliklite <span class="red--text">ovdje </span> da odaberete datum i vrijeme početka izleta</span>        
                        <span v-else>Početak izleta:</span>
                    </label>
                </Datetime>



                <!-- END -->
                <p class="mt-6 mb-0 blue--text"><strong>Kraj izleta</strong></p>
                <Datetime
                type="datetime"
                v-model="end"
                input-id="endDate"
                :class="{'not-selected' : !end}"
                :min-datetime="min"
                >
                    <label for="endDate" slot="before">
                        <span v-if="!end">Kliklite <span class="red--text">ovdje </span> da odaberete datum i vrijeme kraja izleta</span>        
                        <span v-else>Kraj izleta:</span>
                    </label>
                </Datetime>



                <v-textarea
                outlined
                class='mt-5'
                name="input-7-4"
                label="Dodatna poruka (opciono)"
                v-model='message'
                ></v-textarea>

                <v-btn
                v-if="title && price && start && end"
                color="blue"
                class='white--text'
                elevation="2"
                x-large
                @click="book"
                >
                Rezerviši
                </v-btn>
                <v-btn
                v-else
                color="red"
                class='white--text'
                elevation="2"
                x-large
                >
                Molimo odaberite sve parametre
                </v-btn>


                <SuccessDialog 
                :show="showSuccessDialog" 
                @closeSuccessDialog="showSuccessDialog = false"
                />
                <ErrorDialog
                :show="showErrorDialog"
                :error='error'
                @closeErrorDialog="showErrorDialog = false"
                />



        </v-col>
    </v-row>
</template>
<script>
import { Datetime } from 'vue-datetime';
import axios from 'axios'
import SuccessDialog from './SuccessDialog'
import ErrorDialog from './ErrorDialog'

export default {
    components : {
         Datetime,
         SuccessDialog,
         ErrorDialog
    },
    data(){
        return {
            title : "",
            price : "",
            start : "",
            end : "",
            message : "",
            showSuccessDialog : false,
            showErrorDialog : false,
            error : "",
            min : (new Date(Date.now() - (new Date()).getTimezoneOffset() * 60000)).toISOString().substr(0, 10),
        }
    },

    methods : {
        book(){
            if(this.end < this.start){
                this.showErrorDialog = true
                this.error = "Vrijeme kraja mora biti nakon vremena početka izleta"
                return
            }

            if(isNaN(this.price)){
                this.showErrorDialog = true
                this.error = "Cijena mora biti broj"
                return
            }

            axios.post('/private-tours', {
                title : this.title,
                price : this.price,
                start : this.start,
                end : this.end,
                message : this.message,
            }).then(({data}) => {
              this.showSuccessDialog = true
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
}
</script>