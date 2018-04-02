<template>
  <div>
    <h1 class="subtitle is-3">Mi equipo de INGENIA</h1>
    <h1 class="title is-1">{{group.name}}</h1>
    <hr>
    <div class="content">
      <h5>
        <b>Acerca del equipo</b>
      </h5>
      <p>{{group.description}}</p>
      <table class="table is-narrow is-bordered">
        <tbody>
          <tr>
            <th>Año de fundación</th>
            <td>{{group.year}}</td>
          </tr>
          <tr>
            <th>
              Participaciones anteriores
            </th>
            <td>
              {{group.previous_editions.length ? group.previous_editions.join(', ') : 'No registra'}}
            </td>
          </tr>
           <tr v-if="group.email">
            <th>Telefono de contacto</th>
            <td>{{group.email}}</td>
          </tr>
          <tr v-if="group.telephone">
            <th>Telefono de contacto</th>
            <td>{{group.telephone}}</td>
          </tr>
          <tr>
            <th>Pagina web</th>
            <td>{{group.web ? group.web : 'No registra'}}</td>
          </tr>
          <tr>
            <th>Facebook</th>
            <td>{{group.facebook ? group.facebook : 'No registra'}}</td>
          </tr>
        </tbody>
      </table>
    </div>
    <div v-if="group.parent_organization">
    <h1 class="subtitle is-5">El equipo no es parte de una organización</h1>
    </div>
    <div class="content" v-else>
    <h1 class="subtitle is-4">El equipo es parte de una organización</h1>
    <h1 class="title is-2">{{group.parent_organization.name}}</h1>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      user: {},
      group: {}
    };
  },
  created: function() {
    this.user = this.$store.state.user;
    this.group = this.user.groups[0];
  },
  methods: {
    openDeleteUser: function(id) {
      this.userSelected = id;
      this.showModal = true;
    }
  }
};
</script>
