<template>
  <section>
    <div class="has-text-centered">
      <img src="/assets/img/ingenia-logo.svg" class="image is-centered" style="max-width: 250px;">
      <br>
      <h1 class="title is-3 is-300 has-text-primary">¡Bienvenidxs! </h1>
      <h1 class="subtitle is-4">Formulario de presentación de proyectos</h1>
    </div>
    <br>
    <article class="message is-link">
      <div class="message-body has-text-centered">
        Desde el Gabinete Joven y la Secretaría de Juventudes recomendamos tener a mano
        <a href="mailto:ingenia@santafe.gob.ar">
          <b>el reglamento</b>
        </a>
        del programa en el momento que se dispongan a completar el presente formulario. Recuerden que pueden contar con nosotros para responder sus dudas y preguntas, así como orientarlos en la tarea de presentar su proyecto. Pueden escribirnos todas las veces que sea necesario a
        <a href="mailto:ingenia@santafe.gob.ar">
          <b>ingenia@santafe.gob.ar</b>
        </a>
      </div>
    </article>
    <br>
    <button @click="showForm = true" v-show="!showForm" class="button is-primary is-fullwidth is-large is-800">
      <i class="fas fa-rocket fa-fw"></i> ¡Comenzar con el formulario!</button>
    <section v-if="showForm">
      <div class="notification is-link">
        <h1 class="title is-1 is-700">
          <i class="far fa-edit fa-fw"></i> Sobre el
          <u>EQUIPO</u>
        </h1>
      </div>
      <form-equipo ref="formEquipo" :equipo.sync="equipo"></form-equipo>
      <div class="notification is-link">
        <h1 class="title is-1 is-700">
          <i class="far fa-edit fa-fw"></i> Sobre el
          <u>PROYECTO</u>
        </h1>
      </div>
      <form-proyecto ref="formProyecto" :proyecto.sync="proyecto"></form-proyecto>
      <b-message class="has-text-centered">
        Al ingresar tu formulario, tu proyecto será agregado al catálogo.
        <i class="em em-smiley"></i> ¡Podras gestionar tu equipo, enviar invitaciones a los miembros para que se registren, subir los documentos faltantes, y hacer cualquier cambio que quieras al proyecto a medida que la convocatoria se mantenga abierta!
      </b-message>
      <button @click="submitForm" class="button is-large is-primary is-fullwidth" :class="{'is-loading': isLoading}">
        <i class="fa fa-paper-plane"></i>&nbsp;&nbsp;¡Guardar formulario y comenzar!</button>
    </section>
    <b-loading :is-full-page="false" :active.sync="isLoading"></b-loading>
  </section>
</template>

<script>
import FormEquipo from "../../utils/FormEquipo";
import FormProyecto from "../../utils/FormProyecto";

export default {
  components: {
    FormProyecto,
    FormEquipo
  },
  data() {
    return {
      showForm: true,
      isLoading: false,
      proyecto: {
        nombre: null,
        resumen: null,
        fundamentacion: null,
        tematica: null,
        enEjecucion: null,
        descripcionEjecucion: null,
        localidad: null,
        otraLocalidad: null,
        barrios: [],
        objetivos: [],
        actividades: [],
        presupuesto: [],
        presupuestoTotal: 0,
        conOrganizacion: null,
        organizacion: {
          nombre: null,
          tematicas: [],
          otraTematica: null,
          localidad: null,
          otraLocalidad: null,
          contacto: {
            web: null,
            facebook: null,
            email: null,
            telefono: null
          }
        }
      },
      equipo: {
        nombre: null,
        descripcion: null,
        localidad: null,
        otraLocalidad: null,
        fundacion: null,
        deUnaOrganizacion: null,
        organizacion: {
          nombre: null,
          tematicas: [],
          otraTematica: null,
          localidad: null,
          otraLocalidad: null,
          contacto: {
            web: null,
            facebook: null,
            email: null,
            telefono: null
          }
        }
      }
    };
  },
  methods: {
    submitForm: function() {
      Promise.all([
        this.$refs.formEquipo.validateForm(),
        this.$refs.formEquipo.validateLocalidad(),
        this.$refs.formEquipo.validateOrganizacion(),
        this.$refs.formEquipo.validateLocalidadOrganizacion(),
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
              message: "Formulario enviado!",
              type: "is-success",
              actionText: "OK"
            });
          } else {
            this.$snackbar.open({
              message:
                "Faltan algunos datos o son incorrectos. Verifique el formulario.",
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
