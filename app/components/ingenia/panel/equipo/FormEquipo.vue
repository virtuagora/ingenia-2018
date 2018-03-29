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
        <div class="field">
            <label class="label is-size-4" :class="{'has-text-danger': errors.has('equipo.localizacion.nodo') || errors.has('equipo.localizacion.departamento') || errors.has('equipo.localizacion.localidad') }">
                <i class="fas fa-angle-double-right"></i> ¿De donde es el equipo? *</label>
            <b-field grouped>
                <b-field label="Nodo" expanded>
                    <b-select v-model="equipo.localizacion.nodo" data-vv-name="equipo.localizacion.nodo" data-vv-as="'Región/Nodo'" v-validate="'required'" placeholder="Nodo" expanded>
                        <option>Mr.</option>
                        <option>Ms.</option>
                    </b-select>
                </b-field>
                <b-field label="Departamento" expanded>
                    <b-select v-model="equipo.localizacion.departamento" placeholder="Departamento" data-vv-name="equipo.localizacion.departamento" data-vv-as="'Departamento'" v-validate="'required'" expanded>
                        <option>Mr.</option>
                        <option>Ms.</option>
                    </b-select>
                </b-field>
                <b-field label="Localidad" expanded>
                    <b-select v-model="equipo.localizacion.localidad" placeholder="Localidad" data-vv-name="equipo.localizacion.localidad" data-vv-as="'Localidad'" v-validate="'required'" expanded>
                        <option>Mr.</option>
                        <option>Ms.</option>
                    </b-select>
                </b-field>
            </b-field>
            <span v-show="errors.has('equipo.localizacion.nodo')" class="help is-danger">{{errors.first('equipo.localizacion.nodo')}}</span>
            <span v-show="errors.has('equipo.localizacion.departamento')" class="help is-danger">{{errors.first('equipo.localizacion.departamento')}}</span>
            <span v-show="errors.has('equipo.localizacion.localidad')" class="help is-danger">{{errors.first('equipo.localizacion.localidad')}}</span>
        </div>
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
        <form-organizacion ref="formEquipoOrganizacion" v-if="equipo.deUnaOrganizacion" :organizacion.sync="equipo.organizacion"></form-organizacion>
    </section>
</template>

<script>
import FormOrganizacion from "../proyecto/FormOrganizacion";

export default {
  props: ["equipo"],
  components: {
    FormOrganizacion
  },
  methods: {
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
    validateOrganizacionForm: function() {
      if (this.equipo.deUnaOrganizacion) {
        return this.$refs.formEquipoOrganizacion.validateForm();
      } else {
        return true;
      }
    }
  }
};
</script>
