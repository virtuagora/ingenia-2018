<template>
  <div>
    <h1 class="subtitle is-3">Subir carta de conformidad</h1>
    <div class="notification is-warning" v-show="pendiente">
      <b>¡Gracias por enviar tu DNI!</b> El mismo está pendiente de ser verificado por un administrador de INGENIA.
    </div>
    <div class="notification is-warning" v-show="pendiente">
      <b>DNI Rechazado.</b> Vuelva a subir un formulario valido
    </div>
    <div v-show="!pendiente">
      <b-message>Uno de los requisitos para que tu proyecto sea admitido en INGENIA es que los integrantes de tu equipo INGENIA firmen la carta de conformidad. Una vez que termines de armar tu equipo, hacé que firmen todos la carta. Cuando lo tengas listo, en el siguiente campo subí un archivo donde se vea la carta de conformidad firmada por todos los integrantes.
        <br>Tamaño del archivo: 3MB. Se aceptan .JPG, .JPEG, .PDF, .DOC o .DOCX
      </b-message>
      <form :action="saveAgreementUrl.replace(':gro',this.user.groups[0].id)" ref="formConformidad" method="post" enctype="multipart/form-data">
        <b-field class="file is-medium">
          <b-upload v-model="files" name="archivo" v-validate="'required|size:3072|mimes:application/pdf,invalid/pdf,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document,image/jpeg,image/pjpeg'">
            <a class="button is-link is-medium">
              <b-icon icon="upload"></b-icon>
              <span>Click para cargar</span>
            </a>
          </b-upload>
          <span class="file-name" style="max-width: none;">
            {{ files && files.length ? files[0].name : 'Seleccione un archivo para subir...' }}
          </span>
        </b-field>
        <p v-show="errors.has('archivo')" class="has-text-danger">Requerido. Debe ser un archivo .JPG, .JPEG, .PDF, .DOC o .DOCX de hasta 3MB como máximo.</p>
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
  props:['saveAgreementUrl'],
  data() {
    return {
      pendiente: false,
      rechazado: false,
      verificado: false,
      files: [],
      isLoading: false,
      user: {}
    };
  },
  created: function() {
    this.user = this.$store.state.user;
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
          this.$refs.formConformidad.submit();
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
  },
  beforeRouteEnter(to, from, next) {
    console.log('Authorized')
    if (
      vm.$store.state.user.groups[0] !== undefined &&
      vm.$store.state.user.groups[0].pivot.relation === "responsable"
    ) {
      next();
    } else {
      console.log('Unauthorized - Kicking to dashboard!')
      next({ name: "panelOverview" });
    }
  }
};
</script>
