<template>
  <div>
    <h1 class="subtitle is-3">Enviar invitaciones a participar</h1>
    <p>¡Sumá gente a tu proyecto!
      <i class="em em-muscle"></i> Enviá invitaciones a tus miembros para que sean parte de tu equipo INGENIA. Recibiran un correo para que se registren en el sistema y completen sus datos para figurar como parte del proyecto.</p>
    <br>
    <b-message>
      Ingresá el correo electrónico del integrante a invitar. Un email le llegará a su correo electrónico. Una vez que se registre, el participante quedará vinculado a tu proyecto.
    </b-message>
    <h1 class="subtitle is-5 has-text-link">Quedan {{ 25 - invitations.length }} de invitaciones para enviar (por email) o solicitudes de participar.</h1>
    <div class="field is-grouped">
      <div class="control">
        <a @click.prevent class="button is-large is-static">
          <span class="icon">
            <i class="fas fa-envelope"></i>
          </span>
        </a>
      </div>
      <div class="control is-expanded">
        <input type="email" v-model.lazy="email" name="email" v-validate="'required|email'" class="input is-large" :class="{'is-danger': errors.has('email')}">
        <span class="help is-danger" v-show="errors.has('email')">
          <i class="fas fa-times-circle fa-fw"></i>&nbsp;ERROR. Debe ser un email bien formado</span>
      </div>
    </div>
    <div class="field">
      <label class="label">
        <i class="fas fa-angle-double-right"></i> Escribí un mensaje, invitando a que participe! (Max.: 200 caracteres)</label>
      <div class="control">
        <b-input maxlength="200" v-model="comment" name="comment" type="textarea" rows="2" minlength="10" v-validate="'required|min:10|max:200'"></b-input>
        <span class="help is-danger" v-show="errors.has('comment')">
          <i class="fas fa-times-circle fa-fw"></i>&nbsp;¡ATENCIÓN! ¡Al menos un mensaje es requerido!</span>
      </div>
    </div>
    <div class="field">
      <div class="control is-clearfix">
        <a @click="submit" type="submit" :disabled="invitations.length == 25" class="button is-primary is-medium is-pulled-right" :class="{'is-loading': isLoading}">
          <i class="fa fa-paper-plane fa-fw"></i> Enviar</a>
      </div>
    </div>
    <div class="notification is-success" v-show="response.ok">
      <i class="fas fa-check fa-fw"></i> Invitación enviada con éxito a {{emailSent}}.
    </div>
    <b-loading :active.sync="isLoading"></b-loading>
  </div>
</template>

<script>
export default {
  props: ["sendInvitationUrl",'teamUrl'],
  data() {
    return {
      isLoading: false,
      email: null,
      emailSent: null,
      comment: "¡Hola! Soy "+ this.$store.state.user.names + " y me gustaría que seas parte del equipo INGENIA: " + this.$store.state.user.groups[0].name,
      response: {
        ok: false
      },
      user: {},
      invitations: []
    };
  },
  created: function() {
    this.user = this.$store.state.user;
  },
  mounted: function(){
    this.checkInvitations();
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
              this.sendInvitationUrl.replace(
                ":gro",
                this.$store.getters.getUserGroup.id
              ),
              this.payload
            )
            .then(response => {
              console.log(response);
              this.$snackbar.open({
                message: "Invitación enviada con exito",
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
    },
    checkInvitations: function() {
      this.$http
        .get(this.fetchTeamUrl)
        .then(response => {
          this.invitations = response.data.invitations;
          
        })
        .catch(error => {
          console.error(error.message);
        });
    }
  },
  computed: {
    fetchTeamUrl: function() {
      return this.teamUrl.replace(":gro", this.user.groups[0].id);
    },
    payload: function() {
      return {
        email: this.email,
        comment: this.isOptional(this.comment)
      };
    }
  },
  beforeRouteEnter(to, from, next) {
    next(vm => {
      if (
        vm.user.groups[0] !== undefined &&
        vm.user.groups[0].pivot.relation === "responsable"
      ) {
        console.log("Authorized");
      } else {
        console.log("Unauthorized - Kicking to dashboard!");
        next({ name: "panelOverview" });
      }
    });
  }
};
</script>
