import UsuarioPanel from './UsuarioPanel'
import Overview from './Overview'
import store from '../../store'
import http from '../../http'

// Panel
import Perfil from './perfil/Perfil'
import VerPerfil from './perfil/VerPerfil'
import EditarPerfil from './perfil/EditarPerfil'
import CambiarPassword from './perfil/CambiarPassword'


const basePath = '/panel'

const routes = [
  {
    path: basePath,
    component: UsuarioPanel,
    props: true,
    children: [
      {
        path: '',
        component: Overview,
        name: 'panelOverview',
        props: true
      },
      {
        path: 'perfil',
        component: Perfil,
        props: true,
        children: [
          {
            path: '',
            component: VerPerfil,
            name: 'usuarioVerPerfil',
            props: true
          },
          {
            path: 'editar',
            component: EditarPerfil,
            name: 'usuarioEditarPerfil',
            props: true
          },
          {
            path: 'password',
            component: CambiarPassword,
            name: 'usuarioCambiarPassword',
            props: true
          },
        ]        
      },
      // {
      //   path: 'edit',
      //   component: EditProfile,
      //   name: 'accountEditProfile',
      //   props: true
      // },
      // {
      //   path: 'password',
      //   component: Password,
      //   name: 'accountPassword',
      //   props: true
      // },
    ],
    beforeEnter: (to, from, next) => {
      console.log('First time entering, getting user...')
      http.get('https://jsonplaceholder.typicode.com/users/' + (Math.floor(Math.random() * 10) + 1) )
        .then(response => {
          // JSON responses are automatically parsed.
          store.commit('bind', { user: response.data })
          next()
        })
        .catch(e => {
          console.log(e)
          next()
        })
    }
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