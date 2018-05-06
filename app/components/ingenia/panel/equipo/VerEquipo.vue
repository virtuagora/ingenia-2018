<template>
  <div>
    <div class="tabs">
  <ul>
    <li :class="{'is-active': $route.name == 'userVerEquipo'}"><router-link :to="{ name: 'userVerEquipo'}">Ver equipo</router-link></li>
    <li :class="{'is-active': $route.name == 'userEditarEquipo'}"  v-if="user.groups[0].pivot.relation == 'responsable'"><router-link :to="{ name: 'userEditarEquipo'}">Editar datos</router-link></li>
    <li :class="{'is-active': $route.name == 'userVerIntegrantes'}"  v-if="user.groups[0].pivot.relation == 'responsable'"><router-link :to="{ name: 'userVerIntegrantes'}">Ver los integrantes</router-link></li>
  </ul>
</div>
    <h1 class="subtitle is-3">Mi equipo de INGENIA</h1>
    <h1 class="title is-1">{{group.name}}</h1>
    <hr>
    <div class="content">
      <h5>
        <b>Acerca del equipo</b>
      </h5>
      <p>{{group.description}}</p>
      <h5>
        <b>Ubicación</b>
      </h5>
      <Localidad :locality-id="group.locality_id" :locality-other="group.locality_other"></Localidad>
      <br>
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
            <th>Email de contacto</th>
            <td>{{group.email}}</td>
          </tr>
          <tr v-if="group.telephone">
            <th>Telefono de contacto</th>
            <td>{{group.telephone}}</td>
          </tr>
          <tr>
            <th>Página web</th>
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
      <h1 class="subtitle is-5">El equipo es parte de una organización</h1>
      <h1 class="title is-3">{{group.parent_organization.name}}</h1>
      <div class="content">
        <h5>
          <b>Tematicas que trabaja la organización</b>
        </h5>
        <p>{{arrayTopics.join(', ')}}</p>
        <h5>
          <b>Ubicación de la organización</b>
        </h5>
        <Localidad :locality-id="group.parent_organization.locality_id" :locality-other="group.parent_organization.locality_other"></Localidad>
        <br>
        <table class="table is-narrow is-bordered">
          <tbody>
            <tr>
              <th>Email de contacto</th>
              <td>{{group.parent_organization.email ? group.parent_organization.email : 'No registra'}}</td>
            </tr>
            <tr>
              <th>Telefono de contacto</th>
              <td>{{group.parent_organization.telephone ? group.parent_organization.telephone : 'No registra'}}</td>
            </tr>
            <tr>
              <th>Página web</th>
              <td>{{group.parent_organization.web ? group.parent_organization.web : 'No registra'}}</td>
            </tr>
            <tr>
              <th>Facebook</th>
              <td>{{group.parent_organization.facebook ? group.parent_organization.facebook : 'No registra'}}</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
    <div class="content" v-else>
      <h1 class="subtitle is-5">El equipo no es parte de una organización</h1>
    </div>
  </div>
</template>

<script>
import Localidad from "../../utils/GetLocalidad";

export default {
  components: {
    Localidad
  },
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
  },
  computed: {
    arrayTopics: function() {
      if (this.group.parent_organization) {
        let arr = this.group.parent_organization.topics.slice();
        if(this.group.parent_organization.topic_other){
          arr.push(this.group.parent_organization.topic_other)
        }
        return arr
      } else {
        return [];
      }
    }
  }
};
</script>
