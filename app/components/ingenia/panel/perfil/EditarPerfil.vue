<template>
  <div>
    <h1 class="title is-3">Editar perfil</h1>
    <hr>
  </div>
</template>

<script>
export default {
  props: ['id'],
  data() {
    return {
      user: {}
    };
  },
  created: function(){
        this.user = this.$store.state.user

  },

};
</script>