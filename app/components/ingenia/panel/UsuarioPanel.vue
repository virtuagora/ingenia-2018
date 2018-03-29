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
            <router-link :to="{ name: 'usuarioVerPerfil'}" exact-active-class="is-active">Ver perfil</router-link>
          </li>
          <li>
            <router-link :to="{ name: 'usuarioEditarPerfil'}" exact-active-class="is-active">Editar perfil</router-link>
          </li>
          <li>
            <router-link :to="{ name: 'usuarioEditarEmail'}" exact-active-class="is-active">Editar email</router-link>
          </li>
          <li>
            <router-link :to="{ name: 'usuarioEditarPassword'}" exact-active-class="is-active">Cambiar contraseña</router-link>
          </li>
        </ul>
        <hr>
        <p class="menu-label">
          INGENIA 2018
        </p>
        <ul class="menu-list">
          <li>
          <router-link :to="{ name: 'usuarioInscripcionIngenia'}" exact-active-class="is-active">¡Participá con un proyecto!</router-link>
        </li>
        </ul>
        <p class="menu-label">
          Mi proyecto Ingenia
        </p>
        <ul class="menu-list">
        <li>
          <router-link :to="{ name: 'usuarioSubirProyecto'}" exact-active-class="is-active">Subir un proyecto</router-link>
        </li>
        <li>
          <router-link :to="{ name: 'usuarioVerProyecto'}" exact-active-class="is-active">Mi proyecto INGENIA</router-link>
        </li>
        <li>
          <router-link :to="{ name: 'usuarioSubirImagen'}" exact-active-class="is-active">Subir imagen</router-link>
        </li>
        <li>
          <router-link :to="{ name: 'usuarioSubirAvalOrganizacion'}" exact-active-class="is-active">Subir aval de organización</router-link>
        </li>
        </ul>
        <p class="menu-label">
          Mi equipo Ingenia
        </p>
        <ul class="menu-list">
        <li>
          <router-link :to="{ name: 'usuarioVerInvitaciones'}" exact-active-class="is-active">Invitaciones</router-link>
        </li>
        <li>
          <router-link :to="{ name: 'usuarioVerEquipo'}" exact-active-class="is-active">Mi equipo INGENIA</router-link>
        </li>
        <li>
          <router-link :to="{ name: 'usuarioVerMisDatosPersonales'}" exact-active-class="is-active">Mis datos personales</router-link>
        </li>
        <li>
          <router-link :to="{ name: 'usuarioSubirDNI'}" exact-active-class="is-active">Cargar mi DNI</router-link>
        </li>
        
        </ul>
      </aside>
    </div>
    <div class="column is-7 is-offset-1">
      <router-view></router-view>
    </div>
  </div>
</template>

<script>
export default {
  props: ["id"],
  data() {
    return {};
  },
  mounted: function() {
    document.getElementById("loading").remove();
  }
};
</script>
