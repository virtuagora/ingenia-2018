<template>
  <article class="content">
    <h1 class="title is-2">¡Hola {{this.user.names}}!</h1>
    <p>¡Bienvenidx a tu panel de control! Aquí tendrás control de toda tu información
      <i class="em em-wink"></i>
    </p>
    <div class="notification is-light is-clearfix">
      <a href="#" class="button is-dark is-dark is-pulled-right">Editar perfil</a>
      <span class="is-600 is-size-5">
        <i class="fas fa-angle-double-right fa-lg fa-fw"></i> No te olvides de completar tu perfil!</span>
      <br>Completá tus datos para que otras personas sepan quien sos<br>Si presentás un proyecto
      <b>Ingenia</b> te recomendamos que lo hagas para que la gente se sume a tu proyecto!
    </div>
    <hr>
      <img src="/assets/img/ingenia-logo.svg" class="image" style="max-width: 150px;">
    <h1 class="subtitle is-4">¿Vas a participar de INGENIA 2018?</h1>
    <section v-if="this.user.pending_tasks.length > 0">
    <p>Previo a ser parte de un equipo o de presentar tu proyecto INGENIA, debes cumplir los siguientes requerimientos.
      <div class="notification is-warning" v-show="this.user.pending_tasks.includes('email')">
        <a href="#" class="button is-dark is-pulled-right" style="margin-left:20px;margin-bottom:10px;">Actualizar email</a>
        <span class="is-600 is-size-5">
          <i class="fas fa-angle-double-right fa-lg fa-fw"></i> Verificá tu email</span>
        <br>Si te registraste usando Facebook, es importante que registres un email para ser integrante de un equipo INGENIA
      </div>
      <div class="notification is-warning" v-show="this.user.pending_tasks.includes('profile')">
        <div class="is-clearfix">
        <a href="#" class="button is-dark is-pulled-right" style="margin-left:20px">Editar datos</a>
        <span class="is-600 is-size-4">
          <i class="fas fa-angle-double-right fa-lg fa-fw"></i> Completá tus datos personales</span>
        </div>
        <br>Para ser integrante de un equipo INGENIA debes completar tus datos personales y tener entre 15 y 29 años. <b>NOTA: </b>Los responsable o co-responsable de un proyecto, deben tener entre 18 y 29 años.
      </div>
      <div class="notification is-warning" v-show="this.user.pending_tasks.includes('dni')">
        <a href="#" class="button is-dark is-pulled-right" style="margin-left:20px;margin-bottom:10px;">Subir DNI</a>
        <span class="is-600 is-size-5">
          <i class="fas fa-angle-double-right fa-lg fa-fw"></i> Carga tu DNI</span>
        <br>Como requisito del reglamento, es fundamental que subas un archivo con tu DNI.
      </div>
    </section>
      <div class="notification is-success" v-else >
      <i class="fas fa-check fa-fw"></i> Ya te encontras en condiciones para participar subiento tu proyecto INGENIA o siendo integrante de algún equipo
    </div>

  </article>
</template>

<script>
export default {
  props: ["id"],
  data() {
    return {
      user: {}
    };
  },
  created: function() {
    this.user = this.$store.state.user;
  }
};
</script>
