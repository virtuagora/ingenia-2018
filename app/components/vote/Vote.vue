<template>
  <div>
    <div class="has-text-centered">
      <h1 class="subtitle is-5">Hello, {{userState ? userState.user : 'Stranger'}}! Choose an answer</h1>
      <h1 class="title is-4">{{title}}</h1>
      <aside class="menu" v-show="!hasVoted">
        <ul class="menu-list">
          <li v-for="(option, index) in options" :key="index">
            <a @click="send(option)">{{option}}</a>
          </li>
        </ul>
      </aside>
      <div v-show="loading && hasVoted">
        <h1 class="title is-3">Loading...</h1>
      </div>
      <div class="notification is-info " v-if="hasVoted && successMessage != ''">
        {{successMessage}}
      </div>
      <div class="notification is-warning" v-else-if="hasVoted && userState == ''">
        <button class="delete" @click="rollback()"></button>
        To vote, you have to be <a href="/login">logged in</a>
      </div>
      <div class="notification is-warning" v-else-if="hasVoted && errorMessage != ''">
        {{errorMessage}}
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: "vote-component",
  props: ["title", "options", "loginUrl"],
  data() {
    return {
      userState: '',
      hasVoted: false,
      votedOption: "",
      loading: false,
      errorMessage: "",
      successMessage: ""
    };
  },
  created: function() {
    //this.userState = JSON.parse(document.getElementById('userState').innerText)
    console.log(userState);
    console.log(JSON.parse(document.getElementById("userState").innerText));
  },
  methods: {
    send: function(option) {
      this.hasVoted = true;
      if (this.userState == '') {
        
      } else {
        // TODO: Send vote to API
        this.loading = true;
        this.votedOption = option;
        this.successMessage = "Thank you! Your vote has been submited";
        this.loading = false;
      }
    },
    rollback: function(){
      this.hasVoted = false;
    }
  }
};
</script>

<style lang="scss" scoped>
.menu-list li a {
  font-size: 1.3rem;
  border: 1px solid #eaeaea;
  margin: 0.5rem 0;
  &:hover {
    border-color: transparent;
  }
}
</style>
