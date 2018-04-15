<template>
  <div>
    <section>
      <div class="has-text-centered">
        <img src="/assets/img/ingenia-logo.svg" class="image is-centered" style="max-width: 250px;">
      </div>
      <br>
      <b-message class="has-text-centered">
        Acá podes editar los datos de tu equipo. Podes cambiarlos hasta que envies tu proyecto.
      </b-message>
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
      <b-loading :active.sync="isLoading"></b-loading>
    </section>
  </div>
</template>

<script>
import FormEquipo from '../../utils/FormEquipo'

export default {
  props: ["saveTeamUrl"],
  components:{
    FormEquipo
  },
  data(){
    return {
      isLoading: false,
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
              .post(this.saveTeamUrl, this.payload)
              .then(response => {
                this.$snackbar.open({
                  message: "Los datos del equipo han sido actualizados",
                  type: "is-success",
                  actionText: "OK"
                });
                this.isLoading = false;
                this.response.ok = true;
                this.$store.commit('updateUser')
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

