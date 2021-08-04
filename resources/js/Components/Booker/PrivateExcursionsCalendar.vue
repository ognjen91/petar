<template>
  <div>
    <v-sheet
      tile
      height="54"
      class="d-flex mb-5"
    >
      <v-btn
        class="ma-2"
        @click="$refs.calendar.prev()"
      >
        prethodni mjesec
      </v-btn>

      <v-spacer></v-spacer>
      <v-btn
        class="ma-2"
        @click="$refs.calendar.next()"
      >
        naredni mjesec
      </v-btn>
    </v-sheet>
   
    <v-sheet height="600">
      <v-calendar
        ref="calendar"
        v-model="value"
        :weekdays="weekday"
        :events="formatedExcursions"
        :type="type"
        :event-overlap-mode="mode"
        :event-overlap-threshold="30"
        :event-color="getEventColor"
        @click:date="toggleType"
        @click:more="toggleType"
      ></v-calendar>
    </v-sheet>


  </div>
</template>
<script>
  export default {
    props : {
      excursions : {
        type : Array,
        required : true
      }
    },

    computed : {
      formatedExcursions(){
        return this.excursions.map(exc => { return {
          name : exc.title,
          start : new Date(exc.start * 1000),
          end : new Date(exc.end * 1000),
          color : exc.color,
          timed : true
        }
        })
      }
    },

    data: () => ({
      type: 'month',
      types: ['category','month', 'week', 'day', '4day'],
      mode: 'stack',
      modes: ['stack', 'column'],
      weekday: [0, 1, 2, 3, 4, 5, 6],
      weekdays: [
        { text: 'Sun - Sat', value: [0, 1, 2, 3, 4, 5, 6] },
        { text: 'Mon - Sun', value: [1, 2, 3, 4, 5, 6, 0] },
        { text: 'Mon - Fri', value: [1, 2, 3, 4, 5] },
        { text: 'Mon, Wed, Fri', value: [1, 3, 5] },
      ],
      value: '',
    }),
    methods: {
      toggleType(){
        this.type = this.type !== 'month'? 'month' : 'day'
      },

      getEventColor (event) {
        return event.color
      },
      rnd (a, b) {
        return Math.floor((b - a + 1) * Math.random()) + a
      },
    },
  }
</script>