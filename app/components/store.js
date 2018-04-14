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
        console.warn('Anonymous. Null user.')        
        state.user = null
        state.expires = null
        return;
      }
      // If there is a user in the session
      else {
        console.log('-- Has session')        
        if(state.user != null){
          if (state.user.id == session.id){
          console.log('---- Same User, same session.')      
            return;
          } else {
          console.warn('---- Not the same user, cleaning. Needs to update!')
            state.user = null
            return;            
          }
        } else {
          console.warn('---- No local data, Needs to update!')
          return;
        }
      }
    },
    bind: function (state, element) {
      Object.assign(state, element);
    },
    updateUser: function(state, session){
      if(session.id != null){
        if(Date.now() > state.expires){
          http.get(window.getUserDataUrl())
            .then(response => {
              console.log('User updated. Saving!');
              Object.assign(state, { user: response.data })
              Object.assign(state, { expires: Date.now() + 5*60*1000 })
            })
            .catch(e => {
              console.error(e)
            })
        } else {
          console.log('Data still fresh');          
        }
      } else {
        console.log('No need to fetch user');
      }
    },
    // forceUpdateUser: function (state) {
    //     http.get(window.getUserDataUrl())
    //       .then(response => {
    //         console.log('User updated. Saving!');
    //         state.user = response.data
    //         state.sync = true            
    //         // vm.$refs.userPanel.forceUserUpdate();
    //       })
    //       .catch(e => {
    //         console.error(e)
    //         state.sync = false            
    //       })
    // }
  },
  actions: {
    prepareData: function ({commit}, session){
      return new Promise((resolve, reject) => {
          commit('checkUser', session)
          resolve(true)
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