<template>
  <section>
    <div class="media">
      <div class="media-left is-hidden-touch">
        <img src="/assets/img/icons-historia.svg" class="logo-header image is-centered" style="width:150px">
      </div>
      <div class="media-content has-text-right-desktop has-text-centered-touch" style="overflow: visible">
        <img src="/assets/img/icons-historia.svg" class="logo-header image is-centered is-hidden-desktop" style="width:150px">
        <h1 class="title is-size-2-desktop is-size-3-touch is-600">Toda historia nace en equipo</h1>
        <h1 class="subtitle is-5 is-size-6-touch">Acercate a sus historias y conocelos mas personalmente</h1>
      </div>
    </div>
    <br>
    <!-- Main container -->
    <nav class="level">
      <!-- Left side -->
      <div class="level-left">
        <div class="level-item">
          <p class="subtitle is-5">
            <strong>0</strong> historias
          </p>
        </div>
      </div>

      <!-- Right side -->
      <div class="level-right">
        <p class="level-item">
          <a class="button is-info is-400 is-outlined"><i class="fas fa-edit fa-fw"></i>&nbsp;Publicar una nueva historia</a>
        </p>
      </div>
    </nav>
    <div class="has-text-centered box">
      <h1 class="subtitle is-5 is-marginless">El equipo aún no ha publicado ninguna historia</h1>
    </div>
    <hr>
  </section>
</template>

<script>
export default {
  props: [
    "project",
    "sendRequestJoin",
    "notes",
    "isAdmin",
    "isCoordinator",
    "idCoordinator",
    "putProjectNote",
    "selected"
  ],
  data() {
    return {
      user: {},
      notesCopy: this.notes,
      wannaColaborate: false,
      showEditObservation: false,
      message: "",
      isLoading: false,
      response: {
        ok: false
      },
      noteInput: "",
      sent: false
    };
  },
  computed: {
    imageUrl: function() {
      if (this.project.has_image) {
        return "/project/" + this.project.id + "/picture";
      }
      return "/assets/img/neuronas-ingenia-noimg.jpg";
    }
  }
};
</script>

<style>
</style>
