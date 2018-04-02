  <template>
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
          <li>
            <router-link :to="{ name: 'userEditarPerfil'}" exact-active-class="is-active">Editar perfil público</router-link>
          </li>
          <li>
            <router-link :to="{ name: 'userEditarPassword'}" exact-active-class="is-active">Cambiar contraseña</router-link>
          </li>
        </ul>
        <hr>
        <p class="menu-label">
          INGENIA 2018
        </p>
        <ul class="menu-list">
          <li>
            <router-link :to="{ name: 'userVerInvitaciones'}" exact-active-class="is-active">Mis invitaciones</router-link>
          </li>
          <li>
            <a>Mis datos personales</a>
          </li>
          <li>
            <ul>
              <li>
                <router-link :to="{ name: 'userEditarMisDatosPersonales'}" exact-active-class="is-active">
                  <i class="fas fa-fw" :class="{'fa-check': !this.user.pending_tasks.includes('profile'), 'fa-exclamation': this.user.pending_tasks.includes('profile') }"></i>&nbsp;Actualizar datos personales</router-link>
              </li>
              <li>
                <router-link :to="{ name: 'userEditarEmail'}" exact-active-class="is-active">
                  <i class="fas fa-fw" :class="{'fa-check': !this.user.pending_tasks.includes('email'), 'fa-exclamation': this.user.pending_tasks.includes('email') }"></i>&nbsp;Actualizar mi email</router-link>
              </li>
              <li>
                <router-link :to="{ name: 'userSubirDNI'}" exact-active-class="is-active">
                  <i class="fas fa-fw" :class="{'fa-check': !this.user.pending_tasks.includes('dni'), 'fa-exclamation': this.user.pending_tasks.includes('dni') }"></i>&nbsp;Subir mi DNI</router-link>
              </li>
            </ul>
          </li>
          <li>
            <router-link :to="{ name: 'userInscripcionIngenia'}" exact-active-class="is-active">¡Presentá un nuevo proyecto!</router-link>
          </li>
        </ul>
        <p class="menu-label">
          Mi equipo Ingenia 2018
        </p>
        <ul class="menu-list">
          <li>
            <router-link :to="{ name: 'userVerEquipo'}" exact-active-class="is-active">Mi equipo INGENIA</router-link>
          </li>
          <li>
            <router-link :to="{ name: 'userEditarEquipo'}" exact-active-class="is-active">Editar datos del equipo</router-link>
          </li>
          <li>
            <router-link :to="{ name: 'userEnviarInvitaciones'}" exact-active-class="is-active">Enviar invitaciones</router-link>
          </li>
          <li>
            <router-link :to="{ name: 'userSubirConformidad'}" exact-active-class="is-active">Subir carta de conformidad </router-link>
          </li>
        </ul>
        <p class="menu-label">
          Mi proyecto Ingenia 2018
        </p>
        <ul class="menu-list">
          <li>
            <router-link :to="{ name: 'userVerProyecto'}" exact-active-class="is-active">Mi proyecto INGENIA</router-link>
          </li>
          <li>
            <router-link :to="{ name: 'userEditarProyecto'}" exact-active-class="is-active">Editar datos del proyecto</router-link>
          </li>
          <li>
            <router-link :to="{ name: 'userSubirImagen'}" exact-active-class="is-active">Subir imagen del proyecto</router-link>
          </li>
          <li>
            <router-link :to="{ name: 'userSubirAvalOrganizacion'}" exact-active-class="is-active">Subir carta de aval</router-link>
          </li>
          <li>
            <router-link :to="{ name: 'userEnviarProyecto'}" class="is-size-5" exact-active-class="is-active">
              <i class="fas fa-paper-plane fa-fw"></i> ¡Enviar proyecto!</router-link>
          </li>
        </ul>

      </aside>
    </div>
    <div class="column is-7 is-offset-1">
      <router-view :save-team-url="saveTeamUrl" :save-project-url="saveProjectUrl" :save-user-profile-url="saveUserProfileUrl" :save-user-dni-url="saveUserDniUrl" :send-invitation-url="sendInvitationUrl"></router-view>
    </div>
  </div>
</template>

<script>
export default {
  props: [
    "id",
    "saveTeamUrl",
    "saveProjectUrl",
    "saveUserProfileUrl",
    "saveUserDniUrl",
    "sendInvitationUrl"
  ],
  data() {
    return {
      user: {}
    };
  },
  created: function() {
    this.user = this.$store.state.user;
  },
  mounted: function() {
    document.getElementById("loading").remove();
  },
  methods: {
    forceUserUpdate: function() {
      this.user = this.$store.state.user;
    }
  },
  // beforeRouteUpdate(to, from, next) {
  //   console.log("Usuario Actualizado");
  //   this.user = this.$store.state.user;
  //   next();
  // }
};
</script>
