<template>
  <div>
    <div>
      <div class="field">
        <label class="label is-size-4" :class="{'has-text-danger': errors.has('organization.name')}">
          <i class="fas fa-angle-double-right"></i> Nombre de la organización *</label>
        <input type="text" v-model="organization.name" data-vv-name="organization.name" data-vv-as="'Nombre organización'" v-validate="'required'" class="input is-medium" placeholder="Requerido *">
        <span v-show="errors.has('organization.name')" class="help is-danger">{{errors.first('organization.name')}}</span>
      </div>
      <br>
      <div class="field">
        <label class="label is-size-4">
          <i class="fas fa-angle-double-right"></i> ¿Qué temas trabaja la organización?</label>
        <div class="columns">
          <div class="column">
            <div class="field">
              <b-checkbox v-model="organization.topics" native-value="Arte y Cultura">Arte y Cultura</b-checkbox>
            </div>
            <div class="field">
              <b-checkbox v-model="organization.topics" native-value="Comunicación">Comunicación</b-checkbox>
            </div>
            <div class="field">
              <b-checkbox v-model="organization.topics" native-value="Deporte y recreación">Deporte y recreación</b-checkbox>
            </div>
          </div>
          <div class="column">
            <div class="field">
              <b-checkbox v-model="organization.topics" native-value="Diversidad Sexual">Diversidad Sexual</b-checkbox>
            </div>
            <div class="field">
              <b-checkbox v-model="organization.topics" native-value="Integración Social">Integración Social</b-checkbox>
            </div>
            <div class="field">
              <b-checkbox v-model="organization.topics" native-value="Medio Ambiente">Medio Ambiente</b-checkbox>
            </div>

          </div>
          <div class="column">
            <div class="field">
              <b-checkbox v-model="organization.topics" native-value="Salud y Discapacidad">Salud y Discapacidad</b-checkbox>
            </div>
            <div class="field">
              <b-checkbox v-model="organization.topics" native-value="Ciudadanía y Participación">Ciudadanía y Participación</b-checkbox>
            </div>
            <div class="field">
              <input type="text" v-model="organization.topic_other" class="input" placeholder="Otro tema">
            </div>
          </div>
        </div>
      </div>
        <Localidad ref="localidadForm" @updateLocalidad="updateLocalidad" @updateLocalidadCustom="updateLocalidadCustom"></Localidad>
      <div class="field">
        <label class="label is-size-4">
          <i class="fas fa-angle-double-right"></i> Redes y contacto</label>
        <div class="field is-grouped">
          <div class="control">
            <a @click.prevent class="button is-medium is-static">
              <span class="icon">
                <i class="fas fa-globe"></i>
              </span>
            </a>
          </div>
          <p class="control is-expanded">
            <input v-model="organization.web" data-vv-name="organization.web" data-vv-as="'Web de la organización'" v-validate="'url'" class="input is-medium" type="text" placeholder="URL página web (Ej: http://www.organization.org)">
            <span v-show="errors.has('organization.web')" class="help is-danger">{{errors.first('organization.web')}}</span>
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
            <input v-model="organization.facebook" class="input is-medium" type="text" placeholder="https://www.facebook.com/GabineteJoven">
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
            <input v-model="organization.email" data-vv-name="organization.email" data-vv-as="'Email de la organización'" v-validate="'email'" class="input is-medium" type="email" placeholder="Email de contacto">
            <span v-show="errors.has('organization.email')" class="help is-danger">{{errors.first('organization.email')}}</span>
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
            <input v-model="organization.telephone" class="input is-medium" type="text" placeholder="Ej: 0342 - 4123456">
          </p>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import Localidad from "./FieldLocalidad";

export default {
  props: ["organization"],
  data() {
    return {};
  },
  components: {
    Localidad
  },
  methods: {
    updateLocalidad: function(id){
        this.organization.locality = id
    },
    updateLocalidadCustom: function(localityName){
        this.organization.locality_other = localityName
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
