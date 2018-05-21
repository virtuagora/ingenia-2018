// Base Components
import Vue from 'vue'
import Buefy from 'buefy'
import http from './http'
import router from './router'
import store from './store'
import es from 'vee-validate/dist/locale/es';
import VeeValidate, { Validator } from 'vee-validate';
import { VueMasonryPlugin } from 'vue-masonry';

Validator.localize('es', es);

Vue.use(VeeValidate);
Vue.use(VueMasonryPlugin);

// VIRTUAGORA 2.0 COMPONENTS
// Register every component that you will be using in any twig template.
// Note: Some child components might be imported inside their parent components.
// Some frameworks like AXIOS ($http variable) should be globally implemented in here.

// Core Components
// import Login from './base/Login.vue'
// import SignUp from './base/SignUp.vue'
// import CompleteSignUp from './base/CompleteSignUp.vue'

// Node Components
// import NodeComment from './core/NodeComment.vue'
// import NodeComments from './core/NodeComments.vue'
// import NodeUsersParticipated from './core/NodeUsersParticipated.vue'

// Voting Components
// import Vote from './vote/Vote.vue'
// import VoteResults from './vote/VoteResults.vue'

// Ingenia 2018 Componentes
import VueCarousel from 'vue-carousel';
import Login from './ingenia/Login'
import FBRegister from './ingenia/FBRegister'
import CompletarRegistro from './ingenia/CompletarRegistro'
import CompletarResetPassword from './ingenia/CompletarResetPassword'
import Catalogo from './ingenia/catalogo/Catalogo'
import ProjectCarousel from './ingenia/catalogo/ProjectCarousel'
import GetLocalidad from './ingenia/utils/GetLocalidad'
import Avatar from './ingenia/utils/Avatar'
import Localidad from './ingenia/utils/GetLocalidad'
import VoteProject from './ingenia/project/VoteProject'
import NavbarProject from './ingenia/project/NavbarProject'
import PrintProject from './ingenia/project/PrintProject'
import Status from "./ingenia/utils/Status";
import Countdown from "./ingenia/utils/Countdown";

import VerProyectoButtonHeroIndex from "./ingenia/utils/VerProyectoButtonHeroIndex";
import VerProyectoTabIndex from "./ingenia/utils/VerProyectoTabIndex";

// vue-textarea-autosize
// autosizer for textareas
import VueTextareaAutosize from 'vue-textarea-autosize'

Vue.use(VueTextareaAutosize)
Vue.use(VueCarousel)

// We are going to use Buefy
Vue.use(Buefy, {
  defaultIconPack: 'fas',
  defaultDialogConfirmText: 'OK',
  defaultDialogCancelText: 'Cancelar',
  defaultDayNames: ['Dom', 'Lun', 'Mar', 'Mie', 'Jue', 'Vie', 'Sab'],
  defaultMonthNames: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre']
})

// Axios configuration
// go to http.js to configure axios according to your needs
Vue.prototype.$http = http

Vue.mixin({
  methods: {
    isOptional: function (value) {
      if (value === null || value === "") {
        return null;
      }
      if (typeof value !== "undefined" && value.length == 0) {
        return [];
      } else return value;
    },
    forceUpdateState: function (ref) {
      return new Promise((resolve, reject) => {
      http.get(window.getUserDataUrl())
        .then(response => {
          console.log('Updating user');
          store.commit('bind', { user: response.data })
          store.commit('bind', { expires: Date.now() + 5 * 60 * 1000 })
          if (ref != null || ref != undefined) {
            console.log('Updating ref: ' + ref);            
            vm.$refs[ref].updateUserState()
          }
          resolve(response.data)
        })
        .catch(e => {
          console.error(e)
          vm.$snackbar.open({
            message: "Error al conectarse con el servidor. Por favor, recarge la página.",
            type: "is-danger",
            actionText: "Cerrar"
          });
          reject(e)          
        })
      })
    },
    updateState: function () {
      if(Date.now() > store.state.expires){
        http.get(window.getUserDataUrl())
          .then(response => {
            console.log('Updating user');
            store.commit('bind', { user: response.data })
            store.commit('bind', { expires: Date.now() + 5 * 60 * 1000 })
          })
          .catch(e => {
            console.error(e)
            vm.$snackbar.open({
              message: "Error al conectarse con el servidor. Por favor, recarge la página.",
              type: "is-danger",
              actionText: "Cerrar"
            });
          })
      } else {
        console.log('State still fresh');        
      }
    }
  }
})

window.vm = new Vue({ // eslint-disable-line no-new
  el: '#vue', // The id of the DOM element,
  http,
  router,
  store,
  components: {
    // Register core componentes
    // 'login': Login,
    // 'sign-up': SignUp,
    // 'complete-sign-up': CompleteSignUp,
    // Register node components
    // 'node-comment': NodeComment,
    // 'node-comments': NodeComments,
    // 'node-users-participated': NodeUsersParticipated,
    // Register nodes
    // 'vote-results': VoteResults,
    // 'vote': Vote,
    // Ingenia2018
    Login,
    CompletarRegistro,
    CompletarResetPassword,
    Catalogo,
    ProjectCarousel,
    GetLocalidad,
    VoteProject,
    NavbarProject,
    Avatar,
    Localidad,
    Status,
    Countdown,
    'fb-register': FBRegister,
    VerProyectoButtonHeroIndex,
    VerProyectoTabIndex,
    PrintProject
  },
  beforeCreate: function () {
    store.dispatch('prepareData', window.getUserId()).then(response => {
      // response => true if the user state expired.
      if(response){
      this.updateState()
      }
    })
  }
})