<template>
  <article class="content">
    <h1 class="subtitle is-3">¡Hola {{formUrl}}!</h1>
    <p>¡Bienvenidx a tu panel de control! Aquí tendras control de toda tu información
      <i class="em em-wink"></i>
    </p>
    <div class="notification is-light is-clearfix">
      <a href="#" class="button is-dark is-dark is-pulled-right">Editar perfil</a>
      <span class="is-600 is-size-5"><i class="fas fa-angle-double-right fa-lg fa-fw"></i> No te olvides de completar tu perfil!</span>
      <br>Completá tus datos para que otras personas sepan quien sos<br>Si presentas un proyecto <b>Ingenia</b> te recomendamos que lo hagas para que la gente se sume a tu proyecto!
    </div>
    <div class="notification is-warning">
      <a href="#" class="button is-dark is-dark is-pulled-right">Editar email</a>
      <span class="is-600 is-size-5"><i class="fas fa-angle-double-right fa-lg fa-fw"></i> Completá tu email</span>
      <br>¿Participas en algun proyecto Ingenia?<br>Es importante que nos mantengamos informados. Por eso es necesario que ingreses tu dirección de email en el sistema.
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

    // console.log(this.$store.state)
    // // console.log(this.$store.getters.get('user'))
    // this.user = (this.$store.getters.get).user;
    // console.log(this.$store.getters.get)
    // Object.assign(this.user,this.$store.getters.getKey('user'))
  }
  // computed:{
  //   user: function(){
  //    return this.$store.getters.get('user')
  //   }
  // }
  // created: function() {
  //   this.user = this.$store.state.user
  // }
};
</script>
