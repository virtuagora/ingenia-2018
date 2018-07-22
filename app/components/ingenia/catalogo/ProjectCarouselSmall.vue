<template>
  <carousel v-if="!isLoading" class="project-carousel" :perPageCustom="items" :navigationEnabled="false" :paginationEnabled="false" :autoplay="true" :scrollPerPage="false" :autoplayTimeout="10000" :autoplayHoverPause="true">
    <slide class="item-carousel-small" v-for="project in projects" :key="project.id">
      <div class="notification is-primary" :class="{'with-image': project.has_image}" :style="project.has_image ? styleWithImage(project.id) : styleWithoutImage()">
        <div class="bancar-count-container">
          <img src="/assets/img/trophy2.svg" v-show="project.selected" style="height:32px; vertical-align:middle" alt="">
          <img src="/assets/img/ribbon-bancar.svg" style="height:35px; vertical-align:middle" alt="">
          <span class="counter-likes is-size-4 is-600">{{zeroPad(project.likes,3)}}</span>
        </div>
        <div class="data-project">
          <a :href="'/proyecto/'+project.id" target="_blank" style="text-decoration:none;">
            <span class="tag is-black">{{getCategory(project.category_id)}}</span><span v-show="project.selected" class="tag is-warning"><i class="fas fa-trophy fa-fw"></i></span>
            <h1 class="title is-5">{{project.name}}</h1>
            <h1 class="subtitle is-6">
              <i class="fas fa-users"></i>&nbsp;{{project.group.name}}</h1>
          </a>
        </div>
      </div>
    </slide>
    <slide class="item-carousel-small">
      <div class="notification is-info with-image" :style="styleWithoutImage()">
        <div class="bancar-count-container">
          <img src="/assets/img/more.svg" style="height:32px; vertical-align:middle" alt="">
        </div>
        <div class="data-project">
          <a href="/proyectos" target="_blank" style="text-decoration:none;">
            <h1 class="title is-5">¡Aún hay más proyectos por ver!</h1>
            <h1 class="subtitle is-6">¡Visitá el listado con mas de +1700 proyectos!</h1>
          </a>
        </div>
      </div>
    </slide>
  </carousel>
  <div class="notification" v-else>
    <br>
    <b-loading :is-full-page="false" :active.sync="isLoading"></b-loading>
    <br>
  </div>
</template>

<script>
import { Carousel, Slide } from "vue-carousel";

export default {
  props: ["getProjectsUrl", "onlyOne", "selected"],
  components: {
    Carousel,
    Slide
  },
  data() {
    return {
      projects: [],
      categorias: [],
      isLoading: true
    };
  },
  mounted: function() {
    this.isLoading = true;
    Promise.all([this.$http.get("/category"), this.$http.get(this.urlGet)])
      .then(responses => {
        this.isLoading = false;
        this.categorias = responses[0].data;
        this.projects = this.shuffleArray(responses[1].data.data);
      })
      .catch(error => {
        this.isLoading = false;
        console.error(error);
      });
  },
  methods: {
    shuffleArray: function(arr) {
      return arr
        .map(a => [Math.random(), a])
        .sort((a, b) => a[0] - b[0])
        .map(a => a[1]);
    },
    getCategory(id) {
      let caty = this.categorias.find(x => {
        return x.id === id;
      });
      return caty ? caty.name : "";
    },
    zeroPad: function(num, places) {
      var zero = places - num.toString().length + 1;
      return Array(+(zero > 0 && zero)).join("0") + num;
    },
    styleWithImage: function(id) {
      return (
        "background: linear-gradient(15deg, rgba(28, 4, 83, 0.9) 0%, rgba(74, 156, 214, 0.4) 39%, rgba(49, 0, 98, 0.2) 55%, rgba(48, 0, 96, 0.0) 75%), url(/project/" +
        id +
        "/picture) center center / cover"
      );
    },
    styleWithoutImage: function() {
      return "background: url(/assets/img/neuronas-ingenia.jpg) center center / cover";
    }
  },
  computed: {
    items: function(){
      if(this.onlyOne == true){
        return [[0,1],[768, 1], [1024, 1]]
      } else{
        return [[0,1],[768, 2], [1024, 3]]
      }
    },
    urlGet: function() {
      let query = [];
      // if (this.nameToSearch !== "") {
      //   query.push("s=" + this.nameToSearch);
      // }
      // if (this.categoriaSelected !== null) {
      //   query.push("cat=" + this.categoriaSelected);
      // }
      // if (this.regionSelected !== null) {
      //   query.push("reg=" + this.regionSelected.id);
      // }
      // if (this.departamentoSelected !== null) {
      //   query.push("dep=" + this.departamentoSelected.id);
      // }
      // if (this.localidadSelected !== null) {
      //   query.push("loc=" + this.localidadSelected.id);
      // }
      if(this.selected != null){
        query.push("sel=" + this.selected);
      }
      // query.push("page=" + (Math.floor(Math.random() * 115) + 1));
      // query.push("size=" + 7);
      return this.getProjectsUrl.concat(
        query.length > 0 ? "?" : "",
        query.join("&")
      );
      // return this.getProjectsUrl;
    }
  }
};
</script>

<style lang="scss" scoped>
.data-project {
  .title,
  .subtitle {
    color: #fff;
  }
  position: absolute;
  bottom: 10px;
  left: 10px;
  margin-right: 10px;
}
</style>

