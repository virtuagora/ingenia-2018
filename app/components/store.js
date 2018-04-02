import Vue from 'vue'
import Vuex from 'vuex'
import http from './http'

Vue.use(Vuex)

const store = new Vuex.Store({
  state: {
    
  },
  mutations: {
    bind: function (state, user) {
      Object.assign(state, user);
    },
    updateUser: function(state){
      http.get(window.getUserDataUrl())
        .then(response => {
          console.log('User updated. Forcing panel to update');
          state.user = response.data
          vm.$refs.userPanel.forceUserUpdate();
        })
        .catch(e => {
          console.error(e)
        })
    }
  },
  getters: {
    get: (state) => {
      return key => {return state[key]}
      // return state
    },
    getUserGroup: (state) => {
      return state.user.groups[0]
    }
  }
})

export default store;