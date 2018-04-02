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
    console.log('Authorized')
    if (
      vm.$store.state.user.groups[0] !== undefined &&
      vm.$store.state.user.groups[0].pivot.relation === "responsable"
    ) {
      next();
    } else {
      console.log('Unauthorized - Kicking to dashboard!')
      next({ name: "panelOverview" });
    }
  }
}
</script>
