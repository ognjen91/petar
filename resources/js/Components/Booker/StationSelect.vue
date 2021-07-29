<template>
    <v-row>
        <v-col cols="12" id="station-select" class='flex justify-center align-center'>
            <v-select
            v-model="selected"
            :items="stations"
            item-text="name"
            item-value="id"
            label="Izaberite stanicu"
            return-object
            single-line
            >
            </v-select>
        </v-col>
    </v-row>
</template>
<script>
export default {
    props : {
        stations : {
            Type : Array,
            required : false,
            default : () => []
        }
    },

    data(){
        return {
             selected: { name: 'Izaberite stanicu', id: null},
        }
    },
    watch : {
        selected : {
            // This will let Vue know to look inside the array
            deep: true,

            // We have to move our method to a handler field
            handler(newVal){
                this.$emit('stationSelected', newVal)
            }
        }
    }
}
</script>