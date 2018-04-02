<template>
  <router-view 
  :save-team-url="saveTeamUrl"
:save-project-url="saveProjectUrl"
:save-user-profile-url="saveUserProfileUrl"
:save-user-dni-url="saveUserDniUrl"
:send-invitation-url="sendInvitationUrl"></router-view>
</template>

<script>
export default {
  props:["saveTeamUrl", "saveProjectUrl",'saveUserProfileUrl','saveUserDniUrl','sendInvitationUrl']
}
</script>