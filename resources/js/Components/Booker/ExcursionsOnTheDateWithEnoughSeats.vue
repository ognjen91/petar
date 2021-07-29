<template>
    <v-row>
        <v-col cols="12">
            <v-btn
            :color="excursion.id == selectedExcursionId? 'blue' : 'gray'"
            v-for="(excursion, i) in excursions"
            :key="'exc-with-free-seats-'+i"
            elevation="2"
            x-large
            style="width : 100%;"
            @click="excursionSelected(excursion)"
            class='mb-4'
            :class="{'white--text' : excursion.id == selectedExcursionId}"
            >
                {{excursion.departure}}
            </v-btn>            
        </v-col>
    </v-row>
</template>
<script>
export default {
    props : {
        excursions : {
            Type : Array,
            required : false
        },
        selectedExcursionId : {
            Type : Number || null,
            required : true
        }
    },

    methods : {
        excursionSelected(excursion){
            this.$emit('excursionSelected', excursion)
        }
    }
}
</script>