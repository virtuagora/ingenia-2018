<template>
  <div>
    <div class="notification is-warning has-text-centered">
      <b>
        <i class="fas fa-eye"></i> Estas viendo tu perfil público</b><br>Los datos personales para participar de un proyecto no se ven publicamente
    </div>
    <article class="media">
      <Avatar :subject="user.subject" class="media-left" size="128" />
      <div class="media-content" v-if="user != {}">
        <h1 class="title is-1">{{user.subject.display_name}}</h1>
        <h1 class="subtitle is-4" v-show="user.groups[0] !== undefined">
          <i class="em em-tada"></i> Participante de INGENIA 2018</h1>
        <div class="field">
        </div>
        <div class="field">
          <label class="label">Acerca de mí</label>
          <p class="nl2br" v-if="user.bio === null">
            <i>- Sin información -</i>
          </p>
          <p class="nl2br" v-else>{{user.bio}}</p>
        </div>
        <div class="field" v-if="user.groups[0] !== undefined">
          <label class="label">Acerca de mi equipo INGENIA</label>
          <p>Soy miembro del equipo <b>{{user.groups[0].name}}</b></p>
        </div>
         <div class="field" v-if="user.groups[0] !== undefined && user.groups[0].project !== null">
          <label class="label">Acerca proyecto INGENIA</label>
          <p>Mi proyecto se llama <a :href="'/proyecto/' + user.groups[0].project.id">{{user.groups[0].project.name}}</a></p>
          <p>Visitanos y bancanos! <i class="em em-muscle"></i></p>
        </div>
      </div>
      <div class="media-content" v-else>
        <span class="icon">
          <i class="fas fa-cog fa-spin"></i>
        </span> Loading . . .
      </div>
    </article>
  </div>
</template>

<script>
import Avatar from "../../utils/Avatar";
export default {
  props: ["id"],
  components: {
    Avatar
  },
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