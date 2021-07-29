import axios from 'axios'

export default {
  namespaced: true,

  state : {
    allSuitabilities : [],
  },

  getters : {
    getAllSuitabilities : state => state.allSuitabilities,
    
  },

  mutations : {
    SET_SUITABILITIES(state, suitabilities){
        state.allSuitabilities = suitabilities
    },

  },

  actions: {
    getAllSuitabilities({ commit }){
        axios.get(`/api/suitabilities`)
        .then(({data}) => {
          commit('SET_SUITABILITIES', data.suitabilities)
        })
        .catch((error) => {
          console.log(error);
        })
    }
  }



}