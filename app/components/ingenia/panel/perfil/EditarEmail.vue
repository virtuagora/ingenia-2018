<template>
  <section>
    <h1 class="subtitle is-3">Cambiar mi email asociado</h1>
    <div class="notification">Escriba aqui el mail asociado a su cuenta</div>
    <b-field horizontal label="Email actual">
      <input type="email" v-model="user.email" id="" class="input">
    </b-field>
    <b-field horizontal label="Nuevo email">
      <input type="email" v-model="user.email" id="" class="input">
    </b-field>
    <b-field horizontal label="Repita el nuevo email">
      <input type="email" v-model="user.email" id="" class="input">
    </b-field>
  </section>
</template>

<script>
export default {
  props: ["id"],
  data() {
    return {
      user: {},
      actualEmail: null,
      nuevoEmail: null,
      confirmarEmail: null      
    };
  },
  created: function() {
    this.user = this.$store.state.user;
  }
};
</script>
