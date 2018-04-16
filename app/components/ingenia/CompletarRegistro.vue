<template>
  <div>
    <div v-if="!response.replied">
      <div class="notification is-white has-text-centered">
        <h1 class="subtitle is-5">¡Gracias por ser parte de esta instancia participativa!</h1>
        <p>Participá bancando los proyectos que mas te gusten o presentando tu propio proyecto Ingenia.<br>¡Tú participación es muy importante!
          <i class="em em-muscle"></i>
        </p>
      </div>
      <div class="field">
        <label class="label">Nombres *</label>
        <div class="control">
          <input type="text" v-model="user.names" name="names" v-validate="'required|alpha_spaces'" class="input has-text-centered is-medium" style="padding-left:0" placeholder="Ingresá nombres aquí">
          <span class="help is-danger" v-show="errors.has('names')">
            <i class="fas fa-times-circle fa-fw"></i> Error. Campo requerido</span>
        </div>
      </div>
      <div class="field">
        <label class="label">Apellidos *</label>
        <div class="control">
          <input type="text" v-model="user.surnames" name="surnames" v-validate="'required|alpha_spaces'" class="input has-text-centered is-medium" style="padding-left:0" placeholder="Ingresá apellidos aquí">
          <span class="help is-danger" v-show="errors.has('surnames')">
            <i class="fas fa-times-circle fa-fw"></i> Error. La contraseña no puede ser vacia</span>
        </div>
      </div>
      <div class="field">
        <label class="label">Contraseña *</label>
        <div class="control">
          <input type="password" v-model="user.password" name="password" v-validate="'required'" class="input has-text-centered is-medium" style="padding-left:0" placeholder="Ingresá tu contraseña">
          <span class="help is-danger" v-show="errors.has('password')">
            <i class="fas fa-times-circle fa-fw"></i> Error. La contraseña no puede ser vacia</span>

        </div>
      </div>
      <div class="field">
        <div class="control">
          <button @click="submitSignUp" class="button is-large is-primary is-fullwidth" :class="{'is-loading': isLoading}">
            <i class="fas fa-user-plus"></i>&nbsp;&nbsp;Crear cuenta</button>
        </div>
      </div>
    </div>
    <div v-else>
      <div class="notification is-success" v-show="response.replied && response.ok">
        <i class="fas fa-check fa-lg fa-fw"></i>
        ¡El usuario ha sido creado correctamente! Continue iniciando sesión
        <i class="em em-smiley"></i>
        <br>
        <br>
        <a :href="logInUrl" class="button is-white is-outline"><i class="fas fa-sign-in-alt fa-lg"></i>&nbsp;&nbsp;Iniciar sesión</a>
      </div>
      <div class="notification is-danger" v-show="response.replied && !response.ok">
        <i class="fas fa-times fa-lg fa-fw"></i>
        Error al crear el usuario. Por favor intente más tarde. Si el problema persiste, contactesé con Gabinete Joven
      </div>
    </div>
    <b-loading :is-full-page="false" :active.sync="isLoading"></b-loading>
  </div>
</template>

<script>
export default {
  props: ["formUrl", "logInUrl", "token"],
  data() {
    return {
      user: {
        names: null,
        surnames: null,
        password: null,
        token: null
      },
      isLoading: false,
      response: {
        replied: null,
        ok: null,
        message: null
      }
    };
  },
  beforeMount: function() {
    this.user.token = this.token;
  },
  methods: {
    submitSignUp: function() {
      this.$validator
        .validateAll()
        .then(result => {
          if (!result) {
            this.$snackbar.open({
              message: "Error. Verifique los campos.",
              type: "is-danger",
              actionText: "Cerrar"
            });
            return false;
          }
          this.isLoading = true;
          this.$http
            .post(this.formUrl, this.user)
            .then(response => {
              this.isLoading = false;
              this.response.replied = true;
              this.response.ok = true;
            })
            .catch(error => {
              console.error(error.message);
              this.isLoading = false;
              this.response.replied = true;
              this.response.ok = false;
              this.$snackbar.open({
                message: "Error inesperado",
                type: "is-danger",
                actionText: "Cerrar"
              });
              return false;
            });
        })
        .catch(error => {
          this.$snackbar.open({
            message: "Error inesperado",
            type: "is-danger",
            actionText: "Cerrar"
          });
          return false;
        });
    }
  },
  mounted: function() {
    document.getElementById("loading").remove();
  }
};
</script>
