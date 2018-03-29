// Base Components
import Vue from 'vue'
import Buefy from 'buefy'
import http from './http'
import router from './router'
import store from './store'
import es from 'vee-validate/dist/locale/es';
import VeeValidate, { Validator } from 'vee-validate';
Validator.localize('es', es);

Vue.use(VeeValidate);

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
import Registro from './ingenia/Registro'
import CompletarRegistro from './ingenia/CompletarRegistro'
import Catalogo from './ingenia/catalogo/Catalogo'

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

new Vue({ // eslint-disable-line no-new
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
    Registro,
    CompletarRegistro,
    Catalogo,
    'fb-register': FBRegister,
  }
})