<template>
    <section>
        <div class="field">
            <label class="label is-size-4" :class="{'has-text-danger': errors.has('equipo.nombre')}">
                <i class="fas fa-angle-double-right"></i> Nombre del equipo</label>
            <div class="control">
                <input v-model="equipo.nombre" data-vv-name="equipo.nombre" data-vv-as="'Nombre del equipo'" type="text" v-validate="'required'" class="input is-large" placeholder="Requerido *">
                <span v-show="errors.has('equipo.nombre')" class="help is-danger">{{errors.first('equipo.nombre')}}</span>
            </div>
        </div>
        <div class="field">
            <label class="label is-size-4" :class="{'has-text-danger': errors.has('equipo.descripcion')}">
                <i class="fas fa-angle-double-right"></i> Acerca del equipo</label>
            <p>Breve descripción de tu equipo. Contanos acerca de a qué se dedican, como se formó, actividades mas recientes, etc. Máximo 250 caracteres</p>
            <div class="control">
                <b-input v-model="equipo.descripcion" data-vv-name="equipo.descripcion" data-vv-as="'Descripción del equipo'" v-validate="'required|min:10|max:250'" type="textarea" minlength="10" maxlength="250" rows="3" placeholder="Requerido *. Breve descripcion de tu equipo">
                </b-input>
                <span v-show="errors.has('equipo.descripcion')" class="help is-danger">{{errors.first('equipo.descripcion')}}</span>
            </div>
        </div>
        <Localidad ref="localidadForm" @updateLocalidad="updateLocalidad" @updateLocalidadCustom="updateLocalidadCustom"></Localidad>
        <div class="field">
            <label class="label is-size-4" :class="{'has-text-danger': errors.has('equipo.fundacion')}">
                <i class="fas fa-angle-double-right"></i> ¿En que año se conformó el grupo? *</label>
            <b-field expanded>
                <b-select size="is-medium" v-model="equipo.fundacion" data-vv-name="equipo.fundacion" data-vv-as="'Región/Nodo'" v-validate="'required'" placeholder="Nodo" expanded>
                    <option v-for="n in 29" :key="n" :value="2019-n">{{2019-n}}</option>
                </b-select>
            </b-field>
        </div>
        <div class="field">
            <label class="label is-size-4" :class="{'has-text-danger': errors.has('equipo.deUnaOrganizacion')}">
                <i class="fas fa-angle-double-right"></i> ¿El grupo pertenece a alguna organización o institución? * </label>
            <b-field>
                <b-radio-button v-model="equipo.deUnaOrganizacion" data-vv-name="equipo.deUnaOrganizacion" data-vv-as="'Organizacion a la que pertenece el equipo'" v-validate="'required'" :native-value="true" type="is-primary" size="is-medium">
                    <span>
                        <i class="fas fa-check"></i> Si</span>
                </b-radio-button>
                <b-radio-button v-model="equipo.deUnaOrganizacion" :native-value="false" type="is-primary" size="is-medium">
                    <span>
                        <i class="fas fa-times"></i> No</span>
                </b-radio-button>
            </b-field>
            <span v-show="errors.has('equipo.deUnaOrganizacion')" class="help is-danger">{{errors.first('equipo.deUnaOrganizacion')}}</span>
        </div>
        <br>
        <form-organizacion ref="formOrganizacion" v-if="equipo.deUnaOrganizacion" :organizacion.sync="equipo.organizacion"></form-organizacion>
    </section>
</template>

<script>
import Localidad from "./FieldLocalidad";
import FormOrganizacion from "./FormOrganizacion";

export default {
  props: ["equipo"],
  components: {
    Localidad,
    FormOrganizacion
  },
  methods: {
    updateLocalidad: function(id) {
      this.equipo.localidad = id;
    },
    updateLocalidadCustom: function(localidadCustom) {
      this.equipo.otraLocalidad = localidadCustom;
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
      if (this.equipo.deUnaOrganizacion) {
        return this.$refs.formOrganizacion.validateForm();
      } else {
        return true;
      }
    },
    validateLocalidadOrganizacion: function() {
      if (this.equipo.deUnaOrganizacion) {
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
