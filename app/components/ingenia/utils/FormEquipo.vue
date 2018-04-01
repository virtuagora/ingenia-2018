<template>
    <section>
        <div class="field">
            <label class="label is-size-4" :class="{'has-text-danger': errors.has('team.name')}">
                <i class="fas fa-angle-double-right"></i> Nombre del equipo</label>
            <div class="control">
                <input :value="team.name" @input="team.name = ($event.target.value != '' ? $event.target.value : null)" data-vv-name="team.name" data-vv-as="'Nombre del equipo'" type="text" v-validate="'required'" class="input is-large" placeholder="Requerido *">
                <span v-show="errors.has('team.name')" class="help is-danger">{{errors.first('team.name')}}</span>
            </div>
        </div>
        <div class="field">
            <label class="label is-size-4" :class="{'has-text-danger': errors.has('team.description')}">
                <i class="fas fa-angle-double-right"></i> Acerca del equipo</label>
            <p>Breve descripción de tu equipo. Contanos acerca de a qué se dedican, como se formó, actividades mas recientes, etc. Máximo 1000 caracteres</p>
            <div class="control">
                <b-input v-model="team.description" data-vv-name="team.description" data-vv-as="'Descripción del equipo'" v-validate="'required|min:10|max:1000'" type="textarea" minlength="10" maxlength="1000" rows="3" placeholder="Requerido *. Breve descripcion de tu equipo">
                </b-input>
                <span v-show="errors.has('team.description')" class="help is-danger">{{errors.first('team.description')}}</span>
            </div>
        </div>
        <Localidad ref="localidadForm" @updateLocalidad="updateLocalidad" @updateLocalidadCustom="updateLocalidadCustom"></Localidad>
        <div class="field">
            <label class="label is-size-4" :class="{'has-text-danger': errors.has('team.year')}">
                <i class="fas fa-angle-double-right"></i> ¿En que año se conformó el equipo? *</label>
            <b-field expanded>
                <b-select size="is-medium" v-model="team.year" data-vv-name="team.year" data-vv-as="'Región/Nodo'" v-validate="'required'" placeholder="Nodo" expanded>
                    <option v-for="number in listYearFoundation" :key="number" :value="number">{{number}}</option>
                </b-select>
            </b-field>
        </div>
        <div class="field">
            <label class="label is-size-4">
                <i class="fas fa-angle-double-right"></i> Si el equipo (o algun integrante) participó en ediciones anteriores de Ingenia, seleccione el o los años.</label>
                <b-checkbox v-model="team.previous_editions" v-for="year in listPreviousEditions" :key="year" :native-value="year">
                    Año {{year}}
                </b-checkbox>
        </div>
        <div class="field">
        <label class="label is-size-4">
          <i class="fas fa-angle-double-right"></i> Redes y contacto del equipo</label>
        <div class="field is-grouped">
          <div class="control">
            <a @click.prevent class="button is-medium is-static">
              <span class="icon">
                <i class="fas fa-globe"></i>
              </span>
            </a>
          </div>
          <p class="control is-expanded">
            <input v-model="team.web" data-vv-name="team.web" data-vv-as="'Web de la organización'" v-validate="'url'" class="input is-medium" type="text" placeholder="URL página web (Ej: http://www.organizacion.org)">
            <span v-show="errors.has('team.web')" class="help is-danger">{{errors.first('team.web')}}</span>
          </p>
        </div>
        <div class="field is-grouped">
          <div class="control">
            <a @click.prevent class="button is-medium is-static">
              <span class="icon">
                <i class="fab fa-facebook"></i>
              </span>
            </a>
          </div>
          <p class="control is-expanded">
            <input v-model="team.facebook" class="input is-medium" type="text" placeholder="https://www.facebook.com/GabineteJoven">
          </p>
        </div>
        <div class="field is-grouped">
          <div class="control">
            <a @click.prevent class="button is-medium is-static">
              <span class="icon">
                <i class="fas fa-envelope"></i>
              </span>
            </a>
          </div>
          <p class="control is-expanded">
            <input v-model="team.email" data-vv-name="team.email" data-vv-as="'Email de la organización'" v-validate="'email'" class="input is-medium" type="team.email" placeholder="Email de contacto">
            <span v-show="errors.has('team.email')" class="help is-danger">{{errors.first('team.email')}}</span>
          </p>
        </div>
        <div class="field is-grouped">
          <div class="control">
            <a @click.prevent class="button is-medium is-static">
              <span class="icon">
                <i class="fas fa-phone"></i>
              </span>
            </a>
          </div>
          <p class="control is-expanded">
            <input v-model="team.telephone" class="input is-medium" type="text" placeholder="Ej: 0342 - 4123456">
          </p>
        </div>
      </div>
      <div class="field">
            <label class="label is-size-4" :class="{'has-text-danger': errors.has('deUnaOrganizacion')}">
                <i class="fas fa-angle-double-right"></i> ¿El equipo pertenece a alguna organización o institución? * </label>
            <b-field>
                <b-radio-button v-model="deUnaOrganizacion" data-vv-name="deUnaOrganizacion" data-vv-as="'Organizacion a la que pertenece el equipo'" v-validate="'required'" :native-value="true" type="is-primary" size="is-medium">
                    <span>
                        <i class="fas fa-check"></i> Si</span>
                </b-radio-button>
                <b-radio-button v-model="deUnaOrganizacion" :native-value="false" type="is-primary" size="is-medium">
                    <span>
                        <i class="fas fa-times"></i> No</span>
                </b-radio-button>
            </b-field>
            <span v-show="errors.has('deUnaOrganizacion')" class="help is-danger">{{errors.first('deUnaOrganizacion')}}</span>
        </div>
        <br>
        <form-organizacion ref="formOrganizacion" v-if="deUnaOrganizacion" :organization.sync="team.parent_organization"></form-organizacion>
    </section>
</template>

<script>
import Localidad from "./FieldLocalidad";
import FormOrganizacion from "./FormOrganizacion";

export default {
  props: ["team"],
  components: {
    Localidad,
    FormOrganizacion
  },
  data() {
    return {
      deUnaOrganizacion: null,
      listPreviousEditions: [],
      filteredPreviousEditions: [],
      listYearFoundation: []
    };
  },
  mounted: function() {
    for (let i = 2000; i <= 2017; i++) {
      this.listPreviousEditions.push(i);
    }
    for (let i = 1900; i <= 2018; i++) {
      this.listYearFoundation.push(i);
    }
  },
  methods: {
    updateLocalidad: function(id) {
      this.team.locality_id = id;
    },
    updateLocalidadCustom: function(localityName) {
      this.team.locality_other = localityName;
    },
    validateForm: function() {
      let promise = new Promise((resolve, reject) => {
        this.$validator.validateAll().then(result => {
          if (!result) {
            console.log("Equipo: Hay errores en los datos");
            return resolve(result);
          }
          console.log("Equipo: OK");
          return resolve(result);
        });
      });
      return promise;
    },
    validateOrganizacion: function() {
      if (this.team.deUnaOrganizacion) {
        return this.$refs.formOrganizacion.validateForm();
      } else {
        return true;
      }
    },
    validateLocalidadOrganizacion: function() {
      if (this.deUnaOrganizacion) {
        return this.$refs.formOrganizacion.validateLocalidad();
      } else {
        return true;
      }
    },
    validateLocalidad: function() {
      return this.$refs.localidadForm.validateForm();
    }
  }
};
</script>
