<template>
  <div>
     <h1 class="subtitle is-3">Otras opciones</h1>
     <hr>
      <!-- <span class="tag is-primary">PRÓXIMAMENTE</span>  -->
      <h1 class="title is-1">Eliminar participación</h1>
    <div class="content">
      <p>Si queres darte de baja de Ingenia, borrando tu proyecto y tu equipo, podes eliminar tu proyecto cuando quieras. <b>ATENCIÓN:</b> No hay forma de revertir esta acción. Una vez que elimina su equipo y su proyecto, las siguientes cosas ocurren:</p>
      <ul>
        <li>Todos los integrantes del equipo se desvinculan. Todos (e incluyéndote) podran participar de otros equipos.</li>
        <li>Se elimina el proyecto, borrando toda información asociada con al mismo, como comentarios, cantidad de bancadas, y demas, se eliminan.</li>
        </ul>  
    </div>
    <b-message>
      <b>Atención:</b> Al borrar todo, su sesión se va a cerrar. Debera volver a loguearse.
    </b-message>
    <!-- <div class="field" v-show="showDelete">
          <label class="label is-size-5" :class="{'has-text-danger': errors.has('profile.address')}">
            <i class="fas fa-angle-double-right"></i> Ingresá tu contraseña para confirmar *</label>
          <div class="control">
            <input v-model="password" data-vv-name="password" data-vv-as="'Contraseña'" type="password" v-validate="'required'" class="input is-medium" placeholder="(Requerido)">
            <span v-show="errors.has('password')" class="help is-danger">
              <i class="fas fa-times-circle fa-fw"></i>&nbsp;{{errors.first('password')}}</span>
          </div>
        </div> -->
    <a @click="showDelete = true" class="button is-warning is-fullwidth is-medium" v-show="!showDelete"><i class="fas fa-trash fa-fw"></i>&nbsp;Eliminar participación</a>
    <a @click="deleteNAOW" class="button is-danger is-fullwidth is-medium" :class="{'is-loading': this.isLoading}" v-show="showDelete"><i class="fas fa-exclamation-triangle fa-fw"></i>&nbsp;<i class="fas fa-trash fa-fw"></i>&nbsp;¿Confirma que quiere eliminar TODO?</a>
  </div>
</template>

<script>
export default {
  props: ['deleteProjectUrl'],
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
      return this.deleteProjectUrl.replace(':pro', this.user.groups[0].project.id)
    }
  }
}
</script>

<style>

</style>
