<template>
  <div>
    <div class="tabs">
  <ul>
    <li :class="{'is-active': $route.name == 'userVerPerfil'}"><router-link :to="{ name: 'userVerPerfil'}">Ver perfil</router-link></li>
    <li :class="{'is-active': $route.name == 'userEditarPerfil'}"><router-link :to="{ name: 'userEditarPerfil'}">Editar perfil</router-link></li>
    <li :class="{'is-active': $route.name == 'userEditarAvatar'}"><router-link :to="{ name: 'userEditarAvatar'}">Cambiar avatar</router-link></li>
  </ul>
</div>
    <h1 class="subtitle is-3">Cambiar avatar</h1>
    <p>Virtuagora no gestiona las imagenes de perfil. Integramos el uso de otros servicios para los avatares, en el caso de que estes participando de multiples instancias de Virtuágora.</p>
    <br>
    <div class="notification is-info">
      ¡Te animamos a que si formas parte de un equipo, muestres quien sos con una imagen de perfil!
    </div>
    <p>Para cambiar tu imagen de perfil seguí las siguientes instrucciones:</p>
    <br>
    <div class="columns">
      <div class="column" v-if="user.subject.img_type == 0">
        <h1 class="subtitle is-4">Si te registraste con
          <i class="fas fa-envelope"></i>
        </h1>
        <hr>
        <ul>
          <li>
          <i class="fas fa-angle-right fa-fw fa-lg"></i> Registrate en <a href="https://es.gravatar.com/">Gravatar.com</a> utilizando <u>el mismo email</u> con el que usas para loguearte en Ingenia
          </li>
          <li>
          <i class="fas fa-angle-right fa-fw fa-lg"></i> Una vez registrado, subí una nueva imagen de perfil y asocialo a tu email
          </li>
          <li>
          <i class="fas fa-angle-right fa-fw fa-lg"></i> Una vez realizado, en unos minutos tu imagen de perfil deberia actualizarse. ¡Paciencia!
          </li>
          <li>
          <i class="fas fa-angle-right fa-fw fa-lg"></i> Recordá: Cada vez que quieras cambiar tu imagen de perfil, deberas de hacerlo desde <a href="https://es.gravatar.com/">Gravatar.com</a>
          </li>
        </ul>
      </div>
      <div class="column" v-if="user.subject.img_type == 1"> 
        <h1 class="subtitle is-4">Si te registraste con
          <i class="fab fa-facebook"></i>
        </h1>
        <hr>
        <p>Si te registraste y te logueas utilizando tu cuenta de Facebook, la única forma de actualizar tu imagen de perfil es actualizando tu imagen de Facebook. Ambos están sincronizados, así que para cambiar tu imagen de perfil deberas de hacerlo desde <a href="https://facebook.com/">Facebook.com</a></p>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: ["id"],
  data() {
    return {
      user: {},
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

