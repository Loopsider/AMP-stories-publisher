<template>
    <div class="app">
        <app-header></app-header>
        <div class="app-body">
            <div class="app-content">
                <router-view></router-view>
            </div>
        </div>
    </div>
</template>

<script>
  import AppHeader from "../components/Header/Header";

  export default {
    name: "auth-container",
    components: {
      AppHeader,
    },
    mounted () {
      this.$store.dispatch('updateAppTitle', '');
    },
  }
</script>

<style scoped>
    .app-body {
        display: table;
        width: 100%;
        min-height: calc(100vh - 100px);
    }

    .app-content {
        display: table-cell;
        vertical-align: middle;
    }
</style>