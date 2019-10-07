<template>
  <div class="videos-list">
    <md-button class="md-raised md-primary mb-3 pr-2 ml-0" @click="redirectToCreateVideo">
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
        @row-clicked="redirectToVideo"
      >
        <template slot="tool" slot-scope="row">
          <div class="table-actions">
            <span>
              <md-icon style="font-size:20px!important;">file_copy</md-icon>
              <md-tooltip md-direction="top">Duplicate</md-tooltip>
            </span>
            <span @click="showRemoveVideoDialog(row.item)">
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
      md-title="Do you really want to remove this video?"
      md-confirm-text="Ok"
      md-cancel-text="Cancel"
      @md-cancel="showRemoveDialog = false"
      @md-confirm="removeVideo"
    />
  </div>
</template>

<script>
import utils from "../utils";

export default {
  name: "videos",
  // created(){
  //   Vue.$emit("reset:language");
  // },
  data() {
    return {
      filters: [],
      searches: [],
      videos: [],
      sortBy: "last_update",
      sortDesc: true,
      currentPage: 1,
      perPage: 10,
      columns: [
        { key: "title", label: "Title", sortable: true },
        { key: "language", label: "Language", sortable: true },
        { key: "owner", label: "Owner", sortable: true },
        { key: "last_update", label: "Last Update", sortable: true },
        { key: "tool", label: "Actions", sortable: false }
      ],
      pageOptions: [10, 20, 50],
      showRemoveDialog: false,
      removeVideoId: null
    };
  },
  methods: {
    filterdByUser(user) {
      if (user !== "all") {
        this.filters = this.videos.filter(video => {
          return video.owner_id === this.$store.state.userInfo.id;
        });
      } else {
        this.filters = this.videos;
      }
      Vue.$emit("search:clear");
    },
    search(term) {
      this.searches = utils.searchTable(this.filters, term);
    },
    redirectToVideo(video) {
      this.$router.push(`/backoffice/video/${video.id}`);
    },
    redirectToCreateVideo() {
      this.$router.push("/backoffice/video");
    },
    loadVideos() {
      String.prototype.capitalize = function() {
        return this.charAt(0).toUpperCase() + this.slice(1);
      };
      Vue.block();
      this.$http
        .get("/video/list")
        .then(
          response => {
            Vue.unBlock();
            this.videos = response.data.map(video => {
              return {
                id: video.id,
                title: video.title,
                language: video.video_language.capitalize(),
                owner: video.user.name,
                owner_id: video.user.id,
                last_update: video.updated_at ? video.updated_at : ""
              };
            });
            this.searches = this.videos;
            this.filters = this.videos;
            Vue.$emit("reset:language");
          },
          error => {
            Vue.unBlock();
          }
        )
        .catch(Vue.handleClientError);
    },
    showRemoveVideoDialog(video) {
      event.stopPropagation();
      this.showRemoveDialog = true;
      this.removeVideoId = video.id;
    },
    removeVideo() {
      const data = { id: this.removeVideoId };
      Vue.block();
      this.$http
        .post("/video/delete", data)
        .then(
          response => {
            Vue.unBlock();
            Vue.alertBox({
              title: "Success",
              text: "Successfully removed this video!",
              type: "success"
            });
            this.loadVideos();
          },
          error => {
            Vue.unBlock();
          }
        )
        .catch(Vue.handleClientError);
    }
  },
  computed: {
    totalRows() {
      return this.searches.length;
    }
  },
  mounted() {
    this.loadVideos();
    this.$store.dispatch("updateAppTitle", "Videos");
    Vue.$on("user:select", user => {
      this.filterdByUser(user);
    });
    Vue.$on("search:table", term => {
      this.search(term);
    });
    Vue.$on("search:forEntities", searchObj => {
      if (searchObj.languageField == "all") {
        this.searches = utils.searchForVideos(this.videos, {searchField:searchObj.searchField,languageField:null});
      } else {
        this.searches = utils.searchForVideos(this.videos, {searchField:searchObj.searchField,languageField:searchObj.languageField});
      }
    });
    Vue.$emit("search:clear");
  },
  beforeDestroy() {
    Vue.$off("user:select");
    Vue.$off("search:table");
    Vue.$off("search:language");
  }
};
</script>

<style scoped>
</style>