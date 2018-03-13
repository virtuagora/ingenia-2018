<template>
  <div>
    <div class="field">
      <label class="label is-size-4">
        <i class="fas fa-angle-double-right"></i> Nombre del proyecto</label>
      <div class="control">
        <input v-model="proyecto.nombre" type="text" v-validate="'required'" class="input is-large" placeholder="Requerido *">
      </div>
    </div>
    <div class="field">
      <label class="label is-size-4">
        <i class="fas fa-angle-double-right"></i> Resumen del proyecto</label>
      <div class="control">
        <b-input v-model="proyecto.resumen" type="textarea" minlength="10" v-validate="'required'" maxlength="250" rows="3" placeholder="Requerido *. Breve descripcion de tu proyecto">
        </b-input>
      </div>
    </div>
    <div class="field">
      <label class="label is-size-4">
        <i class="fas fa-angle-double-right"></i> Fundamentación</label>
      <div class="control">
        <b-input v-model="proyecto.fundamentacion" type="textarea" minlength="10" maxlength="200" rows="3" placeholder="Requerido *. ¿Por qué vale la pena realizar el proyecto? Máximo 200 palabras">
        </b-input>
      </div>
    </div>
    <div class="field">
      <label class="label is-size-4">
        <i class="fas fa-angle-double-right"></i> ¿Que temática trabaja el proyecto?</label>
      <p>Seleccione la temática para saber de que trata</p>
      <br>
      <b-field>
        <b-select size="is-large" v-validate="'required'" v-model="proyecto.tematica" placeholder="Seleccione la temática" expanded>
          <option :value="1">Integración Social</option>
          <option :value="2">Medio Ambiente</option>
          <option :value="3">Deporte y recreación</option>
          <option :value="4">Educación</option>
          <option :value="5">Cultura</option>
          <option :value="6">Empleo y Capacitación</option>
          <option :value="7">Comunicación</option>
          <option :value="8">Salud</option>
        </b-select>
      </b-field>
      <div class="notification is-light" v-show="proyecto.tematica != null">
        <h1 class="title is-5" v-show="proyecto.tematica == 1">Integracion Social</h1>
        <p v-show="proyecto.tematica == 1">Incluimos aquí aquellos proyectos cuyos objetivos y actividades apuntaban a mejorar la convivencia a nivel social, implementando acciones destinadas a jóvenes, y a población en general, que tengan algunos de sus derechos vulnerados. Entre ellos jóvenes en situación de vulnerabilidad social, jóvenes con discapacidad, entre otros.</p>
        <h1 class="title is-5" v-show="proyecto.tematica == 2">Medio Ambiente</h1>
        <p v-show="proyecto.tematica == 2">esta categoría engloba a los proyectos que se propusieron mejorar las condiciones del medio ambiente. Los mismos enfocan una amplia variedad de temáticas como ser la recolección sustentable de residuos, el reciclado de basura, la disminución de gases tóxicos; hasta el cuidado de otros seres vivos como plantas y animales.</p>
        <h1 class="title is-5" v-show="proyecto.tematica == 3">Deporte y recreación</h1>
        <p v-show="proyecto.tematica == 3">este eje integra los proyectos abocados a promover el bienestar joven mediante la organización de actividades deportivas y recreativas. En muchos casos, se trataban de proyectos que pretendían la recuperación de espacios públicos-en su mayoría plazas públicas- para tales fines.</p>
        <h1 class="title is-5" v-show="proyecto.tematica == 4">Educación</h1>
        <p v-show="proyecto.tematica == 4">Se incluyen en esta categoría a los proyectos juveniles cuyos objetivos y actividades tienen como fin brindar mayor educación a la sociedad en general. La educación es entendida en sentido amplio por los jóvenes, abarcando desde la enseñanza de los propios derechos hasta la educación sexual, adoptando desde formatos tradicionales de jornadas de formación básicas hasta actividades lúdicas.</p>
        <h1 class="title is-5" v-show="proyecto.tematica == 5">Cultura</h1>
        <p v-show="proyecto.tematica == 5">Caben aquí las iniciativas orientadas a al promoción de la cultura local, regional y provincial, desde diferentes expresiones artísticas: danzas, teatro, música, pintura, productos artesanales, entre otros.</p>
        <h1 class="title is-5" v-show="proyecto.tematica == 6">Empleo y Capacitación</h1>
        <p v-show="proyecto.tematica == 6">Se encuentran en esta categoría los proyectos juveniles cuyos intereses se relacionaban con el mundo del trabajo, la posibilidad de acceder al mismo y la mejora de las condiciones laborales. Aquí, se encuentran también algunas iniciativas que, no atendiendo a las bases del Programa Ingenia, se formularon con la intención de lograr un microemprendimiento de carácter privado.</p>
        <h1 class="title is-5" v-show="proyecto.tematica == 7">Comunicación</h1>
        <p v-show="proyecto.tematica == 7">Se abarca con este eje a los proyectos destinados a amplificar la voz joven. Caben aquí las acciones para crear medios de comunicación gráficos y audiovisuales, como así también aquellas actividades que promocionan la participación juvenil en los medios existentes.</p>
        <h1 class="title is-5" v-show="proyecto.tematica == 8">Salud</h1>
        <p v-show="proyecto.tematica == 8">Lorem ipsum dolor sit amet consectetur adipisicing elit. Fuga saepe natus accusamus delectus deserunt eaque repellat nemo temporibus necessitatibus perspiciatis rerum laboriosam, perferendis soluta nam molestias repellendus magni eos in?</p>
      </div>
    </div>
    <br>
    <div class="field">
      <label class="label is-size-4">
        <i class="fas fa-angle-double-right"></i> ¿El proyecto ya esta en ejecución o en funcionamiento? *</label>
      <p>Si se trata de una iniciativa primigenia que aun no se ha desarrollado marcar
        <i class="fas fa-times"></i> NO</p>
      <br>
      <div class="control">
        <b-field>
          <b-radio-button v-model="proyecto.enEjecucion" :native-value="true" type="is-primary" size="is-medium">
            <span>
              <i class="fas fa-check"></i> Si</span>
          </b-radio-button>
          <b-radio-button v-model="proyecto.enEjecucion" :native-value="false" type="is-primary" size="is-medium">
            <span>
              <i class="fas fa-times"></i> No</span>
          </b-radio-button>
        </b-field>
      </div>
    </div>
    <br>
    <div class="field" v-show="proyecto.enEjecucion">
      <label class="label is-size-5">
        <i class="fas fa-caret-right"></i> ¿De qué forma, cómo y dónde se está ejecutando?</label>
      <div class="control">
        <b-input type="textarea" minlength="10" maxlength="100" rows="3" placeholder="Requerido *">
        </b-input>
      </div>
    </div>
    <div class="field">
      <label class="label is-size-4">
        <i class="fas fa-angle-double-right"></i> ¿Donde se implementa o implementará territorialmente el proyecto? *</label>
      <b-field grouped>
        <b-field label="Nodo" expanded>
          <b-select v-model="proyecto.localizacion.nodo" placeholder="Nodo" expanded>
            <option>Mr.</option>
            <option>Ms.</option>
          </b-select>
        </b-field>
        <b-field label="Departamento" expanded>
          <b-select v-model="proyecto.localizacion.departamento" placeholder="Departamento" expanded>
            <option>Mr.</option>
            <option>Ms.</option>
          </b-select>
        </b-field>
        <b-field label="Localidad" expanded>
          <b-select v-model="proyecto.localizacion.localidad" placeholder="Localidad" expanded>
            <option>Mr.</option>
            <option>Ms.</option>
          </b-select>
        </b-field>
      </b-field>
    </div>
    <div class="field">
      <label class="label is-size-4">
        <i class="fas fa-angle-double-right"></i>¿En que barrio/s se llevará adelante? *</label>
      <p>Indiquen el/los barrios donde realizarán el proyecto. Separe con comas, o presione TAB</p>
      <br>
      <b-taginput v-model="proyecto.barrios" size="is-medium" maxtags="5" icon="map-marker" type="is-primary" placeholder="Nombre del barrio">
      </b-taginput>
      <br>
    </div>
    <div class="field">
      <label class="label is-size-4">
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
            <button @click="addObjetivo" class="button is-primary is-medium">
              <i class="fas fa-plus"></i>
            </button>
          </p>
        </div>
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
      <label class="label is-size-4">
        <i class="fas fa-angle-double-right"></i> Calendario de Actividades *</label>
      <p>Coloque por cada actividad la fecha en que se realizará</p>
      <br>
      <div class="field is-grouped">
        <div class="control">
          <b-datepicker placeholder="Haga clic!" v-model="dateActividad" size="is-medium" :date-formatter="(date) => date.toLocaleDateString('es-AR')" :min-date="new Date()" :max-date="new Date('12/31/2018')" icon-pack="far" icon="calendar-alt">
          </b-datepicker>
        </div>
        <p class="control is-expanded">
          <input class="input is-medium" v-model="inputActividad" type="text" placeholder="Escriba el objetivo">
        </p>
        <p class="control">
          <button @click="addActividad" class="button is-primary is-medium">
            <i class="far fa-calendar-plus"></i>
          </button>
        </p>
      </div>
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
      <label class="label is-size-4">
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
          <input class="input is-medium" v-model="inputItemMonto" type="text" placeholder="Monto en AR$">
          <span class="help">Ingrese números sin decimal, puntos o comas</span>
        </p>
        <p class="control">
          <button @click="addItem" class="button is-primary is-medium">
            <i class="fas fa-plus"></i>
          </button>
        </p>
      </div>
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
      <label class="label is-size-4">
        <i class="fas fa-angle-double-right"></i> ¿Las actividades las realizarán en coordinación con otras organizaciones y/o instituciones? * </label>
      <p>Se refiere a organizaciones y/o instituciones diferentes a la de pertenencia</p>
      <p>Si el proyecto se realiza en coordinación de alguna institución y/o organización deben adjuntar la carta aval.</p>
      <br>
      <b-field>
        <b-radio-button v-model="proyecto.conOrganizacion" :native-value="true" type="is-primary" size="is-medium">
          <span>
            <i class="fas fa-check"></i> Si</span>
        </b-radio-button>
        <b-radio-button v-model="proyecto.conOrganizacion" :native-value="false" type="is-primary" size="is-medium">
          <span>
            <i class="fas fa-times"></i> No</span>
        </b-radio-button>
      </b-field>
    </div>
    <br>

    <organizacion v-if="proyecto.conOrganizacion" :organizacion.sync="proyecto.organizacion"></organizacion>
    <br>

    <hr>
    <br>
    <button class="button is-large is-primary is-fullwidth">
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
      if (this.inputObjetivos != "") {
        this.proyecto.objetivos.push(this.inputObjetivos);
        this.inputObjetivos = "";
      }
    },
    removeObjetivo: function(index) {
      this.proyecto.objetivos.splice(index, 1);
    },
    addActividad: function() {
      if (this.inputActividad != "" && this.dateActividad != null) {
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
      if (
        this.inputItemRubro != null &&
        this.inputItemDescripcion != null &&
        this.inputItemMonto != null
      ) {
        this.proyecto.presupuesto.push({
          rubro: this.inputItemRubro,
          descripcion: this.inputItemDescripcion,
          monto: this.inputItemMonto
        });
        this.inputItemRubro = null;
        this.inputItemDescripcion = null;
        this.inputItemMonto = null;
      }
    },
    removeItem: function(index) {
      this.proyecto.presupuesto.splice(index, 1);
    }
  },
  computed: {
    montoTotal: function() {
      let montoTotal = 0;
      this.proyecto.presupuesto.forEach(item => {
        montoTotal += parseFloat(item.monto);
      });
      return montoTotal;
    }
  }
};
</script>
