<template>
  <div>
        <div class="tabs">
  <ul>
    <li :class="{'is-active': $route.name == 'userVerProyecto'}" v-if="user.groups[0].project !== null"><router-link :to="{ name: 'userVerProyecto'}">Ver proyecto</router-link></li>
    <!-- <li :class="{'is-active': $route.name == 'userEditarProyecto'}" v-if="user.groups[0].pivot.relation == 'responsable' && user.groups[0].project !== null"><router-link :to="{ name: 'userEditarProyecto'}">Editar proyecto</router-link></li> -->
    <li :class="{'is-active': $route.name == 'userSubirImagen'}" v-if="user.groups[0].pivot.relation == 'responsable' && user.groups[0].project !== null"><router-link :to="{ name: 'userSubirImagen'}">Subir imagen del proyecto</router-link></li>
  </ul>
</div>
    <h1 class="subtitle is-3">Mi proyecto de INGENIA</h1>
    <h1 class="title is-1">{{project.name}}</h1>
    <hr>
    <b-message class="has-text-centered" type="is-warning">
      La convocatoria ha cerrado, la opcion de editar el proyecto ya no se encuentra más disponible
    </b-message>
    <a :href="'/proyecto/'+project.id" class="button is-info is-outlined is-fullwidth">Ir a la página del proyecto</a>
    <br>
    <div class="content is-clearfix">
     <div class="box is-paddingless is-pulled-right" style="max-width:350px; margin:10px">
              <img :src="imageUrl" class="image" style="margin: 0 auto; border-radius:5px;" alt="">
            </div>
      <h5>
        <b>Acerca del proyecto</b>
      </h5>
      <p>{{project.abstract}}</p>
      <h5>
        <b>Fundamentación</b>
      </h5>
      <p>{{project.foundation}}</p>
      <h5>
        <b>Categoría</b>
      </h5>
        <p class="tag is-primary is-medium">{{project.category.name}}</p>
      <h5>
        <b>Trabajo previo</b>
      </h5>
      <p v-if="project.previous_work">{{project.previous_work}}</p>
      <p v-else>
        <i>No presenta trabajo previo</i>
      </p>
      <div class="columns">
        <div class="column">
          <h5>
            <b>Donde se implementará</b>
          </h5>
          <Localidad :locality-id="project.locality_id" :locality-other="project.locality_other"></Localidad>
        </div>
        <div class="column">
          <h5>
            <b>Barrios en que se implementara</b>
          </h5>
          <p>{{project.neighbourhoods.join(', ')}}</p>
        </div>
      </div>

    </div>
    <div class="content">
      <h5>
        <b>Objetivos</b>
      </h5>
      <table class="table is-narrow is-bordered">
        <tbody v-if="project.goals.length">
          <tr v-for="(objetivo, index) in project.goals" :key="index">
            <td>
              <i class="fas fa-flag-checkered fa-fw"></i> {{objetivo}}</td>
          </tr>
        </tbody>
        <tbody v-else>
          <tr>
            <td class="has-text-centered" colspan="2">
              <i>No se han ingresado objetivos</i>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
    <div class="content">
      <h5>
        <b>Calendario de actividades</b>
      </h5>
      <table class="table is-narrow is-bordered">
        <thead>
          <tr>
            <th width="120px">Fecha</th>
            <th>Actividad</th>
          </tr>
        </thead>
        <tbody v-if="project.schedule.length">
          <tr v-for="(actividad, index) in project.schedule" :key="index">
            <td>
              <i class="far fa-calendar-check fa-fw"></i> {{actividad.date}}</td>
            <td>{{actividad.activity}}</td>
          </tr>
        </tbody>
        <tbody v-else>
          <tr>
            <td class="has-text-centered" colspan="3">
              <i>No se han ingresado actividades</i>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
    <div class="content">
      <h5>
        <b>Presupuesto solicitado</b>
      </h5>
      <table class="table is-narrow is-bordered">
        <thead>
          <tr>
            <th width="120px">Rubro</th>
            <th>Descripción</th>
            <th width="120px" class="has-text-centered">Monto</th>
          </tr>
        </thead>
        <tbody v-if="project.budget.length">
          <tr v-for="(item, index) in project.budget" :key="index">
            <td>{{item.category}}</td>
            <td>{{item.description}}</td>
            <td class="has-text-centered">$ {{item.amount}}</td>
          </tr>
          <tr>
            <th colspan="2" class="has-text-right">Monto total:</th>
            <td class="has-text-centered">$ {{montoTotal}}</td>
          </tr>
        </tbody>
        <tbody v-else>
          <tr>
            <td class="has-text-centered" colspan="4">
              <i>No se han ingresado items en el presupuesto</i>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
    <div v-if="project.organization">
      <h1 class="subtitle is-5">El proyecto se trabajará en conjunto con una organización</h1>
      <h1 class="title is-3">{{project.organization.name}}</h1>
      <div class="content">
        <h5>
          <b>Tematicas que trabaja la organización</b>
        </h5>
        <p>{{arrayTopics.join(', ')}}</p>
        <h5>
          <b>Ubicación de la organización</b>
        </h5>
        <Localidad :locality-id="project.organization.locality_id" :locality-other="project.organization.locality_other"></Localidad>
        <br>
        <table class="table is-narrow is-bordered">
          <tbody>
            <tr>
              <th>Email de contacto</th>
              <td>{{project.organization.email ? project.organization.email : 'No registra'}}</td>
            </tr>
            <tr>
              <th>Teléfono de contacto</th>
              <td>{{project.organization.telephone ? project.organization.telephone : 'No registra'}}</td>
            </tr>
            <tr>
              <th>Página web</th>
              <td>{{project.organization.web ? project.organization.web : 'No registra'}}</td>
            </tr>
            <tr>
              <th>Facebook</th>
              <td>{{project.organization.facebook ? project.organization.facebook : 'No registra'}}</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
    <div class="content" v-else>
      <h1 class="subtitle is-5">El proyecto no registra trabajo en conjunto con una organización</h1>
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
      project: {}
    };
  },
  created: function() {
    this.user = this.$store.state.user;
    this.project = this.user.groups[0].project;
  },
  methods: {
    openDeleteUser: function(id) {
      this.userSelected = id;
      this.showModal = true;
    }
  },
  computed: {
    montoTotal: function() {
      const reducer = (accumulator, item) =>
        accumulator + parseFloat(item.amount);
      return this.project.budget.reduce(reducer, 0);
    },
    arrayTopics: function() {
      if (this.project.organization) {
        let arr = this.project.organization.topics.slice();
        arr.push(this.project.organization.topic_other);
        return arr;
      } else {
        [""];
      }
    },
    imageUrl: function() {
      if (this.project.has_image) {
        return "/project/" + this.project.id + "/picture";
      }
      return "/assets/img/neuronas-ingenia-noimg.jpg";
    }
  }
};
</script>
