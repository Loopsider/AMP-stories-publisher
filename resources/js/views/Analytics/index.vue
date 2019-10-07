<template>

</template>

<script>
  export default {
    name: "analytics",

    mounted () {
      this.$store.dispatch('updateAppTitle', 'Analytics');
    }
  }
</script>

<style scoped>

</style>