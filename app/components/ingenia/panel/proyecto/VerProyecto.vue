<template>
<div>
  <h1 class="subtitle is-3">Mi proyecto INGENIA</h1>
</div>
</template>

<script>
export default {
  data() {
    return {
      user: {},
      group: {}
    };
  },
  created: function() {
    this.user = this.$store.state.user;
    // this.group = this.user.groups[0];
  },
  methods: {
    openDeleteUser: function(id) {
      this.userSelected = id;
      this.showModal = true;
    }
  }
}
</script>
