import store from '../../store'
import http from '../../http'

// Panel
import AdminPanel from './AdminPanel'
import Overview from './Overview'
import FechaCierre from './FechaCierre'
import ListadoProyectos from './ListadoProyectos'
import DescargarMatriz from './DescargarMatriz'
import ListaNegraDNI from './ListaNegraDNI'


const basePath = '/administracion'

const routes = [
  {
    path: basePath,
    component: AdminPanel,
    props: true,
    children: [
      {
        path: '',
        component: Overview,
        name: 'administracionOverview',
        props: true
      },
      {
        path: 'fecha-cierre',
        component: FechaCierre,
        name: 'adminFechaCierre',
        props: true
      },
      {
        path: 'proyectos',
        component: ListadoProyectos,
        name: 'adminListadoProyectos',
        props: true
      },
      {
        path: 'blacklist',
        component: ListaNegraDNI,
        name: 'adminListaNegraDNI',
        props: true
      },
      {
        path: 'descargar-matriz',
        component: DescargarMatriz,
        name: 'adminDescargarMatriz',
        props: true
      }
    ],
    // beforeEnter: (to, from, next) => {
    //   console.log('First time entering, getting user...')
    //   http.get(window.getUserDataUrl())
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
]

export default routes;