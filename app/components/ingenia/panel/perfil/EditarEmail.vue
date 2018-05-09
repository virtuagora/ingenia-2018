<template>
  <section>
    <h1 class="subtitle is-3">Verificar mi identidad</h1>
    <b-message>Uno de los requerimientos para participar de INGENIA es el de verificar tu identidad. Si te registraste con tu email o con tu Facebook</b-message>
    <section v-if="this.user.pending_tasks.includes('email')">
      <div class="field is-grouped">
        <div class="control">
          <a @click.prevent class="button is-medium is-static">
            Email
          </a>
        </div>
        <div class="control is-expanded">
          <input type="email" v-model="email" data-vv-name="email" class="input is-medium" placeholder="Ingrese el email a verificar" v-validate="'email'" :class="{'is-danger': errors.has('email')}">
          <span class="help is-danger" v-show="errors.has('email')">
            <i class="fas fa-times-circle fa-fw"></i>&nbsp;ERROR. Debe ser un email bien formado.</span>
        </div>
      </div>
      <div class="field" v-if="!response.ok">
        <div class="control is-clearfix">
          <a @click="submit" type="submit" class="button is-primary is-medium is-pulled-right" :class="{'is-loading': isLoading}">
            <i class="fa fa-paper-plane fa-fw"></i> Enviar</a>
        </div>
      </div>
      <div class="notification is-success" v-else>
        <i class="fas fa-check fa-fw"></i> ¡Perfecto! Hemos enviado un mail a tu correo {{emailSent}} para que puedas verificar tu cuenta.
      </div>
    </section>
    <div class="notification is-success" v-else>
      <i class="fas fa-check fa-fw"></i> Hemos verificado tu identidad {{user.subject.img_type === 0 ? 'con tu email, ¡Perfecto!' : 'con tu Facebook, ¡Perfecto!'}}
      <span :v-if="user.subject.img_type === 1"><br></span><a :href="'https://facebook.com/'+user.subject.img_hash" :v-if="user.subject.img_type === 1"><i class="fab fa-facebook fa-lg fa-fw"></i> {{user.name}}</a>
      <span :v-if="user.subject.img_type === 0"><i class="fa fa-envelope fa-lg fa-fw"></i> <b>{{user.email}}</b></span>
    </div>
    <b-loading :active.sync="isLoading"></b-loading>
  </section>
</template>

<script>
export default {
  props: ["id", "savePendingEmail"],
  data() {
    return {
      email: null,
      isLoading: false,
      user: {},
      response: {
        ok: false
      }
    };
  },
  methods: {
    submit: function() {
      this.emailSent = null;
      this.response.ok = false;
      this.$validator
        .validateAll()
        .then(result => {
          if (!result) {
            this.$snackbar.open({
              message: "Algunos datos faltan o son incorrectos. Verifíquelos.",
              type: "is-danger",
              actionText: "Cerrar"
            });
            return false;
          }
          this.isLoading = true;
          this.$http
            .post(
              this.savePendingEmail.replace(":usr", this.user.id),
              this.payload
            )
            .then(response => {
              console.log(response);
              this.$snackbar.open({
                message: "Email de verificacion enviado con exito",
                type: "is-success",
                actionText: "OK"
              });
              this.isLoading = false;
              this.response.ok = true;
              this.emailSent = this.email;
              this.email = null;
            })
            .catch(error => {
              console.error(error.message);
              this.isLoading = false;
              this.$snackbar.open({
                message: "Error inesperado",
                type: "is-danger",
                actionText: "Cerrar"
              });
              return false;
            });
        })
        .catch(error => {
          console.error(error.message);

          this.$snackbar.open({
            message: "Error inesperado",
            type: "is-danger",
            actionText: "Cerrar"
          });
          return false;
        });
    }
  },
  created: function() {
    this.user = this.$store.state.user;
  },
  computed: {
    payload: function() {
      return {
        email: this.email
      };
    }
  }
};
</script>
