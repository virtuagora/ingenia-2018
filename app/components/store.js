import Vue from 'vue'
import Vuex from 'vuex'
import http from './http'
import createPersistedState from 'vuex-persistedstate'

Vue.use(Vuex)

const store = new Vuex.Store({
  state: {
    user: null
  },
  mutations: {
    checkUser: function(state,session){
      // If the session is an anonymous one, drop the user state
      console.log('Checking session')
      if(session.id == null){
        console.warn('Anonymous. Null user.')        
        state.user = null
        return false;
      }
      // If there is a user in the session
      else {
        console.log('-- Has session')        
        if(state.user != null){
          if (state.user.id == session.id){
          console.log('---- Same User, same session.')                    
          } else {
          console.warn('---- Not the same user, cleaning. Needs to update!')
          }
        } else {
          console.warn('---- No local data, Needs to update!')
        }
      }
    },
    bind: function (state, user) {
      Object.assign(state, user);
    },
    updateUser: function(state, session){
      if(session.id != null){
      http.get(window.getUserDataUrl())
        .then(response => {
          console.log('User updated. Saviiiiiiiiiiiiing!');
          Object.assign(state, {user: response.data})
        })
        .catch(e => {
          console.error(e)
        })
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
          resolve()
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
    paths: ['user']
  })]
})

export default store;