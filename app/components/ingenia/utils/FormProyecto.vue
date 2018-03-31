<template>
  <div>
    
    <div class="field">
      <label class="label is-size-4" :class="{'has-text-danger': errors.has('proyecto.nombre')}">
        <i class="fas fa-angle-double-right"></i> Nombre del proyecto</label>
      <div class="control">
        <input v-model="proyecto.nombre" data-vv-name="proyecto.nombre" data-vv-as="'Nombre del proyecto'" type="text" v-validate="'required'" class="input is-large" placeholder="Requerido *">
        <span v-show="errors.has('proyecto.nombre')" class="help is-danger">{{errors.first('proyecto.nombre')}}</span>
      </div>
    </div>
    <div class="field">
      <label class="label is-size-4" :class="{'has-text-danger': errors.has('proyecto.resumen')}">
        <i class="fas fa-angle-double-right"></i> Resumen del proyecto</label>
      <p>Breve descripción de tu proyecto. Máximo 250 caracteres</p>
      <div class="control">
        <b-input v-model="proyecto.resumen" data-vv-name="proyecto.resumen" data-vv-as="'Resumen del proyecto'" v-validate="'required|min:10|max:250'" type="textarea" minlength="10" maxlength="250" rows="3" placeholder="Requerido *. Breve descripcion de tu proyecto">
        </b-input>
        <span v-show="errors.has('proyecto.resumen')" class="help is-danger">{{errors.first('proyecto.resumen')}}</span>
      </div>
    </div>
    <div class="field">
      <label class="label is-size-4" :class="{'has-text-danger': errors.has('proyecto.fundamentacion')}">
        <i class="fas fa-angle-double-right"></i> Fundamentación</label>
      <p>¿Por qué vale la pena realizar el proyecto? Máximo 400 caracteres</p>
      <div class="control">
        <b-input v-model="proyecto.fundamentacion" data-vv-name="proyecto.fundamentacion" data-vv-as="'Fundamentación'" v-validate="'required|min:10|max:400'" type="textarea" minlength="10" maxlength="400" rows="3" placeholder="Requerido *">
        </b-input>
        <span v-show="errors.has('proyecto.fundamentacion')" class="help is-danger">{{errors.first('proyecto.fundamentacion')}}</span>
      </div>
    </div>
    <div class="field">
      <label class="label is-size-4" :class="{'has-text-danger': errors.has('proyecto.tematica')}">
        <i class="fas fa-angle-double-right"></i> ¿Que temática trabaja el proyecto?</label>
      <p>Seleccione la temática para saber de que trata</p>
      <br>
      <b-field>
        <b-select size="is-large" data-vv-name="proyecto.tematica" data-vv-as="'Temática'" v-validate="'required'" v-model="proyecto.tematica" :disabled="categorias.length == 0" :loading="categoriasLoading" placeholder="Seleccione la temática" expanded>
          <option v-for="categoria in categorias" :key="categoria.id" :value="categoria.id">{{categoria.name}}</option>
          
        </b-select>
      </b-field>
      <span v-show="errors.has('proyecto.tematica')" class="help is-danger">{{errors.first('proyecto.tematica')}}</span>
      <div class="notification is-primary" v-show="proyecto.tematica != null">
        <p v-for="categoria in categorias" :key="categoria.id" v-show="proyecto.tematica == categoria.id">{{categoria.description}}</p>
      </div>
    </div>
    <br>
    <div class="field">
      <label class="label is-size-4" :class="{'has-text-danger': errors.has('proyecto.enEjecucion')}">
        <i class="fas fa-angle-double-right"></i> ¿El proyecto ya esta en ejecución o en funcionamiento? *</label>
      <p>Si se trata de una iniciativa primigenia que aun no se ha desarrollado marcar
        <i class="fas fa-times"></i> NO</p>
      <br>
      <div class="control">
        <b-field>
          <b-radio-button data-vv-name="proyecto.enEjecucion" data-vv-as="'En ejecución o en funcionamiento'" v-validate="'required'" v-model="proyecto.enEjecucion" :native-value="true" type="is-primary" size="is-medium">
            <span>
              <i class="fas fa-check"></i> Si</span>
          </b-radio-button>
          <b-radio-button v-model="proyecto.enEjecucion" :native-value="false" type="is-primary" size="is-medium">
            <span>
              <i class="fas fa-times"></i> No</span>
          </b-radio-button>
        </b-field>
        <span v-show="errors.has('proyecto.enEjecucion')" class="help is-danger">{{errors.first('proyecto.enEjecucion')}}</span>
      </div>
    </div>
    <br>
    <div class="field" v-if="proyecto.enEjecucion">
      <label class="label is-size-5" :class="{'has-text-danger': errors.has('proyecto.descripcionEjecucion')}">
        <i class="fas fa-caret-right"></i> ¿De qué forma, cómo y dónde se está ejecutando?</label>
      <div class="control">
        <b-input type="textarea" v-model="proyecto.descripcionEjecucion" data-vv-name="proyecto.descripcionEjecucion" data-vv-as="'Descripcion de la ejecución'" v-validate="'required|min:10|max:100'" minlength="10" maxlength="100" rows="3" placeholder="Requerido *">
        </b-input>
        <span v-show="errors.has('proyecto.descripcionEjecucion')" class="help is-danger">{{errors.first('proyecto.descripcionEjecucion')}}</span>
      </div>
    </div>
        <Localidad ref="localidadForm" @updateLocalidad="updateLocalidad" @updateLocalidadCustom="updateLocalidadCustom"></Localidad>
    <div class="field">
      <label class="label is-size-4" :class="{'has-text-danger': errors.has('proyecto.barrios')}">
        <i class="fas fa-angle-double-right"></i>¿En que barrio/s se llevará adelante? *</label>
      <p>Indiquen el/los barrios donde realizarán el proyecto. Separe con comas, o presione TAB</p>
      <br>
      <b-taginput v-model="proyecto.barrios" data-vv-name="proyecto.barrios" data-vv-as="'Barrios'" v-validate="'required'" size="is-medium" icon="map-marker" type="is-primary" placeholder="Nombre del barrio">
      </b-taginput>
      <span v-show="errors.has('proyecto.barrios')" class="help is-danger">{{errors.first('proyecto.barrios')}}</span>
      <br>

    </div>
    <div class="field">
      <label class="label is-size-4" :class="{'has-text-danger': errors.has('proyecto.objetivos')}">
        <i class="fas fa-angle-double-right"></i> Objetivos *</label>
      <p>¿Qué se quiere lograr? Tratar de ser muy breves, concretos y precisos</p>
      <div class="control">
        <br>
        <div class="field is-grouped">
          <div class="control">
            <a @click.prevent class="button is-medium is-static">
              <i class="fas fa-flag-checkered"></i>
            </a>
          </div>
          <p class="control is-expanded">
            <input class="input is-medium" v-model="inputObjetivos" type="text" placeholder="Escriba el objetivo">
          </p>
          <p class="control">
            <button @click="addObjetivo" class="button is-primary is-medium" :disabled="disableAddObjetivo">
              <i class="fas fa-plus"></i>
            </button>
          </p>
        </div>
        <input type="hidden" v-model="proyecto.objetivos" data-vv-name="proyecto.objetivos" data-vv-as="'Objetivos'" v-validate="'required'">
        <span v-show="errors.has('proyecto.objetivos')" class="help is-danger">{{errors.first('proyecto.objetivos')}}</span>
        <br>
        <div class="content">
          <table class="table is-narrow is-bordered">
            <thead>
              <tr>
                <th>Objetivos</th>
                <th width="50px" class="has-text-centered">
                  <i class="fas fa-times"></i>
                </th>
              </tr>
            </thead>
            <tbody v-if="proyecto.objetivos.length">
              <tr v-for="(objetivo, index) in proyecto.objetivos" :key="index">
                <td>
                  <i class="fas fa-flag-checkered fa-fw"></i> {{objetivo}}</td>
                <td class="has-text-centered">
                  <a @click="removeObjetivo(index)">
                    <i class="fas fa-times"></i>
                  </a>
                </td>
              </tr>
            </tbody>
            <tbody v-else>
              <tr>
                <td class="has-text-centered" colspan="2">
                  <i>No se han ingresado barrios</i>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
    <div class="field">
      <label class="label is-size-4" :class="{'has-text-danger': errors.has('proyecto.actividades')}">
        <i class="fas fa-angle-double-right"></i> Calendario de Actividades *</label>
      <p>Coloque por cada actividad la fecha en que se realizará</p>
      <br>
      <div class="field is-grouped">
        <div class="control">
          <b-datepicker placeholder="Haga clic!" v-model="dateActividad" size="is-medium" :date-formatter="(date) => date.toLocaleDateString('es-AR')" :min-date="new Date()" :max-date="new Date('12/31/2018')" icon="calendar-alt">
          </b-datepicker>
        </div>
        <p class="control is-expanded">
          <input class="input is-medium" v-model="inputActividad" type="text" placeholder="Escriba el objetivo">
        </p>
        <p class="control">
          <button @click="addActividad" class="button is-primary is-medium" :disabled="disableAddActividad">
            <i class="far fa-calendar-plus"></i>
          </button>
        </p>
      </div>
      <input type="hidden" v-model="proyecto.actividades" data-vv-name="proyecto.actividades" data-vv-as="'Actividades'" v-validate="'required'">
      <span v-show="errors.has('proyecto.actividades')" class="help is-danger">{{errors.first('proyecto.actividades')}}</span>
      <br>
      <div class="content">
        <table class="table is-narrow is-bordered">
          <thead>
            <tr>
              <th width="120px">Fecha</th>
              <th>Actividad</th>
              <th width="50px" class="has-text-centered">
                <i class="fas fa-times"></i>
              </th>
            </tr>
          </thead>
          <tbody v-if="proyecto.actividades.length">
            <tr v-for="(actividad, index) in proyecto.actividades" :key="index">
              <td>
                <i class="far fa-calendar-check fa-fw"></i> {{actividad.fecha.toLocaleDateString('es-AR')}}</td>
              <td>{{actividad.descripcion}}</td>
              <td class="has-text-centered">
                <a @click="removeActividad(index)">
                  <i class="fas fa-times"></i>
                </a>
              </td>
            </tr>
          </tbody>
          <tbody v-else>
            <tr>
              <td class="has-text-centered" colspan="3">
                <i>No se han ingresado actividades</i>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
    <div class="field">
      <label class="label is-size-4" :class="{'has-text-danger': errors.has('proyecto.presupuesto')}">
        <i class="fas fa-angle-double-right"></i> Presupuesto *</label>
      <p>
        <span class="tag is-warning">Importante</span>&nbsp;&nbsp;Recuerda que el tope es de $22.000.-</p>
      <br>
      <div class="field is-grouped">
        <p class="control is-expanded">
          <input class="input is-medium" v-model="inputItemRubro" type="text" placeholder="Rubro Item">
        </p>
        <p class="control is-expanded">
          <input class="input is-medium" v-model="inputItemDescripcion" type="text" placeholder="Descripcion Item">
        </p>
        <p class="control is-expanded">
          <input class="input is-medium" v-model="inputItemMonto" data-vv-name="inputItemMonto" data-vv-as="'Monto'" v-validate="'numeric'" type="text" placeholder="Monto en AR$">
          <span v-if="errors.has('inputItemMonto')" class="help is-danger">{{errors.first('inputItemMonto')}}</span>
          <span v-else class="help">Ingrese números sin decimal, puntos o comas</span>

        </p>
        <p class="control">
          <button @click="addItem" class="button is-primary is-medium" :disabled="disableAddItem">
            <i class="fas fa-plus"></i>
          </button>
        </p>
      </div>
      <input type="hidden" v-model="proyecto.presupuesto" data-vv-name="proyecto.presupuesto" data-vv-as="'Presupuesto'" v-validate="'required'">
      <span v-show="errors.has('proyecto.presupuesto')" class="help is-danger">{{errors.first('proyecto.presupuesto')}}</span>
      <br>
      <div class="content">
        <table class="table is-narrow is-bordered">
          <thead>
            <tr>
              <th width="120px">Rubro</th>
              <th>Descripción</th>
              <th class="has-text-centered">Monto</th>
              <th width="50px" class="has-text-centered">
                <i class="fas fa-times"></i>
              </th>
            </tr>
          </thead>
          <tbody v-if="proyecto.presupuesto.length">
            <tr v-for="(item, index) in proyecto.presupuesto" :key="index">
              <td>{{item.rubro}}</td>
              <td>{{item.descripcion}}</td>
              <td class="has-text-centered">$ {{item.monto}}</td>
              <td class="has-text-centered">
                <a @click="removeItem(index)">
                  <i class="fas fa-times"></i>
                </a>
              </td>
            </tr>
            <tr>
              <th colspan="2" class="has-text-right">Monto total:</th>
              <td class="has-text-centered">$ {{montoTotal}}</td>
              <td></td>
            </tr>
          </tbody>
          <tbody v-else>
            <tr>
              <td class="has-text-centered" colspan="4">
                <i>No se han ingresado items en el presupuesto</i>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
      <label class="label is-size-4" v-if="proyecto.presupuesto.length">
        <i class="fas fa-angle-double-right"></i> Monto total solicitado: $ {{montoTotal}}</label>
    </div>
    <hr>
    <div class="field">
      <label class="label is-size-4" :class="{'has-text-danger': errors.has('proyecto.conOrganizacion')}">
        <i class="fas fa-angle-double-right"></i> ¿Las actividades las realizarán en coordinación con otras organizaciones y/o instituciones? * </label>
      <p>Se refiere a organizaciones y/o instituciones <u>diferentes</u> a la que pertenece el equipo</p>
      <p><b>IMPORTANTE:</b> Si el proyecto se realiza en coordinación con otra institución y/o organización, debera adjuntar la carta aval en el sistema en otro momento.</p>
      <br>
      <b-field>
        <b-radio-button v-model="proyecto.conOrganizacion" data-vv-name="proyecto.conOrganizacion" data-vv-as="'Trabajo en conjunto con Organización'" v-validate="'required'" :native-value="true" type="is-primary" size="is-medium">
          <span>
            <i class="fas fa-check"></i> Si</span>
        </b-radio-button>
        <b-radio-button v-model="proyecto.conOrganizacion" :native-value="false" type="is-primary" size="is-medium">
          <span>
            <i class="fas fa-times"></i> No</span>
        </b-radio-button>
      </b-field>
      <span v-show="errors.has('proyecto.conOrganizacion')" class="help is-danger">{{errors.first('proyecto.conOrganizacion')}}</span>
    </div>
    <br>
    <form-organizacion ref="formOrganizacion" v-if="proyecto.conOrganizacion" :organizacion.sync="proyecto.organizacion"></form-organizacion>
  </div>
