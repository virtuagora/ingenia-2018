<template>
  <div>
     <div class="tabs">
  <ul>
    <li :class="{'is-active': $route.name == 'userVerEquipo'}"><router-link :to="{ name: 'userVerEquipo'}">Ver equipo</router-link></li>
    <li :class="{'is-active': $route.name == 'userEditarEquipo'}"  v-if="user.groups[0].pivot.relation == 'responsable'"><router-link :to="{ name: 'userEditarEquipo'}">Editar datos</router-link></li>
    <li :class="{'is-active': $route.name == 'userVerIntegrantes'}"  v-if="user.groups[0].pivot.relation == 'responsable'"><router-link :to="{ name: 'userVerIntegrantes'}">Ver los integrantes</router-link></li>
  </ul>
</div>
    <h1 class="subtitle is-3">Integrantes del equipo</h1>
    <table class="table is-fullwidth text-middle">
      <thead>
        <tr>
          <th>Nombre y apellido</th>
          <th class="has-text-centered" width="60px">Acciones</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="(member,index) in members" :key="index">
          <td>
            <Avatar :user="member" class="inline-image" size="24" /> {{member.subject.display_name}}
            <span class="tag is-warning is-pulled-right" v-if="member.pivot.relation === 'responsable'"><i class="fas fa-star"></i>&nbsp;Responsable</span>
            <div class="tags has-addons is-pulled-right" v-if="member.pivot.relation === 'co-responsable'">
              <span class="tag is-link"><i class="fas fa-shield-alt"></i>&nbsp;Co-responsable</span>
              <b-tooltip label="Quitar co-responsable" type="is-dark">
              <a @click="removeSecondInCharge(member.id)" class="tag is-delete"></a>
              </b-tooltip>
            </div>
          </td>
          <td>
            <div class="field is-grouped">
              <div class="control is-expanded has-text-centered" v-if="!group.second_in_charge && member.pivot.relation !== 'responsable'">
                <b-tooltip label="Asignar co-responsable" type="is-dark">
                  <button @click="assignSecondInCharge(member.id)" class="button is-fullwidth is-outlined is-small is-link">
                    <i class="fas fa-shield-alt"></i>
                  </button>
                </b-tooltip>
              </div>
              <div class="control is-expanded has-text-centered" v-if="member.pivot.relation !== 'responsable'">
                <b-tooltip label="Quitar del equipo" type="is-dark">
                  <button @click="openRemoveUser(member.id, member.subject.display_name)" class="button is-fullwidth is-outlined is-small is-danger">
                    <i class="far fa-trash-alt"></i>
                  </button>
                </b-tooltip>
              </div>
            </div>
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
        </tr>
      </thead>
      <tbody>
        <tr v-if="cantInvitaciones == 0">
          <td colspan="2">No hay invitaciones para listar</td>
        </tr>
        <tr v-for="(invitation, index) in group.invitations" v-if="invitation.state == 'pending'" :key="index">
          <td>{{invitation.email}}<br>
            <p class="is-size-7 nl2br">
              <i>{{invitation.comment}}</i>
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
      <tbody v-if="cantSolicitudes > 0">
        <tr v-for="(invitation, index) in group.invitations" v-if="invitation.state == 'requested'" :key="index">
          <td>
            <get-usuario :id="invitation.user_id" />
            <p class="is-size-7 nl2br">
              <i>{{invitation.comment}}</i>
            </p>
          </td>
          <td class="has-text-centered">
            <i class="fas fa-clock fa-fw"></i>&nbsp;Solicitado
          </td>
          <td class="has-text-centered">
            <button @click="openAcceptRequest(invitation.id, invitation.comment)" class="button is-outlined is-small is-success">
              <i class="fas fa-check"></i>
            </button>
          </td>
        </tr>
      </tbody>
      <tbody v-else>
        <tr>
          <td colspan="2">No hay solicitudes para listar</td>
        </tr>
      </tbody>
    </table>
    <b-modal ref="modalRemoveUser" :active.sync="showRemoveModal" :width="640" scroll="keep">
      <div class="notification is-danger">
        <article class="media">
          <figure class="media-left">
            <span class="icon is-medium">
              <i class="fas fa-trash-alt fa-2x"></i>
            </span>
          </figure>
          <div class="media-content">
            <h1 class="title is-4">¿Desea eliminar a {{deleteUserName}} del equipo?</h1>
            <p>Si desea volver a agregarlo al equipo, debera volver a enviar la invitación</p>
            <br>
            <div class="field">
              <div class="control">
                <button @click="submitRemoveUser" class="button is-white">Si, quitar del equipo</button>
              </div>
            </div>
          </div>
          <div class="media-right">
            <button @click="$refs.modalRemoveUser.close()" class="delete"></button>
          </div>
        </article>
      </div>
    </b-modal>
    <b-modal ref="modalAcceptRequest" :active.sync="showAcceptRequestModal" :width="640" scroll="keep">
      <div class="box">
        <article class="media">
          <figure class="media-left">
            <span class="icon is-medium">
              <i class="fas fa-trash-alt fa-2x"></i>
            </span>
          </figure>
          <div class="media-content">
            <h1 class="title is-4">¿Desea aceptar la solicitud?</h1>
            <p>Mensaje recibido:</p>
             <p class="is-size-7 nl2br">
              <i>{{acceptRequestComment}}</i>
            </p>
            <br>
            <div class="field">
              <div class="control">
                <button @click="submitAcceptRequest" class="button is-success">Si, agregar al equipo</button>
              </div>
            </div>
          </div>
          <div class="media-right">
            <button @click="$refs.modalAcceptRequest.close()" class="delete"></button>
          </div>
        </article>
      </div>
    </b-modal>
    <!-- <b-modal ref="modalRemoveInvitation" :active.sync="showRemoveInvitationModal" :width="640" scroll="keep">
      <div class="notification is-danger">
        <article class="media">
          <figure class="media-left">
            <span class="icon is-medium">
              <i class="fas fa-trash-alt fa-2x"></i>
            </span>
          </figure>
          <div class="media-content">
            <h1 class="title is-4">¿Desea eliminar la invitación a {{deleteInvitationEmail}}?</h1>
            <div class="field">
              <div class="control">
                <button @click="submitRemoveUser" class="button is-white is-outlined">Si, eliminar invitación</button>
              </div>
            </div>
          </div>
          <div class="media-right">
            <button @click="$refs.modalRemoveInvitation.close()" class="delete"></button>
          </div>
        </article>
      </div>
    </b-modal> -->
    <b-loading :active.sync="isLoading"></b-loading>
  </div>
