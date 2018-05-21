<template>
  <carousel class="project-carousel" :perPageCustom="[[480,1],[768, 3], [1024, 4],[1216,5]]" :navigationEnabled="true" :autoplay="true" :autoplayTimeout="10000" :autoplayHoverPause="true" >
    <slide class="item-carousel" v-for="project in projects" :key="project.id">
      <div class="notification is-primary" :class="{'with-image': project.has_image}" :style="project.has_image ? styleWithImage(project.id) : styleWithoutImage()">
        <div class="bancar-count-container"><img src="/assets/img/ribbon-bancar.svg" style="height:70px; vertical-align:middle" alt="">
          <span class="counter-likes is-size-2 is-700">{{zeroPad(project.likes,3)}}</span>
        </div>
        <div class="data-project">
          <a :href="'/proyecto/'+project.id" target="_blank" style="text-decoration:none;">
            <span class="tag is-black">{{getCategory(project.category_id)}}</span>
            <h1 class="title is-4">{{project.name}}</h1>
            <h1 class="subtitle is-5">
              <i class="fas fa-users"></i>&nbsp;{{project.group.name}}</h1>
          </a>
        </div>
      </div>
    </slide>
    <slide class="item-carousel">
      <div class="notification is-info with-image" :style="styleWithoutImage()">
        <div class="data-project">
          <a href="/proyectos" target="_blank" style="text-decoration:none;">
            <h1 class="title is-2">¡Aún hay mas proyectos por ver!</h1>
            <h1 class="subtitle is-4">¡Visita el listado con mas de +1700 proyectos!</h1>
          </a>
        </div>
      </div>
    </slide>
  </carousel>
</template>

<script>
import { Carousel, Slide } from "vue-carousel";

export default {
  props: ["getProjectsUrl"],
  components: {
    Carousel,
    Slide
  },
  data() {
    return {
      projects: [],
      categorias: []
    };
  },
  created: function() {
    Promise.all([this.$http.get("/category"), this.$http.get(this.urlGet)])
      .then(responses => {
        this.categorias = responses[0].data;
        this.projects = this.shuffleArray(responses[1].data.data);
      })
      .catch(error => {
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
      // query.push("page=" + (Math.floor(Math.random() * 54) + 1));
      query.push("size=" + 14);
      return this.getProjectsUrl.concat(
        query.length > 0 ? "?" : "",
        query.join("&")
      );
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

