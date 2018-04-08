<template>
  <div>
    <h1 class="subtitle is-3">Mis invitaciones a participar</h1>
    <p>Estas son tus invitaciones a participar:</p>
    <br>
    <div class="notification" v-show="this.user.invitations.length == 0">
      No tiene invitaciones pendientes.
    </div>
    <div class="card">
      <div class="card-content">
        <h1 class="title is-4">Nombre del grupo</h1>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  data(){
    return{
      user: {}
    }
  },
  created: function(){
    this.user = this.$store.state.user
  }
}
</script>
