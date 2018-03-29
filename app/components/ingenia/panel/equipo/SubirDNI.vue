<template>
  <div>
    <h1 class="subtitle is-3">Subir DNI</h1>
    <p>Uno de los requerimientos para admitir el proyecto INGENIA y para formar parte de un equipo INGENIA es el de subir y validar el DNI de todos los integrantes del equipo.</p>
    <br>
    <div class="notification is-warning">
      <b>¡Gracias por enviar tu DNI!</b> El mismo está pendiente de ser verificado por un administrador de INGENIA.
    </div>
    <b-message>
      A continuación, por favor, suba una foto o escaneo de la parte posterior y anterior de su DNI
    </b-message>
    <form action="" method="post" enctype="multipart/form-data">
      <b-field class="file is-medium">
        <b-upload v-model="files" name="documento">
          <a class="button is-link is-medium">
            <b-icon icon="upload"></b-icon>
            <span>Click para cargar</span>
          </a>
        </b-upload>
        <span class="file-name" v-if="files && files.length">
          {{ files[0].name }}
        </span>
      </b-field>
      <div class="field">
        <div class="control is-clearfix">
          <button @click.prevent="isLoading = true" type="submit" class="button is-primary is-medium is-pulled-right" :class="{'is-loading': isLoading}">
            <i class="fa fa-paper-plane fa-fw"></i> Enviar</button>
        </div>
      </div>
    </form>
    <b-loading :is-full-page="false" :active.sync="isLoading"></b-loading>
  </div>
</template>

<script>
export default {
  data() {
    return {
      files: [],
      isLoading: false,

    };
  }
};
</script>
