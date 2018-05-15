<template>
  <div>
    <h1 class="subtitle is-3">Subir carta de conformidad</h1>
    <div v-show="!pendiente">
      <b-message>Uno de los requisitos para que tu proyecto sea admitido en INGENIA es que los integrantes de tu equipo firme la carta de conformidad. Una vez que termines de armar el equipo, todos deben firmar la carta. Una vez listo, subí un archivo donde se vea la carta de conformidad firmada por todos los integrantes.
        <br>Tamaño del archivo: 3MB. Se aceptan .JPG, .JPEG, .PDF, .DOC o .DOCX
      </b-message>
      <div class="notification" v-show="verifying">
        <i class="fas fa-cog fa-spin"></i>&nbsp;Revisando si enviaste la carta de conformidad . . .
      </div>
      <div class="notification is-success is-clearfix" v-show="user.groups[0].uploaded_agreement && !verifying">
        <i class="fas fa-check fa-fw"></i>La carta de conformidad ha sido enviada y guardada correctamente
        <a :href="'/group/'+user.groups[0].id+'/agreement'" class="is-pulled-right button is-small is-white">
          <i class="fas fa-download"></i>&nbsp;Descargar</a>
      </div>
      <div v-show="user.groups[0].uploaded_agreement && !verifying">
        <p>
          <i>Al subir de nuevo la carta, se sobreescribe el archivo anterior.</i>
        </p>
        <br>
      </div>
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
        <p v-show="errors.has('archivo')" class="has-text-danger">Requerido. Debe ser un archivo .JPG, .JPEG, .PDF, .DOC o .DOCX de hasta 3MB como máximo.<br></p>
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
  props: ["saveAgreementUrl"],
  data() {
    return {
      pendiente: false,
      rechazado: false,
      verificado: false,
      files: [],
      isLoading: false,
      user: {},
      verifying: true
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
          message: "Error al verificar la carta de conformidad.",
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
