<template>
    <panel-item :field="field">
        <template slot="value">

            <h2 class='mb-3'>Rekapitulacija po stanicama</h2>
            <table class='mb-5'>
            <tr>
                <th>Stanica</th>
                <th>Odraslih</th>
                <th>Djece</th>
                <th>Ukupno putnika</th>
            </tr>
            <tr
            v-for="(reservationStationData, i) in reservationsByStation"
            :key="'res-data'+i"
            >
                <td>{{reservationStationData['stationName']}}</td>
                <td>{{reservationStationData['seats']}}</td>
                <td>{{reservationStationData['childSeats']}}</td>
                <td>{{reservationStationData['seats'] + reservationStationData['childSeats']}}</td>
            </tr>
            <tr>
                <td></td>
                <td>Ukupno <br> <strong>{{totalRecapData['totalSeats']}}</strong></td>
                <td>Ukupno <br> <strong>{{totalRecapData['totalChildSeats']}}</strong></td>
                <td>Ukupno <br> <strong>{{totalRecapData['totalSeats'] + totalRecapData['totalChildSeats']}}</strong></td>
            </tr>
            </table>


            <h2 class='mb-3 mt-5'>Rekapitulacija po bukerima</h2>
            <table>
            <tr>
                <th>Buker</th>
                <th>Mjesta</th>
                <th>Mjesta za djecu</th>
                <th>Ukupno mjesta</th>
                <th>Naplaćeno</th>
            </tr>
            <tr
            v-for="(reservationBookerData, i) in reservationsByBooker"
            :key="'res-data'+i"
            >
                <td>{{reservationBookerData['name']}}</td>
                <td>{{reservationBookerData['seats']}}</td>
                <td>{{reservationBookerData['childSeats']}}</td>
                <td>{{+reservationBookerData['seats'] + +reservationBookerData['childSeats']}}</td>
                <td>{{reservationBookerData['price']}}</td>
                <!-- <td>{{reservationBookerData['seats'] + reservationBookerData['childSeats']}}</td> -->
            </tr>
            <tr>
                <td></td>
                <td>Ukupno <br> <strong>{{totalRecapData['totalSeats']}}</strong></td>
                <td>Ukupno <br> <strong>{{totalRecapData['totalChildSeats']}}</strong></td>
                <td>Ukupno <br> <strong>{{+totalRecapData['totalChildSeats'] + +totalRecapData['totalSeats']}}</strong></td>
                <td>Ukupno <br> <strong>{{totalPrice}} &euro;</strong></td>
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
                    let adults = stationReservations[j]['seats']? parseInt(stationReservations[j]['seats']) : 0
                    let children = stationReservations[j]['child_seats']? parseInt(stationReservations[j]['child_seats']) : 0
                    seats += adults
                    childSeats += children
                }

                reservationsByStation.push({
                    stationName,
                    seats,
                    childSeats
                })
            }

            return reservationsByStation
        },

        reservationsByBooker(){
            let bookersData = []
            this.field.bookers.forEach((booker)=>{
                let reservationsOfTheBooker = this.field.reservations.filter(reservation => reservation.booker_id == booker.id)
                let price = this.getReservationsTotalPrice(reservationsOfTheBooker)
                let seats = this.getReservationsTotalSeats(reservationsOfTheBooker)
                let childSeats = this.getReservationsTotalChildSeats(reservationsOfTheBooker)

                bookersData.push({
                    name : booker.name,
                    price,
                    seats,
                    childSeats
                    // reservations : reservationsOfTheBooker
                })
            })
            return bookersData
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
        },

        totalPrice(){
            let totalExcursionPrice = 0
            this.field.reservations.forEach(reservation => {totalExcursionPrice += +reservation.price})
            return totalExcursionPrice
        }
    },

    methods : {
        getReservationsTotalPrice(reservations){
            let totalPrice = 0;
            reservations.forEach(reservation => {totalPrice = totalPrice + +reservation.price})

            return totalPrice;
        },
        getReservationsTotalSeats(reservations){
            let totalSeats = 0;
            reservations.forEach(reservation => {totalSeats = totalSeats + +reservation.seats})

            return totalSeats;
        },
        getReservationsTotalChildSeats(reservations){
            let totalSeats = 0;
            reservations.forEach(reservation => {totalSeats = totalSeats + +reservation.child_seats})

            return totalSeats;
        },
    }
}
</script>

<style scoped>
    table {
    font-family: arial, sans-serif;
    border-collapse: collapse;
    width: 100%;
    margin-bottom: 30px;
    }

    td, th {
    border: 1px solid #dddddd;
    text-align: left;
    padding: 8px;
    }

    tr:nth-child(even) {
    background-color: #dddddd;
    }

    h2{
        color: #0000b9;
    }
</style>
