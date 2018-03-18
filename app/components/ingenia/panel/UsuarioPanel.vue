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
            <router-link :to="{ name: 'usuarioPerfil'}" exact-active-class="is-active">Ver perfil</router-link>
          </li>
          <li>
            <router-link :to="{ name: 'usuarioEditarPerfil'}" exact-active-class="is-active">Editar perfil</router-link>
          </li>
          <li>
            <router-link :to="{ name: 'usuarioCambiarPassword'}" exact-active-class="is-active">Cambiar password</router-link>
          </li>
        </ul>
        <p class="menu-label">
          Ingenia 2018
        </p>
        <ul class="menu-list">

        </ul>
      </aside>
    </div>
    <div class="column is-5 is-offset-2">
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
  beforeCreate: function(){
    
  }
};
</script>
