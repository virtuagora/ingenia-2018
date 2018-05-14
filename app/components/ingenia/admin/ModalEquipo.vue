<template>
  <div class="modal-card">
    <header class="modal-card-head">
      <p class="modal-card-title">{{group.name}}</p>
    </header>
    <section class="modal-card-body">
      <div class="content is-small">
        <h4>
          <b>Acerca del equipo</b>
        </h4>
        <p class="nl2br">{{group.description}}</p>
        <h4>
          <b>Ubicación</b>
        </h4>
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
        <div v-if="group.parent_organization">
          <h4><b>El equipo es parte de una organización</b></h4>
          <p>{{group.parent_organization.name}}</p>
          <h4>
            <b>Tematicas que trabaja la organización</b>
          </h4>
          <p>{{arrayTopics.join(', ')}}</p>
          <h4>
            <b>Ubicación de la organización</b>
          </h4>
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
        <div class="content is-small" v-else>
          <h4>El equipo no es parte de una organización</h4>
        </div>
      </div>
      <div v-if="!isLoading">
        <article class="media" v-for="member in members" :key="member.id">
          <Avatar :user="member" class="media-left" size="64"></Avatar>
          <div class="media-content" style="overflow: inherit">
            <h1 class="is-size-5 is-600">{{member.subject.display_name}}
              <b-tooltip label="Responsable" type="is-warning" position="is-bottom" v-if="member.pivot.relation === 'responsable'">
                <i class="fas fa-star has-text-warning"></i>
              </b-tooltip>
              <b-tooltip label="Co-responsable" type="is-info" position="is-bottom" v-if="member.pivot.relation === 'co-responsable'">
                <i class="fas fa-shield-alt has-text-info"></i>
              </b-tooltip>
            </h1>
            <div class="content is-small">
            <p v-if="member.subject.img_type === 0"><i class="fas fa-check has-text-success fa-fw fa-lg"></i><i class="fas fa-envelope fa-fw fa-lg"></i>&nbsp;Verificado con correo electrónico: <b>{{member.email}}</b></p>
    <p v-if="member.subject.img_type === 1"><i class="fas fa-check has-text-success fa-fw fa-lg"></i><i class="fab fa-facebook has-text-link fa-fw fa-lg"></i>&nbsp;Verificado utilizando perfil de Facebook: <a :href="'https://facebook.com/' + member.subject.img_hash" target="_blank">{{member.subject.display_name}}</a></p>
            <p class="nl2br" v-if="member.bio">{{member.bio}}</p>
            <p v-else>
              <i>- Sin información -</i>
            </p>
            </div>
          </div>
        </article>
        <br>
      </div>
      <div v-else>
        <div class="notification is-light">
          <br>
          <br>
          <b-loading :is-full-page="false" :active.sync="isLoading"></b-loading>
          <br>
          <br>
        </div>
      </div>
    </section>
    <footer class="modal-card-foot">
      <button class="button is-dark" type="button" @click="$parent.close()">Close</button>
    </footer>
  </div>
</template>

<script>
import Localidad from "../utils/GetLocalidad";
import Avatar from "../utils/Avatar";
export default {
  props: ["id", "getGroupMembers", "group"],
  components: {
    Avatar,
    Localidad
  },
  data() {
    return {
      isLoading: true,
      members: []
    };
  },
  mounted: function() {
    Promise.all([this.$http.get(this.fetchGroupMembers)])
      .then(responses => {
        this.members = responses[0].data;
        this.isLoading = false;
      })
      .catch(error => {
        console.error(error.message);
        this.isLoading = false;
        this.$snackbar.open({
          message: "Error al obtener los integrantes del equipo.",
          type: "is-danger",
          actionText: "Cerrar"
        });
      });
  },
  computed: {
    fetchGroupMembers: function() {
      return this.getGroupMembers.replace(":gro", this.group.id);
    },
    arrayTopics: function() {
      if (this.group.parent_organization) {
        let arr = this.group.parent_organization.topics.slice();
        if (this.group.parent_organization.topic_other) {
          arr.push(this.group.parent_organization.topic_other);
        }
        return arr;
      } else {
        return [];
      }
    }
  }
};
</script>