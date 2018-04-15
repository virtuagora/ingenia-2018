<template>
  <article class="content">
    <img src="/assets/img/ingenia-logo.svg" class="image" style="max-width: 150px;">
    <h1 class="title is-2">¡Hola {{user.names}}!</h1>
    <p>¡Bienvenidx a tu panel de control! Aquí tendrás control de toda tu información
      <i class="em em-wink"></i>
    </p>
    <div class="notification is-light is-clearfix">
      <router-link :to="{ name: 'userEditarPerfil'}" class="button is-dark is-dark is-pulled-right">Editar perfil</router-link>
      <span class="is-600 is-size-5">
        <i class="fas fa-angle-double-right fa-lg fa-fw"></i> ¡Mantené tu perfíl actualizado!</span>
      <br>Completá tus datos para que otras personas sepan quien sos<br>Si presentás un proyecto
      <b>Ingenia</b> te recomendamos que lo hagas para que la gente se sume a tu proyecto!
    </div>
    <hr>
    <section v-if="user.pending_tasks.length > 0">
      <h1 class="subtitle is-4">¿Vas a participar de INGENIA 2018?</h1>
      <p>Previo a ser parte de un equipo o de presentar tu proyecto INGENIA, debes cumplir los siguientes requerimientos.
        <div class="notification is-warning" v-show="user.pending_tasks.includes('email')">
          <a href="#" class="button is-dark is-pulled-right" style="margin-left:20px;margin-bottom:10px;">Actualizar email</a>
          <span class="is-600 is-size-5">
            <i class="fas fa-angle-double-right fa-lg fa-fw"></i> Verificá tu email</span>
          <br>Si te registraste usando Facebook, es importante que registres un email para ser integrante de un equipo INGENIA
        </div>
        <div class="notification is-warning" v-show="user.pending_tasks.includes('profile')">
          <div class="is-clearfix">
            <a href="#" class="button is-dark is-pulled-right" style="margin-left:20px">Editar datos</a>
            <span class="is-600 is-size-4">
              <i class="fas fa-angle-double-right fa-lg fa-fw"></i> Completá tus datos personales</span>
          </div>
          <br>Para ser integrante de un equipo INGENIA debes completar tus datos personales y tener entre 15 y 29 años.
          <b>NOTA: </b>Los responsable o co-responsable de un proyecto, deben tener entre 18 y 29 años.
        </div>
        <div class="notification is-warning" v-show="user.pending_tasks.includes('dni')">
          <a href="#" class="button is-dark is-pulled-right" style="margin-left:20px;margin-bottom:10px;">Subir DNI</a>
          <span class="is-600 is-size-5">
            <i class="fas fa-angle-double-right fa-lg fa-fw"></i> Carga tu DNI</span>
          <br>Como requisito del reglamento, es fundamental que subas un archivo con tu DNI.
        </div>
    </section>
    <section v-else>
      <div class="notification is-success" v-if="user.groups[0] === undefined">
        <i class="fas fa-check fa-fw"></i> ¡Ya te encontras en condiciones para participar de INGENIA!
      </div>
      <div class="notification is-link" v-else>
        <i class="fas fa-info fa-fw"></i> ¡Felicidades! ¡Sos parte del equipo {{user.groups[0].name}}!
      </div>
      <h1 class="subtitle is-4">¿Que podes hacer ahora?</h1>
      <div class="notification is-light is-clearfix" v-if="user.groups[0] === undefined && user.invitations.length > 0">
        <router-link :to="{ name: 'userVerInvitaciones'}" class="button is-dark is-dark is-pulled-right" style="margin-left:20px;margin-bottom:10px;">Ver invitaciones y solicitudes</router-link>
        <span class="is-600 is-size-5">
          <i class="fas fa-angle-double-right fa-lg fa-fw"></i> ¡Tenes invitaciones pendientes!</span>
        <p>
          ¡Te han invitado a ser parte de un equipo ingenia, no pierdas la chance!
        </p>
      </div>
      <div class="" v-if="user.groups[0] !== undefined">
          <div class="notification is-light is-clearfix" v-if="user.groups[0].pivot.relation == 'responsable'">
            <router-link :to="{ name: 'userVerInvitaciones'}" class="button is-dark is-dark is-pulled-right" style="margin-left:20px;margin-bottom:10px;">Ver integrantes</router-link>
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
          <div class="notification is-light is-clearfix" v-if="!user.groups[0].full_team">
            <span class="is-600 is-size-5">
              <i class="fas fa-angle-double-right fa-lg fa-fw"></i>Deben asignar un co-responsable del equipo</span>
            <p>
              Uno de los requerimientos es que alguien del equipo sea co-responsable asignado. Recuerden confirmarlo en el listado.
            </p>
          </div>
          <div class="notification is-success" v-else>
           <i class="fas fa-check fa-fw"></i> ¡Bien! ¡Ya completaste el cupo mínimo de tu equipo INGENIA!
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
          <div class="notification is-light" v-if="user.groups[0].project.organization !== null && user.groups[0].uploaded_letter">
           <span class="is-600 is-size-5">
              <i class="fas fa-angle-double-right fa-lg fa-fw"></i>Aún deben entregar la carta de aval de la organización</span>
            <p>
              Tu proyecto se realiza en conjunto con la organizacion <b>{{user.groups[0].project.organization.name}}</b> por lo que es requerido que suban la carta con el aval de la organización firmada por ellos.
            </p>
          </div>
      </div>
    </section>
  </article>
</template>

<script>
import GetGrupo from "../utils/GetGrupo";

export default {
  components: {
    GetGrupo
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
