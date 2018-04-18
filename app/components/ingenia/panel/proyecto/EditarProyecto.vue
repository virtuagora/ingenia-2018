<template>
  <div>
            <div class="tabs">
  <ul>
    <li :class="{'is-active': $route.name == 'userVerProyecto'}" v-if="user.groups[0].project !== null"><router-link :to="{ name: 'userVerProyecto'}">Ver proyecto</router-link></li>
    <li :class="{'is-active': $route.name == 'userEditarProyecto'}" v-if="user.groups[0].pivot.relation == 'responsable' && user.groups[0].project !== null"><router-link :to="{ name: 'userEditarProyecto'}">Editar proyecto</router-link></li>
    <li :class="{'is-active': $route.name == 'userSubirImagen'}" v-if="user.groups[0].pivot.relation == 'responsable' && user.groups[0].project !== null"><router-link :to="{ name: 'userSubirImagen'}">Subir imagen del proyecto</router-link></li>
  </ul>
</div>
      <div class="has-text-centered">
        <img src="/assets/img/ingenia-logo.svg" class="image is-centered" style="max-width: 250px;">
      </div>
      <br>
      <b-message class="has-text-centered">
        Ingresá aquí todos los datos requeridos sobre el proyecto.
        <br>¡Estos datos serán visibles para todos los que visiten el punto de encuentro del proyecto! Podes editarlo cuantas veces necesites, hasta el cierre de la convocatoria.
      </b-message>
    <section v-if="!editMode || (editMode && fetchResponse.replied && fetchResponse.ok)">
      <div class="notification is-link">
        <h1 class="title is-2 is-700">
          <i class="far fa-edit fa-fw"></i> Sobre el
          <u>PROYECTO</u>
        </h1>
      </div>
      <form-proyecto ref="formProyecto" :project.sync="project"></form-proyecto>
      <br>
      <div class="notification is-success" v-show="response.ok">
        <i class="fas fa-check fa-fw"></i> Datos enviados y guardados con éxito
      </div>
      <button @click="submit" v-show="!response.ok" class="button is-large is-primary is-fullwidth" :class="{'is-loading': isLoading}">
        <i class="fas fa-save"></i>&nbsp;&nbsp;Guardar</button>
      <b-loading :active.sync="isLoading"></b-loading>
    </section>
    <section v-if="editMode && fetchResponse.replied && !fetchResponse.ok">
      <div class="notification is-danger">
        <i class="fas fa-times fa-fw"></i> Error al conseguir los datos del proyecto. Vuelva a cargar la página
      </div>
    </section>
    <b-loading :active.sync="isLoading"></b-loading>
  </div>
</template>

<script>
import FormProyecto from "../../utils/FormProyecto";

export default {
  props: ["projectUrl", "saveProjectUrl", "editProjectUrl"],
  components: {
    FormProyecto
  },
  data() {
    return {
      isLoading: false,
      fetchResponse: {
        replied: false,
        ok: false
      },
      response: {
        replied: false,
        ok: false
      },
      project: {
        name: null,
        abstract: null,
        foundation: null,
        category_id: null,
        previous_work: null,
        locality_id: null,
        locality_other: null,
        neighbourhoods: [],
        goals: [],
        schedule: [],
        budget: [],
        organization: null
      },
      user: {},
      editMode: false
    };
  },
  created: function() {
    this.user = this.$store.state.user;
    if (this.user.groups[0].project !== null) {
      this.editMode = true;
      this.isLoading = true;
      this.$http
        .get(this.fetchProjectUrl)
        .then(response => {
          this.project.name = response.data.name;
          this.project.abstract = response.data.abstract;
          this.project.foundation = response.data.foundation;
          this.project.category_id = response.data.category.id;
          this.project.previous_work = response.data.previous_work;
          this.project.locality_id = response.data.locality_id;
          this.project.locality_other = response.data.locality_other;
          this.project.neighbourhoods = response.data.neighbourhoods;
          this.project.goals = response.data.goals;
          this.project.schedule = response.data.schedule;
          this.project.budget = response.data.budget;
          this.project.organization = response.data.organization ? response.data.organization : false
          this.fetchResponse.replied = true;
          this.fetchResponse.ok = true;
          this.isLoading = false;
        })
        .catch(error => {
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
    }
  },
  methods: {
    isOptional: function(value) {
      if (value === null || value === "") {
        return null;
      }
      if (typeof value !== "undefined" && value.length == 0) {
        return [];
      } else return value;
    },
    submit: function() {
      Promise.all([
        this.$refs.formProyecto.validateForm(),
        this.$refs.formProyecto.validateLocalidad(),
        this.$refs.formProyecto.validateOrganizacion(),
        this.$refs.formProyecto.validateLocalidadOrganizacion()
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
              .post(this.urlPost, this.payload)
              .then(response => {
                console.log(response);
                this.$snackbar.open({
                  message:
                    "Proyecto " +
                    (this.editMode ? "editado" : "creado") +
                    " con éxito",
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
  computed: {
    urlPost: function() {
      if (this.editMode) {
        return this.editProjectUrl.replace(
          ":pro",
          this.user.groups[0].project.id
        );
      } else {
        return this.saveProjectUrl;
      }
    },
    fetchProjectUrl: function() {
      return this.projectUrl.replace(":pro", this.user.groups[0].project.id);
    },
    payload: function() {
      let load = {
        name: this.project.name,
        abstract: this.project.abstract,
        foundation: this.project.foundation,
        category_id: this.project.category_id,
        previous_work: this.isOptional(this.project.previous_work),
        locality_id: this.project.locality_id,
        locality_other: this.isOptional(this.project.locality_other),
        neighbourhoods: this.project.neighbourhoods,
        goals: this.project.goals,
        schedule: this.project.schedule,
        budget: this.project.budget
      };
      if (this.project.organization != null) {
        load.organization = {
          name: this.project.organization.name,
          topics: this.isOptional(this.project.organization.topics),
          topic_other: this.isOptional(this.project.organization.topic_other),
          locality_id: this.project.organization.locality_id,
          locality_other: this.isOptional(
            this.project.organization.locality_other
          ),
          web: this.isOptional(this.project.organization.web),
          facebook: this.isOptional(this.project.organization.facebook),
          telephone: this.isOptional(this.project.organization.telephone),
          email: this.isOptional(this.project.organization.email)
        };
      } else {
        load.organization = null;
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
};
</script>
