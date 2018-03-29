<template>
  <div>
    <h1 class="subtitle is-3">Subir DNI</h1>
    <p>Uno de los requerimientos para admitir el proyecto INGENIA y para formar parte de un equipo INGENIA es el de subir y validar el DNI de todos los integrantes del equipo.</p>
    <br>
    <div class="notification is-warning" v-show="pendiente">
      <b>¡Gracias por enviar tu DNI!</b> El mismo está pendiente de ser verificado por un administrador de INGENIA.
    </div>
    <div class="notification is-warning" v-show="pendiente">
      <b>DNI Rechazado.</b> Vuelva a subir un formulario valido
    </div>
    <div v-show="!pendiente">
      <b-message>
        A continuación, por favor, suba una foto o escaneo de la parte posterior y anterior de su DNI
        <br>Maximo: 3MB. Se aceptan .jpg o .jepg
      </b-message>
      <form action="" ref="formDNI" method="post" enctype="multipart/form-data">
        <b-field class="file is-medium">
          <b-upload v-model="files" name="documento" accept="image/jpeg" v-validate="'required|size:3072|mimes:image/jpeg'">
            <a class="button is-link is-medium">
              <b-icon icon="upload"></b-icon>
              <span>Click para cargar</span>
            </a>
          </b-upload>
          <span class="file-name" style="max-width: none;">
            {{ files && files.length ? files[0].name : 'Seleccione un archivo para subir...' }}
          </span>
        </b-field>
        <p v-show="errors.has('documento')" class="has-text-danger">Requerido. Debe ser una imagen .JPG de hasta 3MB como máximo.</p>
        <div class="field">
          <div class="control is-clearfix">
            <a @click="submit" type="submit" class="button is-primary is-medium is-pulled-right" :class="{'is-loading': isLoading}">
              <i class="fa fa-paper-plane fa-fw"></i> Enviar</a>
          </div>
        </div>
      </form>
    </div>
    <b-loading :is-full-page="false" :active.sync="isLoading"></b-loading>
  </div>
</template>

<script>
export default {
  data() {
    return {
      pendiente: false,
      rechazado: false,
      verificado: false,
      files: [],
      isLoading: false
    };
  },
  methods: {
    submit: function() {
      this.$validator
        .validateAll()
        .then(result => {
          if (!result) {
            this.$snackbar.open({
              message: "Error en el formulario. Verifíquelo",
              type: "is-danger",
              actionText: "Cerrar"
            });
            return false;
          }
          this.isLoading = true;
          // this.$refs.formDNI.submit();
        })
        .catch(error => {
          this.$snackbar.open({
            message: "Error inesperado",
            type: "is-danger",
            actionText: "Cerrar"
          });
          return false;
        });
    }
  }
};
</script>
