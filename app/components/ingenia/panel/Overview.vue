<template>
  <article>
    <img src="/assets/img/ingenia-logo.svg" class="image" style="max-width: 150px;">
    <br>
    <h1 class="title is-2">¡Hola {{user.names}}!</h1>
    <p>¡Bienvenidx a tu panel de control! Aquí tendrás control de toda tu información
      <i class="em em-wink"></i>
    </p>
    <br>
    <status></status>
    <br>
    <br>
    <div class="notification is-light is-clearfix is-hidden">
      <router-link :to="{ name: 'userEditarPerfil'}" class="button is-dark is-dark is-pulled-right">Editar perfil</router-link>
      <span class="is-600 is-size-5">
        <i class="fas fa-angle-double-right fa-lg fa-fw"></i> ¡Mantené tu perfíl actualizado!</span>
      <br>Completá tus datos para que otras personas sepan quien sos<br>Si presentás un proyecto
      <b>Ingenia</b> te recomendamos que lo hagas para que la gente se sume a tu proyecto!
    </div>
    <section v-if="user.pending_tasks.length > 0">
      <hr>
      <h1 class="subtitle is-4">¿Vas a participar de INGENIA 2018?</h1>
      <p>Previo a ser parte de un equipo o de presentar tu proyecto INGENIA, debes cumplir los siguientes requerimientos.
        <div class="notification is-warning" v-show="user.pending_tasks.includes('email')">
          <router-link :to="{ name: 'userEditarEmail'}" class="button is-dark is-pulled-right" style="margin-left:20px;margin-bottom:10px;">Actualizar email</router-link>
          <span class="is-600 is-size-5">
            <i class="fas fa-angle-double-right fa-lg fa-fw"></i> Verificá tu email</span>
          <br>Si te registraste usando Facebook, es importante que registres un email para ser integrante de un equipo INGENIA
        </div>
        <div class="notification is-warning" v-show="user.pending_tasks.includes('profile')">
          <div class="is-clearfix">
            <router-link :to="{ name: 'userEditarMisDatosPersonales'}" class="button is-dark is-pulled-right" style="margin-left:20px">Editar datos</router-link>
            <span class="is-600 is-size-4">
              <i class="fas fa-angle-double-right fa-lg fa-fw"></i> Completá tus datos personales</span>
          </div>
          <br>Para ser integrante de un equipo INGENIA debes completar tus datos personales y tener entre 15 y 29 años.
          <b>NOTA: </b>Los responsable o co-responsable de un proyecto, deben tener entre 18 y 29 años.
        </div>
        <div class="notification is-warning" v-show="user.pending_tasks.includes('dni')">
          <router-link :to="{ name: 'userSubirDNI'}" class="button is-dark is-pulled-right" style="margin-left:20px;margin-bottom:10px;">Subir DNI</router-link>
          <span class="is-600 is-size-5">
            <i class="fas fa-angle-double-right fa-lg fa-fw"></i> Carga tu DNI</span>
          <br>Como requisito del reglamento, es fundamental que subas un archivo con tu DNI.
        </div>
    </section>
    <section v-else>
      <div class="notification is-success" v-if="user.groups[0] === undefined">
        <i class="fas fa-check fa-fw"></i> ¡Ya te encontras en condiciones para participar en un equipo INGENIA o presentando un proyecto!
      </div>
      <section v-else>
        <div class="notification is-link">
          <i class="fas fa-info fa-fw"></i> ¡Felicidades! ¡Sos parte del equipo <b>{{user.groups[0].name}}</b>! <i class="em em-muscle"></i>
        </div>
        <h1 class="title is-3">
          <i class="fas fa-tasks"></i> ¡Revisen si faltan datos a completar!</h1>
        <h5 class="subtitle is-5">Nota: El listado se actualiza cada
          <i class="fas fa-clock"></i> 5 minutos</h5>
        <div class="notification is-light is-clearfix" v-if="user.invitations.length > 0">
          <router-link :to="{ name: 'userVerInvitaciones'}" class="button is-dark is-dark is-pulled-right" style="margin-left:20px;margin-bottom:10px;">Ver invitaciones y solicitudes</router-link>
          <span class="is-600 is-size-5">
            <i class="fas fa-angle-double-right fa-lg fa-fw"></i> ¡Tenes invitaciones pendientes!</span>
          <p>
            ¡Te han invitado a ser parte de un equipo ingenia, no pierdas la chance!
          </p>
        </div>
        <div class="notification is-light is-clearfix" v-if="user.groups[0].pivot.relation == 'responsable'">
          <router-link :to="{ name: 'userVerIntegrantes'}" class="button is-dark is-dark is-pulled-right" style="margin-left:20px;margin-bottom:10px;">Ver integrantes</router-link>
          <span class="is-600 is-size-5">
            <i class="fas fa-angle-double-right fa-lg fa-fw"></i>Acordate de revisar invitaciones y solicitudes pendientes</span>
          <p>
            Recorda revisar tus invitaciones pendientes y las solicitudes que la gente envia para ser parte de tu equipo.
          </p>
        </div>
        <div class="notification is-light is-clearfix" v-if="!user.groups[0].full_team">
          <span class="is-600 is-size-5">
            <i class="fas fa-angle-double-right fa-lg fa-fw"></i>Sigan completando el equipo</span>
          <p>
            Recuerden que la cantidad mínima para ser un equipo valido debe ser de 5 integrantes.
          </p>
        </div>
        <div class="notification is-success" v-else>
          <i class="fas fa-check fa-fw"></i> ¡Bien! ¡Ya completaste el cupo mínimo de tu equipo INGENIA!
        </div>
        <div class="notification is-light is-clearfix" v-if="!user.groups[0].second_in_charge">
          <span class="is-600 is-size-5">
            <i class="fas fa-angle-double-right fa-lg fa-fw"></i>Deben asignar un co-responsable del equipo</span>
          <p>
            Uno de los requerimientos es que alguien del equipo sea co-responsable asignado. Recuerden confirmarlo en el listado.
          </p>
        </div>
        <div class="notification is-success" v-else>
          <i class="fas fa-check fa-fw"></i> ¡Bien! ¡Han asignado correctamente a un co-responsable en su equipo INGENIA!
        </div>
        <div class="notification is-light" v-if="!user.groups[0].uploaded_agreement">
          <span class="is-600 is-size-5">
            <i class="fas fa-angle-double-right fa-lg fa-fw"></i>Aún deben entregar la carta de conformidad del equipo</span>
          <p>
            No te olvides cuando tengas a todos los integrantes, de completar y subir un escaneo o foto con la carta de conformidad firmada por todos los integrantes del equipo.
          </p>
        </div>
        <div class="notification is-success" v-else>
          <i class="fas fa-check fa-fw"></i> ¡Genial! ¡La carta de conformidad ya fue entregada con éxito!
        </div>
        <section v-if="user.groups[0].project !== null">
        <div class="notification is-light" v-if="user.groups[0].project.organization !== null && !user.groups[0].uploaded_letter">
          <span class="is-600 is-size-5">
            <i class="fas fa-angle-double-right fa-lg fa-fw"></i>Aún deben entregar la carta de aval de la organización</span>
          <p>
            Tu proyecto se realiza en conjunto con la organizacion
            <b>{{user.groups[0].project.organization.name}}</b> por lo que es requerido que suban la carta con el aval de la organización firmada por ellos.
          </p>
        </div>
        <div class="notification is-success" v-else>
          <i class="fas fa-check fa-fw"></i> ¡Genial! ¡Han subido correctamente la carta de aval de la organización
        </div>
        </section>
        <div class="notification is-warning" v-else>
          <span class="is-600 is-size-5">
            <i class="fas fa-angle-double-right fa-lg fa-fw"></i>Aún no han cargado un proyecto</span>
          <p>
            Recordá que deben de cargar los datos del proyecto antes que se cumpla la fecha límite para la carga. Van a poder editar los datos todas las veces que lo necesiten.
          </p>
        </div>
      </section>
    </section>
  </article>
</template>

<script>
import Status from "../utils/Status";

export default {
  components: {
    Status
  },
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
