<template>
<router-view 
:id="id" 
:user-url="userUrl"
:save-user-profile-url="saveUserProfileUrl"
:save-user-dni-url="saveUserDniUrl" 
:save-user-public-profile-url="saveUserPublicProfileUrl"
:team-url="teamUrl"
:save-team-url="saveTeamUrl"
:send-invitation-url="sendInvitationUrl"
:save-letter-url="saveLetterUrl"
:save-agreement-url="saveAgreementUrl"
:project-url="projectUrl"
:save-project-url="saveProjectUrl"
></router-view>
</template>

<script>
export default {
  props: [
    "id",
	"userUrl",
	"saveUserProfileUrl",
	"saveUserDniUrl",
	"saveUserPublicProfileUrl",
	"teamUrl",
	"saveTeamUrl",
	"sendInvitationUrl",
	"saveLetterUrl",
	"saveAgreementUrl",
	"projectUrl",
	"saveProjectUrl",
  ],
}
</script>