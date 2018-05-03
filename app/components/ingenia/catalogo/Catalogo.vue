<template>
  <section v-if="!isLoading">
    <!-- Main container -->
    <nav class="level projects-filter">
      <!-- Left side -->
      <div class="level-left">
        <div class="level-item">
          <div class="field has-addons">
            <p class="control has-icons-left">
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
      <div class="level-right">
        <div class="level-item">
            <b-field expanded>
              <b-select v-model="categoriaSelected" size="is-medium" v-validate="'required'" :disabled="categorias.length == 0" :loading="categoriasLoading" placeholder="Categoria" expanded>
                <option v-for="categoria in categorias" :key="categoria.id" :value="categoria.id">{{categoria.name}}</option>
              </b-select>
            </b-field>
        </div>            
        <div class="level-item">
            <b-field expanded>
              <b-select v-model="regionSelected" size="is-medium" data-vv-name="regionSelected" data-vv-as="'Región'" v-validate="'required'" placeholder="Región" :disabled="regiones.length == 0" :loading="regionLoading" expanded>
                <option v-for="region in regiones" :key="region.id" :value="region">{{region.name}}</option>
              </b-select>
            </b-field>
          </div>            
        <div class="level-item">
            <b-field expanded>
              <b-select v-model="departamentoSelected" size="is-medium" placeholder="Departamento" data-vv-name="departamentoSelected" data-vv-as="'Departamento'" v-validate="'required'" :disabled="departamentos.length == 0" :loading="departamentoLoading" expanded>
                <option v-for="departamento in departamentos" :key="departamento.id" :value="departamento">{{departamento.name}}</option>
              </b-select>
            </b-field>
            </div>            
        <div class="level-item">
            <b-field expanded>
              <b-select v-model="localidadSelected" size="is-medium" placeholder="Localidad" data-vv-name="localidadSelected" data-vv-as="'Localidad'" v-validate="'required'" :disabled="localidades.length == 0" :loading="localidadLoading" expanded>
                <option v-for="localidad in localidades" :key="localidad.id" :value="localidad">{{localidad.name}}</option>
              </b-select>
            </b-field>
            </div>            
        <div class="level-item">
            <b-field>
              <a @click="search()" class="button is-medium is-white">
                <i class="fas fa-search"></i>
              </a>
            </b-field>
            </div>            
        <div class="level-item">
            <b-field>
              <a @click="cleanFilters()" class="button is-medium is-white">
                <i class="fas fa-eraser"></i>
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
          <a :href="'/proyecto/'+project.id" style="text-decoration:none;">
            <h1 class="title is-4">{{project.name}}</h1>
          </a>
          <!-- <p class="is-size-5 is-300">Hay Futuro</p> -->
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
      nameToSearch: ""
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
    cleanFilters: function() {
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
    styleWithImage: function(id) {
      return (
        "background: linear-gradient(35deg, rgba(0, 23, 69, 0.9) 0%, rgba(74, 156, 214, 0.7) 39%, rgba(49, 0, 98, 0.2) 75%, rgba(48, 0, 96, 0.0) 100%), url(/project/" +
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
        query.push("dep=", this.departamentoSelected.id);
      }
      if (this.localidadSelected !== null) {
        query.push("loc=", this.localidadSelected.id);
      }
      return this.getProjectsUrl.concat(
        query.length > 0 ? "?" : "",
        query.join("&")
      );
    }
  }
};
</script>
