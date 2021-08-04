<template>
        <v-select
          v-model="selectedValue"
          :items="dates"
          item-text="label"
          item-value="value"
          label="Izaberite datum"
          persistent-hint
          return-object
          single-line
          @input="goToDate"
        >
        </v-select>
</template>
<script>
export default {
    props : {
        dates : {
            Type : Array,
            required : true,
            validator : (datesArray) => datesArray.every(val => val.label && val.value)
        },
        baseUrl : {
            Type : String,
            required : false,
            default : "/"
        }
    },

    data(){
        return{
            selectedValue : null
        }
    },

    methods : {
        goToDate(val){
            // console.log(val.value)
            let url  = `${this.baseUrl}?date=${this.selectedValue.value}`
            window.location.href = url
        }
    }
}
</script>