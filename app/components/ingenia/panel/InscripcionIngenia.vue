<template>
  <section>
    <div class="has-text-centered">
      <img src="/assets/img/ingenia-logo.svg" class="image is-centered" style="max-width: 250px;">
      <br>
      <h1 class="title is-3 is-300 has-text-primary">¡Hola {{user.names}}!</h1>
      <h1 class="subtitle is-4">Formulario de pre-inscripción</h1>
    </div>
    <br>
    <div v-if="!showForm">
    <b-message>
      Desde el Gabinete Joven y la Secretaría de Juventudes recomendamos tener a mano
      <a href="mailto:ingenia@santafe.gob.ar">
        <b>el reglamento</b>
      </a>
      del programa en el momento que se dispongan a completar el presente formulario. Recuerden que pueden contar con nosotros para responder sus dudas y preguntas, así como orientarlos en la tarea de presentar su proyecto. Pueden escribirnos todas las veces que sea necesario a
      <a href="mailto:ingenia@santafe.gob.ar">
        <b>ingenia@santafe.gob.ar</b>
      </a>
    </b-message>
    <section v-if="this.user.pending_tasks.length > 0">
      <p>Previo a presentar tu proyecto INGENIA, debes cumplir los siguientes requerimientos.</p>
      <div class="notification is-warning" v-show="this.user.pending_tasks.includes('email')">
        <a href="#" class="button is-dark is-pulled-right" style="margin-left:20px;margin-bottom:10px;">Actualizar email</a>
        <span class="is-600 is-size-5">
          <i class="fas fa-angle-double-right fa-lg fa-fw"></i> Verificá tu email</span>
        <br>Si te registraste usando Facebook, es importante que registres un email para ser integrante de un equipo INGENIA
      </div>
      <div class="notification is-warning" v-show="this.user.pending_tasks.includes('profile')">
        <div class="is-clearfix">
          <a href="#" class="button is-dark is-pulled-right" style="margin-left:20px">Editar datos</a>
          <span class="is-600 is-size-4">
            <i class="fas fa-angle-double-right fa-lg fa-fw"></i> Completá tus datos personales</span>
        </div>
        <br>Para ser integrante de un equipo INGENIA debes completar tus datos personales y tener entre 15 y 29 años.
        <b>NOTA: </b>Los responsable o co-responsable de un proyecto, deben tener entre 18 y 29 años.
      </div>
      <div class="notification is-warning" v-show="this.user.pending_tasks.includes('dni')">
        <a href="#" class="button is-dark is-pulled-right" style="margin-left:20px;margin-bottom:10px;">Subir DNI</a>
        <span class="is-600 is-size-5">
          <i class="fas fa-angle-double-right fa-lg fa-fw"></i> Carga tu DNI</span>
        <br>Como requisito del reglamento, es fundamental que subas un archivo con tu DNI.
      </div>
    </section>
    <div v-else>
      <div class="notification is-success">
        <i class="fas fa-check fa-fw"></i> ¡Ya te encontras en condiciones para crear tu proyecto INGENIA!
      </div>
      <button @click="showForm = true" v-if="!showForm" class="button is-primary is-fullwidth is-large is-800">
        <i class="fas fa-rocket fa-fw"></i> ¡Comenzar con el formulario!</button>
    </div>
    </div>
    <section v-if="showForm && !response.replied">
      <b-message>
        El primer paso es inscribir los datos del equipo. No te preocupes por los integrantes del mismo, eso lo podrás hacer mas tarde.
      </b-message>
      <div class="notification is-link">
        <h1 class="title is-1 is-700">
          <i class="far fa-edit fa-fw"></i> Sobre el
          <u>EQUIPO</u>
        </h1>
      </div>
      <form-equipo ref="formEquipo" :team.sync="team"></form-equipo>
      <br>
      <button @click="submitEquipo" class="button is-large is-primary is-fullwidth" :class="{'is-loading': isLoading}">
        <i class="fas fa-save"></i>&nbsp;&nbsp;¡Guardar equipo!</button>
    </section>
    <section v-if="showForm && response.replied && response.ok">
      <div class="notification is-success">
          <i class="fas fa-check fa-fw"></i> ¡Tu equipo ha sido guardado! ¡Ahora podes ingresar los datos de tu proyecto INGENIA e invitar a mas personas a tu equipo!
      </div>
      <h1 class="title is-3">No te olvides de lo siguiente...</h1>
      <div class="content">
        <p>
          <i class="fas fa-arrow-right fa-fw fa-lg"></i> Completá el formulario de tu proyecto Ingenia para que tu proyecto se vea en la plataforma. Podes editarlo hasta que envies el formulario para su admisión antes de la fecha de cierre.</p>
        <p>
          <i class="fas fa-arrow-right fa-fw fa-lg"></i> Invitá al co-responsable de tu proyecto INGENIA enviandole una invitación a ser parte del equipo. <b>Es requisito que tu proyecto tenga un co-responsable asignado.</b></p>
        <p>
          <i class="fas fa-arrow-right fa-fw fa-lg"></i> Invitá a otros miembros a tu equipo enviandoles invitaciones por email. ¡Recordales que completen sus datos para poder aceptar la invitación!</p>
        <p>
          <i class="fas fa-arrow-right fa-fw fa-lg"></i> ¿Tu proyecto se hace en conjunto con alguna institución u organización? Descargá la
          <a href="">carta de aval</a> que debe ir firmada para subirla más tarde.</p>
        <p>
          <i class="fas fa-arrow-right fa-fw fa-lg"></i> Asegurate de imprimir y hacer firmar la
          <a href="">carta de conformidad del equipo</a> que debe ir firmada por todos para subirla más tarde.</p>
        <p>
          <i class="fas fa-arrow-right fa-fw fa-lg"></i> Completá tu perfil público para que todos sepan quien sos (Ayudas a que la gente quiera ayudarte)</p>
      </div>
      <h1 class="subtitle is-5 is-spaced">Tenes tiempo de hacer modificaciones, invitar gente a tu equipo, cumplir con otros requerimientos (Documentacion, sumar gente a tu equipo, etc.)
        <u>hasta la fecha de cierre</u>
      </h1>
      <h1 class="title is-4 is-700 has-text-link">No te olvides que cuando termines de completar todos los datos, antes de la fecha de cierre, <u>debes enviar el proyecto a evaluación</u></h1>
    </section>
    <b-loading :active.sync="isLoading"></b-loading>
  </section>
</template>

<script>
import FormEquipo from "../utils/FormEquipo";
import FormProyecto from "../utils/FormProyecto";

export default {
  props: ["saveTeamUrl"],
  components: {
    FormProyecto,
    FormEquipo
  },
  data() {
    return {
      showForm: false,
      isLoading: false,
      response: {
        replied: false,
        ok: false
      },
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
      user: {}
    };
  },
  created: function() {
    this.user = this.$store.state.user;
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
    submitEquipo: function() {
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
                  message: "¡Inscripción realizada!",
                  type: "is-success",
                  actionText: "OK"
                });
                this.$store.commit('updateUser')
                this.isLoading = false;
                this.response.replied = true;
                this.response.ok = true;
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
  }
};
</script>
