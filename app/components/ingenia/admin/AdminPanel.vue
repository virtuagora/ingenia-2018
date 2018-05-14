<template>
  <section>
    <div class="columns">
      <div class="column is-2">
        <aside class="menu">
          <p class="menu-label">
            Mi perfil
          </p>
          <ul class="menu-list">
            <li>
              <router-link :to="{ name: 'administracionOverview'}" exact-active-class="is-active" exact>Inicio</router-link>
            </li>
          </ul>
          <hr>
           <p class="menu-label">
            Administrar
          </p>
          <ul class="menu-list">
            <li>
              <router-link :to="{ name: 'adminListadoProyectos'}" exact-active-class="is-active">Listado de proyectos</router-link>
            </li>
            <li>
              <router-link :to="{ name: 'adminDescargarMatriz'}" exact-active-class="is-active">Descargar matriz</router-link>
            </li>
            <li>
              <router-link :to="{ name: 'adminVerificarDNI'}" exact-active-class="is-active">Verificar DNIs</router-link>
            </li>
            <li>
              <router-link :to="{ name: 'adminListaNegraDNI'}" exact-active-class="is-active">Lista negra de DNIs</router-link>
            </li>
          </ul>
          <p class="menu-label">
            Configurar
          </p>
          <ul class="menu-list">
            <li>
              <router-link :to="{ name: 'adminFechaCierre'}" exact-active-class="is-active">Fecha de cierre</router-link>
            </li>
            <li>
              <router-link :to="{ name: 'adminFechaCierre'}" exact-active-class="is-active">Roles de Usuarios</router-link>
            </li>
          </ul>
        </aside>
      </div>
      <div class="column">
        <router-view :settings="settings" :get-projects="getProjects" :get-users="getUsers" 
        :get-letter="getLetter" :get-agreement="getAgreement" :get-group-members="getGroupMembers"
        :get-user-dni="getUserDni" 
        :team-url="teamUrl" ></router-view>
      </div>
    </div>
  </section>
</template>

<script>
export default {
  props: ['settings','getProjects','getGroupMembers','teamUrl','getUsers','getLetter','getAgreement','getUserDni'],
  data() {
    return {
      user: {}
    };
  },
  created: function() {
    this.user = this.$store.state.user;
    if (this.user === null) {
      window.location.href = "/login";
    }
  },
  mounted: function() {
    document.getElementById("loading").remove();
  },
  methods: {},
  computed: {}
};
</script>
