  <template>
  <section>
    <div class="columns">
      <div class="column is-3">
        <aside class="menu">
          <p class="menu-label">
            Mi perfil
          </p>
          <ul class="menu-list">
            <li>
              <router-link :to="{ name: 'panelOverview'}" exact-active-class="is-active" exact>Inicio</router-link>
            </li>
            <li>
              <router-link :to="{ name: 'userVerPerfil'}" exact-active-class="is-active">Mi perfil público</router-link>
            </li>
          </ul>
          <hr>
          <p class="menu-label">
            INGENIA 2018
          </p>
          <ul class="menu-list">
            <li v-if="user.groups[0] === undefined">
              <router-link :to="{ name: 'userVerInvitaciones'}" exact-active-class="is-active">Mis invitaciones y solicitudes
                <span class="badge is-badge-danger is-badge-small" :class="{'is-hidden': this.user.invitations.length == 0}" data-badge="">
                  <i class="fas fa-envelope fa-fw"></i>
                </span>
              </router-link>
            </li>
            <li>
              <a>Mis datos personales</a>
            </li>
            <li>
              <ul>
                <li>
                  <router-link :to="{ name: 'userEditarMisDatosPersonales'}" exact-active-class="is-active">
                    <i class="fas fa-fw" :class="{'fa-check has-text-success': !this.user.pending_tasks.includes('profile'), 'fa-exclamation has-text-danger': this.user.pending_tasks.includes('profile') }" :style="$route.name == 'userEditarMisDatosPersonales' ? 'color: white !important;' : ''"></i>&nbsp;Actualizar datos personales</router-link>
                </li>
                <!-- <li>
                <router-link :to="{ name: 'userEditarEmail'}" exact-active-class="is-active">
                  <i class="fas fa-fw" :class="{'fa-check has-text-success': !this.user.pending_tasks.includes('email'), 'fa-exclamation has-text-danger': this.user.pending_tasks.includes('email') }" :style="$route.name == 'userEditarEmail' ? 'color: white !important;' : ''"></i>&nbsp;Verificar mi email</router-link>
              </li> -->
                <li>
                  <router-link :to="{ name: 'userSubirDNI'}" exact-active-class="is-active">
                    <i class="fas fa-fw" :class="{'fa-check has-text-success': !this.user.pending_tasks.includes('dni'), 'fa-exclamation has-text-danger': this.user.pending_tasks.includes('dni') }" :style="$route.name == 'userSubirDNI' ? 'color: white !important;' : ''"></i>&nbsp;Subir mi DNI</router-link>
                </li>
              </ul>
            </li>
            <li v-if="user.groups[0] === undefined && (new Date(deadline)) > (new Date())">
              <router-link :to="{ name: 'userInscripcionIngenia'}" exact-active-class="is-active">¡Presentá un proyecto!</router-link>
            </li>
          </ul>
          <p class="menu-label" v-if="user.groups[0] !== undefined">
            Mi equipo Ingenia
          </p>
          <ul class="menu-list" v-if="user.groups[0] !== undefined">
            <li>
              <router-link :to="{ name: 'userVerEquipo'}" exact-active-class="is-active">Ver mi equipo</router-link>
            </li>
            <li v-if="user.groups[0].pivot.relation == 'responsable'">
              <router-link :to="{ name: 'userVerIntegrantes'}" exact-active-class="is-active">Ver integrantes e invitaciones
                <span class="badge is-badge-danger is-badge-small" :class="{'is-hidden': cantSolicitudes == 0}" data-badge="">
                  <i class="fas fa-envelope fa-fw"></i>
                </span>
              </router-link>
            </li>
            <li v-if="user.groups[0].pivot.relation == 'responsable'">
              <router-link :to="{ name: 'userEnviarInvitaciones'}" exact-active-class="is-active">Invitar a alguien al equipo</router-link>
            </li>
            <li v-if="user.groups[0].pivot.relation == 'responsable'">
              <router-link :to="{ name: 'userSubirConformidad'}" exact-active-class="is-active">
                <i class="fas fa-fw" :class="{'fa-check has-text-success': user.groups[0].uploaded_agreement, 'fa-exclamation has-text-danger': !user.groups[0].uploaded_agreement }" :style="$route.name == 'userSubirConformidad' ? 'color: white !important;' : ''"></i>&nbsp;Subir carta de conformidad </router-link>
            </li>
          </ul>
          <p class="menu-label" v-if="user.groups[0] !== undefined">
            Mi proyecto Ingenia
          </p>
          <ul class="menu-list" v-if="user.groups[0] !== undefined ">
            <li v-if="user.groups[0].project === null && user.groups[0].pivot.relation == 'responsable'">
              <router-link :to="{ name: 'userEditarProyecto'}" exact-active-class="is-active">Cargar proyecto</router-link>
            </li>
            <li v-if="user.groups[0].project !== null">
              <router-link :to="{ name: 'userVerProyecto'}" exact-active-class="is-active">Ver mi proyecto</router-link>
            </li>
            <li v-if="user.groups[0].pivot.relation == 'responsable' && user.groups[0].project !== null && user.groups[0].project.organization !== null">
              <router-link :to="{ name: 'userSubirAvalOrganizacion'}" exact-active-class="is-active">
                <i class="fas fa-fw" :class="{'fa-check has-text-success': user.groups[0].project.organization !== null && user.groups[0].uploaded_letter, 'fa-exclamation has-text-danger': user.groups[0].project.organization !== null && !user.groups[0].uploaded_letter }" :style="$route.name == 'userSubirAvalOrganizacion' ? 'color: white !important;' : ''"></i>&nbsp;Subir carta de aval</router-link>
            </li>
            <li v-if="user.groups[0].pivot.relation == 'responsable' && user.groups[0].project !== null && user.groups[0].project.selected == true">
              <a :href="'/grupo/' + user.groups[0].id + '/historia/nuevo'"><i class="fas fa-camera-retro fa-lg fa-fw"></i>&nbsp;Subir una historia</a>
            </li>
            <li v-if="user.groups[0].pivot.relation == 'responsable' && user.groups[0].project !== null && user.groups[0].project.selected == true">
              <router-link :to="{ name: 'userSubirRecibos'}" exact-active-class="is-active">
                <i class="fas fa-archive fa-lg fa-fw"></i>&nbsp;Subir recibos</router-link>
            </li>
          </ul>
          <!-- <p class="menu-label" v-if="user.groups[0] !== undefined && user.groups[0].pivot.relation == 'responsable'">
            Avanzado
          </p>
          <ul class="menu-list" v-if="user.groups[0] !== undefined && user.groups[0].pivot.relation == 'responsable'">
            <li>
              <router-link :to="{ name: 'userOtrasOpciones'}" exact-active-class="is-active">Otras opciones</router-link>
            </li>
          </ul> -->
          <!-- <ul class="menu-list" v-if="user.groups[0] !== undefined && user.groups[0].project !== null && user.groups[0].pivot.relation == 'responsable'">
          <li v-if="user.groups[0].pivot.relation == 'responsable'">
            <router-link :to="{ name: 'userOtrasOpciones'}" exact-active-class="is-active">Otras opciones</router-link>
          </li>
        </ul> -->
        </aside>
      </div>
      <div class="column is-7 is-offset-1">
        <router-view :id="id" :deadline="deadline" :user-url="userUrl" :save-user-profile-url="saveUserProfileUrl" :save-user-dni-url="saveUserDniUrl" :save-pending-email="savePendingEmail" :save-user-public-profile-url="saveUserPublicProfileUrl" :team-url="teamUrl" :save-team-url="saveTeamUrl" :edit-team-url="editTeamUrl" :send-invitation-url="sendInvitationUrl" :team-users-url="teamUsersUrl" :team-invitations-url="teamInvitationsUrl" :save-agreement-url="saveAgreementUrl" :project-url="projectUrl" :save-project-url="saveProjectUrl" :edit-project-url="editProjectUrl" :delete-group-url="deleteGroupUrl" :save-image-url="saveImageUrl" :save-letter-url="saveLetterUrl" :get-group-members="getGroupMembers" :accept-group-invitation="acceptGroupInvitation" :accept-group-request="acceptGroupRequest" :remove-group-user="removeGroupUser" :remove-group-invitation="removeGroupInvitation" :assign-group-second="assignGroupSecond" :delete-group-second="deleteGroupSecond"></router-view>
      </div>
    </div>
  </section>
