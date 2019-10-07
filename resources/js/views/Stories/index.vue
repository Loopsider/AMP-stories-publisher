<template>
  <div class="stories-list">
    <md-button class="md-raised md-primary mb-3 pr-2 ml-0" @click="redirectToCreateStory">
      <md-icon>add</md-icon>CREATE
    </md-button>
    <div class="table-responsive md-elevation-3">
      <b-table
        striped
        hover
        show-empty
        small
        head-class="txHead"
        class="dashboard-table mb-0"
        :current-page="currentPage"
        :per-page="perPage"
        :sort-by.sync="sortBy"
        :sort-desc.sync="sortDesc"
        :items="searches"
        :fields="columns"
        @row-clicked="redirectToStory"
      >
        <template slot="tool" slot-scope="row">
          <div class="table-actions">
            <span>
              <md-icon style="font-size:20px!important;">file_copy</md-icon>
              <md-tooltip md-direction="top">Duplicate</md-tooltip>
            </span>
            <span @click="showRemoveStoryDialog(row.item)">
              <md-icon>delete_outline</md-icon>
              <md-tooltip md-direction="top">Remove</md-tooltip>
            </span>
          </div>
        </template>
      </b-table>
    </div>
    <div class="mt-4">
      <div class="form-group float-left">
        <b-form-select :options="pageOptions" v-model="perPage" />
      </div>
      <b-pagination
        :total-rows="totalRows"
        :per-page="perPage"
        v-model="currentPage"
        class="justify-content-center"
      />
    </div>
    <md-dialog-confirm
      :md-active.sync="showRemoveDialog"
      md-title="Do you really want to remove this story?"
      md-confirm-text="Ok"
      md-cancel-text="Cancel"
      @md-cancel="showRemoveDialog = false"
      @md-confirm="removeStory"
    />
  </div>
</template>

<script>
import utils from "../utils";

export default {
  name: "stories",
  // created() {
  //   Vue.$emit("reset:language");
  // },
  data() {
    return {
      filters: [],
      searches: [],
      stories: [],
      sortBy: "last_update",
      sortDesc: true,
      currentPage: 1,
      perPage: 10,
      columns: [
        { key: "title", label: "Title", sortable: true },
        { key: "language", label: "Language", sortable: true },
        { key: "owner", label: "Owner", sortable: true },
        { key: "last_update", label: "Last Update", sortable: true },
        { key: "status", label: "status", sortable: true },
        { key: "tool", label: "Actions", sortable: false }
      ],
      pageOptions: [10, 20, 50],
      showRemoveDialog: false,
      removeStoryId: null
    };
  },
  methods: {
    filterdByUser(user) {
      if (user !== "all") {
        this.filters = this.stories.filter(story => {
          return story.owner_id === this.$store.state.userInfo.id;
        });
      } else {
        this.filters = this.stories;
      }
      Vue.$emit("search:clear");
    },
    search(term) {
      this.searches = utils.searchTable(this.filters, term);
    },
    redirectToStory(story) {
      this.$store.dispatch("updateAppTitle", story.title);
      this.$router.push(`/backoffice/story/${story.id}`);
    },
    redirectToCreateStory() {
      this.$router.push("/backoffice/story");
    },
    loadStories() {
      String.prototype.capitalize = function() {
        return this.charAt(0).toUpperCase() + this.slice(1);
      };
      Vue.block();
      this.$http
        .get("/story/list")
        .then(
          response => {
            Vue.unBlock();
            this.stories = response.data.map(story => {
              return {
                id: story.id,
                title: story.title,
                language: story.story_language.capitalize(),
                owner: story.user.name,
                owner_id: story.user.id,
                last_update: story.updated_at ? story.updated_at : "",
                status: story.status
              };
            });
            this.searches = this.stories;
            this.filters = this.stories;
            Vue.$emit("reset:language");
          },
          error => {
            Vue.unBlock();
          }
        )
        .catch(Vue.handleClientError);
    },
    showRemoveStoryDialog(story) {
      event.stopPropagation();
      this.showRemoveDialog = true;
      this.removeStoryId = story.id;
    },
    removeStory() {
      const data = { id: this.removeStoryId };
      Vue.block();
      this.$http
        .post("/story/delete", data)
        .then(
          response => {
            Vue.unBlock();
            Vue.alertBox({
              title: "Success",
              text: "Successfully removed this story!",
              type: "success"
            });
            this.loadStories();
          },
          error => {
            Vue.unBlock();
          }
        )
        .catch(Vue.handleClientError);
    }
  },
  mounted() {
    this.loadStories();
    this.$store.dispatch("updateAppTitle", "Stories");
    Vue.$on("user:select", user => {
      this.filterdByUser(user);
    });
    Vue.$on("search:table", term => {
      this.search(term);
    });
    Vue.$on("search:forEntities", searchObj => {
      if (searchObj.languageField && searchObj.languageField == "all") {
        this.searches = utils.searchForStories(this.stories, {searchField:searchObj.searchField,languageField:null});
      } else if(searchObj.languageField && searchObj.languageField != "all"){
        this.searches = utils.searchForStories(this.stories, {searchField:searchObj.searchField,languageField:searchObj.languageField});
      }
    });
    Vue.$emit("search:clear");
  },
  computed: {
    totalRows() {
      return this.searches.length;
    }
  },
  beforeDestroy() {
    Vue.$off("user:select");
    Vue.$off("search:table");
  }
};
</script>

<style scoped>
</style>