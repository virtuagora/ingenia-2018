<template>
  <section>
    <h1 class="subtitle is-3">Cambiar fecha de cierre de la convocatoria</h1>
    <p>Aquí pueden modificar la fecha de cierre de la inscripción de los proyectos.<br>Una vez que se pasa esta fecha, se ocultan las opciones de carga de datos personales, cartas, etc.</p>
    <br>
    <b-message type="is-warning">
      Nota: Al modificar este dato, tu sesión se cerrará, tendras que volver a loguearte.
    </b-message>
    <br>
    <div class="columns">
      <div class="column">

        <div class="field">
          <label class="label is-size-4">
            <i class="fas fa-angle-double-right"></i>&nbsp;Día de cierre</label>
          <b-datepicker v-model="theDate" placeholder="Click para elegir..." icon="calendar" size="is-medium">
          </b-datepicker>
        </div>
      </div>
      <div class="column">
        <div class="field">
          <label class="label is-size-4">
            <i class="fas fa-angle-double-right"></i>&nbsp;Hora de cierre</label>
          <b-timepicker v-model="theTime" size="is-medium" placeholder="Click para elegir..." icon="clock"></b-timepicker>
        </div>
      </div>
    </div>
    <div class="buttons">
        <button @click="submit()" class="button is-link is-medium">Guardar</button>
      </div>
  </section>
</template>

<script>
export default {
  props: ["settings"],
  data() {
    return {
      theTime: null,
      theDate: null,
    };
  },
  created:function(){
     this.theTime = new Date(this.settings.deadline),
     this.theDate = new Date(this.settings.deadline)
  },
  methods: {
    submit: function(){
      this.$http.post('/option/deadline',this.payload)
      .then(response => {
        window.location.href = '/logout'
      })
      .catch(x => {
          this.$snackbar.open({
            message: "Error inesperado",
            type: "is-danger",
            actionText: "Cerrar"
          });
          return false;
        });
    }
  },
  computed: {
    payload: function(){
      return {
        value: this.theDate.toISOString().split('T')[0] + ' ' + this.theTime.toTimeString().split(' ')[0]
      }
    }

  }
};
</script>

<style>

</style>