</template>

<script>
export default {
  props: [
    "id",
    "deadline",
    "userUrl",
    "saveUserProfileUrl",
    "saveUserDniUrl",
    "savePendingEmail",
    "saveUserPublicProfileUrl",
    "teamUrl",
    "saveTeamUrl",
    "editTeamUrl",
    "sendInvitationUrl",
    "teamUsersUrl",
    "teamInvitationsUrl",
    "saveAgreementUrl",
    "projectUrl",
    "saveProjectUrl",
    "editProjectUrl",
    "deleteGroupUrl",
    "saveImageUrl",
    "saveLetterUrl",
    "getGroupMembers",
    "acceptGroupInvitation",
    "acceptGroupRequest",
    "removeGroupUser",
    "removeGroupInvitation",
    "assignGroupSecond",
    "deleteGroupSecond"
  ],
  data() {
    return {
      user: {},
      group: {
        invitations: []
      }
    };
  },
  created: function() {
    this.user = this.$store.state.user;
    if (this.user === null) {
      window.location.href = "/login";
    }
    if (
      this.user.groups[0] !== undefined &&
      this.user.groups[0].pivot.relation == "responsable"
    ) {
      this.checkInvitations();
    }
    let intervalId = setInterval(
      function() {
        this.$http
          .get("/ping")
          .then(response => {
            if (response.data.message == "Login") {
              clearInterval(intervalId);
              this.$snackbar.open({
                message: "Error, se cerró la sesión. Haga clic en el boton de reingresar para volver a iniciar sesión (se abrirá una nueva ventana)",
                type: "is-warning",
                actionText: "Re-ingresar",
                indefinite: true,
                onAction: () => {
                  window.open("/login");
                  this.checkLogin();
                }
              });
            }
            console.log(response.data.message);
          })
          .catch(error => {
            console.error(error);
            clearInterval(intervalId);
            this.$snackbar.open({
              message: "Error, se cayó la conexión. Recargue la página. (Se perderán los datos, recomendamos guardarlos para volver a ingresarlos)",
              type: "is-warning",
              actionText: "Recargar",
              indefinite: true,
              onAction: () => {
                location.reload();
              }
            });
          });
      }.bind(this),
      120000
    );
  },

  mounted: function() {
    document.getElementById("loading").remove();
  },
  methods: {
    checkLogin() {
      let intervalId = setInterval(
        function() {
          this.$http
            .get("/ping")
            .then(response => {
              if (response.data.message == "Pong!") {
                console.log(response.data.message);
                clearInterval(intervalId);
                this.$snackbar.open({
                  message: "Tu sesión ha sido reestablecida!",
                  type: "is-success",
                  actionText: "OK",
                  indefinite: true
                });
                this.restartPong();
              }
              else {
                console.log("Debe reiniciar sesión");                
              }
            })
            .catch(error => {
              console.error(error);
              clearInterval(intervalId);
              this.$snackbar.open({
                message: "Error, se cayó la conexión. Recargue la página. (Se perderán los datos, recomendamos guardarlos para volver a ingresarlos)",
                type: "is-warning",
                actionText: "Recargar",
                indefinite: true,
                onAction: () => {
                  location.reload();
                }
              });
            });
        }.bind(this),
        120000
      );
    },
    restartPong() {
      let intervalId = setInterval(
        function() {
          this.$http
            .get("/ping")
            .then(response => {
              if (response.data.message == "Login") {
                clearInterval(intervalId);
                this.$snackbar.open({
                  message: "Error, se cerró la sesión. Haga clic en el boton de reingresar para volver a iniciar sesión (se abrirá una nueva ventana)",
                  type: "is-warning",
                  actionText: "Re-ingresar",
                  indefinite: true,
                  onAction: () => {
                    window.open("/login");
                    this.checkLogin();
                  }
                });
              }
              console.log(response.data.message);
            })
            .catch(error => {
              console.error(error);
              clearInterval(intervalId);
              this.$snackbar.open({
                message: "Error, se cayó la conexión. Recargue la página. (Se perderán los datos, recomendamos guardarlos para volver a ingresarlos)",
                type: "is-warning",
                actionText: "Recargar",
                indefinite: true,
                onAction: () => {
                  location.reload();
                }
              });
            });
        }.bind(this),
        120000
      );
    },
    updateUserState: function() {
      this.user = this.$store.state.user;
    },
    checkInvitations: function() {
      this.$http
        .get(this.fetchTeamUrl)
        .then(response => {
          this.group = response.data;
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
    cantSolicitudes: function() {
      return this.group.invitations.filter(x => {
        return x.state == "requested";
      }).length;
    }
  }
};
</script>
