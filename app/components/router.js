
import Vue from 'vue'
import VueRouter from 'vue-router'

// import routes from settings
// import routeSettings from './base/settings/routes'
// import routeAccount from './base/account/routes'
// import routeCreateNode from './node/routes'

import routeUserPanel from './ingenia/panel/routes'

Vue.use(VueRouter)

let routes = [].concat(
  // routeSettings,
  // routeAccount,
  // routeCreateNode
  routeUserPanel
)


const router = new VueRouter({
  mode: 'history',
  routes: routes
})

export default router;