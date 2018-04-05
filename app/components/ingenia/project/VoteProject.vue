<template>
  <div class="has-text-centered">
    <div class="list-of-avatars">
      <b-tooltip label="Guillermo Croppi">
      <figure class="image is-32x32 is-rounded">
        <a href="#">
          <img alt="Placeholder image" src="https://pbs.twimg.com/profile_images/908495954524983297/nSinJTho_400x400.jpg">
        </a>
      </figure>
      </b-tooltip>
      <b-tooltip label="Guillermo Croppi">      
      <figure class="image is-32x32 is-rounded">
        <a href="#">
          <img alt="Placeholder image" src="https://pbs.twimg.com/profile_images/908111377495199745/NSSDkikD_400x400.jpg">
        </a>
      </figure>
      </b-tooltip>

      <b-tooltip label="Guillermo Croppi">
      <figure class="image is-32x32 is-rounded">
        <a href="#">
          <img alt="Placeholder image" src="https://pbs.twimg.com/profile_images/802586078620385280/A41MfrN4_400x400.jpg">
        </a>
      </figure>
      </b-tooltip>
      <b-tooltip label="Guillermo Croppi">
      <figure class="image is-32x32 is-rounded">
        <a href="#">
          <img alt="Placeholder image" src="https://pbs.twimg.com/profile_images/932316632369778688/XspSB7QY_400x400.jpg">
        </a>
      </figure>
      </b-tooltip>
    </div>
    <p class="title is-3 is-marginless has-text-warning">
      <img src="/img/muscle-color.svg" style="height:50px;vertical-align:bottom" alt=""> {{vote ? 87 : 86}} personas</p>
    <p>bancan este proyecto
      <b>{{ vote ? '¡Vos tambien!' : '¿Y vos?' }}</b>&nbsp;&nbsp;<i class="em" :class="{'em-open_mouth': !vote, 'em-smile': vote}"></i>
    </p>
    <br>
    <button class="button is-medium is-fullwidth" :class="{'is-outlined is-warning': !vote, 'is-info is-800': vote}" @click="vote = !vote">
      <i class="em em-muscle"></i>&nbsp;{{ vote ? '¡Guillermo banca este proyecto!' : '¡Yo lo banco!'}}</button>
    <br>
    <div class="columns is-marginless">
      <div class="column is-8">
    <p>¡Tambien podes bancar compartiendo en las redes!</p>
      </div>
      <div class="column">
    <p>
      <a href="#"><i class="fab fa-facebook fa-2x fa-fw"></i></a>
      <a href="#"><i class="fab fa-twitter fa-2x fa-fw"></i></a>
    </p>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  data(){
    return {
      vote: false
    }
  }
};
</script>
