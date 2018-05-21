<template>
  <section>
     <h1 class="subtitle is-3">Descargar matriz de proyectos</h1>
     <b-message>
       Aquí podrá descargar la matriz de los proyectos. (Se actualiza cada 4 horas desde la ultima vez que descargo)
       <br><b>Cuidado con pasarse el archivo, pueden llegar a tener inconsistencia en los datos.</b>
     </b-message>
     <div class="buttons">
       <a class="button is-link is-medium" href="/admin/matriz/proyectos" target="_blank"><i class="fas fa-download fa-fw"></i>&nbsp;Matriz de PROYECTOS</a>
       <a class="button is-link is-medium" href="/admin/matriz/equipos" target="_blank"><i class="fas fa-download fa-fw"></i>&nbsp;Matriz de EQUIPOS</a>
     </div>
  </section>
</template>

<script>
export default {

}
</script>

<style>

</style>
