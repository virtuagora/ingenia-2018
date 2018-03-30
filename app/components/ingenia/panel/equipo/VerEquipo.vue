<template>
  <div>
    <h1 class="subtitle is-3">Mi equipo de INGENIA</h1>
    <h1 class="title is-5">Integrantes del equipo registrados</h1>
    <table class="table is-fullwidth text-middle">
      <thead>
        <tr>
          <th>Nombre y apellido</th>
          <th>Email</th>
          <th class="has-text-right">Acciones</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td>
            <a href="#">
              <figure class="image is-24x24 is-inline-block is-rounded">
                <img src="https://pbs.twimg.com/profile_images/908495954524983297/nSinJTho_400x400.jpg" alt="">
              </figure>&nbsp;&nbsp;Guillermo Croppi</a>
          </td>
          <td>guillermocroppi@gmail.com</td>
          <td class="has-text-right">
            <button @click="openDeleteUser(2)" class="button is-outlined is-small is-danger">
              <i class="far fa-trash-alt"></i>
            </button>
          </td>
        </tr>
      </tbody>
    </table>
    <hr>
    <h1 class="title is-5">Invitaciones enviadas</h1>
    <table class="table is-fullwidth text-middle">
      <thead>
        <tr>
          <th>Email</th>
          <th class="has-text-centered" width="150px">Estado</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td>guillermocroppi@gmail.com</td>
          <td class="has-text-centered">
            <span class="tag is-success">
              <i class="fas fa-check fa-fw"></i>&nbsp;Aceptó
            </span>
          </td>
        </tr>
        <tr>
          <td>guillermocroppi@gmail.com</td>
          <td class="has-text-centered">
            <span class="tag is-warning">
              <i class="far fa-clock fa-fw"></i>&nbsp;Pendiente
            </span>
          </td>
        </tr>
      </tbody>
    </table>
    <b-modal ref="modalNow" :active.sync="showModal" :width="640" scroll="keep">
      <div class="notification is-danger">
        <article class="media">
          <figure class="media-left">
            <span class="icon is-medium">
              <i class="fas fa-trash-alt fa-2x"></i>
            </span>
          </figure>
          <div class="media-content">
            <h1 class="title is-4">¿Desea eliminar al participante?</h1>
            <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Culpa mollitia blanditiis delectus, error corporis.</p>
          </div>
          <div class="media-right">
            <button @click="$refs.modalNow.close()" class="delete"></button>
          </div>
        </article>
        <div class="field">
          <div class="control is-clearfix">
            <div class="button is-white is-pulled-right">Eliminar</div>
          </div>
        </div>
      </div>
    </b-modal>
  </div>
</template>

<script>
export default {
  data() {
    return {
      showModal: false,
      userSelected: null
    };
  },
  methods: {
    openDeleteUser: function(id){
      this.userSelected = id
      this.showModal = true
    }
  }
};
</script>
