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
      <p>Breve descripción de tu proyecto</p>
      <div class="control">
        <b-input v-model="proyecto.resumen" data-vv-name="proyecto.resumen" data-vv-as="'Resumen del proyecto'" v-validate="'required|min:10|max:250'" type="textarea" minlength="10" maxlength="250" rows="3" placeholder="Requerido *. Breve descripcion de tu proyecto">
        </b-input>
        <span v-show="errors.has('proyecto.resumen')" class="help is-danger">{{errors.first('proyecto.resumen')}}</span>
      </div>
    </div>
    <div class="field">
      <label class="label is-size-4" :class="{'has-text-danger': errors.has('proyecto.fundamentacion')}">
        <i class="fas fa-angle-double-right"></i> Fundamentación</label>
      <p>¿Por qué vale la pena realizar el proyecto? Máximo 200 palabras</p>
      <div class="control">
        <b-input v-model="proyecto.fundamentacion" data-vv-name="proyecto.fundamentacion" data-vv-as="'Fundamentación'" v-validate="'required|min:10|max:250'" type="textarea" minlength="10" maxlength="200" rows="3" placeholder="Requerido *">
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
        <b-select size="is-large" data-vv-name="proyecto.tematica" data-vv-as="'Temática'" v-validate="'required'" v-model="proyecto.tematica" placeholder="Seleccione la temática" expanded>
          <option value="Integración Social">Integración Social</option>
          <option value="Medio Ambiente">Medio Ambiente</option>
          <option value="Deporte y recreación">Deporte y recreación</option>
          <option value="Educación">Educación</option>
          <option value="Cultura">Cultura</option>
          <option value="Empleo y Capacitación">Empleo y Capacitación</option>
          <option value="Comunicación">Comunicación</option>
          <option value="Salud">Salud</option>
        </b-select>
      </b-field>
      <span v-show="errors.has('proyecto.tematica')" class="help is-danger">{{errors.first('proyecto.tematica')}}</span>
      <div class="notification is-light" v-show="proyecto.tematica != null">
        <h1 class="title is-5" v-show="proyecto.tematica == 'Integracion Social'">Integracion Social</h1>
        <p v-show="proyecto.tematica == 'Integracion Social'">Incluimos aquí aquellos proyectos cuyos objetivos y actividades apuntaban a mejorar la convivencia a nivel social, implementando acciones destinadas a jóvenes, y a población en general, que tengan algunos de sus derechos vulnerados. Entre ellos jóvenes en situación de vulnerabilidad social, jóvenes con discapacidad, entre otros.</p>
        <h1 class="title is-5" v-show="proyecto.tematica == 'Medio Ambiente'">Medio Ambiente</h1>
        <p v-show="proyecto.tematica == 'Medio Ambiente'">esta categoría engloba a los proyectos que se propusieron mejorar las condiciones del medio ambiente. Los mismos enfocan una amplia variedad de temáticas como ser la recolección sustentable de residuos, el reciclado de basura, la disminución de gases tóxicos; hasta el cuidado de otros seres vivos como plantas y animales.</p>
        <h1 class="title is-5" v-show="proyecto.tematica == 'Deporte y recreación'">Deporte y recreación</h1>
        <p v-show="proyecto.tematica == 'Deporte y recreación'">este eje integra los proyectos abocados a promover el bienestar joven mediante la organización de actividades deportivas y recreativas. En muchos casos, se trataban de proyectos que pretendían la recuperación de espacios públicos-en su mayoría plazas públicas- para tales fines.</p>
        <h1 class="title is-5" v-show="proyecto.tematica == 'Educación'">Educación</h1>
        <p v-show="proyecto.tematica == 'Educación'">Se incluyen en esta categoría a los proyectos juveniles cuyos objetivos y actividades tienen como fin brindar mayor educación a la sociedad en general. La educación es entendida en sentido amplio por los jóvenes, abarcando desde la enseñanza de los propios derechos hasta la educación sexual, adoptando desde formatos tradicionales de jornadas de formación básicas hasta actividades lúdicas.</p>
        <h1 class="title is-5" v-show="proyecto.tematica == 'Cultura'">Cultura</h1>
        <p v-show="proyecto.tematica == 'Cultura'">Caben aquí las iniciativas orientadas a al promoción de la cultura local, regional y provincial, desde diferentes expresiones artísticas: danzas, teatro, música, pintura, productos artesanales, entre otros.</p>
        <h1 class="title is-5" v-show="proyecto.tematica == 'Empleo y Capacitación'">Empleo y Capacitación</h1>
        <p v-show="proyecto.tematica == 'Empleo y Capacitación'">Se encuentran en esta categoría los proyectos juveniles cuyos intereses se relacionaban con el mundo del trabajo, la posibilidad de acceder al mismo y la mejora de las condiciones laborales. Aquí, se encuentran también algunas iniciativas que, no atendiendo a las bases del Programa Ingenia, se formularon con la intención de lograr un microemprendimiento de carácter privado.</p>
        <h1 class="title is-5" v-show="proyecto.tematica == 'Comunicación'">Comunicación</h1>
        <p v-show="proyecto.tematica == 'Comunicación'">Se abarca con este eje a los proyectos destinados a amplificar la voz joven. Caben aquí las acciones para crear medios de comunicación gráficos y audiovisuales, como así también aquellas actividades que promocionan la participación juvenil en los medios existentes.</p>
        <h1 class="title is-5" v-show="proyecto.tematica == 'Salud'">Salud</h1>
        <p v-show="proyecto.tematica == 'Salud'">Lorem ipsum dolor sit amet consectetur adipisicing elit. Fuga saepe natus accusamus delectus deserunt eaque repellat nemo temporibus necessitatibus perspiciatis rerum laboriosam, perferendis soluta nam molestias repellendus magni eos in?</p>
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
    <div class="field">
      <label class="label is-size-4" :class="{'has-text-danger': errors.has('proyecto.localizacion.nodo') || errors.has('proyecto.localizacion.departamento') || errors.has('proyecto.localizacion.localidad') }">
        <i class="fas fa-angle-double-right"></i> ¿Donde se implementa o implementará territorialmente el proyecto? *</label>
      <b-field grouped>
        <b-field label="Nodo" expanded>
          <b-select v-model="proyecto.localizacion.nodo" data-vv-name="proyecto.localizacion.nodo" data-vv-as="'Región/Nodo'" v-validate="'required'" placeholder="Nodo" expanded>
            <option>Mr.</option>
            <option>Ms.</option>
          </b-select>
        </b-field>
        <b-field label="Departamento" expanded>
          <b-select v-model="proyecto.localizacion.departamento" placeholder="Departamento" data-vv-name="proyecto.localizacion.departamento" data-vv-as="'Departamento'" v-validate="'required'" expanded>
            <option>Mr.</option>
            <option>Ms.</option>
          </b-select>
        </b-field>
        <b-field label="Localidad" expanded>
          <b-select v-model="proyecto.localizacion.localidad" placeholder="Localidad" data-vv-name="proyecto.localizacion.localidad" data-vv-as="'Localidad'" v-validate="'required'" expanded>
            <option>Mr.</option>
            <option>Ms.</option>
          </b-select>
        </b-field>
      </b-field>
      <span v-show="errors.has('proyecto.localizacion.nodo')" class="help is-danger">{{errors.first('proyecto.localizacion.nodo')}}</span>
      <span v-show="errors.has('proyecto.localizacion.departamento')" class="help is-danger">{{errors.first('proyecto.localizacion.departamento')}}</span>
      <span v-show="errors.has('proyecto.localizacion.localidad')" class="help is-danger">{{errors.first('proyecto.localizacion.localidad')}}</span>
    </div>
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
        <span class="tag is-warning">Importante</span>&nbsp;&nbsp;Recuerda que el tope es de $20.000.-</p>
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
      <p>Se refiere a organizaciones y/o instituciones diferentes a la de pertenencia</p>
      <p>Si el proyecto se realiza en coordinación de alguna institución y/o organización deben adjuntar la carta aval.</p>
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
    <organizacion ref="orgForm" v-if="proyecto.conOrganizacion" :organizacion.sync="proyecto.organizacion"></organizacion>
    <br>
    <hr>
    <br>
    <button @click="submitForm" class="button is-large is-primary is-fullwidth">
      <i class="fa fa-paper-plane"></i>&nbsp;&nbsp;Guardar y enviar</button>
    <br>
  </div>
