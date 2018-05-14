<template>
  <section>
    <h1 class="subtitle is-3">Lista negra de DNIs</h1>
    <table>
      <thead>
        <tr>
          <th>DNI</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="dni in blacklist" :key="dni">
          <td>{{dni}}</td>
        </tr>
      </tbody>
    </table>
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
     this.blacklist = res.data
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