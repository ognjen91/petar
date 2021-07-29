<template>
  <div class="text-center">
    <v-overlay :value="showSnackbar"></v-overlay>
    <v-snackbar
      v-model="showSnackbar"
      :multi-line="multiLine"
      color="green"
      centered
    >
        Rezervacija je uspješno izvršena
      <template v-slot:action="{ attrs }">
        <v-btn
          color="red"
          text
          v-bind="attrs"
          @click="$emit('closeSuccessDialog')"
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
        }
    },

    data: () => ({
      multiLine: true,
      showSnackbar: false,
      text: `I'm a multi-line snackbar.`,
    }),

    watch : {
        show(newVal){
            this.showSnackbar = newVal
        }
    }
  }
</script>