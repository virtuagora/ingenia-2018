<template>
  <section>
    <div v-if="isLoading" class="notification">
      <br>
      <b-loading :is-full-page="false" :active.sync="isLoading"></b-loading>
      <br>
    </div>
    <div v-else>
      <div class="columns is-multiline is-mobile">
        <div class="column is-3" v-for="story in stories" :key="story.id">
          <a href="">
            <img :src="'/stories/images/' + story.id" alt="">
          </a>
        </div>
      </div>
      <!-- <p v-for="story in stories" :key="story.id">
        <img :src="'/stories/images/' + story.id" alt="">
      </p> -->
      <infinite-loading ref="infiniteLoading" @infinite="infiniteHandler">
        <div class="box has-text-centered" v-if="stories.length == 0" style="margin-top:15px;" slot="no-results">
          :(
          <h1 class="subtitle is-5 is-marginless">
            ¡No han publicado historias!
          </h1>
        </div>
        <span slot="no-more">
          <i class="fas fa-info-circle"></i> ¡Fín de las historias!
        </span>
      </infinite-loading>
    </div>
  </section>
</template>

<script>
import InfiniteLoading from "vue-infinite-loading";


export default {
  data() {
    return {
      stories: [],
      isLoading: false,
      paginator: {
        current_page: null,
        last_page: null,
        next_page_url: null,
        prev_page_url: null
      }
    };
  },
  components: {
    InfiniteLoading
  },
  created: function() {
    this.user = this.$store.state.user;
  },
  methods: {
getstories: function() {
      this.isLoading = true;
      this.$http
        .get(this.storiesUrl)
        .then(response => {
          // this.stories = this.stories.concat(response.data.data);
          this.stories = response.data.data;
          this.isLoading = false;
        })
        .catch(error => {
          console.error(error.message);
          this.isLoading = false;
          this.$snackbar.open({
            message: "Error al obtener los comentarios. Recargue la página",
            type: "is-danger",
            actionText: "Cerrar"
          });
        });
    },
    resetEverything: function() {
      this.stories = [];
      this.paginator.current_page = null;
      this.paginator.last_page = null;
      this.paginator.next_page_url = null;
      this.paginator.prev_page_url = null;
      this.$nextTick(() => {
        this.$refs.infiniteLoading.$emit("$InfiniteLoading:reset");
      });
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
          .get(this.storiesUrl)
          .then(response => {
            if (response.data.data === undefined)
              throw { message: "Error en query" };
            this.stories = this.stories.concat(response.data.data);
            this.fillPaginator(response.data);
            $state.loaded();
          })
          .catch(error => {
            console.error(error.message);
            this.$snackbar.open({
              message: "Error al obtener los historias",
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
            this.stories = this.stories.concat(response.data.data);
            this.fillPaginator(response.data);
            $state.loaded();
          })
          .catch(error => {
            console.error(error.message);
            this.$snackbar.open({
              message: "Error al obtener historias",
              type: "is-danger",
              actionText: "Cerrar"
            });
            $state.complete();
          });
      } else {
        $state.complete();
      }
    },
    updatestories: function() {
      this.resetEverything();
    },
  },
  computed: {
    storiesUrl: function(){
      return '/stories/all?size=4'
    }
  }
};
</script>

<style>

</style>
