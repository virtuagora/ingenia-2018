<template>
  <article>
    <img src="/assets/img/ingenia-logo.svg" class="image" style="max-width: 150px;">
    <br>
    <h1 class="title is-2">¡Hola {{user.names}}!</h1>
    <h1 class="subtitle is-5" v-if="user.groups[0] !== undefined">
          <i class="em em-tada"></i> Miembro del equipo <b>{{user.groups[0].name}}</b> </h1>
    <p>¡Bienvenidx a tu panel! Aquí tendrás control de toda tu información
      <i class="em em-wink"></i>
    </p>
    <br>
    <p v-if="user.groups[0] === undefined">Previo a ser parte de un equipo o de presentar tu proyecto INGENIA, debes cumplir los siguientes requerimientos.</p>
    <br>
    <status></status>
    <br>
    <countdown :date="deadline"></countdown>
    <div class="notification is-info" v-if="user.groups[0] !== undefined && user.groups[0].project !== null">
        <i class="fas fa-eye fa-fw"></i> Tu proyecto está publico en la web, podes ingresar haciendo <b><a :href="'/proyecto/' + user.groups[0].project.id">clic aqui</a></b>
      </div>
        <!-- <div class="notification is-light is-clearfix" v-if="user.invitations.length > 0">
          <router-link :to="{ name: 'userVerInvitaciones'}" class="button is-dark is-dark is-pulled-right" style="margin-left:20px;margin-bottom:10px;">Ver invitaciones y solicitudes</router-link>
          <span class="is-600 is-size-5">
            <i class="fas fa-angle-double-right fa-lg fa-fw"></i> ¡Tenes invitaciones pendientes!</span>
          <p>
            ¡Te han invitado a ser parte de un equipo ingenia, no pierdas la chance!
          </p>
        </div>       -->
  </article>
</template>

<script>
import Status from "../utils/Status";
import Countdown from "../utils/Countdown";

export default {
  components: {
    Status,
    Countdown
  },
  props: ["id","deadline"],
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
