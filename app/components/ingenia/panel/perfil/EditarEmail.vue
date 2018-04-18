<template>
  <section>
    <h1 class="subtitle is-3">Verificar mi email</h1>
    <b-message>Uno de los requerimientos para ser integrante de un equipo INGENIA o presentar un proyecto INGENIA es el de tener una dirección de email verificada. A continuación escribí tu dirección de email. Un email te llegará para que puedas validar tu email.</b-message>
    <section v-if="this.user.pending_tasks.includes('email')">
    <b-field horizontal custom-class="is-medium" label="Nuevo email">
      <input type="email" v-model="email" class="input is-medium" v-validate="'email'">
    </b-field>
    </section>
    <div class="notification is-success" v-else>
      <i class="fas fa-check fa-fw"></i> Tu mail ha sido ingresado y verificado correctamente
    </div>
  </section>
</template>

<script>
export default {
  props: ["id"],
  data() {
    return {
      email: null,
      user: {}
    };
  },
  created: function() {
    this.user = this.$store.state.user;
  }
};
</script>
