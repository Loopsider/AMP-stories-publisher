<template>
    <div>
        <md-table v-model="searched" md-sort="name" md-sort-order="asc" md-card md-fixed-header>
            <md-table-toolbar>
                <div class="md-toolbar-section-start">
                </div>
                <md-field md-clearable class="md-toolbar-section-end">
                    <md-input placeholder="Search by name..." v-model="search" @input="searchOnTable"/>
                </md-field>
            </md-table-toolbar>

            <md-table-empty-state
                md-label="No users found"
                :md-description="`No user found for this '${search}' query. Try a different search term or create a new user.`">
            </md-table-empty-state>

            <md-table-row slot="md-table-row" slot-scope="{ item }">
                <md-table-cell md-label="ID" md-sort-by="id" md-numeric>{{ item.id }}</md-table-cell>
                <md-table-cell md-label="Name" md-sort-by="name">{{ item.name }}</md-table-cell>
                <md-table-cell md-label="Email" md-sort-by="email">{{ item.email }}</md-table-cell>
                <md-table-cell md-label="Language" md-sort-by="language">{{ item.language }}</md-table-cell>
                <md-table-cell md-label="CreatedAt" md-sort-by="createdAt">{{ item.createdAt }}</md-table-cell>
                <md-table-cell md-label="VerifiedAt" md-sort-by="verifiedAt">{{ item.verifiedAt }}</md-table-cell>
                <md-table-cell md-label="Role" md-sort-by="role">{{ item.role }}</md-table-cell>
            </md-table-row>
        </md-table>
    </div>
</template>

<script>
  const toLower = text => {
    return text.toString().toLowerCase();
  }

  const searchByName = (items, term) => {
    if (term) {
      return items.filter(item => toLower(item.name).includes(toLower(term)));
    }

    return items;
  }

  export default {
    name: "user-management",
    data: () => ({
      search: null,
      searched: [],
      users: [],
    }),
    methods: {
      newUser () {
        window.alert('Noop')
      },
      searchOnTable () {
        this.searched = searchByName(this.users, this.search)
      },
      loadUsers () {
        Vue.block();
        this.$http.get('/admin/list-users').then((response) => {
          Vue.unBlock();
          this.users = response.data.map((user) => {
            return {
              'id': user.id,
              'name': user.name,
              'email': user.email,
              'language':user.language,
              'createdAt': user.created_at ? user.created_at : '',
              'verifiedAt': user.email_verified_at ? user.email_verified_at : '',
              'role': user.role[0].name,
            }
          });
          this.searched = this.users;
        }, (error) => {
          Vue.unBlock();
        }).catch(Vue.handleClientError);
      }
    },
    mounted () {
      this.loadUsers();
    }
  }
</script>

<style scoped>

</style>