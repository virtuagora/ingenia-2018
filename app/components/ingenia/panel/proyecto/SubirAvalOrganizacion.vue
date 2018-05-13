<template>
  <div>
    <h1 class="subtitle is-3">Subir aval de organización</h1>
    <p>De acuerdo a los datos ingresados, el proyecto se realiza en coordinación con otra institución y/o organización, debera adjuntar la carta aval.</p>
    <p>Como requerimiento, debe subir la carta aval firmada por la institución u organización</p>
    <br>
    <div v-show="!pendiente">
      <b-message>
        En el siguiente campo subí un archivo donde se vea la carta de aval firmada por la organización.
        <br>Tamaño del archivo: 3MB. Se aceptan .JPG, .JPEG, .PDF, .DOC o .DOCX
      </b-message>
    <div class="notification" v-show="verifying">
      <i class="fas fa-cog fa-spin"></i>&nbsp;Revisando si enviaste la carta de conformidad . . .
    </div>
    <div class="notification is-success" v-show="user.groups[0].uploaded_letter && !verifying">
      <i class="fas fa-check fa-fw"></i>La carta de aval ha sido enviada y guardada correctamente
    </div>
      <form :action="formUrl" ref="formAval" method="post" enctype="multipart/form-data">
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
  props: ['saveLetterUrl'],
  data() {
    return {
      pendiente: false,
      rechazado: false,
      verificado: false,
      files: [],
      isLoading: false,
      user: {},
      verifying: true,
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
          message: "Error al verificar la carta de aval.",
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
              message: "Error en el formulario. Verifíquelo",
              type: "is-danger",
              actionText: "Cerrar"
            });
            return false;
          }
          this.isLoading = true;
          this.$refs.formAval.submit();
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
  computed:{
    formUrl: function(){
      return this.saveLetterUrl.replace(':gro',this.user.groups[0].id)
    }
  },
  beforeRouteEnter(to, from, next) {
    next(vm => {
      if (
        vm.user.groups[0] !== undefined &&
        vm.user.groups[0].pivot.relation === "responsable"
      ) {
        console.log("Authorized");
      } else {
        console.log("Unauthorized - Kicking to dashboard!");
        next({ name: "panelOverview" });
      }
    });
  }
};
</script>
