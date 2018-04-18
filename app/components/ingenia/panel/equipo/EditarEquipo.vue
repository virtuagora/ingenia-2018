<template>
  <div>
    <section>
      <div class="tabs">
  <ul>
    <li :class="{'is-active': $route.name == 'userVerEquipo'}"><router-link :to="{ name: 'userVerEquipo'}">Ver equipo</router-link></li>
    <li :class="{'is-active': $route.name == 'userEditarEquipo'}"  v-if="user.groups[0].pivot.relation == 'responsable'"><router-link :to="{ name: 'userEditarEquipo'}">Editar datos</router-link></li>
    <li :class="{'is-active': $route.name == 'userVerIntegrantes'}"  v-if="user.groups[0].pivot.relation == 'responsable'"><router-link :to="{ name: 'userVerIntegrantes'}">Ver los integrantes</router-link></li>
  </ul>
</div>
      <div class="has-text-centered">
        <img src="/assets/img/ingenia-logo.svg" class="image is-centered" style="max-width: 250px;">
      </div>
      <br>
      <b-message class="has-text-centered">
        Acá podes editar los datos de tu equipo. Podes cambiar todos los datos hasta el cierre de la convocatoria.
      </b-message>
      <section v-if="fetchResponse.replied && fetchResponse.ok">
      <div class="notification is-link">
        <h1 class="title is-1 is-700">
          <i class="far fa-edit fa-fw"></i> Sobre el
          <u>EQUIPO</u>
        </h1>
      </div>
      <form-equipo ref="formEquipo" :team.sync="team"></form-equipo>
      <br>
      <div class="notification is-success" v-show="response.ok">
        <i class="fas fa-check fa-fw"></i> Datos enviados y guardados con éxito
      </div>
      <button @click="submit" v-show="!response.ok" class="button is-large is-primary is-fullwidth" :class="{'is-loading': isLoading}">
        <i class="fas fa-save"></i>&nbsp;&nbsp;Guardar</button>
      </section>
      <section v-if="fetchResponse.replied && !fetchResponse.ok">
        <div class="notification is-danger">
        <i class="fas fa-times fa-fw"></i> Error al conseguir los datos del equipo. Vuelva a cargar la página
      </div>
      </section>
      <b-loading :active.sync="isLoading"></b-loading>
    </section>
  </div>
</template>

<script>
import FormEquipo from '../../utils/FormEquipo'

export default {
  props: ["teamUrl","editTeamUrl"],
  components:{
    FormEquipo
  },
  data(){
    return {
      isLoading: true,
      fetchResponse:{
        replied: false,
        ok: false
      },
      response:{
        replied: false,
        ok: false
      },
      user: {},
      team: {
        name: null,
        description: null,
        year: null,
        previous_editions: [],
        locality_id: null,
        locality_other: null,
        parent_organization: null,
        web: null,
        facebook: null,
        telephone: null,
        email: null
      },
    }
  },
   created: function() {
    this.user = this.$store.state.user;
  },
  mounted: function(){
    this.$http.get(this.fetchTeamUrl)
    .then(response => {
      this.team.name = response.data.name
      this.team.description = response.data.description
      this.team.year = response.data.year
      this.team.previous_editions = response.data.previous_editions
      this.team.locality_id = response.data.locality_id
      this.team.locality_other = response.data.locality_other
      this.team.parent_organization = response.data.parent_organization ? response.data.parent_organization : false
      this.team.web = response.data.web
      this.team.facebook = response.data.facebook
      this.team.telephone = response.data.telephone
      this.team.email = response.data.email
      this.fetchResponse.replied = true;
      this.fetchResponse.ok = true;
      this.isLoading = false
    }).catch(error => {
          console.error(error.message);
          this.isLoading = false;
          this.fetchResponse.replied = true;
          this.$snackbar.open({
            message: "Error inesperado. Recarge la pagina.",
            type: "is-danger",
            actionText: "Cerrar"
          });
          return false;
        });
  },
  methods: {
    submit: function() {
      Promise.all([
        this.$refs.formEquipo.validateForm(),
        this.$refs.formEquipo.validateLocalidad(),
        this.$refs.formEquipo.validateOrganizacion(),
        this.$refs.formEquipo.validateLocalidadOrganizacion()
      ])
        .then(values => {
          if (
            values.every(x => {
              return x == true;
            })
          ) {
            console.log("Sending form!");
            this.isLoading = true;
            this.$http
              .post(this.postEditTeamUrl, this.payload)
              .then(response => {
                this.$snackbar.open({
                  message: "Los datos del equipo han sido actualizados",
                  type: "is-success",
                  actionText: "OK"
                });
                this.isLoading = false;
                this.response.ok = true;
                this.forceUpdateState('userPanel')
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
          } else {
            this.$snackbar.open({
              message: "Algunos datos faltan o son incorrectos. Verifíquelos.",
              type: "is-danger",
              actionText: "Cerrar"
            });
          }
        })
        .catch(result => {
          console.error(result);
          this.$snackbar.open({
            message: "Error inesperado.",
            type: "is-danger",
            actionText: "Cerrar"
          });
        });
    }
  },
  computed:{
    fetchTeamUrl: function(){
      return this.teamUrl.replace(':gro',this.user.groups[0].id)
    },
    postEditTeamUrl: function(){
      return this.editTeamUrl.replace(':gro',this.user.groups[0].id)
    },
    payload: function() {
      let load = {
        name: this.team.name,
        description: this.team.description,
        year: this.team.year,
        previous_editions: this.isOptional(this.team.previous_editions),
        locality_id: this.team.locality_id,
        locality_other: this.isOptional(this.team.locality_other),
        web: this.isOptional(this.team.web),
        facebook: this.isOptional(this.team.facebook),
        telephone: this.isOptional(this.team.telephone),
        email: this.isOptional(this.team.email)
      };
      if (this.team.parent_organization != null) {
        load.parent_organization = {
          name: this.team.parent_organization.name,
          topics: this.isOptional(this.team.parent_organization.topics),
          topic_other: this.isOptional(
            this.team.parent_organization.topic_other
          ),
          locality_id: this.team.parent_organization.locality_id,
          locality_other: this.isOptional(
            this.team.parent_organization.locality_other
          ),
          web: this.isOptional(this.team.parent_organization.web),
          facebook: this.isOptional(this.team.parent_organization.facebook),
          telephone: this.isOptional(this.team.parent_organization.telephone),
          email: this.isOptional(this.team.parent_organization.email)
        };
      } else {
        load.parent_organization = null;
      }
      return load;
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
}
</script>

