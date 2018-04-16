<template>
  <div>
    <div v-if="registro.enviando == null">
      <i class="em em-heart"></i>
      <div class="notification is-white has-text-centered">
        <h1 class="subtitle is-5">¡Gracias por ser parte de esta instancia!</h1>
        <p>Participá bancando los proyectos que mas te gusten o presentando tu propio proyecto Ingenia<br>¡Tú participación es muy importante!
          <i class="em em-muscle"></i>
        </p>
      </div>
      <div class="field">
        <div class="control">
          <button @click="facebookRegister" class="button is-large is-link is-fullwidth">
            <i class="fas fa-rocket"></i>&nbsp;&nbsp;¡Comenzá a participar!</button>
        </div>
      </div>
    </div>
    <div v-else>
      <div class="notification is-link" v-show="registro.enviado == null">
        <i class="fas fa-cog fa-spin fa-lg fa-fw"></i>
        Iniciando sesión...
      </div>
    </div>
    <form ref="facebookRegisterForm" :action="formUrl">
      <input type="hidden" name="token" :value="token">
    </form>
  </div>
</template>

<script>
import FacebookLogin from "./FBLogin";
export default {
  props: ["message", "formUrl", "token"],
  components: {
    FacebookLogin
  },
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
    facebookRegister: function() {
      console.log("Registrando usuario");
      this.registro.enviando = true;
      this.$refs.facebookRegisterForm.submit();
      // this.registro.enviado = true;
      // this.registro.ok = true;
    }
  },
  mounted: function() {
    document.getElementById("loading").remove();
  }
};
</script>