</template>

<script>
import Localidad from './FieldLocalidad'
import FormOrganizacion from "./FormOrganizacion";
export default {
  props: ['proyecto'],
  components: {
    Localidad,
    FormOrganizacion
  },
  data() {
    return {
      radioEstado: null,
      inputObjetivos: null,
      inputActividad: null,
      inputItemRubro: null,
      inputItemDescripcion: null,
      inputItemMonto: null,
      dateActividad: null,
      categoriasLoading: false,
      categorias: []
    };
  },
  mounted: function() {
    this.categoriasLoading = true;
    this.$http
      .get("/category")
      .then(response => {
        console.log(response);
        this.categorias = response.data;
        this.categoriasLoading = false;
      })
      .catch(error => {
        console.error(error.message);
        this.$snackbar.open({
          message: "Error inesperado",
          type: "is-danger",
          actionText: "Cerrar"
        });
        this.categoriasLoading = false;
      });
  },
  methods: {
    updateLocalidad: function(id){
        this.proyecto.localidad = id
    },
    updateLocalidadCustom: function(localidadCustom){
        this.proyecto.otraLocalidad = localidadCustom
    },
    addObjetivo: function() {
      if (!this.disableAddObjetivo) {
        this.proyecto.objetivos.push(this.inputObjetivos);
        this.inputObjetivos = "";
      }
    },
    removeObjetivo: function(index) {
      this.proyecto.objetivos.splice(index, 1);
    },
    addActividad: function() {
      if (!this.disableAddActividad) {
        this.proyecto.actividades.push({
          fecha: this.dateActividad,
          descripcion: this.inputActividad
        });
        this.dateActividad = null;
        this.inputActividad = null;
      }
    },
    removeActividad: function(index) {
      this.proyecto.actividades.splice(index, 1);
    },
    addItem: function() {
      this.$validator
        .validate("inputItemMonto", this.inputItemMonto)
        .then(result => {
          if (result) {
            if (!this.disableAddItem) {
              if (parseFloat(this.inputItemMonto) + this.montoTotal > 22000) {
                this.$snackbar.open(
                  "El item excede el total permitido ($22000)"
                );
                return;
              }
              this.proyecto.presupuesto.push({
                rubro: this.inputItemRubro,
                descripcion: this.inputItemDescripcion,
                monto: this.inputItemMonto
              });
              this.inputItemRubro = null;
              this.inputItemDescripcion = null;
              this.inputItemMonto = null;
            }
          } else {
            this.$snackbar.open(
              "El monto debe ser un numero sin coma ni punto decimal"
            );
          }
        });
    },
    removeItem: function(index) {
      this.proyecto.presupuesto.splice(index, 1);
    },
    validateForm: function() {
      let promise = new Promise((resolve, reject) => {
        this.$validator.validateAll().then(result => {
          if (!result) {
            console.log("Proyecto: Hay errores en los datos");
            return resolve(result);
          }
          console.log("Proyecto: OK");
          return resolve(result);
        });
      });
      return promise;
    },  
    validateOrganizacion: function() {
      if (this.proyecto.conOrganizacion) {
        return this.$refs.formOrganizacion.validateForm();
      } else {
        return true;
      }
    },
    validateLocalidadOrganizacion: function(){
      if (this.proyecto.conOrganizacion) {
        return this.$refs.formOrganizacion.validateLocalidad();
      } else {
        return true;
      }
    },
    validateLocalidad: function(){
        return this.$refs.localidadForm.validateForm();
    }
  },
  computed: {
    montoTotal: function() {
      const reducer = (accumulator, item) =>
        accumulator + parseFloat(item.monto);
      return this.proyecto.presupuesto.reduce(reducer, 0);
    },
    disableAddItem: function() {
      return (
        this.inputItemRubro == null ||
        this.inputItemRubro == "" ||
        this.inputItemDescripcion == null ||
        this.inputItemDescripcion == "" ||
        this.inputItemMonto == null ||
        this.inputItemMonto == ""
      );
    },
    disableAddActividad: function() {
      return (
        this.inputActividad == "" ||
        this.inputActividad == null ||
        this.dateActividad == null
      );
    },
    disableAddObjetivo: function() {
      return this.inputObjetivos == "" || this.inputObjetivos == null;
    }
  }
};
</script>
