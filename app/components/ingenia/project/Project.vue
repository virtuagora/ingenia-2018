<template>
<div>
  <team-avatars :id="project.group.id" :team-url="teamUrl" :get-group-members="getGroupMembers"></team-avatars>
  <router-view :project="project" :send-request-join="sendRequestJoin"></router-view>
</div>
</template>

<script>
import TeamAvatars from './TeamAvatars'
export default {
  props:['project',"getGroupMembers", 'sendRequestJoin', 'teamUrl'],
  components:{
    TeamAvatars
  }
}
</script>

<style>

</style>
