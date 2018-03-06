import CreateNode from './CreateNode'
import Vote from './create/Vote'
import store from '../store'
import http from '../http'

const basePath = '/node/create'

const routes = [
  {
    path: basePath,
    component: CreateNode,
    name: 'createNode',
    children: [
      {
        path: 'vote',
        component: Vote,
        name: 'createVote',
      },
    ],
    // beforeEnter: (to, from, next) => {
    //   console.log('First time entering, getting user...')
    //   http.get('https://jsonplaceholder.typicode.com/users/' + to.params.id)
    //     .then(response => {
    //       // JSON responses are automatically parsed.
    //       store.commit('bind', { user: response.data })
    //       next()
    //     })
    //     .catch(e => {
    //       console.log(e)
    //       next()
    //     })
    // }
  }

  // {
  //   path: basePath,
  //   component: Overview,
  //   name: 'accountProfile',
  //   props: true
  // },
  // {
  //   path: basePath + 'edit',
  //   component: EditProfile,
  //   name: 'accountEditProfile',
  //   props: true
  // },
  // {
  //   path: basePath + 'password',
  //   component: Password,
  //   name: 'accountPassword',
  //   props: true
  // },

]

export default routes;