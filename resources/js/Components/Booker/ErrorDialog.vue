<template>
  <div class="text-center">
    <v-overlay :value="showSnackbar"></v-overlay>
    <v-snackbar
      v-model="showSnackbar"
      :multi-line="multiLine"
      color="red"
      centered
    >
        {{error}}
      <template v-slot:action="{ attrs }">
        <v-btn
          color="black"
          text
          v-bind="attrs"
          @click="$emit('closeErrorDialog')"
        >
          Zatvori
        </v-btn>
      </template>
    </v-snackbar>
  </div>
</template>

<script>
  export default {
    props : {
        show : {
            Type : Boolean,
            required : true
        },
        error : {
            Type : String,
            required : true
        }
    },

    data: () => ({
      multiLine: true,
      showSnackbar: false,
    }),

    watch : {
        show(newVal){
            this.showSnackbar = newVal
        }
    }
  }
</script>