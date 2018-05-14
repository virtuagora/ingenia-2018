<template>
  <section>
    <h1 class="subtitle is-3">Verificar DNIs</h1>
    <nav class="level projects-filter">
      <!-- Left side -->
      <div class="level-left">
        <div class="level-item">
          <div class="field has-addons" style="flex-grow: 1">
            <p class="control is-expanded has-icons-left">
              <input v-model="nameToSearch" class="input" type="text" placeholder="Buscar por nombre">
              <span class="icon is-left">
                <i class="fas fa-chevron-right fa-lg"></i>
              </span>
            </p>
            <p class="control">
              <button @click="search()" class="button is-white is-600">
                Buscar
              </button>
            </p>
          </div>
        </div>
      </div>
      <!-- Right side -->
      <div class="level-right">
        <div class="level-item">
          <b-field>
            <a @click="verifiedToggle = !verifiedToggle" class="button is-white" :class="{'is-success': verifiedToggle, 'is-dark': !verifiedToggle}">
              {{verifiedToggle ? 'Verificados' : 'No verificados'}}
            </a>
          </b-field>
        </div>
        <div class="level-item">
          <b-field>
            <a @click="cleanFilters()" class="button is-white">
              <i class="fas fa-eraser fa-fw"></i>&nbsp;Borrar
              <span class="is-hidden-desktop">&nbsp;filtros</span>
            </a>
          </b-field>
        </div>
      </div>
    </nav>
    <div class="content">
      <table class="table is-fullwidth">
        <thead>
          <tr>
            <!-- <th width="15" class="has-text-centered">
              <i class="fa fa-hashtag"></i>
            </th> -->
            <th width="90">DNI</th>
            <th>Nombre y apellido</th>
            <th width="90" class="has-text-centered">
              <i class="fas fa-download"></i>
            </th>
            <th width="90" class="has-text-centered">
              <i class="fas fa-check"></i>
            </th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="user in users" :key="user.id">
            <!-- <td class="has-text-centered">{{user.id}}</td> -->
            <td>{{user.dni}}</td>
            <td>
              <a :href="'/usuario/'+user.id">{{user.surnames}}, {{user.names}}</a>&nbsp;&nbsp;
              <span class="tag is-danger" v-show="inBlacklist(user)">
                <i class="fas fa-exclamation-triangle"></i>&nbsp;En lista negra</span>
            </td>
            <td class="has-text-centered">
              <a :href="getUserDNIUrl(user)" target="_blank" class="button is-small">
                <i class="fas fa-eye"></i>&nbsp;Ver</a>
            </td>
            <td class="has-text-centered">
              <a @click="verificarUser(user)" class="button is-success is-outlined is-small">
                <i class="fas fa-check"></i>&nbsp;Verificar</a>
            </td>
          </tr>
        </tbody>
        <tfoot>
          <tr>
            <th colspan="4">
              <infinite-loading @infinite="infiniteHandler" ref="infiniteLoading">
                <span slot="no-results">
                  <i class="fas fa-info-circle"></i> Fin de los resultados
                </span>
                <span slot="no-more">
                  <i class="fas fa-info-circle"></i> ¡Fín de la lista!
                </span>
              </infinite-loading>
            </th>
          </tr>
        </tfoot>
      </table>
    </div>
    <b-loading :active.sync="isLoading"></b-loading>
  </section>
</template>

<script>
import InfiniteLoading from "vue-infinite-loading";
import Localidad from "../utils/GetLocalidad";

