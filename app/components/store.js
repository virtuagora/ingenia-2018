import Vue from 'vue'
import Vuex from 'vuex'

Vue.use(Vuex)

const store = new Vuex.Store({
  state: {
    
  },
  mutations: {
    bind: function (state, user) {
      Object.assign(state, user);
    }
  },
  getters: {
    get: (state) => {
      return key => {return state[key]}
      // return state
    }
  }
})

export default store;