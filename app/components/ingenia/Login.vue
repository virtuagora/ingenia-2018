<template>
  <div>
    <div v-if="!loginEmail && !loginFacebook && !register">
      <button @click="loginEmail = true" class="button is-dark is-400 is-medium is-fullwidth">
        <i class="fas fa-envelope fa-fw fa-lg"></i>
        &nbsp;&nbsp;Iniciar sesión con mi email
      </button>
      <br>
      <button @click="loginFacebook = true" class="button is-link is-medium is-fullwidth">
        <i class="fab fa-facebook-square fa-fw fa-lg"></i>
        &nbsp;&nbsp;Iniciar sesión con Facebook
      </button>
      <br>
      <div class="strike">
        <span>¿No tenes cuenta todavía?</span>
      </div>
      <br>
      <button @click="register = true" class="button is-primary is-outlined is-medium is-fullwidth">
        <i class="fas fa-user-plus fa-fw fa-lg"></i>
        &nbsp;&nbsp;¡Registrate!
      </button>
    </div>
    <div v-if="register">
      <div v-if="registro.enviando == null">
        <div class="notification is-light has-text-centered">
          Ingresa tu dirección de email, te enviaremos un correo con un link para que completes tu registro
        </div>
        <div class="field">
          <div class="control">
            <input type="email" name="email" v-model="registro.email" class="input is-large has-text-centered" placeholder="Email">
          </div>
        </div>
        <div class="field">
          <div class="control">
            <button @click="registrarUsuario" class="button is-large is-primary is-fullwidth">
              <i class="fas fa-paper-plane"></i>&nbsp;&nbsp;Crear cuenta</button>
          </div>
        </div>
        <div class="field">
          <div class="control">
            <button @click="register = false" class="button is-white is-fullwidth">
              <i class="fas fa-undo"></i>&nbsp;&nbsp;Volver atrás
            </button>
          </div>
        </div>
      </div>
      <div v-else>
        <div class="notification is-link" v-show="registro.enviado == null">
          <i class="fas fa-cog fa-spin fa-lg fa-fw"></i>
          Enviando email...
        </div>
        <div class="notification is-success" v-show="registro.enviado != null && registro.ok">
          <i class="fas fa-check fa-lg fa-fw"></i>
          El email ha sido enviado a tu casilla de correo "{{registro.email}}". Revise su casilla y entre al link que hemos enviado.
        </div>
        <div class="notification is-danger" v-show="registro.enviado != null && !registro.ok">
          <i class="fas fa-times fa-lg fa-fw"></i>
          Error enviando el email a "{{registro.email}}". Por favor intente más tarde. Si el problema persiste, contactesé con Gabinete Joven
        </div>
      </div>
    </div>
    <div v-if="loginEmail">
      <div class="field">
        <div class="control">
          <input type="email" name="email" v-model="userEmail" class="input is-large has-text-centered" placeholder="Email">
        </div>
      </div>
      <div class="field">
        <div class="control">
          <input type="password" name="password" v-model="userPassword" class="input is-large has-text-centered" placeholder="Contraseña">
        </div>
      </div>
      <br>
      <div class="field">
        <div class="control">
          <button class="button is-large is-primary is-fullwidth">
            <i class="fas fa-sign-in-alt"></i>&nbsp;&nbsp;Iniciar sesión</button>
        </div>
      </div>
      <div class="field">
        <div class="control">
          <button @click="loginEmail = false" class="button is-white is-fullwidth">
            <i class="fas fa-undo"></i>&nbsp;&nbsp;Volver atrás
          </button>
        </div>
      </div>
      <hr>
    </div>
    <div v-if="loginFacebook">
      <div class="notification is-link">
        <i class="fas fa-cog fa-spin fa-lg fa-fw"></i>
        Iniciando sesion con Facebook...
      </div>
    </div>
    <br>
  </div>
</template>

<script>
export default {
  props: ["message", "formUrl", "signUpUrl", "homeUrl"],
  data() {
    return {
      loginEmail: false,
      loginFacebook: false,
      register: false,
      userEmail: "",
      userPassword: "",
      registro: {
        email: "",
        enviando: null,
        enviado: null,
        ok: null
      }
    };
  },
  methods: {
    submit: function() {
      console.log(":)");
    },
    registrarUsuario: function() {
      console.log("Registrar Usuario");
      this.registro.enviando = true;
      // this.registro.enviado = true;
      // this.registro.ok = true;
    }
  },
  mounted: function() { 
    document.getElementById("loading").remove();
  }
};
</script>

