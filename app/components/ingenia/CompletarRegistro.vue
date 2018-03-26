<template>
  <div>
    
  </div>
</template>

<script>
export default {
  props: ["message", "formUrl", "signUpUrl", "homeUrl"],
  data() {
    return {
      loginEmail: false,
      loginFacebook: false,
      register: false,
      userEmail: "",
      userPassword: "",
      registro: {
        email: "",
        enviando: null,
        enviado: null,
        ok: null
      }
    };
  },
  methods: {
    submit: function() {
      console.log(":)");
    },
    registrarUsuario: function() {
      console.log("Registrar Usuario");
      this.registro.enviando = true;
      // this.registro.enviado = true;
      // this.registro.ok = true;
    }
  },
  mounted: function() { 
    document.getElementById("loading").remove();
  }
};
</script>
