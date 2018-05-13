import store from '../../store'
import http from '../../http'

// Panel
import UsuarioPanel from './UsuarioPanel'
import Overview from './Overview'
import InscripcionIngenia from './InscripcionIngenia'
import OtrasOpciones from './OtrasOpciones'

import Perfil from './perfil/Perfil'
import VerPerfil from './perfil/VerPerfil'
import EditarPerfil from './perfil/EditarPerfil'
import EditarEmail from './perfil/EditarEmail'
import EditarAvatar from './perfil/EditarAvatar'
import VerInvitaciones from './perfil/VerInvitaciones'
import EditarMisDatosPersonales from './perfil/EditarMisDatosPersonales'
import SubirDNI from './perfil/SubirDNI'

import Proyecto from './proyecto/Proyecto'
import VerProyecto from './proyecto/VerProyecto'
import EditarProyecto from './proyecto/EditarProyecto'
import SubirImagen from './proyecto/SubirImagen'
import SubirAvalOrganizacion from './proyecto/SubirAvalOrganizacion'
import EnviarProyecto from './proyecto/EnviarProyecto'

import Equipo from './equipo/Equipo'
import VerEquipo from './equipo/VerEquipo'
import VerIntegrantes from './equipo/VerIntegrantes'
import EditarEquipo from './equipo/EditarEquipo'
import EnviarInvitaciones from './equipo/EnviarInvitaciones'
import SubirConformidad from './equipo/SubirConformidad'


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
        path: 'otras-opciones',
        component: OtrasOpciones,
        name: 'userOtrasOpciones',
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
          // {
          //   path: 'cambiar-email',
          //   component: EditarEmail,
          //   name: 'userEditarEmail',
          //   props: true
          // },
          {
            path: 'cambiar-avatar',
            component: EditarAvatar,
            name: 'userEditarAvatar',
            props: true
          },
          {
            path: 'invitaciones',
            component: VerInvitaciones,
            name: 'userVerInvitaciones',
            props: true
          },
          {
            path: 'mis-datos',
            component: EditarMisDatosPersonales,
            name: 'userEditarMisDatosPersonales',
            props: true
          },
          {
            path: 'subir-documento',
            component: SubirDNI,
            name: 'userSubirDNI',
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
            path: 'integrantes',
            component: VerIntegrantes,
            name: 'userVerIntegrantes',
            props: true
          },
          {
            path: 'editar',
            component: EditarEquipo,
            name: 'userEditarEquipo',
            props: true
          },
          {
            path: 'enviar-invitaciones',
            component: EnviarInvitaciones,
            name: 'userEnviarInvitaciones',
            props: true
          },
          {
            path: 'conformidad',
            component: SubirConformidad,
            name: 'userSubirConformidad',
            props: true
          },
        ]
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