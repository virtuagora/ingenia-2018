<template>
  <div class="hero-foot">
        <nav class="tabs is-boxed">
          <div class="container">
            <ul>
              <li :class="{'is-active': $route.name == 'projectOverview' }">
                <router-link :to="{ name: 'projectOverview'}">Acerca</router-link>
              </li>
              <li :class="{'is-active': $route.name == 'projectTeam' }">
                <router-link :to="{ name: 'projectTeam'}">El equipo</router-link>
              </li>
              <li :class="{'is-active': $route.name == 'projectImplementation' }">
                <router-link :to="{ name: 'projectImplementation'}">El proyecto</router-link>
              </li>
              <li>
                <a @click="scrollMeTo('comentarios')"><i class="fas fa-comments fa-lg"></i>&nbsp;Comentarios</a>
              </li>
              <li><a href="/"><span class="icon"><i class="fas fa-home fa-lg"></i></span></a></li>
            </ul>
          </div>
        </nav>
      </div>
</template>

<script>
export default {
methods:{
  scrollMeTo(id) {
    var element = document.getElementById(id);
    var top = element.offsetTop;

    window.scrollTo(0, top);
  }
}
}
</script>

<style>

</style>
