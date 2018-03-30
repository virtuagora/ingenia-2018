<template>
  <div>
    <h1 class="subtitle is-3">Editar perfil</h1>
    <div class="field">
      <label class="label is-size-4">
        <i class="fas fa-angle-double-right"></i> Acerca de mi</label>
      <div class="control">
         <b-input maxlength="300" rows="3" type="textarea" placeholder="Escribi una breve descripción para que sepan quien sos y que haces! (Max: 300 caracteres)"></b-input>
      </div>
    </div>
    <label class="label is-size-4">
        <i class="fas fa-angle-double-right"></i> Mis redes sociales</label>
    <div class="field is-grouped">
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
    <div class="field is-grouped">
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
    <div class="field is-grouped">
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
  props: ["id"],
  data() {
    return {
      user: {
        bio: null,
        localidad: null,
        redes:{
          facebook: null,
          twitter: null,
          website: null
        }
      }
    };
  },
  created: function() {
    this.user = this.$store.state.user;
  }
};
</script>