<template>
  <section>
    <div class="has-text-centered">
      <img src="/assets/img/ingenia-logo.svg" class="image is-centered" style="max-width: 250px;">
    </div>
    <br>
    <b-message class="has-text-centered">
      Ingresá aquí todos los datos requeridos sobre el proyecto. Estos datos serán visibles para todos los que vayan a visitar el punto de encuentro del proyecto!
    </b-message>
    <div class="notification is-link">
      <h1 class="title is-1 is-700">
        <i class="far fa-edit fa-fw"></i> Sobre el
        <u>PROYECTO</u>
      </h1>
    </div>
    <form-proyecto ref="formProyecto" :project.sync="project"></form-proyecto>
    <br>
    <button @click="submitProyecto" class="button is-large is-primary is-fullwidth" :class="{'is-loading': isLoading}">
      <i class="fa fa-save"></i>&nbsp;&nbsp;Guardar datos del proyecto</button>
    <b-loading :is-full-page="false" :active.sync="isLoading"></b-loading>
  </section>
</template>

<script>
import FormProyecto from '../../utils/FormProyecto'

export default {
  props: ['saveProjectUrl'],
  components: {
    FormProyecto
  },
  data() {
    return {
      isLoading: false,
      response: {
        replied: false,
        ok: false
      },
      project: {
        name: null,
        abstract: null,
        foundation: null,
        category_id: null,
        previousWork: null,
        locality_id: null,
        locality_other: null,
        neighbourhoods: [],
        goals: [],
        schedule: [],
        budget: [],
        organization: null,
      },
      user: {}
    }
  },
  created: function(){
    this.user = this.$store.state.user
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
    submitProyecto: function() {
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
              .post(this.saveProjectUrl, this.payload)
              .then(response => {
                console.log(response);
                this.$snackbar.open({
                  message: "Datos guardados con éxito",
                  type: "is-success",
                  actionText: "OK"
                });
                this.isLoading = false;
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
    payload: function() {
      let load = {
        name: this.project.name,
        abstract: this.project.abstract,
        foundation: this.project.foundation,
        category_id: this.project.category_id,
        previousWork: this.isOptional(this.project.previousWork),
        locality_id: this.project.locality_id,
        locality_other: this.isOptional(this.project.locality_other),
        neighbourhoods: this.project.neighbourhoods,
        goals: this.project.goals,
        schedule: this.project.schedule,
        budget: this.project.budget,
      };
      if (this.project.organization != null) {
        load.organization = {
          name: this.project.organization.name,
          topics: this.isOptional(this.project.organization.topics),
          topic_other: this.isOptional(
            this.project.organization.topic_other
          ),
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
    console.log('Authorized')
    if (
      vm.$store.state.user.groups[0] !== undefined &&
      vm.$store.state.user.groups[0].pivot.relation === "responsable"
    ) {
      next();
    } else {
      console.log('Unauthorized - Kicking to dashboard!')
      next({ name: "panelOverview" });
    }
  }
};
</script>
