<template>
  <router-view save-project-url="saveProjectUrl"></router-view>
</template>

<script>
export default {
  props:['saveProjectUrl']
}
</script>
