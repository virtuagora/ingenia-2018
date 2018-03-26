<template>
  <div>
    <div v-show="!loaded && !error && !success" class="notification is-link">
      <i class="fas fa-cog fa-spin fa-lg fa-fw"></i>
      Iniciando sesion con Facebook...
    </div>
    <div v-show="loaded && !error && !success" class="notification is-link">
      Confirme el inicio de sesión de
      <i class="fab fa-facebook-square fa-lg fa-fw"></i> en la ventana emergente.
      <hr>
      <p class="is-size-7">
        <b>¿No encuentra la ventana emergente?</b> Verifique que su navegador no este bloqueando las ventanas emergentes.
      </p>
    </div>
    <div v-show="loaded && error && !success" class="notification is-danger">
      <p>
        <i class="fas fa-times fa-lg fa-fw"></i>
        <b>Error al iniciar sesión con
          <i class="fab fa-facebook-square fa-lg fa-fw"></i> Facebook
        </b>
      </p>
      <hr>
      <p class="is-size-7">La acción fue cancelada o no se dieron los permisos necesarios para proceder. Vuelva a intentar recargando la página.</p>
    </div>
    <div v-show="loaded && !error && success" class="notification is-link">
      <i class="fas fa-cog fa-check fa-lg fa-fw"></i>
      <i class="fas fa-cog fa-spin fa-lg fa-fw"></i>
      Iniciando sesión.. ¡Un segundo!
    </div>
    <form ref="sendFacebookLoginForm" :action="fbLoginAction" method="post">
      <input type="hidden" name="access" :value="accessToken">
    </form>
  </div>
</template>

<script>
import loadFBSDK from "facebook-sdk-promise";

export default {
  props: ['fbLoginAction'],
  data: function() {
    return {
      FB: null,
      error: false,
      loaded: false,
      success: false,
      accessToken: null
    };
  },
  beforeCreate: function() {
    loadFBSDK().then(FB => {
      console.log("Facebook SDK has loaded!", FB);
      this.FB = FB;
      this.loaded = true;
      FB.init({
        appId: "191906834751311",
        autoLogAppEvents: true,
        xfbml: true,
        cookie: true,
        version: "v2.2"
      });
      FB.login(
        response => {
          {
            if (response.authResponse) {
              //alert('You are logged in &amp; cookie set!');
              //alert(response.authResponse.accessToken);
              this.success = true;
              this.accessToken = response.authResponse.accessToken;
              this.$refs.sendFacebookLoginForm.submit();
              document.getElementById("access").value =
                response.authResponse.accessToken;
              // Now you can redirect the user or do an AJAX request to
              // a PHP script that grabs the signed request from the cookie.
            } else {
              this.error = true;
              this.accessToken = 'lalalalalalall'             
              this.$refs.sendFacebookLoginForm.submit();              
              // alert("User cancelled login or did not fully authorize.");
            }
          }
        },
        { scope: "email" }
      );
    });
  }
};
</script>
