<template>
  <div>
    <h1 class="subtitle is-3">Subir mi DNI</h1>
     <b-message>
      Uno de los requerimientos para ser integrante de un equipo INGENIA o presentar un proyecto INGENIA es el de enviar una imagen o archivo donde se vea la parte delantera y trasera del DNI.
    </b-message>
    <div class="notification is-success"  v-show="!this.user.pending_tasks.includes('dni')">
      <i class="fas fa-check fa-fw"></i>Tu DNI ha sido enviado y guardado correctamente
    </div>
    <div>
      <form :action="saveUserDniUrl" ref="formDNI" method="post" enctype="multipart/form-data">
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
        <br>Maximo: 3MB. Se aceptan .jpg o .jepg
      </b-message>
        <b-field class="file is-medium">
          <b-upload v-model="files" name="archivo" accept="image/jpeg" v-validate="'required|size:3072|mimes:image/jpeg'">
            <a class="button is-link is-medium">
              <b-icon icon="upload"></b-icon>
              <span>Click para cargar</span>
            </a>
          </b-upload>
          <span class="file-name" style="max-width: none;">
            {{ files && files.length ? files[0].name : 'Seleccione un archivo para subir...' }}
          </span>
        </b-field>
        <p v-show="errors.has('archivo')" class="has-text-danger">Requerido. Debe ser una imagen .JPG de hasta 3MB como máximo.</p>
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
  props: ['saveUserDniUrl'],
  data() {
    return {
      user: {
      },
      cargado: false,
      files: [],
      dni: null,
      isLoading: false
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
  }
};
</script>
