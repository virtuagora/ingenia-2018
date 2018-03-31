import store from '../../store'
import http from '../../http'



// Panel
import UsuarioPanel from './UsuarioPanel'
import Overview from './Overview'
import InscripcionIngenia from './InscripcionIngenia'

import Perfil from './perfil/Perfil'
import VerPerfil from './perfil/VerPerfil'
import EditarPerfil from './perfil/EditarPerfil'
import EditarEmail from './perfil/EditarEmail'
import EditarPassword from './perfil/EditarPassword'

import Proyecto from './proyecto/Proyecto'
import VerProyecto from './proyecto/VerProyecto'
import EditarProyecto from './proyecto/EditarProyecto'
import SubirImagen from './proyecto/SubirImagen'
import SubirAvalOrganizacion from './proyecto/SubirAvalOrganizacion'
import EnviarProyecto from './proyecto/EnviarProyecto'

import Equipo from './equipo/Equipo'
import VerInvitaciones from './equipo/VerInvitaciones'
import EnviarInvitaciones from './equipo/EnviarInvitaciones'
import VerEquipo from './equipo/VerEquipo'
import VerMisDatosPersonales from './equipo/VerMisDatosPersonales'
import SubirDNI from './equipo/SubirDNI'


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
        path: 'inscripcion',
        component: InscripcionIngenia,
        name: 'userInscripcionIngenia',
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
            name: 'userVerPerfil',
            props: true
          },
          {
            path: 'editar',
            component: EditarPerfil,
            name: 'userEditarPerfil',
            props: true
          },
          {
            path: 'cambiar-email',
            component: EditarEmail,
            name: 'userEditarEmail',
            props: true
          },
          {
            path: 'cambiar-password',
            component: EditarPassword,
            name: 'userEditarPassword',
            props: true
          },
        ]        
      },
      {
        path: 'proyecto',
        component: Proyecto,
        props: true,
        children: [
          {
            path: '',
            component: VerProyecto,
            name: 'userVerProyecto',
            props: true
          },
          {
            path: 'editar',
            component: EditarProyecto,
            name: 'userEditarProyecto',
            props: true
          },
          {
            path: 'imagen',
            component: SubirImagen,
            name: 'userSubirImagen',
            props: true
          },
          {
            path: 'aval',
            component: SubirAvalOrganizacion,
            name: 'userSubirAvalOrganizacion',
            props: true
          },
          {
            path: 'enviar',
            component: EnviarProyecto,
            name: 'userEnviarProyecto',
            props: true
          },
        ]
      },
      {
        path: 'equipo',
        component: Equipo,
        props: true,
        children: [
          {
            path: '',
            component: VerEquipo,
            name: 'userVerEquipo',
            props: true
          },
          {
            path: 'mis-datos',
            component: VerMisDatosPersonales,
            name: 'userVerMisDatosPersonales',
            props: true
          },
          {
            path: 'ver-invitaciones',
            component: VerInvitaciones,
            name: 'userVerInvitaciones',
            props: true
          },
          {
            path: 'enviar-invitaciones',
            component: EnviarInvitaciones,
            name: 'userEnviarInvitaciones',
            props: true
          },
          {
            path: 'subir-documento',
            component: SubirDNI,
            name: 'userSubirDNI',
            props: true
          },
        ]
      }
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