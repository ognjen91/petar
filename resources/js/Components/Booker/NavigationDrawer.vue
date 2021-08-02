<template>
  <div class='absolute' style="z-index: 3;">
        <v-img
        max-height="40"
        max-width="40"
        src="/images/hamburger.png"
        @click.stop="drawer = !drawer"
        id="nav-drawer-trigger"
        ></v-img>

    <v-navigation-drawer
      v-model="drawer"
      absolute
      temporary
      id='the-drawer'
    >
      <v-list-item>


        <v-list-item-content>
          <v-list-item-title class='blue--text'>Booking Mamma : Brodovi Petar</v-list-item-title>
          <v-list-item-title><strong>{{bookersName}}</strong></v-list-item-title>
        </v-list-item-content>
      </v-list-item>

      <v-divider></v-divider>

      <v-list dense>
        <v-list-item
          v-for="item in items"
          :key="item.title"
          link
        >
              <v-list-item-content>
                  <a :href="item.link">
                    <v-list-item-title>{{ item.title }}</v-list-item-title>
                  </a>
              </v-list-item-content>
        </v-list-item>
        <v-list-item>
              <v-list-item-content>
                    <v-list-item-title @click="logout">Logout</v-list-item-title>
              </v-list-item-content>
        </v-list-item>
      </v-list>
    </v-navigation-drawer>
  </div>
</template>


<script>
import axios from 'axios'

  export default {
    props : {
        bookersName : {
            Type : String,
            required : false,
            default : 'Booker'
        },
        logoutRoute : {
            Type : String,
            required : false,
            default : '/logout'
        }
    },

    data () {
      return {

        drawer: null,
        items: [
          { title: 'Početna', link: '/' },
          { title: 'Moje rezervacije', link: '/moje-rezervacije' },
        ],
      }
    },

    methods : {
      logout(){
            axios.post(this.logoutRoute).then((resp) => {
                setTimeout(()=>{
                    location.reload();
                }, 1000)
            })
      }
    }
  }
</script>