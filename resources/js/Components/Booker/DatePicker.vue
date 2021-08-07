<template>
      <v-row>
        <v-col cols="12" class='flex justify-center align-center'>
          <v-date-picker
          locale="sr-latn" 
          v-model="selectedDate"
          :min="min"
          @input="onInput"
          ></v-date-picker>
        </v-col>
      </v-row>
</template>

<script>
export default {
  props : {
    initialDate : {
      Type : Date,
      required : false
    }
  },

  data () {
    return {
      selectedDate: null,
      min : (new Date(Date.now() - (new Date()).getTimezoneOffset() * 60000)).toISOString().substr(0, 10),
    }
  },

  methods : {
    onInput(newVal){
      this.$emit('dateSelected', newVal)
    }
  },

  mounted(){
    this.selectedDate = this.initialDate
  }

}
</script>