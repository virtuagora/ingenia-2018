<template>
  <section v-if="!isLoading">
    <div class="notification has-text-centered">
      El buscador aun esta en construccion, pero aquí están los últimos proyectos que se subieron <i class="em em-sunglasses"></i>
    </div>
    <div v-masonry transition-duration="1s" item-selector=".item-mosaic">
      <div v-masonry-tile class="item-mosaic" v-for="project in projects" :key="project.id">
        <!-- block item markup -->
        <div class="notification is-primary" :class="{'with-image': project.has_image}" :style="project.has_image ? styleWithImage(project.id) : styleWithoutImage()">
        <a :href="'/proyecto/'+project.id" style="text-decoration:none;">
          <h1 class="title is-4">{{project.name}}</h1>
        </a>
        <!-- <p class="is-size-5 is-300">Hay Futuro</p> -->
        </div>
      </div>
    </div>
  </section>
  <div class="notification" v-else>
    <br>
    <b-loading :is-full-page="false" :active.sync="isLoading"></b-loading>
    <br>
  </div>
</template>

<script>
import InfiniteLoading from "vue-infinite-loading";

export default {
  props: ["getProjectsUrl"],
  data() {
    return {
      isLoading: true,
      projects: []
    };
  },
  mounted: function() {
    this.regionLoading = true;
    this.$http
      .get(this.getProjectsUrl)
      .then(response => {
        this.projects = response.data.data
        this.isLoading = false;
      })
      .catch(error => {
        console.error(error.message);
        this.$snackbar.open({
          message: "Error inesperado",
          type: "is-danger",
          actionText: "Cerrar"
        });
        this.isLoading = false;
      });
  },
  methods: {
    styleWithImage: function(id){
      return 'background: linear-gradient(35deg, rgba(0, 23, 69, 0.9) 0%, rgba(74, 156, 214, 0.7) 39%, rgba(49, 0, 98, 0.2) 75%, rgba(48, 0, 96, 0.0) 100%), url(/project/'+id+ '/picture) center center / cover'
    },
    styleWithoutImage: function(){
      return 'background: url(/assets/img/neuronas-ingenia.jpg) center center / cover'
    }
  }
};
</script>
