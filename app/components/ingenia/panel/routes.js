import UsuarioPanel from './UsuarioPanel'
import Overview from './Overview'
import store from '../../store'
import http from '../../http'

// Panel
import Perfil from './perfil/Perfil'
import VerPerfil from './perfil/VerPerfil'
import EditarPerfil from './perfil/EditarPerfil'
import EditarEmail from './perfil/EditarEmail'
import EditarPassword from './perfil/EditarPassword'

import Proyecto from './proyecto/Proyecto'
import InscripcionIngenia from './proyecto/InscripcionIngenia'
import VerProyecto from './proyecto/VerProyecto'
import SubirImagen from './proyecto/SubirImagen'
import SubirAvalOrganizacion from './proyecto/SubirAvalOrganizacion'
import PublicarProyecto from './proyecto/PublicarProyecto'

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
            path: 'cambiar-email',
            component: EditarEmail,
            name: 'usuarioEditarEmail',
            props: true
          },
          {
            path: 'cambiar-password',
            component: EditarPassword,
            name: 'usuarioEditarPassword',
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
            name: 'usuarioVerProyecto',
            props: true
          },
          {
          path: 'inscribirse',
          component: InscripcionIngenia,
          name: 'usuarioInscripcionIngenia',
          props: true
          },
          // {
          //   path: 'subir-proyecto',
          //   component: SubirProyecto,
          //   name: 'usuarioSubirProyecto',
          //   props: true
          // },
          {
            path: 'subir-imagen',
            component: SubirImagen,
            name: 'usuarioSubirImagen',
            props: true
          },
          {
            path: 'subir-aval-organizacion',
            component: SubirAvalOrganizacion,
            name: 'usuarioSubirAvalOrganizacion',
            props: true
          },
          {
            path: 'publicar',
            component: PublicarProyecto,
            name: 'usuarioPublicarProyecto',
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
            name: 'usuarioVerEquipo',
            props: true
          },
          {
            path: 'mis-datos',
            component: VerMisDatosPersonales,
            name: 'usuarioVerMisDatosPersonales',
            props: true
          },
          {
            path: 'ver-invitaciones',
            component: VerInvitaciones,
            name: 'usuarioVerInvitaciones',
            props: true
          },
          {
            path: 'enviar-invitaciones',
            component: EnviarInvitaciones,
            name: 'usuarioEnviarInvitaciones',
            props: true
          },
          {
            path: 'subir-documento',
            component: SubirDNI,
            name: 'usuarioSubirDNI',
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