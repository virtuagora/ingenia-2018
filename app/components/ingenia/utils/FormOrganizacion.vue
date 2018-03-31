<template>
  <div>
    <div>
      <div class="field">
        <label class="label is-size-4" :class="{'has-text-danger': errors.has('organizacion.nombre')}">
          <i class="fas fa-angle-double-right"></i> Nombre de la organización *</label>
        <input type="text" v-model="organizacion.nombre" data-vv-name="organizacion.nombre" data-vv-as="'Nombre organización'" v-validate="'required'" class="input is-medium" placeholder="Requerido *">
        <span v-show="errors.has('organizacion.nombre')" class="help is-danger">{{errors.first('organizacion.nombre')}}</span>

      </div>
      <br>
      <div class="field">
        <label class="label is-size-4">
          <i class="fas fa-angle-double-right"></i> ¿Qué temas trabaja la organización?</label>
        <div class="columns">
          <div class="column">
            <div class="field">
              <b-checkbox v-model="organizacion.tematicas" native-value="Arte y Cultura">Arte y Cultura</b-checkbox>
            </div>
            <div class="field">
              <b-checkbox v-model="organizacion.tematicas" native-value="Comunicación">Comunicación</b-checkbox>
            </div>
            <div class="field">
              <b-checkbox v-model="organizacion.tematicas" native-value="Deporte y recreación">Deporte y recreación</b-checkbox>
            </div>
          </div>
          <div class="column">
            <div class="field">
              <b-checkbox v-model="organizacion.tematicas" native-value="Diversidad Sexual">Diversidad Sexual</b-checkbox>
            </div>
            <div class="field">
              <b-checkbox v-model="organizacion.tematicas" native-value="Integración Social">Integración Social</b-checkbox>
            </div>
            <div class="field">
              <b-checkbox v-model="organizacion.tematicas" native-value="Medio Ambiente">Medio Ambiente</b-checkbox>
            </div>

          </div>
          <div class="column">
            <div class="field">
              <b-checkbox v-model="organizacion.tematicas" native-value="Salud y Discapacidad">Salud y Discapacidad</b-checkbox>
            </div>
            <div class="field">
              <b-checkbox v-model="organizacion.tematicas" native-value="Ciudadanía y Participación">Ciudadanía y Participación</b-checkbox>
            </div>
            <div class="field">
              <input type="text" v-model="organizacion.otraTematica" class="input" placeholder="Otro tema">
            </div>
          </div>
        </div>
      </div>
        <Localidad ref="localidadForm" @updateLocalidad="updateLocalidad" @updateLocalidadCustom="updateLocalidadCustom"></Localidad>
      <br>
      <div class="field">
        <label class="label is-size-4">
          <i class="fas fa-angle-double-right"></i> Redes y contacto *</label>
        <div class="field is-grouped">
          <div class="control">
            <a @click.prevent class="button is-medium is-static">
              <span class="icon">
                <i class="fas fa-globe"></i>
              </span>
            </a>
          </div>
          <p class="control is-expanded">
            <input v-model="organizacion.contacto.web" data-vv-name="organizacion.contacto.web" data-vv-as="'Web de la organización'" v-validate="'url'" class="input is-medium" type="text" placeholder="URL página web (Ej: http://www.organizacion.org)">
            <span v-show="errors.has('organizacion.contacto.web')" class="help is-danger">{{errors.first('organizacion.contacto.web')}}</span>
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
            <input v-model="organizacion.contacto.facebook" class="input is-medium" type="text" placeholder="https://www.facebook.com/GabineteJoven">
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
            <input v-model="organizacion.contacto.email" data-vv-name="organizacion.contacto.email" data-vv-as="'Email de la organización'" v-validate="'email'" class="input is-medium" type="email" placeholder="Email de contacto">
            <span v-show="errors.has('organizacion.contacto.email')" class="help is-danger">{{errors.first('organizacion.contacto.email')}}</span>
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
            <input v-model="organizacion.contacto.telefono" class="input is-medium" type="text" placeholder="Ej: 0342 - 4123456">
          </p>
        </div>
      </div>
      <br>
    </div>
  </div>
</template>

<script>
import Localidad from "./FieldLocalidad";

export default {
  props: ["organizacion"],
  data() {
    return {};
  },
  components: {
    Localidad
  },
  methods: {
    updateLocalidad: function(id){
        this.organizacion.localidad = id
    },
    updateLocalidadCustom: function(localidadCustom){
        this.organizacion.otraLocalidad = localidadCustom
    },
    validateForm: function() {
      let promise = new Promise((resolve, reject) => {
        this.$validator.validateAll().then(result => {
          if (!result) {
            console.log("Organizacion: Hay errores en los datos");
            return resolve(result);
          }
          console.log("Organizacion: OK");
          return resolve(result);
        });
      });
      return promise;
    },
    validateLocalidad: function(){
        return this.$refs.localidadForm.validateForm();
    }
  }
};
</script>
