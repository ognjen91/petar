<template>
    <panel-item :field="field">
        <template slot="value">
            <table>
            <tr>
                <th>Stanica</th>
                <th>Odraslih</th>
                <th>Djece</th>
                <th>Ukupno putnika</th>
            </tr>
            <tr
            v-for="(reservationData, i) in reservationsByStation"
            :key="'res-data'+i"
            >
                <td>{{reservationData['stationName']}}</td>
                <td>{{reservationData['seats']}}</td>
                <td>{{reservationData['childSeats']}}</td>
                <td>{{reservationData['seats'] + reservationData['childSeats']}}</td>
            </tr>
            <tr>
                <td></td>
                <td>Ukupno <br> <strong>{{totalRecapData['totalSeats']}}</strong></td>
                <td>Ukupno <br> <strong>{{totalRecapData['totalChildSeats']}}</strong></td>
                <td>Ukupno <br> <strong>{{totalRecapData['totalSeats'] + totalRecapData['totalChildSeats']}}</strong></td>
            </tr>
            </table>
        </template>
    </panel-item>
</template>

<script>
export default {
    props: ['resource', 'resourceName', 'resourceId', 'field'],
    computed : {
        reservationsByStation(){
            let reservationsByStation = []
            for(let i=0; i<this.field.stations.length; i++){
                let stationName = this.field.stations[i]['name']
                let stationId = this.field.stations[i]['id']
                let stationReservations = this.field.reservations.filter(reservation => reservation.station_id == stationId)

                let seats = 0
                let childSeats = 0
                for(let j=0; j<stationReservations.length; j++){
                    seats += parseInt(stationReservations[j]['seats'])
                    childSeats += parseInt(stationReservations[j]['child_seats'])
                }

                reservationsByStation.push({
                    stationName,
                    seats,
                    childSeats
                })
            }

            return reservationsByStation
        },

        totalRecapData(){
            let totalSeats = 0
            let totalChildSeats = 0

            for(let i=0; i<this.reservationsByStation.length; i++){
                totalSeats += parseInt(this.reservationsByStation[i]['seats'])
                totalChildSeats += parseInt(this.reservationsByStation[i]['childSeats'])
            }

            return {
                totalSeats,
                totalChildSeats
            }
        }
    }
}
</script>

<style scoped>
    table {
    font-family: arial, sans-serif;
    border-collapse: collapse;
    width: 100%;
    }

    td, th {
    border: 1px solid #dddddd;
    text-align: left;
    padding: 8px;
    }

    tr:nth-child(even) {
    background-color: #dddddd;
    }
</style>
