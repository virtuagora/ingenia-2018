<template>
<div>
  <team-avatars></team-avatars>
  <router-view :project="project" :send-request-join="sendRequestJoin"></router-view>
</div>
</template>

<script>
import TeamAvatars from './TeamAvatars'
export default {
  props:['project', 'sendRequestJoin'],
  components:{
    TeamAvatars
  }
}
</script>

<style>

</style>
