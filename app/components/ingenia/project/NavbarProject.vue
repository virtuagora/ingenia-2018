<template>
  <div class="hero-foot">
        <nav class="tabs is-boxed">
          <div class="container">
            <ul>
              <li :class="{'is-active': $route.name == 'projectOverview' }">
                <router-link :to="{ name: 'projectOverview'}">Sobre el proyecto</router-link>
              </li>
              <li :class="{'is-active': $route.name == 'projectTeam' }">
                <router-link :to="{ name: 'projectTeam'}">El equipo</router-link>
              </li>
              <li :class="{'is-active': $route.name == 'projectImplementation' }">
                <router-link :to="{ name: 'projectImplementation'}">La implementacion</router-link>
              </li>
            </ul>
          </div>
        </nav>
      </div>
</template>

<script>
export default {

}
</script>

<style>

</style>
