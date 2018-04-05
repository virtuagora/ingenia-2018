<template>
<div>
  <team-avatars></team-avatars>
  <router-view :project="project"></router-view>
</div>
</template>

<script>
import TeamAvatars from './TeamAvatars'
export default {
  props:['project'],
  components:{
    TeamAvatars
  }
}
</script>

<style>

</style>
