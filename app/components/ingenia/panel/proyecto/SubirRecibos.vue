<template>
  <div>
    <h1 class="subtitle is-3">Recibos / Comprobantes de compra</h1>
    <p>Aquí vas a poder presentar los comprobantes de las compras hechas para la realización del proyecto. Recorda que este paso es requerido para cuando finalice el proyecto.</p>
    <br>
    <div class="notification is-warning">
      <b>Nota:</b> Aun asi, recordá guardar los comprobantes originales.
    </div>
    <div class="card" v-if="!project.budget_sent">
      <div class="card-content">
        <h1 class="subtitle is-4"><i class="fa fa-plus"></i> Subir nuevo recibo</h1>
        <form :action="formUrl" ref="formReceipt" method="post" enctype="multipart/form-data">
          <hr>
          <div class="field">
            <label class="label">Fecha del comprobante</label>
          <b-datepicker placeholder="Hace clic para seleccionar la fecha" v-model="inputReciboFecha" :mobile-native="false" :date-formatter="(date) => date.toLocaleDateString('es-AR')" icon="calendar-alt">
            </b-datepicker>
            <span v-show="errors.has('inputReciboFecha')" class="help is-danger">
              <i class="fas fa-times-circle fa-fw"></i>&nbsp;{{errors.first('inputReciboFecha')}}</span>
            <input type="hidden" v-model="fechaFinal" v-validate="'required'" name="fecha">
          </div>
          <div class="field">
            <label class="label">Detalle</label>
            <div class="control">
              <input class="input" name="detalle" type="text" v-validate="'required'" placeholder="Escribi el detalle de la compra (Ej: Productos y/o lugar donde lo compraste">
              <div class="help">Si son varios productos, listalos en el campo.</div>
            <span v-show="errors.has('detalle')" class="help is-danger"><i class="fas fa-times-circle fa-fw"></i>&nbsp;{{errors.first('detalle')}}</span>
            </div>
          </div>
          <div class="field">
            <label class="label">Monto</label>
            <div class="control">
              <input class="input" name="monto" v-validate="'required|numeric'" type="text" placeholder="Monto en AR$">
          <span v-if="errors.has('monto')" class="help is-danger">
            <i class="fas fa-times-circle fa-fw"></i>&nbsp;{{errors.first('monto')}}</span>
          <span v-else class="help">Ingrese números sin decimal, puntos o comas</span>
            </div>
          </div>
          <b>Foto/Archivo del comprobante</b>
          <p>Requerido. Debe ser un archivo .JPG, .JPEG, .PDF, .DOC o .DOCX de hasta 3MB como máximo.</p>
          <br>
          <b-field class="file">
            <b-upload v-model="files" name="archivo" v-validate="'required|size:3072|mimes:application/pdf,invalid/pdf,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document,image/jpeg,image/pjpeg'">
              <a class="button is-link">
                <b-icon icon="upload"></b-icon>
                <span>Click para cargar</span>
              </a>
            </b-upload>
            <span class="file-name" style="max-width: none;">
              {{ files && files.length ? files[0].name : 'Seleccione un archivo para subir...' }}
            </span>
          </b-field>
          <p v-show="errors.has('archivo')" class="has-text-danger is-size-7">Por favor, comproba que el archivo cumpla con las condiciones; Es requerido. Debe ser un archivo .JPG, .JPEG, .PDF, .DOC o .DOCX de hasta 3MB como máximo.</p>
          <hr>
          <div class="field">
            <div class="control is-clearfix">
              <a @click="submit" type="submit" class="button is-info is-pulled-right" :class="{'is-loading': isLoading}">
                <i class="fa fa-paper-plane fa-fw"></i>&nbsp;Enviar</a>
            </div>
          </div>
        </form>
      </div>
    </div>
    <br>
    <table class="table is-fullwidth">
      <thead>
        <th width="130px">Fecha compra</th>
        <th>Detalle</th>
        <th class="has-text-centered" width="100px">Monto</th>
        <th class="has-text-centered" width="120px" v-if="!project.budget_sent">Acción</th>
      </thead>
      <tbody>
        <tr v-for="recibo in recibos" :key="recibo.id">
          <td>
            {{recibo.date.split(' ')[0]}}
          </td>
          <td class="is-size-7">{{recibo.detail}}</td>
          <td class="has-text-centered">$ {{recibo.amount.split('.')[0]}}</td>
          <td class="has-text-centered" v-if="!project.budget_sent">
            <a :href="'/project/' + projectId + '/receipts/' + recibo.id" target="_blank" class="button is-small is-outlined is-primary"><i class="fas fa-download fa-fw"></i></a>
            &nbsp;<a @click="deleteRecibo(recibo.id)" class="button is-small is-outlined is-danger"><i class="fas fa-times"></i></a>
          </td>
        </tr>
        <tr v-if="recibos.length == 0">
          <td colspan="4" class="has-text-centered">Aún no se cargaron recibos</td>
        </tr>
      </tbody>
    </table>
    <div v-if="!project.budget_sent">
    <hr>
    <h1 class="title is-4">¿Terminarse de subir todos los recibos?</h1>
    <p>Una vez que hayas presentado todos los recibos de las compras hechas del proyecto, tenes que cerrar tu rendición y presentarlo a Ingenia. Cuando lo envies, Gabinete Joven aprobará la rendición o se comunicará con el equipo si existe algún error para que puedas volver a revisarlo.</p>
    <br>
    <a class="button is-info is-fullwidth is-outlined is-600 is-medium" v-if="showConfirmSending" @click="sendReceipts()"><i class="fas fa-question-circle"></i>&nbsp;Clic de nuevo para confirmar el envio</a>
    <a class="button is-primary is-fullwidth is-outlined is-400" v-else @click="showConfirmSending = true"><i class="fas fa-lock"></i>&nbsp;Cerrar la rendición de gastos</a>
    </div>
    <div v-else>
      <h1 class="title is-4"><i class="fas fa-check has-text-success"></i>&nbsp;&nbsp;Rendicion de gastos enviado!</h1>
      <div class="notification is-success" v-if="project.budget_approved">
        <h1 class="title is-5 is-marginless"><i class="fas fa-check"></i>&nbsp;&nbsp;La rendición de gastos ha sido aprobada!</h1>
      </div>
    </div>
    <b-loading :is-full-page="true" :active.sync="isLoading"></b-loading>
  </div>