</template>

<script>
import Organizacion from "./Organizacion";

export default {
  data() {
    return {
      radioEstado: null,
      inputObjetivos: null,
      inputActividad: null,
      inputItemRubro: null,
      inputItemDescripcion: null,
      inputItemMonto: null,
      dateActividad: null,
      proyecto: {
        nombre: null,
        resumen: null,
        fundamentacion: null,
        tematica: null,
        enEjecucion: null,
        descripcionEjecucion: null,
        localizacion: {
          nodo: null,
          departamento: null,
          localidad: null
        },
        barrios: [],
        objetivos: [],
        actividades: [],
        presupuesto: [],
        conOrganizacion: null,
        organizacion: {
          nombre: null,
          tematicas: [],
          otraTematica: null,
          localizacion: {
            nodo: null,
            departamento: null,
            localidad: null
          },
          contacto: {
            web: null,
            facebook: null,
            email: null,
            telefono: null
          }
        }
      }
    };
  },
  components: {
    Organizacion
  },
  methods: {
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
              if (parseFloat(this.inputItemMonto) + this.montoTotal > 20000) {
                this.$snackbar.open(
                  "El item excede el total permitido ($20000)"
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
    submitForm: function() {
      Promise.all([
        this.$validator.validateAll(),
        (this.proyecto.conOrganizacion == true ? this.$refs.orgForm.validateForm() : true)
      ])
        .then(values => {
          if (
            values.every(x => {
              return x == true;
            })
          ) {
            console.log("Sending form!");
            console.log(this.proyecto);
            this.sending = true;
            this.$snackbar.open({
              message: "Formulario enviado!",
              type: "is-success",
              actionText: "OK"
            });
          } else {
            this.$snackbar.open({
              message:
                "Alguno de los campos son incorrectos. Verifique el formulario.",
              type: "is-danger",
              actionText: "Cerrar"
            });
          }
        })
        .catch(result => {
          console.log("result: ");
          console.log(result);
          this.$snackbar.open({
            message: "Error inesperado. Intente mas tarde.",
            type: "is-danger",
            actionText: "Cerrar"
          });
        });
    }
  },
  computed: {
    montoTotal: function() {
      let montoTotal = 0;
      this.proyecto.presupuesto.forEach(item => {
        montoTotal += parseFloat(item.monto);
      });
      return montoTotal;
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
