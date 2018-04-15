import Vue from 'vue'
import Vuex from 'vuex'
import http from './http'
import createPersistedState from 'vuex-persistedstate'

import moment from "moment";

Vue.use(Vuex)

const store = new Vuex.Store({
  state: {
    user: null,
    expires: null,
  },
  mutations: {
    checkUser: function(state,session){
      // If the session is an anonymous one, drop the user state
      console.log('Checking session')
      if(session.id == null){
        console.info('Anonymous. Null user.')        
        state.user = null
        state.expires = null
        return;
      }
      // If there is a user in the session
      else {
        console.log('-- Has session')        
        if(state.user != null){
          if (state.user.id == session.id){
          console.info('---- Same User, same session.')      
            return;
          } else {
          console.info('---- Not the same user, cleaning. Needs to update!')
            state.user = null
            return;            
          }
        } else {
          console.info('---- No local data, Needs to update!')
          return;
        }
      }
    },
    bind: function (state, element) {
      Object.assign(state, element);
    },
  },
  actions: {
    prepareData: function ({commit}, session){
      return new Promise((resolve, reject) => {
          commit('checkUser', session)
          if(session.id != null){
            // There is a session, get user.
            resolve(true)
          } else {
            // NO session, NO user
            resolve(false)
          }
      })
    }
  },
  getters: {
    get: (state) => {
      return key => {return state[key]}
      // return state
    },
    getUserGroup: (state) => {
      if(state.user != null && state.user.groups.length > 0){
      return state.user.groups[0]
      }
      return null;
    }
  },
  plugins: [createPersistedState({
    key: 'virtuagora-v2',
    paths: ['user', 'expires']
  })]
})

export default store;