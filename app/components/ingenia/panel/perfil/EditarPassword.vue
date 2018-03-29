<template>
<div>
    <h1 class="subtitle is-3">Cambiar mi contraseña</h1>
  <div v-if="facebookLogin">
    <div class="notification is-link">
      <h1 class="title is-4">
        <i class="fas fa-exclamation-triangle "></i> No es posible cambiar la contraseña</h1>
      Tu cuenta esta vinculada con tu perfil de Facebook.
    </div>
  </div>
  <div v-else>
    <h1 class="title is-3">Cambiar contraseña</h1>
    <label class="label">Contraseña actual</label>
    <p>A continuación, escriba su contraseña actual.</p>
    <div class="field">
      <div class="field-body">
        <div class="field">
          <div class="control is-expanded">
            <input v-model="passwordActual" data-vv-name="passwordActual" data-vv-as="'Contraseña actual'" type="password" class="input is-large" name="" v-validate="'required'" placeholder="Contraseña actual">
            <span v-show="errors.has('passwordActual')" class="help is-danger">{{errors.first('passwordActual')}}</span>
          </div>
        </div>
        <div class="field">
          <div class="control is-expanded">
            <input v-model="passwordConfirma" data-vv-name="passwordConfirma" data-vv-as="'Confirmacion'" type="password" class="input is-large" name="" v-validate="'required'" placeholder="Repita su contraseña">
            <span v-show="errors.has('passwordConfirma')" class="help is-danger">{{errors.first('passwordConfirma')}}</span>
          </div>
        </div>
      </div>
    </div>
    <br>
    <label class="label">Nueva contraseña</label>
    <p>A continuación, escriba su contraseña actual.</p>
    <div class="field">
      <div class="control">
        <input v-model="passwordNuevo" data-vv-name="passwordNuevo" data-vv-as="'Nueva contraseña'" type="password" class="input is-large" v-validate="'required'" placeholder="Nueva contraseña">
        <span v-show="errors.has('passwordNuevo')" class="help is-danger">{{errors.first('passwordNuevo')}}</span>
      </div>
    </div>
    <button class="button is-medium is-primary">Guardar</button>
  </div>
</div>
</template>

<script>
export default {
  props: ["id"],
  data() {
    return {
      user: {},
      facebookLogin: true,
      passwordActual: "",
      passwordConfirma: "",
      passwordNuevo: ""
    };
  },
  created: function() {
    this.user = this.$store.state.user;
  },
  methods: {
    submit: function() {
      this.$validator
        .validateAll()
        .then(result => {
          console.log(result);
        })
        .catch(x => {
          console.log(x);
        });
    }
  }
};
</script>

