<template>
  <section class="section">
    <div class="container">
      <div class="columns">
        <div class="column" :class="{'is-offset-3 is-6': user === null || user.groups[0] !== undefined}">
          <h3 class="is-size-3">
            <b>Descripción del proyecto</b>
          </h3>
          <p class="nl2br">{{project.abstract}}</p>
          <hr>
          <h3 class="is-size-3">
            <b>Fundamentación del proyecto</b>
          </h3>
          <p class="nl2br">{{project.foundation}}</p>
        </div>
        <div class="column" v-if="user && user.groups[0] === undefined">
          <div class="notification is-dark has-text-centered">
            <h3 class="is-size-4 is-500">
              <span v-if="user !== null">¡{{user.names}}!</span> ¿Queres colaborar con el equipo?</h3>
            <p>¡Enviales una solicitud para ser parte!</p>
            <br>
            <div v-if="user !== null && user.pending_tasks.length > 0">
              <div class="notification is-warning">
                <p>
                  <i class="fa fa-exclamation-circle fa-fw"></i>
                  <b>IMPORTANTE:</b> Antes debes completar todos tus datos personales para ser parte de un equipo INGENIA. Seguí los pasos indicados en tu panel de usuario.</p>
                <br>
                <a href="/panel" class="button is-dark is-outlined is-medium">Ir al panel</a>
              </div>
            </div>
            <button v-if="user !== null && user.pending_tasks.length == 0" @click="wannaColaborate = true" v-show="!wannaColaborate" class="button is-warning is-outlined is-medium">¡Si! ¡Quiero colaborar!</button>
            <div v-if="wannaColaborate">
              <div class="notification is-white has-text-left" v-if="!response.ok">
                <div class="field">
                  <label class="label">
                    <i class="fas fa-angle-double-right"></i> Escribí un mensaje al responsable del equipo</label>
                  <div class="control">
                    <b-input maxlength="200" v-model="message" type="textarea" rows="2"></b-input>
                  </div>
                </div>
                <div class="field">
                  <div class="control">
                    <button @click="submitInvitacion" class="button is-primary is-fullwidth" :class="{'is-loading': isLoading}">
                      <i class="fa fa-paper-plane fa-fw"></i>&nbsp;Enviar</button>
                  </div>
                </div>
              </div>
              <div class="notification is-success" v-show="response.ok">
                <i class="fas fa-check fa-fw"></i> ¡Tu solicitud ha sido enviada, gracias!
              </div>
            </div>
            <a v-if="user === null" href="/login" class="button is-warning is-outlined is-medium">
              <i class="fas fa-sign-in-alt fa-fw"></i>&nbsp;Inicia sesión para solicitar</a>
            <b-loading :active.sync="isLoading"></b-loading>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>

<script>
export default {
  props: ["project", 'sendRequestJoin'],
  data() {
    return {
      user: {},
      wannaColaborate: false,
      message: null,
      isLoading: false,
      response: {
        ok: false
      }
    };
  },
  created: function() {
    this.user = this.$store.state.user;
  },
  methods: {
    isOptional: function(value) {
      if (value === null || value === "") {
        return null;
      }
      if (typeof value !== "undefined" && value.length == 0) {
        return [];
      } else return value;
    },
    submitInvitacion: function() {
      this.isLoading = true;
      this.$http
        .post(this.sendRequestJoin.replace(":gro", this.project.group.id), this.payload)
        .then(response => {
          this.$snackbar.open({
            message: "¡La solicitud ha sido enviada!",
            type: "is-success",
            actionText: "OK"
          });
          this.isLoading = false;
          this.response.ok = true;
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
    }
  },
  computed: {
    payload: function(){
      return {
        comment: this.isOptional(this.message)
      }
    }
  }
};
</script>
