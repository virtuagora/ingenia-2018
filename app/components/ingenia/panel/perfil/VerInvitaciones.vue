<template>
  <div>
    <h1 class="subtitle is-3">Mis invitaciones y solicitudes</h1>
    <h1 class="title is-4">Invitaciones de equipos recibidas</h1>
    <div class="notification is-warning" v-show="user.pending_tasks.length > 0">
      <i class="fas fa-exclamation-circle"></i> Aun debe completar todos sus datos personales para poder aceptar invitaciones.
    </div>
    <div class="notification" v-show="cantInvitaciones == 0">
      No tiene invitaciones pendientes.
    </div>
    <div class="box" v-for="(invitation, index) in user.invitations" v-if="invitation.state == 'pending'" :key="index">
      <article class="media">
        <div class="media-left">
          <span class="icon is-large">
            <i class="fas fa-envelope fa-2x"></i>
          </span>
        </div>
        <div class="media-content">
          <p class="is-italic is-size-7 nl2br">{{invitation.comment}}</p>
          - Invitación recibida del equipo <b><get-grupo :id="invitation.group_id"/></b>
        </div>
        <div class="media-right">
          <button @click="submitAccept(invitation.id)" class="button is-success" v-if="user.pending_tasks.length == 0">Aceptar!</button>
          <span class="icon is-large has-text-warning" v-else>
            <i class="fas fa-exclamation-circle fa-2x"></i>
          </span>
        </div>
      </article>
    </div>
    <hr>
    <h1 class="title is-4">Solicitudes enviadas a equipos</h1>
    <div class="notification" v-show="cantSolicitudes == 0">
      No ha enviado solicitudes.
    </div>
    <div class="box" v-for="(invitation, index) in user.invitations" v-if="invitation.state == 'requested'" :key="index">
      <article class="media">
        <div class="media-left">
          <span class="icon is-large">
            <i class="fas fa-paper-plane fa-2x"></i>
          </span>
        </div>
        <div class="media-content">
          <p class="is-italic is-size-7 nl2br">{{invitation.comment}}</p>
          - Enviado al equipo <b><get-grupo :id="invitation.group_id"/></b>
        </div>
        <div class="media-right">
          <i class="fa fa-clock fa-fw"></i> Pendiente
        </div>
      </article>
    </div>
    <b-loading :active.sync="isLoading"></b-loading>

  </div>
</template>

<script>
import GetGrupo from '../../utils/GetGrupo'

export default {
  props: ["acceptGroupInvitation"],
  components: {
    GetGrupo
  },
  data() {
    return {
      user: {},
      isLoading: false
    };
  },
  created: function() {
    this.user = this.$store.state.user;
  },
  methods: {
    submitAccept: function(id) {
      console.log("Sending form!");
      this.isLoading = true;
      this.$http
        .post(this.acceptGroupInvitation.replace(":inv", id))
        .then(response => {
          this.$snackbar.open({
            message: "¡La invitacion ha sido aceptada!",
            type: "is-success",
            actionText: "OK"
          });
          this.isLoading = false;
          this.response.ok = true;
          this.forceUpdate("userPanel");
          this.$router.push({ name: "panelOverview" });
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
    cantInvitaciones: function() {
      return this.user.invitations.filter(x => {
        return x.state == "accepted" || x.state == "pending";
      }).length;
    },
    cantSolicitudes: function() {
      return this.user.invitations.filter(x => {
        return x.state == "requested";
      }).length;
    }
  }
};
</script>
