<template>
  <div>
     <h1 class="subtitle is-3">Otras opciones</h1>
     <hr>
      <h1 class="title is-1">Eliminar participación</h1>
    <div class="content">
      <p>Si queres dar de baja tu inscripcion a Ingenia, borrando tú equipo y/o proyecto, podes hacerlo cuando quieras antes de que termine la convocatória. <b>ATENCIÓN:</b> No hay forma de revertir esta acción. Una vez que elimina su equipo y su proyecto, las siguientes cosas ocurren:</p>
      <ul>
        <li>Todos los integrantes del equipo se desvinculan. Todos (e incluyéndote) podran participar de otros equipos.</li>
        <li>Se elimina el proyecto, borrando toda información asociada con al mismo, como comentarios, cantidad de bancadas, y demas, se eliminan.</li>
        </ul>  
    </div>
    <b-message>
      <b>Atención:</b> Al borrar todo, su sesión se va a cerrar. Deberá volver a loguearse.
    </b-message>
    <a @click="showDelete = true" class="button is-warning is-fullwidth is-medium" v-show="!showDelete"><i class="fas fa-trash fa-fw"></i>&nbsp;Eliminar participación</a>
    <a @click="deleteNAOW" class="button is-danger is-fullwidth is-medium" :class="{'is-loading': this.isLoading}" v-show="showDelete"><i class="fas fa-exclamation-triangle fa-fw"></i>&nbsp;<i class="fas fa-trash fa-fw"></i>&nbsp;¿Confirma que quiere eliminar TODO?</a>
  </div>
</template>

<script>
export default {
  props: ['deleteGroupUrl'],
  data(){
    return {
      showDelete: false,
      isLoading: false,
      user: {}
    }
  },
created: function() {
    this.user = this.$store.state.user;
  },
  methods: {
    deleteNAOW: function(){
      this.$http.delete(this.urlDelete)
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
    urlDelete: function(){
      return this.deleteGroupUrl.replace(':gro', this.user.groups[0].id)
    }
  }
}
</script>

<style>

</style>