</template>

<script>
export default {
  props: [],
  data() {
    return {
      // pendiente: false,
      // rechazado: false,
      // verificado: false,
      showConfirmSending: false,
      inputReciboFecha: null,
      fechaFinal: '',
      recibos: [],
      isLoading: false,
      files: [],
      formUrl:
        "/project/" + this.$store.state.user.groups[0].project.id + "/receipts"
      // user: {},
      // verifying: true
    };
  },
  mounted: function() {
    this.getRecibos();
    // this.forceUpdateState("userPanel")
    //   .then(user => {
    //     this.user = this.$store.state.user;
    //     this.verifying = false;
    //   })
    //   .catch(e => {
    //     this.$snackbar.open({
    //       message: "Error al verificar la carta de aval.",
    //       type: "is-danger",
    //       actionText: "Cerrar"
    //     });
    //   });
  },
  methods: {
    getRecibos: function() {
      this.isLoading = true;
      this.$http
        .get(
          "project/" + this.$store.state.user.groups[0].project.id + "/receipts?size=100"
        )
        .then(response => {
          this.recibos = response.data.data;
          this.isLoading = false;
        })
        .catch(error => {
          console.error(error.message);
          this.isLoading = false;
          this.$snackbar.open({
            message: "Error inesperado. Recarge la pagina.",
            type: "is-danger",
            actionText: "Cerrar"
          });
        });
    },
    deleteRecibo: function(id) {
      this.isLoading = true;
      this.$http
        .post(
          "/receipts/" + id
        )
        .then(response => {
          this.$snackbar.open({
            message: "Recibo eliminado del listado",
            type: "is-success",
            actionText: "Cerrar"
          });
          this.getRecibos();
          this.isLoading = false;
        })
        .catch(error => {
          console.error(error.message);
          this.isLoading = false;
          this.$snackbar.open({
            message: "Error inesperado. Recarge la pagina.",
            type: "is-danger",
            actionText: "Cerrar"
          });
        });
    },
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
          this.$refs.formReceipt.submit();
        })
        .catch(error => {
          this.$snackbar.open({
            message: "Error inesperado",
            type: "is-danger",
            actionText: "Cerrar"
          });
          return false;
        });
    },
    sendReceipts: function(){
      this.isLoading = true;
      this.$http
        .post(
          "project/" + this.project.id + "/receipts/send"
        )
        .then(response => {
          this.forceUpdateState("userPanel")
          this.$snackbar.open({
            message: "¡Los recibos han sido enviados para ser aprobados!",
            type: "is-success",
            actionText: "Cerrar"
          });
          this.isLoading = false;
        })
        .catch(error => {
          console.error(error.message);
          this.isLoading = false;
          this.$snackbar.open({
            message: "Error inesperado. Recarge la pagina.",
            type: "is-danger",
            actionText: "Cerrar"
          });
        });
    }
  },
  computed: {
    projectId: function(){
      return this.$store.state.user.groups[0].project.id
    },
    user: function(){
      return this.$store.state.user;
    },
    project: function(){
      return this.$store.state.user.groups[0].project
    }
  },
  watch: {
    inputReciboFecha: function(newVal) {
          this.fechaFinal = newVal.toISOString().split("T")[0];
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
