
import Vue from 'vue'
import VueRouter from 'vue-router'
import store from './store'
import http from './http'

// import routes from settings
// import routeSettings from './base/settings/routes'
// import routeAccount from './base/account/routes'
// import routeCreateNode from './node/routes'

import routeUserPanel from './ingenia/panel/routes'
import routeAdminPanel from './ingenia/admin/routes'
import routeProject from './ingenia/project/routes'

Vue.use(VueRouter)

let routes = [].concat(
  // routeSettings,
  // routeAccount,
  // routeCreateNode
  routeUserPanel,
  routeProject
)


const router = new VueRouter({
  mode: 'history',
  routes: routes,
  scrollBehavior(to, from, savedPosition) {
    return { x: 0, y: 0 }
  }
})

// router.beforeEach((to, from, next) => {
//   store.dispatch('prepareData', window.getUserId()).then(x => {
//     http.get(window.getUserDataUrl())
//       .then(response => {
//         console.log('Updating user');
//         store.commit('bind', { user: response.data })
//         next()
//       })
//       .catch(e => {
//         console.log(e)
//         next()
//       })
//   })

  // console.log('First time entering, getting user...')
  // store.dispatch('prepareData', window.getUserId() )
  // http.get(window.getUserDataUrl())
  //       .then(response => {
  //         // JSON responses are automatically parsed.
  //         store.commit('bind', { user: response.data })
  //         next()
  //       })
  //       .catch(e => {
  //         console.log(e)
  //         next()
  //       })
// })

export default router;