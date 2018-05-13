<template>
  <section>
    <h1 class="subtitle is-3">Listado de proyectos</h1>
    <div class="content">

      <table class="table is-narrow is-fullwidth">
        <thead>
          <tr>
            <th>
              <i class="fa fa-hashtag"></i>
            </th>
            <th>Nombre</th>
            <th>Equipo</th>
            <th class="has-text-centered">
              <i class="em em-muscle"></i>
            </th>
            <th class="has-text-centered">
              <i class="fas fa-users fa-lg fa-fw"></i>
            </th>
            <th class="has-text-centered">
              <i class="fas fa-shield-alt fa-lg fa-fw"></i>
            </th>
            <th class="has-text-centered">
              <i class="far fa-address-book fa-lg fa-fw"></i>
            </th>
            <th class="has-text-centered">
              <i class="fas fa-file-pdf fa-lg fa-fw"></i>
            </th>
            <th class="has-text-centered">
              <i class="fas fa-arrow-down fa-lg fa-fw"></i>
            </th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="project in projects" :key="project.id">
            <td>{{project.id}}</td>
            <td>{{project.name}}</td>
            <td>{{project.group.name}}</td>
            <td class="has-text-centered">{{project.likes}}</td>
            <td class="has-text-centered">
              <i class="fas fa-fw" :class="statusTeam(project)"></i>
            </td>
            <td class="has-text-centered">
              <i class="fas fa-fw" :class="statusSecond(project)"></i>
            </td>
            <td class="has-text-centered">
              <i class="fas fa-fw" :class="statusLetter(project)"></i>
            </td>
            <td class="has-text-centered">
              <i class="fas fa-fw" :class="statusAgreement(project)"></i>
            </td>
            <td class="has-text-centered">
              <a href="#" class="has-text-info">
                <i class="fas fa-print fa-fw"></i>
              </a>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
    <b-loading :active.sync="isLoading"></b-loading>
  </section>
</template>

<script>
export default {
  props: ["getProjects"],
  data() {
    return {
      isLoading: false,
      projects: []
    };
  },
  mounted: function() {
    this.isLoading = true;
    this.$http
      .get(this.getProjects)
      .then(response => {
        this.isLoading = false;
        this.projects = response.data.data;
      })
      .catch(x => {
        this.$snackbar.open({
          message: "Error inesperado",
          type: "is-danger",
          actionText: "Cerrar"
        });
        return false;
      });
  },
  computed: {
    statusTeam: function(pro) {
      return {
        "has-text-success": pro.group.full_team,
        "fa-check": pro.group.full_team,
        "has-text-danger": !pro.group.full_team,
        "fa-times": !pro.group.full_team
      };
    },
    statusSecond: function(pro) {
      return {
        "has-text-success": pro.group.second_in_charge,
        "fa-check": pro.group.second_in_charge,
        "has-text-danger": !pro.group.second_in_charge,
        "fa-times": !pro.group.second_in_charge
      };
    },
    statusLetter: function(pro) {
      return {
        "has-text-success":
          (project.organization != null) & project.group.uploaded_letter,
        "fa-check":
          (project.organization != null) & project.group.uploaded_letter,
        "fa-times":
          (project.organization != null) & !project.group.uploaded_letter,
        "fa-minus": project.organization == null
      };
    },
    statusAgreement: function(pro) {
      return {
        "has-text-success": pro.group.uploaded_agreement,
        "fa-check": pro.group.uploaded_agreement,
        "has-text-danger": !pro.group.uploaded_agreement,
        "fa-times": !pro.group.uploaded_agreement
      };
    }
  }
};
</script>

<style>

</style>
