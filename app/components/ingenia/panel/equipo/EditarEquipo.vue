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
    <form-proyecto ref="formProyecto" :project.sync="project"></form-proyecto>
    <br>
    <button @click="submitProyecto" class="button is-large is-primary is-fullwidth" :class="{'is-loading': isLoading}">
      <i class="fa fa-save"></i>&nbsp;&nbsp;Guardar datos del proyecto</button>
    <b-loading :is-full-page="false" :active.sync="isLoading"></b-loading>
  </section>
  </div>

</template>
