<template>
  <div>
    <h1 class="subtitle is-3">Actualizar mis datos personales</h1>
    <b-message>
      Uno de los requerimientos para ser integrante de un equipo INGENIA o presentar un proyecto INGENIA es el de completar tus datos personales. Completá los mismos en el siguiente formulario.
    </b-message>
    <div class="notification is-warning">¡IMPORTANTE! NO SUBAS LOS DATOS DE OTRA PERSONA.<b>ESTO SON -TUS- DATOS PERSONALES.</b></div>
    <label class="label is-size-5">
      <i class="fas fa-angle-double-right"></i> Registro en la plataforma *
    </label>
    <p v-if="user.subject.img_type === 0"><i class="fas fa-check has-text-success fa-fw fa-lg"></i><i class="fas fa-envelope fa-fw fa-lg"></i>&nbsp;Mediante correo electrónico: <b>{{user.email}}</b></p>
    <p v-if="user.subject.img_type === 1"><i class="fas fa-check has-text-success fa-fw fa-lg"></i><i class="fab fa-facebook has-text-link fa-fw fa-lg"></i>&nbsp;Utilizando mi perfil de Facebook: <a :href="'https://facebook.com/' + user.subject.img_hash" target="_blank">{{user.subject.display_name}}</a></p>
    <br>
    <label class="label is-size-5">
      <i class="fas fa-angle-double-right"></i> ¿De donde sos? *
    </label>
    <Localidad v-if="showLocalityField" ref="localidadForm" @updateLocalidad="updateLocalidad" @updateLocalidadCustom="updateLocalidadCustom"></Localidad>
    <div v-else>
      <button @click="cleanLocalidad" class="button is-light is-pulled-right">Cambiar mi ubicación</button>
      <show-localidad :locality-id="profile.locality_id" :locality-other="profile.locality_other"></show-localidad>
      <br>
      <br>
    </div>
    <div class="columns">
      <div class="column">
        <div class="field">
          <label class="label is-size-5" :class="{'has-text-danger': errors.has('profile.address')}">
            <i class="fas fa-angle-double-right"></i> Dirección *</label>
          <div class="control">
            <input v-model="profile.address" data-vv-name="profile.address" data-vv-as="'Dirección'" type="text" v-validate="'required'" class="input is-medium" placeholder="(Requerido)">
            <span v-show="errors.has('profile.address')" class="help is-danger">
              <i class="fas fa-times-circle fa-fw"></i>&nbsp;{{errors.first('profile.address')}}</span>
          </div>
        </div>
      </div>
      <div class="column">
        <div class="field">
          <label class="label is-size-5" :class="{'has-text-danger': errors.has('profile.neighbourhood')}">
            <i class="fas fa-angle-double-right"></i> Barrio *</label>
          <div class="control">
            <input v-model="profile.neighbourhood" data-vv-name="profile.neighbourhood" data-vv-as="'Barrio'" type="text" v-validate="'required'" class="input is-medium" placeholder="(Requerido)">
            <span v-show="errors.has('profile.neighbourhood')" class="help is-danger">
              <i class="fas fa-times-circle fa-fw"></i>&nbsp;{{errors.first('profile.neighbourhood')}}</span>
          </div>
        </div>
      </div>
    </div>
    <div class="columns">
      <div class="column">
        <div class="field">
          <label class="label is-size-5" :class="{'has-text-danger': errors.has('inputBirthday')}">
            <i class="fas fa-angle-double-right"></i> Fecha de nacimiento *</label>
          <div class="control">
            <b-datepicker placeholder="Hace clic para seleccionar la fecha" v-model="inputBirthday" :mobile-native="false" size="is-medium" :date-formatter="(date) => date.toLocaleDateString('es-AR')" :min-date="new Date('03/01/1988')" :max-date="new Date('12/31/2003')" icon="calendar-alt">
            </b-datepicker>
            <span v-show="errors.has('inputBirthday')" class="help is-danger">
              <i class="fas fa-times-circle fa-fw"></i>&nbsp;{{errors.first('inputBirthday')}}</span>
            <input type="hidden" v-model="inputBirthday" v-validate="'required'" data-vv-name="inputBirthday" data-vv-as="'Fecha de nacimiento'">
          </div>
        </div>
      </div>
      <div class="column">
        <label class="label is-size-5" :class="{'has-text-danger': errors.has('profile.telephone')}">
          <i class="fas fa-angle-double-right"></i> Teléfono *</label>
        <div class="field is-grouped">
          <div class="control">
            <a @click.prevent class="button is-medium is-static">
              <span class="icon">
                <i class="fas fa-phone"></i>
              </span>
            </a>
          </div>
          <p class="control is-expanded">
            <input v-model="profile.telephone" data-vv-name="profile.telephone" data-vv-as="'Teléfono'" class="input is-medium" type="text" v-validate="'required|max:20'" placeholder="(Requerido) Ej: 0342 - 4123456">
            <span v-show="errors.has('profile.telephone')" class="help is-danger">
              <i class="fas fa-times-circle fa-fw"></i>&nbsp;{{errors.first('profile.telephone')}}</span>
          </p>
        </div>
      </div>
    </div>
    <div class="field">
      <label class="label is-size-4" :class="{'has-text-danger': errors.has('profile.gender')}">
        <i class="fas fa-angle-double-right"></i> Genero *</label>
      <b-field>
        <b-select size="is-medium" data-vv-name="profile.gender" data-vv-as="'Genero'" v-validate="'required'" v-model="profile.gender" placeholder="Seleccioná tu genero" expanded>
          <option v-for="(genero, index) in genderList" :key="index" :value="genero">{{genero}}</option>
        </b-select>
      </b-field>
      <span v-show="errors.has('profile.gender')" class="help is-danger">
        <i class="fas fa-times-circle fa-fw"></i>&nbsp;{{errors.first('profile.gender')}}</span>
    </div>
    <br>
    <div class="field">
      <label class="label is-size-4" :class="{'has-text-danger': errors.has('enEjecucion')}">
        <i class="fas fa-angle-double-right"></i> (Opcional) ¿Cómo te enteraste de Ingenia?</label>
      <div class="columns">
        <div class="column">
          <div class="field">
            <b-radio v-model="profile.referer" native-value="Nos aviso un/a amigo/a">Nos aviso un/a amigo/a</b-radio>
          </div>
          <div class="field">
            <b-radio v-model="profile.referer" native-value="Nos aviso el Gabinete Joven">Nos aviso el Gabinete Joven</b-radio>
          </div>
          <div class="field">
            <b-radio v-model="profile.referer" native-value="Lo escuchamos/vimos por radio/TV">Lo escuchamos/vimos por radio/TV</b-radio>
          </div>
        </div>
        <div class="column">
          <div class="field">
            <b-radio v-model="profile.referer" native-value="Lo vimos en diarios/revistas">Lo vimos en diarios/revistas</b-radio>
          </div>
          <div class="field">
            <b-radio v-model="profile.referer" native-value="Lo vimos en las Redes Sociales">Lo vimos en las redes sociales</b-radio>
          </div>
        </div>
      </div>
      <label class="label">Por otro forma:</label>
      <div class="control">
        <b-input v-model="profile.referer_other" data-vv-name="profile.referer_other" data-vv-as="'Como te enteraste'" v-validate="'max:200'" type="textarea" minlength="10" maxlength="200" rows="3" placeholder="Opcional. ¿Como te enteraste de Ingenia?">
        </b-input>
        <span v-show="errors.has('profile.referer_other')" class="help is-danger">
          <i class="fas fa-times-circle fa-fw"></i>&nbsp;{{errors.first('profile.referer')}}</span>
      </div>
    </div>
    <div class="notification is-success" v-show="response.ok">
      <i class="fas fa-check fa-fw"></i> Datos enviados y guardados con éxito
    </div>
    <button @click="submit" v-show="!response.ok" class="button is-large is-primary is-fullwidth" :class="{'is-loading': isLoading}">
      <i class="fas fa-save"></i>&nbsp;&nbsp;Guardar mis datos</button>
    <b-loading :active.sync="isLoading"></b-loading>
  </div>

