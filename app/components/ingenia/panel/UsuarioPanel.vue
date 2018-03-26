  <template>
  <div class="columns">
    <div class="column is-3">
      <aside class="menu">
        <p class="menu-label">
          Mi perfil
        </p>
        <ul class="menu-list">
          <li>
            <router-link :to="{ name: 'panelOverview'}" exact-active-class="is-active" exact>Inicio</router-link>
          </li>
         <li>
            <router-link :to="{ name: 'usuarioVerPerfil'}" exact-active-class="is-active">Ver perfil</router-link>
          </li>
          <li>
            <router-link :to="{ name: 'usuarioEditarPerfil'}" exact-active-class="is-active">Editar perfil</router-link>
          </li>
          <li>
            <router-link :to="{ name: 'usuarioCambiarPassword'}" exact-active-class="is-active">Cambiar contraseña</router-link>
          </li>
        </ul>
        <p class="menu-label">
          Ingenia 2018
        </p>
        <ul class="menu-list">

        </ul>
      </aside>
    </div>
    <div class="column is-7 is-offset-1">
      <router-view></router-view>
    </div>
  </div>
</template>

<script>
export default {
  props: ['id'],
  data(){
    return {

    }
  },
  mounted: function(){
    document.getElementById('loading').remove();
  }
};
</script>
