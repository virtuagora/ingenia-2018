<template>
  <div>
    <h1 class="subtitle is-3">Integrantes del equipo</h1>
    <h1 class="title is-5">Integrantes del equipo registrados</h1>
    <table class="table is-fullwidth text-middle">
      <thead>
        <tr>
          <th>Nombre y apellido</th>
          <th>Email</th>
          <th class="has-text-centered" width="60px">Acciones</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td>Guillermo Croppi
          </td>
          <td>guillermocroppi@gmail.com</td>
          <td class="has-text-centered">
            <button @click="openRemoveUser(2, 'Guillermo Croppi')" class="button is-outlined is-small is-danger">
              <i class="far fa-trash-alt"></i>
            </button>
          </td>
        </tr>
      </tbody>
    </table>
    <hr>
    <h1 class="title is-5">Invitaciones</h1>
    <table class="table is-fullwidth text-middle">
      <thead>
        <tr>
          <th>Email</th>
          <th class="has-text-centered" width="150px">Estado</th>
          <th class="has-text-centered" width="60px">Acciones</th>
        </tr>
      </thead>
      <tbody>
        <tr v-if="cantInvitaciones == 0">
          <td colspan="2">No se han hecho invitaciones</td>
        </tr>
        <tr v-for="(invitation, index) in group.invitations" v-if="invitation.state == 'pending' || invitation.state == 'accepted'" :key="index">
          <td>{{invitation.email}}<br>
            <p class="is-size-7 nl2br">
              <i>Lorem ipsum dolor sit amet consectetur, adipisicing elit. Tenetur, sint exercitationem? Dolor, nam fugit eos modi, vel maiores quod voluptatibus sunt enim molestias reprehenderit earum et aperiam laborum cupiditate. Odio?</i>
            </p>
          </td>
          <td class="has-text-centered">
            <span v-if="invitation.state == 'accepted'">
              <i class="fas fa-check fa-fw"></i>&nbsp;Aceptada
            </span>
            <span v-if="invitation.state == 'pending'">
              <i class="fas fa-clock fa-fw"></i>&nbsp;Pendiente
            </span>
          </td>
          <td class="has-text-centered">
            <button @click="openRemoveInvitation(invitation.id, invitation.email)" class="button is-outlined is-small is-danger">
              <i class="fas fa-times"></i>
            </button>
          </td>
        </tr>
      </tbody>
    </table>
    <hr>
    <h1 class="title is-5">Solicitudes</h1>
    <table class="table is-fullwidth text-middle">
      <thead>
        <tr>
          <th>Email</th>
          <th class="has-text-centered" width="150px">Estado</th>
          <th class="has-text-centered" width="60px">Acciones</th>

        </tr>
      </thead>
      <tbody>
        <tr v-if="cantSolicitudes == 0">
          <td colspan="2">No se han recibido solicitudes</td>
        </tr>
        <tr v-for="(invitation, index) in group.invitations" v-if="invitation.state == 'requested'" :key="index">
          <td><get-usuario :id="invitation.user_id" />
          <p class="is-size-7 nl2br">
              <i>{{invitation.comment}}</i>
            </p></td>
          <td class="has-text-centered">
              <i class="fas fa-clock fa-fw"></i>&nbsp;Solicitado
          </td>
          <td class="has-text-centered">
            <button @click="openRemoveInvitation(invitation.id, invitation.email)" class="button is-outlined is-small is-success">
              <i class="fas fa-check"></i>
            </button>
          </td>
        </tr>
      </tbody>
    </table>
    <b-modal ref="modalNow" :active.sync="showRemoveModal" :width="640" scroll="keep">
      <div class="notification is-danger">
        <article class="media">
          <figure class="media-left">
            <span class="icon is-medium">
              <i class="fas fa-trash-alt fa-2x"></i>
            </span>
          </figure>
          <div class="media-content">
            <h1 class="title is-4">¿Desea quitar a {{deleteUserName}} del equipo?</h1>
            <div class="field">
              <div class="control">
                <button @click="submitRemoveUser" class="button is-white is-outlined">Si, quitar del equipo</button>
              </div>
            </div>
          </div>
          <div class="media-right">
            <button @click="$refs.modalNow.close()" class="delete"></button>
          </div>
        </article>
      </div>
    </b-modal>
    <b-modal ref="modalNow" :active.sync="showRemoveInvitationModal" :width="640" scroll="keep">
      <div class="notification is-danger">
        <article class="media">
          <figure class="media-left">
            <span class="icon is-medium">
              <i class="fas fa-trash-alt fa-2x"></i>
            </span>
          </figure>
          <div class="media-content">
            <h1 class="title is-4">¿Desea eliminar la invitacion a {{deleteInvitationEmail}}?</h1>
            <div class="field">
              <div class="control">
                <button @click="submitRemoveUser" class="button is-white is-outlined">Si, eliminar</button>
              </div>
            </div>
          </div>
          <div class="media-right">
            <button @click="$refs.modalNow.close()" class="delete"></button>
          </div>
        </article>
      </div>
    </b-modal>
    <b-loading :active.sync="isLoading"></b-loading>
  </div>
</template>

<script>
import GetUsuario from '../../utils/GetUsuario'

export default {
  props: ["acceptGroupRequest", "removeGroupUser", "removeGroupInvitation"],
  components:{
    GetUsuario
  },
  data() {
    return {
      isLoading: true,
      showRemoveModal: false,
      deleteUser: null,
      deleteUserName: null,
      showRemoveInvitationModal: false,
      deleteInvitation: null,
      deleteInvitationEmail: null,
      user: {},
      group: {
        invitations: []
      }
    };
  },
  created: function() {
    this.user = this.$store.state.user;
  },
  mounted: function() {
    this.getEquipo();
  },
  methods: {
    getEquipo: function() {
      this.$http.get("/group/3").then(response => {
        this.isLoading = false;
        console.log(response.data);
        this.group = response.data;
      });
    },
    openRemoveUser: function(id, name) {
      this.deleteUser = id;
      this.deleteUserName = name;
      this.showRemoveModal = true;
    },
    openRemoveInvitation: function(id, email) {
      this.deleteInvitation = id;
      this.deleteInvitationEmail = email;
      this.showRemoveInvitationModal = true;
    },
    submitRemoveUser: function() {
      this.showRemoveModal = false;
      console.log("Sending form!");
      this.isLoading = true;
      this.$http
        .post(this.saveTeamUrl, this.deleteUser)
        .then(response => {
          this.$snackbar.open({
            message: "Los datos del equipo han sido actualizados",
            type: "is-success",
            actionText: "OK"
          });
          this.isLoading = false;
          this.response.ok = true;
          this.$store.commit("updateUser");
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
      return this.group.invitations.filter(x => {
        return x.state == "accepted" || x.state == "pending";
      }).length;
    },
    cantSolicitudes: function() {
      return this.group.invitations.filter(x => {
        return x.state == "requested";
      }).length;
    }
  }
};
</script>