export default {
  props: ["getUsers", "getGroupMembers", "getUserDni"],
  components: {
    InfiniteLoading,
    Localidad
  },
  data() {
    return {
      isLoading: false,
      users: [],

      paginator: {
        current_page: null,
        last_page: null,
        next_page_url: null,
        prev_page_url: null
      },
      verifiedToggle: false,
      // regionLoading: false,
      // regionSelected: null,
      // departamentoLoading: false,
      // departamentoSelected: null,
      // localidadLoading: false,
      // localidadSelected: null,
      // categoriasLoading: false,
      // categoriaSelected: null,
      // categorias: [],
      // regiones: [],
      // departamentos: [],
      // localidades: [],
      nameToSearch: "",
      blacklist: "",
      filters: false
    };
  },
  mounted: function() {
    this.isLoading = true;
    this.$http
      .get("/option/dni-blacklist")
      .then(res => {
        this.blacklist = res.data.value;
        this.isLoading = false;
      })
      .catch(e => {
        console.error(e);
        this.isLoading = false;
        this.$snackbar.open({
          message: "Error al obtener el listado de DNI",
          type: "is-danger",
          actionText: "Cerrar"
        });
      });
  },
  methods: {
    getUserDNIUrl: function(usr) {
      return this.getUserDni.replace(":usr", usr.id);
    },
    inBlacklist: function(usr) {
      let value = this.blacklist.find((element) => {
        return element === usr.dni;
      });
      return value === undefined ? false : true;
    },
    verificarUser: function(usr) {
      this.$http
        .post(this.saveTeamUrl, this.payload)
        .then(response => {
          this.$snackbar.open({
            message: "¡Inscripción realizada!",
            type: "is-success",
            actionText: "OK"
          });
          this.isLoading = false;
          this.response.replied = true;
          this.response.ok = true;
          this.forceUpdateState("userPanel");
        })
        .catch(error => {
          console.error(error.message);
          this.isLoading = false;
          this.$snackbar.open({
            message: "Error inesperado",
            type: "is-danger",
            actionText: "Cerrar"
          });
          return false;
        });
    },
    // getCategory(id) {
    //   let caty = this.categorias.find(x => {
    //     return x.id === id;
    //   });
    //   return caty ? caty.name : "";
    // },
    showFilters() {
      this.filters = true;
    },
    cleanFilters: function() {
      // this.filters = false;
      // this.regionSelected = null;
      // this.departamentoSelected = null;
      // this.localidadSelected = null;
      // this.categoriaSelected = null;
      // this.departamentos = [];
      // this.localidades = [];
      this.nameToSearch = "";
      this.users = [];
      this.paginator.current_page = null;
      this.paginator.last_page = null;
      this.paginator.next_page_url = null;
      this.paginator.prev_page_url = null;
      this.$nextTick(() => {
        this.$refs.infiniteLoading.$emit("$InfiniteLoading:reset");
      });
    },
    resetEverything: function() {
      this.users = [];
      this.paginator.current_page = null;
      this.paginator.last_page = null;
      this.paginator.next_page_url = null;
      this.paginator.prev_page_url = null;
      this.$nextTick(() => {
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
            if (response.data.data === undefined)
              throw { message: "Error en query" };
            this.users = this.users.concat(response.data.data);
            this.fillPaginator(response.data);
            $state.loaded();
          })
          .catch(error => {
            console.error(error.message);
            this.$snackbar.open({
              message: "Error al obtener la lista de usuarios",
              type: "is-danger",
              actionText: "Cerrar"
            });
            $state.complete();
          });
      } else if (this.paginator.next_page_url) {
        this.$http
          .get(this.paginator.next_page_url)
          .then(response => {
            if (response.data.data === undefined)
              throw { message: "Error en query" };
            this.users = this.users.concat(response.data.data);
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
    },
    // statusTeam: function(pro) {
    //   return {
    //     "has-text-success": pro.group.full_team,
    //     "fa-check": pro.group.full_team,
    //     "has-text-danger": !pro.group.full_team,
    //     "fa-times": !pro.group.full_team
    //   };
    // },
    // statusSecond: function(pro) {
    //   return {
    //     "has-text-success": pro.group.second_in_charge,
    //     "fa-check": pro.group.second_in_charge,
    //     "has-text-danger": !pro.group.second_in_charge,
    //     "fa-times": !pro.group.second_in_charge
    //   };
    // },
    // statusLetter: function(pro) {
    //   return {
    //     "has-text-success":
    //       (pro.organization != null) & pro.group.uploaded_letter,
    //     "fa-check": (pro.organization != null) & pro.group.uploaded_letter,
    //     "fa-times": (pro.organization != null) & !pro.group.uploaded_letter,
    //     "fa-minus": pro.organization == null
    //   };
    // },
    // statusAgreement: function(pro) {
    //   return {
    //     "has-text-success": pro.group.uploaded_agreement,
    //     "fa-check": pro.group.uploaded_agreement,
    //     "has-text-danger": !pro.group.uploaded_agreement,
    //     "fa-times": !pro.group.uploaded_agreement
    //   };
    // },
    // cardProyecto: function(pro) {
    //   this.$modal.open({
    //     parent: this,
    //     component: ModalProyecto,
    //     hasModalCard: true,
    //     props: { project: pro, categorias: this.categorias }
    //   });
    // },
    // cardEquipo: function(gro) {
    //   this.$modal.open({
    //     parent: this,
    //     component: ModalEquipo,
    //     hasModalCard: true,
    //     props: { group: gro, getGroupMembers: this.getGroupMembers }
    //   });
    // },
    showFilters() {
      this.filters = true;
    }
  },
  watch: {
    verifiedToggle: function(newVal) {
      this.resetEverything();
    }
    // regionSelected: function(newVal, oldVal) {
    //   if (newVal != null) {
    //     this.departamentoSelected = null;
    //     this.departamentoLoading = true;
    //     this.$http
    //       .get("/region/" + newVal.id + "/department")
    //       .then(response => {
    //         this.departamentoLoading = false;
    //         this.departamentos = response.data;
    //         this.localidades = [];
    //         this.localidadSelected = null;
    //       })
    //       .catch(error => {
    //         console.error(error.message);
    //         this.departamentoLoading = false;
    //         this.localidades = [];
    //         this.localidadSelected = null;
    //         this.$snackbar.open({
    //           message: "Error inesperado",
    //           type: "is-danger",
    //           actionText: "Cerrar"
    //         });
    //         return false;
    //       });
    //   }
    // },
    // departamentoSelected: function(newVal, oldVal) {
    //   if (newVal != null) {
    //     this.localidadSelected = null;
    //     this.localidadLoading = true;
    //     this.$http
    //       .get("/department/" + newVal.id + "/locality")
    //       .then(response => {
    //         this.localidades = response.data;
    //         this.localidadLoading = false;
    //       })
    //       .catch(error => {
    //         console.error(error.message);
    //         this.localidadLoading = false;
    //         this.$snackbar.open({
    //           message: "Error inesperado",
    //           type: "is-danger",
    //           actionText: "Cerrar"
    //         });
    //         return false;
    //       });
    //   }
    // }
    // localidadSelected: function(newVal, oldVal) {
    //   if (newVal != null) {
    //     this.resetEverything();
    //   }
    // },
  },
  computed: {
    urlGet: function() {
      let query = [];
      query.push("size=100");
      if (this.nameToSearch !== "") {
        query.push("s=" + this.nameToSearch);
      }
      if (this.verifiedToggle) {
        query.push("dni_state=3");
      } else {
        query.push("dni_state=2");
      }
      // if (this.regionSelected !== null) {
      //   query.push("reg=" + this.regionSelected.id);
      // }
      // if (this.departamentoSelected !== null) {
      //   query.push("dep=" + this.departamentoSelected.id);
      // }
      // if (this.localidadSelected !== null) {
      //   query.push("loc=" + this.localidadSelected.id);
      // }
      return this.getUsers.concat(query.length > 0 ? "?" : "", query.join("&"));
    }
  }
};
</script>