<template>
  <section v-if="!isLoading">
    <!-- Main container -->
    <nav class="level projects-filter">
      <!-- Left side -->
      <div class="level-left">
        <div class="level-item">
          <div class="field has-addons" style="flex-grow: 1">
            <p class="control is-expanded has-icons-left">
              <input v-model="nameToSearch" class="input is-medium" type="text" placeholder="Nombre del proyecto">
              <span class="icon is-medium is-left">
                <i class="fas fa-chevron-right fa-lg"></i>
              </span>
            </p>
            <p class="control">
              <button @click="search()" class="button is-white is-medium is-600">
                Buscar
              </button>
            </p>
          </div>
        </div>
      </div>
      <!-- Right side -->
      <div class="level-right" v-if="!filters">
        <div class="level-item">
           <a @click="showFilters()" class="button is-medium is-white">
                <i class="fas fa-filter fa-fw"></i>&nbsp;Otros filtros
              </a>
        </div>
      </div>
      <div class="level-right" v-else>
        <div class="level-item">
            <b-field expanded  style="flex-grow: 1">
              <b-select v-model="categoriaSelected" size="is-medium" v-validate="'required'" :disabled="categorias.length == 0" :loading="categoriasLoading" placeholder="Categoria" expanded>
                <option :value="null">Todas</option>
                <option v-for="categoria in categorias" :key="categoria.id" :value="categoria.id">{{categoria.name}}</option>
              </b-select>
            </b-field>
        </div>            
        <div class="level-item">
            <b-field expanded style="flex-grow: 1">
              <b-select v-model="regionSelected" size="is-medium" data-vv-name="regionSelected" data-vv-as="'Región'" v-validate="'required'" placeholder="Región" :disabled="regiones.length == 0" :loading="regionLoading" expanded>
                <option v-for="region in regiones" :key="region.id" :value="region">{{region.name}}</option>
              </b-select>
            </b-field>
          </div>            
        <div class="level-item" v-if="regionSelected !== null">
            <b-field expanded style="flex-grow: 1">
              <b-select v-model="departamentoSelected" size="is-medium" placeholder="Departamento" data-vv-name="departamentoSelected" data-vv-as="'Departamento'" v-validate="'required'" :disabled="departamentos.length == 0" :loading="departamentoLoading" expanded>
                <option v-for="departamento in departamentos" :key="departamento.id" :value="departamento">{{departamento.name}}</option>
              </b-select>
            </b-field>
            </div>            
        <div class="level-item" v-if="departamentoSelected !== null">
            <b-field expanded style="flex-grow: 1">
              <b-select v-model="localidadSelected" size="is-medium" placeholder="Localidad" data-vv-name="localidadSelected" data-vv-as="'Localidad'" v-validate="'required'" :disabled="localidades.length == 0" :loading="localidadLoading" expanded>
                <option v-for="localidad in localidades" :key="localidad.id" :value="localidad">{{localidad.name}}</option>
              </b-select>
            </b-field>
            </div>            
        <div class="level-item">
            <b-field>
              <a @click="search()" class="button is-medium is-white">
                <i class="fas fa-search fa-fw"></i><span class="is-hidden-desktop">&nbsp;Buscar</span>
              </a>
            </b-field>
            </div>            
        <div class="level-item">
            <b-field>
              <a @click="cleanFilters()" class="button is-medium is-white">
                <i class="fas fa-eraser fa-fw"></i>&nbsp;Borrar<span class="is-hidden-desktop">&nbsp;filtros</span>
              </a>
            </b-field>
        </div>
      </div>
    </nav>
    <br>
    <div v-masonry transition-duration="1s" item-selector=".item-mosaic">
      <div v-masonry-tile class="item-mosaic" v-for="project in projects" :key="project.id">
        <!-- block item markup -->
        <div class="notification is-primary" :class="{'with-image': project.has_image}" :style="project.has_image ? styleWithImage(project.id) : styleWithoutImage()">
          <div class="bancar-count-container">
            <img src="/assets/img/trophy2.svg" v-show="project.selected" style="height:50px; vertical-align:middle" alt="">
            <img src="/assets/img/ribbon-bancar.svg" style="height:40px; vertical-align:middle" alt=""> <span class="counter-likes is-size-3 is-700">{{zeroPad(project.likes,3)}}</span></div>
          <a :href="'/proyecto/'+project.id" target="_blank" style="text-decoration:none;">
            <span class="tag is-black">{{getCategory(project.category_id)}}</span><span v-show="project.selected" class="tag is-warning"><i class="fas fa-trophy fa-fw"></i></span>
            <h1 class="title is-4">{{project.name}}</h1>
            <h1 class="subtitle is-5"><i class="fas fa-users"></i>&nbsp;{{project.group.name}}</h1>
          </a>
        </div>
      </div>
    </div>
    <infinite-loading @infinite="infiniteHandler" ref="infiniteLoading">
      <span slot="no-results">
        <i class="fas fa-info-circle"></i> Fin de los resultados
      </span>
      <span slot="no-more">
        <i class="fas fa-info-circle"></i> ¡Fín de la lista!
      </span>
    </infinite-loading>
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
  components: {
    InfiniteLoading
  },
  data() {
    return {
      isLoading: false,
      projects: [],
      paginator: {
        current_page: null,
        last_page: null,
        next_page_url: null,
        prev_page_url: null
      },
      regionLoading: false,
      regionSelected: null,
      departamentoLoading: false,
      departamentoSelected: null,
      localidadLoading: false,
      localidadSelected: null,
      categoriasLoading: false,
      categoriaSelected: null,
      categorias: [],
      regiones: [],
      departamentos: [],
      localidades: [],
      nameToSearch: "",
      filters: false,
    };
  },
  mounted: function() {
    this.categoriasLoading = true;
    this.regionLoading = true;
    Promise.all([this.$http.get("/category"), this.$http.get("/region")])
      .then(response => {
        this.categorias = response[0].data;
        this.categoriasLoading = false;
        this.regiones = response[1].data;
        this.regionLoading = false;
      })
      .catch(error => {
        console.error(error.message);
        this.$snackbar.open({
          message: "Error inesperado",
          type: "is-danger",
          actionText: "Cerrar"
        });
        this.regionLoading = false;
        this.categoriasLoading = false;
      });
  },
  methods: {
    getCategory(id){
      let caty = this.categorias.find(x => {
        return x.id === id
      })
      return caty ? caty.name : '';
    },
    showFilters(){
      this.filters = true;
    },
    cleanFilters: function() {
      this.filters = false;
      this.regionSelected = null;
      this.departamentoSelected = null;
      this.localidadSelected = null;
      this.categoriaSelected = null;
      this.departamentos = [];
      this.localidades = [];
      this.nameToSearch = "";
      this.projects = [];
      this.paginator.current_page = null;
      this.paginator.last_page = null;
      this.paginator.next_page_url = null;
      this.paginator.prev_page_url = null;
      this.$nextTick(() => {
        this.$redrawVueMasonry();
        this.$refs.infiniteLoading.$emit("$InfiniteLoading:reset");
      });
    },
    resetEverything: function() {
      this.projects = [];
      this.paginator.current_page = null;
      this.paginator.last_page = null;
      this.paginator.next_page_url = null;
      this.paginator.prev_page_url = null;
      this.$nextTick(() => { 
            this.$redrawVueMasonry();        
        this.$refs.infiniteLoading.$emit("$InfiniteLoading:reset");
      });
    },
    search: function() {
      this.resetEverything();
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
    },
    fillPaginator: function(data) {
      this.paginator.current_page = data.current_page;
      this.paginator.last_page = data.last_page;
      this.paginator.next_page_url = data.next_page_url;
      this.paginator.prev_page_url = data.prev_page_url;
    },
    infiniteHandler: function($state) {
      if (this.paginator.current_page == null) {
        this.$http
          .get(this.urlGet)
          .then(response => {
            if(response.data.data === undefined) throw {message: 'Error en query'}
            this.projects = this.projects.concat(response.data.data);
            this.$redrawVueMasonry();
            this.fillPaginator(response.data);
            $state.loaded();
          })
          .catch(error => {
            console.error(error.message);
            this.$snackbar.open({
              message: "Error al obtener la lista de proyectos",
              type: "is-danger",
              actionText: "Cerrar"
            });
            $state.complete();            
          });
      } else if (this.paginator.next_page_url) {
        this.$http
          .get(this.paginator.next_page_url)
          .then(response => {
            if(response.data.data === undefined) throw {message: 'Error en query'}
            this.projects = this.projects.concat(response.data.data);
            this.$redrawVueMasonry();
            this.fillPaginator(response.data);
            $state.loaded();
          })
          .catch(error => {
            console.error(error.message);
            this.$snackbar.open({
              message: "Error al obtener la lista de proyectos",
              type: "is-danger",
              actionText: "Cerrar"
            });
            $state.complete();            
          });
      } else {
        $state.complete();
      }
    }
  },
  watch: {
    regionSelected: function(newVal, oldVal) {
      if (newVal != null) {
        this.departamentoSelected = null;
        this.departamentoLoading = true;
        this.$http
          .get("/region/" + newVal.id + "/department")
          .then(response => {
            this.departamentoLoading = false;
            this.departamentos = response.data;
            this.localidades = [];
            this.localidadSelected = null;
          })
          .catch(error => {
            console.error(error.message);
            this.departamentoLoading = false;
            this.localidades = [];
            this.localidadSelected = null;
            this.$snackbar.open({
              message: "Error inesperado",
              type: "is-danger",
              actionText: "Cerrar"
            });
            return false;
          });
      }
    },
    departamentoSelected: function(newVal, oldVal) {
      if (newVal != null) {
        this.localidadSelected = null;
        this.localidadLoading = true;
        this.$http
          .get("/department/" + newVal.id + "/locality")
          .then(response => {
            this.localidades = response.data;
            this.localidadLoading = false;
          })
          .catch(error => {
            console.error(error.message);
            this.localidadLoading = false;
            this.$snackbar.open({
              message: "Error inesperado",
              type: "is-danger",
              actionText: "Cerrar"
            });
            return false;
          });
      }
    },
    // localidadSelected: function(newVal, oldVal) {
    //   if (newVal != null) {
    //     this.resetEverything();
    //   }
    // },
    
  },
  computed: {
    urlGet: function() {
      let query = [];
      query.push("sel=true");
      if (this.nameToSearch !== "") {
        query.push("s=" + this.nameToSearch);
      }
      if (this.categoriaSelected !== null) {
        query.push("cat=" + this.categoriaSelected);
      }
      if (this.regionSelected !== null) {
        query.push("reg=" + this.regionSelected.id);
      }
      if (this.departamentoSelected !== null) {
        query.push("dep=" + this.departamentoSelected.id);
      }
      if (this.localidadSelected !== null) {
        query.push("loc=" + this.localidadSelected.id);
      }
      return this.getProjectsUrl.concat(
        query.length > 0 ? "?" : "",
        query.join("&")
      );
    }
  }
};
</script>
