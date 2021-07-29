<template>
    <v-row>
        <v-col cols="12" id="excursion-type-select" class='flex justify-center align-center'>
            <v-select
            v-model="selected"
            :items="excursionTypes"
            item-text="name"
            item-value="id"
            label="Izaberite izlet"
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
        excursionTypes : {
            Type : Array,
            required : false,
            default : () => []
        }
    },

    data(){
        return {
             selected: { state: 'Izaberite izlet', id: null},
        }
    },
    watch : {
        selected : {
            // This will let Vue know to look inside the array
            deep: true,

            // We have to move our method to a handler field
            handler(newVal){
                this.$emit('excursionTypeSelected', newVal)
            }
        }
    }
}
</script>