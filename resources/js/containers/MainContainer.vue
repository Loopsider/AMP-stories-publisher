<template>
    <div class="app">
        <template v-if="showEditableHeader">
            <app-editable-header></app-editable-header>
        </template>
        <template v-else>
            <app-header></app-header>
        </template>
        <div class="app-body">
            <template v-if="showAppFilter">
                <app-filter></app-filter>
            </template>
            <template v-if="showAppToolbar">
                <app-toolbar></app-toolbar>
            </template>
            <template v-if="showAppSidebar">
                <app-sidebar></app-sidebar>
            </template>
            <div class="app-content" :class="showAppSidebar ? 'sidebar' : ''">
                <router-view></router-view>
            </div>
        </div>
    </div>
</template>

<script>
  import AppHeader from "../components/Header/Header";
  import AppFilter from "../components/Filter/Filter";
  import AppSidebar from "../components/Sidebar/Sidebar";
  import AppEditableHeader from "../components/Header/EditableHeader";
  import AppToolbar from "../components/ToolBar/ToolBar";

  export default {
    name: "main-container",
    components: {
      AppToolbar,
      AppEditableHeader,
      AppHeader,
      AppSidebar,
      AppFilter
    },
    methods: {
      loadCloudinary () {
        this.$http.get('/video/cloud').then((response) => {
          const data = {
            cloudName: response.data.cloud_name,
            userName: response.data.user_name,
            apiKey: response.data.api_key,
            timestamp: response.data.timestamp,
            signature: response.data.signature
          };
          this.$store.dispatch("updateCloudinary", data);
        }, (error) => {
        }).catch(Vue.handleClientError);
      }
    },
    computed: {
      showAppFilter () {
        return [
          'Videos',
          'Stories'
        ].includes(this.$route.name);
      },
      showAppToolbar () {
        return this.$route.name === 'Story' && this.$store.state.app.editable;
      },
      showEditableHeader () {
        return [
          'Video',
          'Story'
        ].includes(this.$route.name);
      },
      showAppSidebar () {
        return [
          'Videos',
          'Stories',
          'Admin',
          'Analytics'
        ].includes(this.$route.name);
      },
      editable () {
        return this.$store.state.app.editable;
      },
    },
    mounted () {
      this.loadCloudinary();
    }
  }
</script>

<style scoped>

</style>