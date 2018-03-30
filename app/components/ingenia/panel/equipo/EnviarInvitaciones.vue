<template>
  <div>
    <h1 class="subtitle is-3">Enviar invitaciones a participar</h1>
    <p>¡Sumá gente a tu proyecto!
      <i class="em em-muscle"></i> Enviá invitaciones a tus miembros para que sean parte de tu equipo INGENIA. Recibiran un correo para que se registren en el sistema y completen sus datos para figurar como parte del proyecto.</p>
    <b-message>
      Ingresá el correo electrónico del integrante a invitar. Un email le llegará a su correo electrónico. Una vez que se registre, el participante quedará vinculado a tu proyecto.
    </b-message>
    <div class="notification" :class="{'is-success':request.success, 'is-danger': !request.success}" v-show="request.sent">
      <i class="fas fa-fw" :class="{'fa-check':request.success, 'fa-times': !request.success}"></i> ¡Invitación enviada con éxito! 
    </div>
    <div class="field is-grouped">
      <div class="control">
            <a @click.prevent class="button is-large is-static">
              <span class="icon">
                <i class="fas fa-envelope"></i>
              </span>
            </a>
          </div>
      <div class="control is-expanded">
        <input type="email" v-model.lazy="email" name="email" v-validate="'email'" class="input is-large" :class="{'is-danger': errors.has('email')}">
        <span class="help is-danger" v-show="errors.has('email')">ERROR. Debe ser un email bien formado</span>
      </div>
    </div>
    <div class="field">
      <div class="control is-clearfix">
        <a @click="submit" type="submit" class="button is-primary is-medium is-pulled-right" :class="{'is-loading': isLoading}">
          <i class="fa fa-paper-plane fa-fw"></i> Enviar</a>
      </div>
    </div>
    <b-loading :is-full-page="false" :active.sync="isLoading"></b-loading>
  </div>
</template>

<script>
export default {
  data() {
    return {
      isLoading: false,
      email: null,
      request:{
        sent: false,
        success: false,
        message: null
      }
    }
  },
  methods: {
    submit: function() {
      this.$validator
        .validateAll()
        .then(result => {
          if (!result) {
            this.$snackbar.open({
              message: "Error en el formulario. Verifíquelo",
              type: "is-danger",
              actionText: "Cerrar"
            });
            return false;
          }
          this.isLoading = true;
          
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
  }
};
</script>
