<template>
  <v-footer
    color="primary lighten-1"
    padless
    class='mt-5'
  >
    <v-row
      justify="center"
      no-gutters
    >
      <v-col
        class="primary lighten-2 py-4 text-center white--text"
        cols="12"
      >
        <small>{{ new Date().getFullYear() }} &copy; — Booking Mamma by <strong><a href="http://osam.marekting">Osam Marketing</a></strong></small>
      </v-col>
    </v-row>
  </v-footer>
</template>

<script>
  export default {
    data: () => ({
      links: [
        
      ],
    }),
  }
</script>