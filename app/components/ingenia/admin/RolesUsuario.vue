<template>
  <section>
    <h1 class="subtitle is-3">Roles de usuarios</h1>
    <b-message>Aquí podras agregar nuevos administradores o coordinadores.<br>Para eso tendras que ingresar el email del nuevo coordinador o administrador (Nota: Deben tener una cuenta registrada)</b-message>
    <div class="columns">
      <div class="column">
        <h1 class="subtitle is-4">Coordinadores
          <i class="fab fa-user-shield"></i>
        </h1>
        <hr>
        <div class="field is-grouped">
          <div class="control">
            <a @click.prevent class="button is-static">
              <span class="icon">
                <i class="fas fa-envelope"></i>
              </span>
            </a>
          </div>
          <div class="control is-expanded">
            <input type="email" v-model="emailCoord" name="emailCoord" v-validate="'required|email'" class="input" placeholder="Ingrese el email de la cuenta del coordinador" :class="{'is-danger': errors.has('emailCoord')}">
            <span class="help is-danger" v-show="errors.has('emailCoord')">
              <i class="fas fa-times-circle fa-fw"></i>&nbsp;ERROR. Debe ser un email bien formado</span>
          </div>
          <div class="control">
            <a @click="submitCoordinator()" :disabled="errors.has('emailCoord')" class="button is-link">
              <span class="icon">
                <i class="fas fa-paper-plane"></i>
              </span>
            </a>
          </div>
        </div>
        <table class="table is-fullwidth text-middle">
          <tbody>
            <tr v-for="coord in coordinators" :key="coord.id">
              <td>
                <Avatar :user="admin" class="inline-image" size="24" />&nbsp;&nbsp;{{coord.subject.display_name}}</td>
            </tr>
            <tr v-if="coordinators.length == 0">
              <td class="has-text-centered">No hay coordinadores</td>
            </tr>
          </tbody>
        </table>
      </div>
      <div class="column">
        <h1 class="subtitle is-4">Administradores
          <i class="fab fa-shield"></i>
        </h1>
        <hr>
        <div class="field is-grouped">
          <div class="control">
            <a @click.prevent class="button is-static">
              <span class="icon">
                <i class="fas fa-envelope"></i>
              </span>
            </a>
          </div>
          <div class="control is-expanded">
            <input type="email" v-model="emailAdmin" name="emailAdmin" v-validate="'required|email'" class="input" placeholder="Ingrese el email de la cuenta del administrador" :class="{'is-danger': errors.has('emailAdmin')}">
            <span class="help is-danger" v-show="errors.has('emailAdmin')">
              <i class="fas fa-times-circle fa-fw"></i>&nbsp;ERROR. Debe ser un email bien formado</span>
          </div>
          <div class="control">
            <a @click="submitAdmin()" :disabled="errors.has('emailAdmin')" class="button is-link">
              <span class="icon">
                <i class="fas fa-paper-plane"></i>
              </span>
            </a>
          </div>
        </div>
        <table class="table is-fullwidth text-middle">
          <tbody>
            <tr v-for="admin in admins" :key="admin.id">
              <td>
                <Avatar :user="admin" class="inline-image" size="24" />&nbsp;&nbsp;{{admin.subject.display_name}}</td>
            </tr>
            <tr v-if="admins.length == 0">
              <td class="has-text-centered">No hay administradores</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
    <b-loading :active.sync="isLoading"></b-loading>
  </section>

</template>

<script>
import Avatar from "../utils/Avatar";

export default {
  props: ["getUsers",'postRoleUser'],
  components: {
    Avatar
  },
  data() {
    return {
      emailAdmin: "",
      emailCoord: "",
      isLoading: false,
      admins: [],
      coordinators: [],
      paginator: {
        current_page: null,
        last_page: null,
        next_page_url: null,
        prev_page_url: null
      },
      verifiedToggle: false,
      nameToSearch: "",
      blacklist: "",
      filters: false
    };
  },
  mounted: function() {
    this.isLoading = true;
    Promise.all([this.$http.get(this.urlGet1), this.$http.get(this.urlGet2)])
      .then(response => {
        this.admins = response[0].data.data;
        this.coordinators = response[1].data.data;
        this.isLoading = false;
      })
      .catch(error => {
        console.error(error.message);
        this.$snackbar.open({
          message: "Error inesperado",
          type: "is-danger",
          actionText: "Cerrar"
        });
        this.isLoading = false;
      });
  },
  methods: {
    submitCoordinator: function() {
      this.isLoading = true;
      this.$http
        .post(this.postRoleUser,{
          user_email: this.emailCoord,
          role: 'coordin'
        })
        .then(response => {
          this.$snackbar.open({
            message: "Agregado nuevo coordinador",
            type: "is-success",
            actionText: "OK"
          });
          this.getCoordinators();
          this.isLoading = false;
        })
        .catch(error => {
          console.error(error.message);
          this.$snackbar.open({
            message: "Error inesperado",
            type: "is-danger",
            actionText: "Cerrar"
          });
          this.isLoading = false;
        });
    },
    submitAdmin: function() {
      this.isLoading = true;
      this.$http
        .post(this.postRoleUser,{
            user_email: this.emailAdmin,
            role: 'admin'
        })
        .then(response => {
          this.isLoading = false;
          this.$snackbar.open({
            message: "Agregado nuevo administrador",
            type: "is-success",
            actionText: "OK"
          });
          this.getAdmins();
        })
        .catch(error => {
          console.error(error.message);
          this.$snackbar.open({
            message: "Error inesperado",
            type: "is-danger",
            actionText: "Cerrar"
          });
          this.isLoading = false;
        });
    },
    getAdmins: function() {
      this.isLoading = true;
      this.$http
        .get(this.urlGet1)
        .then(response => {
          this.admins = response.data.data;
          this.isLoading = false;
        })
        .catch(error => {
          console.error(error.message);
          this.$snackbar.open({
            message: "Error inesperado",
            type: "is-danger",
            actionText: "Cerrar"
          });
          this.isLoading = false;
        });
    },
    getCoordinators: function() {
      this.isLoading = true;
      this.$http
        .get(this.urlGet2)
        .then(response => {
          this.coordinators = response.data.data;
          this.isLoading = false;
        })
        .catch(error => {
          console.error(error.message);
          this.$snackbar.open({
            message: "Error inesperado",
            type: "is-danger",
            actionText: "Cerrar"
          });
          this.isLoading = false;
        });
    }
  },
  watch: {},
  computed: {
    urlGet1: function() {
      let query = [];
      query.push("size=100");
      query.push("roles=admin");
      return this.getUsers.concat(query.length > 0 ? "?" : "", query.join("&"));
    },
    urlGet2: function() {
      let query = [];
      query.push("size=100");
      query.push("roles=coordin");
      return this.getUsers.concat(query.length > 0 ? "?" : "", query.join("&"));
    }
  }
};
</script>
