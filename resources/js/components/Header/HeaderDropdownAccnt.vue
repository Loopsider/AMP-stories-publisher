<template>
    <div class="app-dropdown-account">
        <b-nav-item-dropdown right no-caret>
            <template slot="button-content">
                <md-avatar class="md-avatar-icon">{{ userName }}</md-avatar>
            </template>
            <b-dropdown-item>
                <md-icon>person</md-icon>
                Settings
            </b-dropdown-item>
            <b-dropdown-item @click="logout">
                <md-icon>exit_to_app</md-icon>
                Logout
            </b-dropdown-item>
        </b-nav-item-dropdown>
    </div>
</template>

<script>
  import { handleLogout } from "../../utils/auth";

  export default {
    name: "HeaderDropdownAccnt",
    data () {
      return {}
    },
    methods: {
      logout () {
        handleLogout();
        this.$router.push('/auth/login');
      }
    },
    computed: {
      userName () {
        if (this.$store.state.userInfo.name) {
          return this.$store.state.userInfo.name.charAt(0).toUpperCase();
        }
        return '';
      }
    }
  }
</script>

<style scoped>

</style>