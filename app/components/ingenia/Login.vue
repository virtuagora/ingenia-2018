<template>
  <div>
    <div v-if="!loginFacebook && !register">
      <form ref="formLocalLogin" :action="loginUrl" method="POST">

      <div class="field-is-marginless">
        <div class="control has-icons-left" >
          <input type="email" name="email" v-model.lazy="loginEmail" v-validate="'required|email'" class="input is-medium has-text-centered" style="border-left:0; border-radius:0" :class="{'is-danger': errors.has('email')}" placeholder="Dirección de email">
          <span class="icon is-left">
            <i class="fas fa-envelope fa-lg fa-fw"></i>
          </span>
        </div>
      </div>
      <div class="field">
        <div class="control has-icons-left">
          <input type="password" name="password" v-model.lazy="loginPassword" v-validate="'required'" class="input is-medium has-text-centered" style="border-left:0; border-radius:0" :class="{'is-danger': errors.has('password')}" placeholder="Contraseña">
          <span class="icon is-left">
            <i class="fas fa-lock fa-lg fa-fw"></i>
          </span>
        </div>
      </div>
        <span class="help is-danger" v-show="errors.has('email')"><i class="fas fa-times-circle fa-fw"></i> Error. debe ser un email bien formado</span>          
        <span class="help is-danger" v-show="errors.has('password')"><i class="fas fa-times-circle fa-fw"></i> Error. La contraseña no puede ser vacia</span>          
      <br>
      <div class="field">
        <div class="control">
          <button @click="submitLogin" class="button is-medium is-primary is-fullwidth" :class="{'is-loading': isLoading}">
            <i class="fas fa-sign-in-alt fa-lg"></i>&nbsp;&nbsp;Iniciar sesión</button>
        </div>
      </div>
      </form>
      <div class="field">
        <div class="control">
          <button @click="register = true" class="button is-primary is-inverted is-medium is-fullwidth">
            <i class="fas fa-user-plus fa-fw fa-lg"></i>
            &nbsp;&nbsp;¡Registrate!
          </button>
        </div>
      </div>
      <br>
      <br>
      <div class="strike">
        <span>Otras opciones de login</span>
      </div>
      <br>
      <button @click="loginFacebook = true" class="button is-link is-medium is-fullwidth">
        <i class="fab fa-facebook-square fa-fw fa-lg"></i>
        &nbsp;&nbsp;Facebook
      </button>
    </div>
    <registro-email :sign-up-url="signUpUrl" :google-key="googleKey" @abort="register = false" v-if="register"></registro-email>
    <facebook-login :fb-login-action="fbLoginAction" :facebook-key="facebookKey" v-if="loginFacebook"></facebook-login>
    <b-loading :active.sync="isLoading"></b-loading>    
  </div>
</template>

<script>
import FacebookLogin from "./FBLogin";
import RegistroEmail from "./RegistroEmail";
export default {
  props: ["message", "loginUrl", 'googleKey', 'facebookKey', "fbLoginAction", "signUpUrl", "homeUrl"],
  components: {
    FacebookLogin,
    RegistroEmail
  },
  data() {
    return {
      loginFacebook: false,
      register: false,
      loginEmail: "",
      loginPassword: "",
      isLoading: false
    };
  },
  methods: {
    submitLogin: function() {
       this.$validator
        .validateAll()
        .then(result => {
          if (!result) {
            this.$snackbar.open({
              message: "Error. Email mal formado o contraseña vacia",
              type: "is-danger",
              actionText: "Cerrar"
            });
            return false;
          }
          this.isLoading = true;
          this.$refs.formLocalLogin.submit();
        })
        .catch(error => {
          this.$snackbar.open({
            message: "Error inesperado",
            type: "is-danger",
            actionText: "Cerrar"
          });
          return false;
        });
    },
  },
  mounted: function() {
    document.getElementById("loading").remove();
  }
};
</script>

