<template>
<div>
  <h1 class="subtitle is-3">Publicar mi proyecto</h1>
</div>
</template>

<script>
export default {
  data(){
    return {
      user: {}
    }
  },
  created: function(){
    this.user = this.$store.state.user
  },
  methods: {

  },
  beforeRouteEnter(to, from, next) {
    next(vm => {
      if (
        vm.user.groups[0] !== undefined &&
        vm.user.groups[0].pivot.relation === "responsable"
      ) {
        console.log("Authorized");
      } else {
        console.log("Unauthorized - Kicking to dashboard!");
        next({ name: "panelOverview" });
      }
    });
  }
}
</script>
