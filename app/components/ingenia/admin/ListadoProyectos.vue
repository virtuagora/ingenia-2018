<template>
  <section>
     <h1 class="subtitle is-3">Listado de proyectos</h1>
    <table class="table is-narrow is-fullwidth">
      <thead>
        <tr>
          <th><i class="fa fa-hashtag"></i></th>
          <th>Nombre</th>
          <th>Equipo</th>
          <th>Estado</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="project in projects" :key="project.id">
          <td>{{project.id}}</td>
          <td>{{project.name}}</td>
          <td>{{project.group.name}}</td>
          <td>{{project.group.name}}</td>
        </tr>
      </tbody>
    </table>
    <b-loading :active.sync="isLoading"></b-loading>
  </section>
</template>

<script>
export default {
  props: ['getProjects'],
  data(){
    return {
      isLoading: false,
      projects: []
    }
  },
  mounted: function(){
    this.isLoading = true;
    this.$http.get(this.getProjects)
    .then(response => {
      this.isLoading = false
      this.projects = response.data.data
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
}
</script>

<style>

</style>
