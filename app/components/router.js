
import Vue from 'vue'
import VueRouter from 'vue-router'

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

export default router;