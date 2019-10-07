<template>
    <div class="admin">
        <md-tabs>
            <md-tab id="tab-posts" md-label="Users">
                <user-management></user-management>
            </md-tab>
            <md-tab id="tab-videos" md-label="Videos">
                Videos Management
            </md-tab>
            <md-tab id="tab-stories" md-label="Stories">
                <story-management></story-management>
            </md-tab>
        </md-tabs>
    </div>
</template>

<script>
  import UserManagement from "./UserManagement/Index";
  import StoryManagement from "./StoryManagement/index";

  export default {
    name: "admin",
    components: {
      StoryManagement,
      UserManagement
    },
    mounted () {
      this.$store.dispatch('updateAppTitle', 'Admin');
    }
  }
</script>

<style scoped>

</style>