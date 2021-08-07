<template>
    <v-row id="my-reservations-params">
        
        <!-- START DATE -->
        <v-col cols="6" :md="showExcursionTypesSelect? 4 : 6" :lg="showExcursionTypesSelect? 2 : 3" class="flex items-center justify-center flex-column">
            <p @click="startCalendar = !startCalendar">Od: {{startDate}}</p>
            <v-date-picker
            v-model="startDate"
            v-if="startCalendar" 
            class='pick-open'
            @input="setStartDate"
            >
            </v-date-picker>
        </v-col>

        <!-- END DATE -->
        <v-col cols="6" :md="showExcursionTypesSelect? 4 : 6" :lg="showExcursionTypesSelect? 2 : 3" class="flex items-center justify-center flex-column">
            <p @click="endCalendar = !endCalendar">Do: {{endDate}}</p>
            <v-date-picker 
            v-model="endDate" 
            v-if="endCalendar" 
            class='pick-open'
            @input="setEndDate"
            >
            </v-date-picker>
        </v-col>

        <!-- EXCURSION TYPE -->
        <v-col cols="6" :md="showExcursionTypesSelect? 4 : 6" :lg="showExcursionTypesSelect? 2 : 3" class="flex items-center justify-center flex-column" v-if='showExcursionTypesSelect'>
            <v-select
            v-model="selectedType"
            :items="excursionTypes"
            item-text="name"
            item-value="id"
            label="Izaberite izlet"
            return-object
            single-line
            >
            </v-select>
        </v-col>


        <!-- ORDER  -->
        <v-col cols="6" :md="showExcursionTypesSelect? 4 : 6" :lg="showExcursionTypesSelect? 2 : 3" class="flex items-center justify-center flex-column" v-if='showExcursionTypesSelect'>
            <v-select
            v-model="selectedOrder"
            :items="orderOptions"
            item-text="label"
            item-value="value"
            label="Izaberite redosljed"
            single-line
            >
            </v-select>
        </v-col>


        <v-col cols="6" :md="showExcursionTypesSelect? 4 : 6" :lg="showExcursionTypesSelect? 3 : 2" class="flex items-center justify-center flex-column">
            <v-btn
            elevation="2"
            @click="redirect"
            >Prikaži</v-btn>    
        </v-col>
    </v-row>
</template>
<script>
export default {
    props : {
        baseUrl : {
            Type : String,
            required : false,
            default : "/moje-rezervacije?"
        },
        excursionTypes : {
            Type : Array,
            required : false,
            default : () => []
        },
        showExcursionTypesSelect :{
            Type : Boolean,
            required : false,
            default : true
        },
        initialStartDate : {
            Type : String,
            required : false,
            default : (new Date(Date.now() - (new Date()).getTimezoneOffset() * 60000)).toISOString().substr(0, 10)
        },
        initialEndDate : {
            Type : String,
            required : false,
            default : (new Date(Date.now() - (new Date()).getTimezoneOffset() * 60000)).toISOString().substr(0, 10)
        },
        order : {
            Type : String,
            required : false,
            default : 'desc',
            validator : value => ['desc', 'asc'].includes(value)
        },
    },

    data(){
        return {
             selectedType: { state: 'Izaberite izlet', id: null},
             startCalendar : false,
             endCalendar : false,
             startDate : (new Date(Date.now() - (new Date()).getTimezoneOffset() * 60000)).toISOString().substr(0, 10),
             endDate : (new Date(Date.now() - (new Date()).getTimezoneOffset() * 60000)).toISOString().substr(0, 10),
             selectedOrder : null,
             orderOptions : [
                 {
                 value : 'asc',
                 label : 'Prvo stariji'
                },
                 {
                 value : 'desc',
                 label : 'Prvo noviji'
                },
            ]
        }
    },

    methods : {
        setStartDate(date){
            this.startDate = date
            this.startCalendar = false
        },
        setEndDate(date){
            this.endDate = date
            this.endCalendar = false
        },

        redirect(){
            let url = `${this.baseUrl}?`
            if(this.selectedType.id) url += `excursionType=${this.selectedType.id}&`
            if(this.startDate) url += `type=${this.startDate}&`
            if(this.endDate) url += `type=${this.endDate}&`
            if(this.selectedOrder) url += `order=${this.selectedOrder}&`

            window.location.href = url;

        }
    },

    watch : {
        startCalendar(newVal){
            if(newVal) this.endCalendar = false
        },
        endCalendar(newVal){
            if(newVal) this.startCalendar = false
        }
    },

    mounted(){
        this.startDate = this.initialStartDate
        this.endDate = this.initialEndDate
    }
}
</script>