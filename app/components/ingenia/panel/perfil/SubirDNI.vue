<template>
  <div>
    <h1 class="subtitle is-3">Subir mi DNI</h1>
    <b-message>
      Uno de los requerimientos para ser integrante de un equipo INGENIA o presentar un proyecto INGENIA es el de enviar una imagen o archivo donde se vea la parte delantera y trasera del DNI.
    </b-message>
    <div class="notification" v-show="verifying">
      <i class="fas fa-cog fa-spin"></i>&nbsp;Revisando si enviaste el DNI . . .
    </div>
    <div class="notification is-success" v-show="!this.user.pending_tasks.includes('dni') && !verifying">
      <i class="fas fa-check fa-fw"></i>Tu DNI ha sido enviado y guardado correctamente
    </div>
    <div>
      <form :action="formUrl" ref="formDNI" method="post" enctype="multipart/form-data">
        <div class="field is-grouped">
          <div class="control">
            <a @click.prevent class="button is-medium is-static">
              N° DNI
            </a>
          </div>
          <div class="control is-expanded">
            <input type="string" v-model="dni" name="dni" v-validate="'required|alpha_num'" class="input is-medium" :class="{'is-danger': errors.has('dni')}" placeholder="Ingresa el numero de DNI">
            <span class="help is-danger" v-show="errors.has('dni')">Requerido. Solamente se aceptan numeros</span>
          </div>
        </div>
        <b-message>
          A continuación, por favor, suba una foto o escaneo de la parte posterior y anterior de su DNI
          <br>Maximo: 3MB. Se aceptan .JPG, .JPEG, .PDF, .DOC o .DOCX
        </b-message>
        <b-field class="file is-medium">
          <b-upload v-model="files" name="archivo" accept="image/jpeg" v-validate="'required|size:3072|mimes:application/pdf,invalid/pdf,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document,image/jpeg,image/pjpeg'">
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
    <b-loading :active.sync="isLoading"></b-loading>
  </div>
</template>

<script>
export default {
  props: ["saveUserDniUrl"],
  data() {
    return {
      user: {},
      cargado: false,
      files: [],
      dni: null,
      verifying: true,
      isLoading: false
    };
  },
  created: function() {
    this.user = this.$store.state.user;
  },
  mounted: function() {
    this.forceUpdateState("userPanel")
      .then(user => {
        this.user = this.$store.state.user;
        this.verifying = false;
      })
      .catch(e => {
        this.$snackbar.open({
          message: "Error al verificar el DNI, por favor recargue la página.",
          type: "is-danger",
          actionText: "Cerrar"
        });
      });
  },
  methods: {
    submit: function() {
      this.$validator
        .validateAll()
        .then(result => {
          if (!result) {
            this.$snackbar.open({
              message: "Algunos datos faltan o son incorrectos. Verifíquelos.",
              type: "is-danger",
              actionText: "Cerrar"
            });
            return false;
          }
          this.isLoading = true;
          this.$refs.formDNI.submit();
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
  computed: {
    formUrl: function() {
      return this.saveUserDniUrl.replace(":usr", this.user.id);
    }
  }
};
</script>
