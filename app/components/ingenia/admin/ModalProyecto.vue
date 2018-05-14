<template>
  <div class="modal-card">
    <header class="modal-card-head">
      <p class="modal-card-title">{{project.name}}</p>
    </header>
    <section class="modal-card-body">
      <div class="content is-small is-clearfix">
        <div class="box is-paddingless is-pulled-right" style="max-width:200px; margin:10px">
          <img :src="imageUrl" class="image" style="margin: 0 auto; border-radius:5px;" alt="">
        </div>
        <h4>
          <b>Acerca del proyecto</b>
        </h4>
        <p class="nl2br">{{project.abstract}}</p>
        <h4>
          <b>Fundamentación</b>
        </h4>
        <p class="nl2br">{{project.foundation}}</p>
        <h4>
          <b>Categoría</b>
        </h4>
        <p class="tag is-primary is-medium">{{getCategory(project.category_id)}}</p>
        <h4>
          <b>Trabajo previo</b>
        </h4>
        <p v-if="project.previous_work">{{project.previous_work}}</p>
        <p v-else>
          <i>No presenta trabajo previo</i>
        </p>
        <div class="columns">
          <div class="column">
            <h4>
              <b>Donde se implementará</b>
            </h4>
            <Localidad :locality-id="project.locality_id" :locality-other="project.locality_other"></Localidad>
          </div>
          <div class="column">
            <h4>
              <b>Barrios en que se implementará</b>
            </h4>
            <p>{{project.neighbourhoods.join(', ')}}</p>
          </div>
        </div>
      </div>
      <div class="content is-small">
        <h4>
          <b>Objetivos</b>
        </h4>
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
      <div class="content is-small">
        <h4>
          <b>Calendario de actividades</b>
        </h4>
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
      <div class="content is-small">
        <h4>
          <b>Presupuesto solicitado</b>
        </h4>
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
      <div v-if="project.organization" class="content is-small">
        <h4>El proyecto se trabajará en conjunto con una organización</h4>
        <p>{{project.organization.name}}</p>
        <div class="content">
          <h4>
            <b>Tematicas que trabaja la organización</b>
          </h4>
          <p>{{arrayTopics.join(', ')}}</p>
          <h4>
            <b>Ubicación de la organización</b>
          </h4>
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
      <div class="content is-small" v-else>
        <h4><b>El proyecto no registra trabajo en conjunto con una organización</b></h4>
      </div>
    </section>
    <footer class="modal-card-foot">
      <button class="button is-dark" type="button" @click="$parent.close()">Close</button>
    </footer>
  </div>
</template>

<script>
import Localidad from "../utils/GetLocalidad";
export default {
  props: ["project", "categorias"],
  components: {
    Localidad
  },
  data() {
    return {};
  },
  methods: {
    getCategory(id) {
      let caty = this.categorias.find(x => {
        return x.id === id;
      });
      return caty ? caty.name : "";
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

<style lang="scss" scoped>
header.modal-card-head {
  background-color: #fff;
  padding: 15px;
  border-bottom: 0;
}
footer.modal-card-foot {
  background-color: #fff;
  border-top: 0;
  padding: 15px;
}
</style>

