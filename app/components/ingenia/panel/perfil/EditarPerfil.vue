<template>
  <div>
    <h1 class="subtitle is-3">Editar perfil</h1>
    <div class="field">
      <label class="label is-size-4">
        <i class="fas fa-angle-double-right"></i> Acerca de mi</label>
      <p>¡Presentate! Contale a todos quien sos, que haces, tus hobbies, ¡lo que quieras! Si sos parte de un equipo INGENIA te recomendamos que lo completes así la gente puede saber mejor quien esta detras del proyecto.</p>
      <br>
      <div class="control">
        <b-input v-model="bio" maxlength="300" rows="3" type="textarea" placeholder="Escribi una breve descripción para que sepan quien sos y que haces! (Max: 300 caracteres)"></b-input>
      </div>
    </div>
    <div class="notification is-success" v-show="response.ok">
      <i class="fas fa-check fa-fw"></i> Datos enviados y guardados con éxito
    </div>
    <button @click="submit" v-show="!response.ok" class="button is-large is-primary is-fullwidth" :class="{'is-loading': isLoading}">
      <i class="fas fa-save"></i>&nbsp;&nbsp;Guardar mis datos</button>
    <b-loading :active.sync="isLoading"></b-loading>
    <!-- <label class="label is-size-4">
        <i class="fas fa-angle-double-right"></i> Mis redes sociales</label> -->
    <div class="field is-grouped" v-if="false">
      <div class="control">
        <a class="button is-medium is-static">
          <span class="icon">
            <i class="fab fa-facebook-square"></i>
          </span>
        </a>
      </div>
      <p class="control is-expanded">
        <input class="input is-medium" type="text" placeholder="Ej: https://www.facebook.com/GabineteJoven/">
      </p>
    </div>
    <div class="field is-grouped" v-if="false">
      <div class="control">
        <a class="button is-medium is-static">
          <span class="icon">
            <i class="fab fa-twitter"></i>
          </span>
        </a>
      </div>
      <p class="control is-expanded">
        <input class="input is-medium" type="text" placeholder="Ej: https://twitter.com/gabinetejoven">
      </p>
    </div>
    <div class="field is-grouped" v-if="false">
      <div class="control">
        <a class="button is-medium is-static">
          <span class="icon">
            <i class="fas fa-globe"></i>
          </span>
        </a>
      </div>
      <p class="control is-expanded">
        <input class="input is-medium" type="text" placeholder="Ej: http://www.santafe.gob.ar">
      </p>
    </div>
  </div>
</template>

<script>
export default {
  props: ["id", "saveUserPublicProfileUrl"],
  data() {
    return {
      bio: null,
      isLoading: false,
      response: {
        ok: false
      }
    };
  },
  created: function() {
    this.user = this.$store.state.user;
    this.bio = this.user.bio
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
    submit: function() {
      this.$validator
        .validateAll()
        .then(result => {
          if (result) {
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
        .catch(x => {
          this.$snackbar.open({
            message: "Error inesperado",
            type: "is-danger",
            actionText: "Cerrar"
          });
          return false;
        });
    }
  },
  computed: {
    payload: function() {
      return {
        bio: this.isOptional(this.bio)
      };
    },
    formUrl: function() {
      return this.saveUserPublicProfileUrl.replace(":usr", this.user.id);
    }
  }
};
</script>