</template>

<script>
import GetUsuario from "../../utils/GetUsuario";
import Avatar from "../../utils/Avatar";

export default {
  props: [
    "teamUrl",
    "getGroupMembers",
    "assignGroupSecond",
    "deleteGroupSecond",
    "acceptGroupRequest",
    "removeGroupUser",
    "removeGroupInvitation"
  ],
  components: {
    GetUsuario,
    Avatar
  },
  data() {
    return {
      isLoading: true,
      response: {
        ok: true
      },
      showRemoveModal: false,
      deleteUser: null,
      deleteUserName: null,
      // showRemoveInvitationModal: false,
      // deleteInvitation: null,
      // deleteInvitationEmail: null,
      showAcceptRequestModal: false,
      acceptRequestId: null,
      acceptRequestComment: null,
      user: {},
      group: {
        invitations: []
      },
      members: []
    };
  },
  created: function() {
    this.user = this.$store.state.user;
  },
  mounted: function() {
    this.getEquipo(this.user.groups[0].id);
  },
  methods: {
    getEquipo: function(id) {
      Promise.all([
        this.$http.get(this.fetchTeamUrl),
        this.$http.get(this.fetchGroupMembers)
      ])
        .then(responses => {
          this.group = responses[0].data;
          this.members = responses[1].data;
          this.isLoading = false;
        })
        .catch(error => {
          console.error(error.message);
          this.isLoading = false;
          this.$snackbar.open({
            message: "Error inesperado. Recarge la pagina.",
            type: "is-danger",
            actionText: "Cerrar"
          });
        });
    },
    openRemoveUser: function(id, name) {
      this.deleteUser = id;
      this.deleteUserName = name;
      this.showRemoveModal = true;
    },
    // openRemoveInvitation: function(id, email) {
    //   this.deleteInvitation = id;
    //   this.deleteInvitationEmail = email;
    //   this.showRemoveInvitationModal = true;
    // },
    openAcceptRequest: function(id, comment) {
      this.acceptRequestId = id;
      this.acceptRequestComment = comment;
      this.showAcceptRequestModal = true;
    },
    submitRemoveUser: function() {
      this.showRemoveModal = false;
      console.log("Sending form!");
      this.isLoading = true;
      let formUrl = this.removeGroupUser.replace(":gro", this.user.groups[0].id)
      formUrl = formUrl.replace(":usr", this.deleteUser)
      this.$http
        .delete(formUrl)
        .then(response => {
          this.$snackbar.open({
            message: this.deleteUserName + " se eliminó del equipo correctamente",
            type: "is-success",
            actionText: "OK"
          });
          this.getEquipo(this.user.groups[0].id);
        })
        .catch(error => {
          console.error(error.message);
          this.isLoading = false;
          this.$snackbar.open({
            message: "Error inesperado. Recarge la pagina.",
            type: "is-danger",
            actionText: "Cerrar"
          });
          return false;
        });
    },
    // submitRemoveInvitation: function() {
    //   this.showRemoveModal = false;
    //   console.log("Sending form!");
    //   this.isLoading = true;
    //   this.$http
    //     .post(this.saveTeamUrl, this.deleteUser)
    //     .then(response => {
    //       this.$snackbar.open({
    //         message: "Los datos del equipo han sido actualizados",
    //         type: "is-success",
    //         actionText: "OK"
    //       });
    //       this.isLoading = false;
    //       this.response.ok = true;
    //       this.getEquipo(this.user.groups[0].id);
    //     })
    //     .catch(error => {
    //       console.error(error.message);
    //       this.isLoading = false;
    //       this.$snackbar.open({
    //         message: "Error inesperado. Recarge la pagina.",
    //         type: "is-danger",
    //         actionText: "Cerrar"
    //       });
    //       return false;
    //     });
    // },
    submitAcceptRequest: function() {
      this.showAcceptRequestModal = false;
      console.log("Sending form!");
      let formUrl = this.acceptGroupRequest.replace(":inv", this.acceptRequestId)
      this.isLoading = true;
      this.$http
        .post(formUrl)
        .then(response => {
          this.$snackbar.open({
            message: "Solicitud aceptada ¡Nuevo miembro del equipo! 🎉",
            type: "is-success",
            actionText: "OK"
          });
          this.getEquipo(this.user.groups[0].id);
        })
        .catch(error => {
          console.error(error.message);
          this.isLoading = false;
          this.$snackbar.open({
            message: "Error inesperado. Recarge la pagina.",
            type: "is-danger",
            actionText: "Cerrar"
          });
          return false;
        });
    },
    assignSecondInCharge: function(id){
      console.log("Sending form!");
      let formUrl = this.assignGroupSecond.replace(":gro", this.user.groups[0].id)
      formUrl = formUrl.replace(":usr", id)
      this.isLoading = true;
      this.$http
        .put(formUrl)
        .then(response => {
          this.$snackbar.open({
            message: "El co-responsable ha sido asignado correctamente",
            type: "is-success",
            actionText: "OK"
          });
          this.response.ok = true;
          this.getEquipo(this.user.groups[0].id);
        })
        .catch(error => {
          console.error(error.message);
          this.isLoading = false;
          this.$snackbar.open({
            message: "Error inesperado. Recarge la pagina.",
            type: "is-danger",
            actionText: "Cerrar"
          });
          return false;
        });
    },
    removeSecondInCharge: function(id){
      console.log("Sending form!");
      let formUrl = this.deleteGroupSecond.replace(":gro", this.user.groups[0].id)
      formUrl = formUrl.replace(":usr", id)
      this.isLoading = true;
      this.$http
        .delete(formUrl)
        .then(response => {
          this.$snackbar.open({
            message: "El co-responsable ha sido desvinculado correctamente",
            type: "is-success",
            actionText: "OK"
          });
          this.response.ok = true;
          this.getEquipo(this.user.groups[0].id);
        })
        .catch(error => {
          console.error(error.message);
          this.isLoading = false;
          this.$snackbar.open({
            message: "Error inesperado. Recarge la pagina.",
            type: "is-danger",
            actionText: "Cerrar"
          });
          return false;
        });
    },
  },
  computed: {
    fetchTeamUrl: function() {
      return this.teamUrl.replace(":gro", this.user.groups[0].id);
    },
    fetchGroupMembers: function() {
      return this.getGroupMembers.replace(":gro", this.user.groups[0].id);
    },
    cantInvitaciones: function() {
      return this.group.invitations.filter(x => {
        return x.state == "pending";
      }).length;
    },
    cantSolicitudes: function() {
      return this.group.invitations.filter(x => {
        return x.state == "requested";
      }).length;
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
