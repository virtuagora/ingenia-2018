<template>
  <section>
    <h1 class="subtitle is-3">Lista negra de DNIs</h1>
    <div class="tags">
    <span class="tag is-dark is-medium" v-for="dni in blacklist" :key="dni">
          {{dni}}
        </span>
    </div>
  </section>
</template>

<script>
export default {
 data(){
   return{
     blacklist: []
   }
 },
 created: function(){
   this.$http.get('/option/dni-blacklist')
   .then(res => {
     this.blacklist = res.data.value
   })
   .catch(e => {
     console.error(e)
     this.$snackbar.open({
          message: "Error al obtener el listado de DNI",
          type: "is-danger",
          actionText: "Cerrar"
        });
   })
 }
};
</script>