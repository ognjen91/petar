<template>
      <v-row>
        <v-col cols="12" class='flex justify-center align-center'>
          <v-date-picker
          locale="sr-latn" 
          v-model="selectedDate"
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
      selectedDate: null
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