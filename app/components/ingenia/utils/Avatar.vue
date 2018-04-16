<template>
    <figure :class="'image is-rounded is-' + size + 'x' + size" >
  <a :href="'/user/' + userId">
        <img :src="url" :alt="userName">
  </a>
    </figure>
</template>

<script>
export default {
  props: ["subject", "size"],
  computed: {
    url: function() {
      switch (this.subject.img_type) {
        case 0:
          return (
            "https://www.gravatar.com/avatar/" +
            this.subject.img_hash +
            "?d=identicon&s=" +
            this.size
          );
        case 1:
          return (
            "https://graph.facebook.com/" +
            this.subject.img_hash +
            "/picture?width=" +
            this.size
          );
        default:
          return (
            "https://www.gravatar.com/avatar/" +
            this.subject.img_hash +
            "?d=identicon&s=" +
            this.size
          );
      }
    },
    userId: function(){
      if(this.subject.user_id !== undefined){
        return this.subject.user_id
      } else {
        return this.subject.id
      }
    },
    userName: function(){
      if(this.subject.display_name === undefined){
        return 'Usuario'
      }
        return this.subject.display_name;
    }
  }
};
</script>
