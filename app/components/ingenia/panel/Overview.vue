<template>
  <article class="content">
    <h1>Bienvenido!</h1>
    <p>En este panel puedes editar tus preferencias</p>
  </article>
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

    // console.log(this.$store.state)
    // // console.log(this.$store.getters.get('user'))
    // this.user = (this.$store.getters.get).user;
    // console.log(this.$store.getters.get)
    // Object.assign(this.user,this.$store.getters.getKey('user'))
  },
  // computed:{
  //   user: function(){
  //    return this.$store.getters.get('user')    
  //   }
  // }
  // created: function() {
  //   this.user = this.$store.state.user
  // }
};
</script>
