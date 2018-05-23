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
          <p class="menu-label" v-if="roles.includes('coordin')">
            Coordinación
          </p>
          <ul class="menu-list" v-if="roles.includes('coordin')">
            <li>
              <router-link :to="{ name: 'coordinListado'}" exact-active-class="is-active">Mis proyectos asignados</router-link>
            </li>
          </ul>
           <p class="menu-label">
            Administrar
          </p>
          <ul class="menu-list">
            <li v-if="roles.includes('admin')">
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
          <p class="menu-label" v-if="roles.includes('admin')">
            Configurar
          </p>
          <ul class="menu-list" v-if="roles.includes('admin')">
            <li>
              <router-link :to="{ name: 'adminFechaCierre'}" exact-active-class="is-active">Fecha de cierre</router-link>
            </li>
            <li>
              <router-link :to="{ name: 'adminRolesUsuario'}" exact-active-class="is-active">Roles de Usuarios</router-link>
            </li>
          </ul>
        </aside>
      </div>
      <div class="column">
        <router-view :settings="settings"
        :roles="roles" 
        :update-review="updateReview"
        :get-projects="getProjects" :get-users="getUsers" 
        :get-letter="getLetter" :get-agreement="getAgreement" :get-group-members="getGroupMembers"
        :get-user-dni="getUserDni" 
        :post-validate-dni="postValidateDni"
        :post-role-user="postRoleUser"
        :put-project-note="putProjectNote"
        :team-url="teamUrl" ></router-view>
      </div>
    </div>
  </section>
</template>

<script>
export default {
  props: ['settings','roles','getProjects','updateReview','putProjectNote','getGroupMembers','teamUrl','getUsers','getLetter','getAgreement','getUserDni','postValidateDni','postRoleUser'],
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
  methods:{
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
        45000
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
  },
  mounted: function() {
    document.getElementById("loading").remove();
  },
  methods: {},
  computed: {}
};
</script>
