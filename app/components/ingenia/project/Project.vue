<template>
<div>
  <team-avatars :id="project.group.id" :team-url="teamUrl" :get-group-members="getGroupMembers"></team-avatars>
  <router-view :project="project" :send-request-join="sendRequestJoin"></router-view>
      <hr class="is-marginless">
  <section class="section" id="comentarios">
      <div class="container">
        <div class="columns">
          <div class="column is-8 is-offset-2"> 
            <comments :comments-url="commentsUrl" :comment-url="commentUrl" :reply-url="replyUrl" :like-url="likeUrl"></comments>
          </div>
        </div>
      </div>
    </section>
</div>
</template>

<script>
import TeamAvatars from './TeamAvatars'
import Comments from './Comments'
export default {
  props:['project',"getGroupMembers", 'sendRequestJoin', 'teamUrl', 'commentsUrl', 'commentUrl','replyUrl','likeUrl'],
  components:{
    TeamAvatars,
    Comments
  },
  methods: {
    
  }
}
</script>

<style>

</style>
