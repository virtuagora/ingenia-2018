<template>
<section>
<div v-if="user.groups[0] !== undefined">

<ul class="steps">
      <li class="steps-segment">
        <span class="steps-marker">
          <span class="icon">
              <i class="fas fa-check"></i>
            </span>
        </span>
        <span class="steps-content">
          <p class="heading">Cupo minimo</p>
        </span>
      </li>
       <li class="steps-segment">
        <span class="steps-marker">
          <span class="icon">
              <i class="fas fa-check"></i>
            </span>
        </span>
        <span class="steps-content">
          <p class="heading">Co-responsable</p>
        </span>
      </li>
       <li class="steps-segment is-active">
        <span class="steps-marker">
          <span class="icon">
              <i class="fas fa-check"></i>
            </span>
        </span>
        <span class="steps-content">
          <p class="heading">Carta de conformidad</p>
        </span>
      </li>
      <li class="steps-segment is-active">
        <span class="steps-marker">
          <span class="icon">
              <i class="fas fa-check"></i>
            </span>
        </span>
        <span class="steps-content">
          <p class="heading">Aval de la organizacion</p>
        </span>
      </li>
      <li class="steps-segment is-dashed">
        <span class="steps-marker">
          <span class="icon">
              <i class="fas fa-clock"></i>
            </span>
        </span>
        <div class="steps-content is-divider-content">
          <p class="heading">Next step unreachable until some action has been taken</p>
        </div>
      </li>
      <li class="steps-segment">
        <span class="steps-marker">
          <span class="icon">
              <i class="fas fa-flag-checkered"></i>
            </span>
        </span>
      </li>
    </ul>
</div>
<div v-else>
  <h5 class="subtitle is-5">Pasos para participar de INGENIA</h5>
    <ul class="steps has-gaps " >
      <li class="steps-segment is-dashed">
        <span class="steps-marker is-hollow">
        <router-link :to="{ name: 'userEditarPerfil'}" exact-active-class="is-active">
          <b-tooltip label="Editar mi perfil" type="is-dark">
          &nbsp;&nbsp;
        </b-tooltip>
          </router-link>
        </span>
        <span class="steps-content">
          <p class="heading">Crear cuenta</p>
          <p class="is-size-7">¡Bienvenido! Completá tu perfil de usuario (Opcional)</p>
        </span>
      </li>
      <li class="steps-segment" :class="{'is-active': user.pending_tasks.includes('email')}">
        <span class="steps-marker" :class="{'is-primary': !user.pending_tasks.includes('email')}">
          <span class="icon">
              <i class="fas fa-check"></i>
            </span>
        </span>
        <span class="steps-content">
          <p class="heading">Verificar email</p>
          <p class="is-size-7">Solo responsables del proyecto deben verificar su cuenta de email</p>
        </span>
      </li>
      <li class="steps-segment" :class="{'is-active': user.pending_tasks.includes('profile')}">
        <span class="steps-marker" :class="{'is-primary': !user.pending_tasks.includes('profile')}">
          <span class="icon">
              <i class="fas" :class="{'fa-check': !user.pending_tasks.includes('profile'), 'fa-clock': user.pending_tasks.includes('profile')}"></i>
            </span>
        </span>
        <span class="steps-content">
          <p class="heading">Datos Personales</p>
          <p class="is-size-7">Requerido para participar de INGENIA</p>          
        </span>
      </li>
       <li class="steps-segment" :class="{'is-active': user.pending_tasks.includes('dni')}">
        <span class="steps-marker" :class="{'is-primary': !user.pending_tasks.includes('dni')}">
          <span class="icon">
              <i class="fas" :class="{'fa-check': !user.pending_tasks.includes('dni'), 'fa-clock': user.pending_tasks.includes('dni')}"></i>
            </span>
        </span>
        <span class="steps-content">
          <p class="heading">Cargar DNI</p>
          <p class="is-size-7">Requerido para participar de INGENIA</p>          
        </span>
      </li>
      <li class="steps-segment" :class="{'is-active': !user.pending_tasks.includes('email') && !user.pending_tasks.includes('profile') && !user.pending_tasks.includes('dni')}">
        <span class="steps-marker" :class="{'is-success': !user.pending_tasks.includes('email') && !user.pending_tasks.includes('profile') && !user.pending_tasks.includes('dni')}">
          <span class="icon">
              <i class="fas fa-flag-checkered"></i>
            </span>
        </span>
        <span class="steps-content">
          <p class="heading">¡OK!</p>
          <p class="is-size-7"></p>          
        </span>
      </li>
    </ul>
</div>
</section>
</template>

<script>
export default {
  data(){
    return {
      user: {}
    }
  },
  created: function(){
    this.user = this.$store.state.user
  },
  methods: {

  },
  beforeRouteEnter(to, from, next) {
    next(vm => {
      if (
        vm.user.groups[0] !== undefined &&
        vm.user.groups[0].pivot.relation === "responsable"
      ) {
        console.log("Authorized");
      } else {
        console.log("Unauthorized - Kicking to dashboard!");
        next({ name: "panelOverview" });
      }
    });
  }
}
</script>
