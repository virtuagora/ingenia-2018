<template>
  <div>
            <div class="tabs">
  <ul>
    <li :class="{'is-active': $route.name == 'userVerProyecto'}" v-if="user.groups[0].project !== null"><router-link :to="{ name: 'userVerProyecto'}">Ver proyecto</router-link></li>
    <li :class="{'is-active': $route.name == 'userEditarProyecto'}" v-if="user.groups[0].pivot.relation == 'responsable' && user.groups[0].project !== null"><router-link :to="{ name: 'userEditarProyecto'}">Editar proyecto</router-link></li>
    <li :class="{'is-active': $route.name == 'userSubirImagen'}" v-if="user.groups[0].pivot.relation == 'responsable' && user.groups[0].project !== null"><router-link :to="{ name: 'userSubirImagen'}">Subir imagen del proyecto</router-link></li>
  </ul>
</div>
    <h1 class="subtitle is-3">Subir imagen del proyecto</h1>
    <div v-show="!pendiente">
      <b-message>
        Subí una imagen relacionada al proyecto para decorar el punto de encuentro de tu proyecto.
        <br>Tamaño del archivo: 3MB. Se aceptan .JPG, .JPEG, PNG
        <br><b>NOTA: Se recomiendan que las dimensiones sean 1000x777 (De no se así se ajustaran las dimensionaes)</b>
      </b-message>
      <form :action="formUrl" ref="formProImg" method="post" enctype="multipart/form-data">
        <b-field class="file is-medium">
          <b-upload v-model="files" name="archivo" v-validate="'required|size:3072|mimes:image/jpeg,image/pjpeg,image/png'">
            <a class="button is-link is-medium">
              <b-icon icon="upload"></b-icon>
              <span>Click para cargar</span>
            </a>
          </b-upload>
          <span class="file-name" style="max-width: none;">
            {{ files && files.length ? files[0].name : 'Seleccione un archivo para subir...' }}
          </span>
        </b-field>
        <p v-show="errors.has('archivo')" class="has-text-danger">Requerido. Debe ser un archivo .JPG, .JPEG, .PNG de hasta 3MB como máximo.</p>
        <div class="field">
          <div class="control is-clearfix">
            <a @click="submit" type="submit" class="button is-primary is-medium is-pulled-right" :class="{'is-loading': isLoading}">
              <i class="fa fa-paper-plane fa-fw"></i> Enviar</a>
          </div>
        </div>
      </form>
      <hr>
      <img :src="formUrl" alt="">
    </div>
    <b-loading :is-full-page="false" :active.sync="isLoading"></b-loading>
  </div>
</template>

<script>
export default {
  props: ['saveImageUrl'],
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
          this.$refs.formProImg.submit();
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
      return this.saveImageUrl.replace(':pro',this.user.groups[0].project.id)
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
saveImageUrl