</template>

<script>
import Localidad from "../../utils/FieldLocalidad";
import ShowLocalidad from "../../utils/GetLocalidad";
export default {
  props: ["saveUserProfileUrl"],
  components: {
    Localidad,
    ShowLocalidad
  },
  data() {
    return {
      isLoading: false,
      genderList: ["Hombre", "Mujer", "HombreTrans", "MujerTrans", "Intersex"],
      inputBirthday: null,
      response: {
        ok: false
      },
      showLocalityField: false,
      profile: {
        birthday: null,
        gender: null,
        address: null,
        telephone: null,
        locality_id: null,
        locality_other: null,
        neighbourhood: null,
        referer: null,
        referer_other: null,
      },
      user: {}
    };
  },
  created: function() { 
    this.user = this.$store.state.user;
    this.profile = {
      birthday: this.user.birthday,
        gender: this.user.gender,
        address: this.user.address,
        telephone: this.user.telephone,
        locality_id: this.user.locality_id,
        locality_other: this.user.locality_other,
        neighbourhood: this.user.neighbourhood,
        referer: this.isOptional(this.user.referer),
        referer_other: this.isOptional(this.user.referer_other),
      }
      this.showLocalityField = this.user.locality_id === null ? true : false 
      this.inputBirthday = this.user.birthday === null ? null : new Date(this.user.birthday)
  },
  methods: {
    isOptional: function(value) {
      if (value === null || value === "") {
        return null;
      }
      if (typeof value !== "undefined" && value.length == 0) {
        return [];
      } else return value;
    },
    updateLocalidad: function(id) {
      this.profile.locality_id = id;
    },
    updateLocalidadCustom: function(localidadCustom) {
      this.profile.locality_other = localidadCustom;
    },
    cleanLocalidad: function(){
      this.profile.locality_id = null
      this.profile.locality_other = null
      this.showLocalityField = true
    },
    submit: function() {
      Promise.all([
        this.$validator.validateAll(),
        (this.showLocalityField ? this.$refs.localidadForm.validateForm() : true)
      ])
        .then(values => {
          if (
            values.every(x => {
              return x == true;
            })
          ) {
            console.log("Sending form!");
            this.isLoading = true;
            this.$http
              .post(this.formUrl, this.payload)
              .then(response => {
                this.$snackbar.open({
                  message: "Tus datos personales han sido actualizados",
                  type: "is-success",
                  actionText: "OK"
                });
                this.isLoading = false;
                this.response.ok = true;
                this.forceUpdateState('userPanel')
              })
              .catch(error => {
                console.error(error.message);
                this.isLoading = false;
                this.$snackbar.open({
                  message: "Error inesperado",
                  type: "is-danger",
                  actionText: "Cerrar"
                });
                return false;
              });
          } else {
            this.$snackbar.open({
              message: "Algunos datos faltan o son incorrectos. Verifíquelos.",
              type: "is-danger",
              actionText: "Cerrar"
            });
          }
        })
        .catch(result => {
          console.error(result);
          this.$snackbar.open({
            message: "Error inesperado.",
            type: "is-danger",
            actionText: "Cerrar"
          });
        });
    }
  },
  computed: {
    payload: function() {
      return {
        birthday: this.profile.birthday,
        gender: this.profile.gender,
        address: this.profile.address,
        telephone: this.profile.telephone,
        locality_id: this.profile.locality_id,
        locality_other: this.isOptional(this.profile.locality_other),
        neighbourhood: this.profile.neighbourhood,
        referer: this.isOptional(this.profile.referer),
        referer_other: this.isOptional(this.profile.referer_other)
      };
    },
    formUrl: function() {
      return this.saveUserProfileUrl.replace(":usr", this.user.id);
    }
  },
  watch: {
    inputBirthday: function(newVal) {
      this.profile.birthday = newVal.toISOString().split("T")[0];
    }
  }
};
</script>
