<template>
  <section>
    <div class="notification is-link">
      <h1 class="title is-1 is-700">
        <i class="far fa-edit fa-fw"></i> Sobre el
        <u>PROYECTO</u>
      </h1>
    </div>
    <b-message class="has-text-centered">

    </b-message>
    <form-proyecto ref="formProyecto" :project.sync="project"></form-proyecto>
    <button @click="submitProyecto" class="button is-large is-primary is-fullwidth" :class="{'is-loading': isLoading}">
      <i class="fa fa-save"></i>&nbsp;&nbsp;¡Guardar proyecto!</button>
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
      project: {
        name: null,
        abstract: null,
        foundation: null,
        category_id: null,
        previousWork: null,
        locality: null,
        locality_other: null,
        neighbourhoods: [],
        goals: [],
        schedule: [],
        budget: [],
        organization: {
          name: null,
          topics: [],
          topic_other: null,
          locality: null,
          otherLocality: null,
          web: null,
          facebook: null,
          telephone: null,
          email: null
        },
      }
    }
  },
  methods: {
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
            this.proyecto.presupuestoTotal = this.montoTotal;
            console.log("Sending form!");
            console.log(JSON.stringify(this.proyecto));
            this.isLoading = true;
            this.$snackbar.open({
              message: "¡Datos guardados!",
              type: "is-success",
              actionText: "OK"
            });
          } else {
            this.$snackbar.open({
              message:
                "Algunos datos faltan o son incorrectos. Verifíquelos.",
              type: "is-danger",
              actionText: "Cerrar"
            });
          }
        })
        .catch(result => {
          console.log("result: ");
          console.log(result);
          this.$snackbar.open({
            message: "Error inesperado. Intente mas tarde.",
            type: "is-danger",
            actionText: "Cerrar"
          });
        });
    }
  }
};
</script>
