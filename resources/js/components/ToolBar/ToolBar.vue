<template>
    <div class="app-toolbar md-elevation-4">
        <div class="page-indexes">
            <md-button v-for="(page, index) in story.data.pages"
                       :class="{'md-primary' : isActive(index)}"
                       @click="selectPage(index)"
                       :key="index">
                {{ (index + 1).toString() }}
                <!--<md-icon v-if="isNew(page.id)">flag</md-icon>-->
            </md-button>
        </div>
        <ul class="page-tools">
            <li @click="showDialog = true">+ Add a new page</li>
            <li @click="showPageTemplateModal">Save current page as model</li>
        </ul>
        <md-dialog :md-active.sync="showDialog">
            <md-dialog-title>Add a new page</md-dialog-title>
            <md-dialog-content>
                <div class="story-templates-list">
                    <div class="page-template"
                         v-for="(template, index) in pageTemplates"
                         @click="addNewPage(index)"
                         :key="index">
                        <div class="page-template-image"
                             :style="{backgroundImage: 'url(' + template.image_url + ')'}">
                        </div>
                        <p>{{ template.title }}</p>
                    </div>
                </div>
            </md-dialog-content>
            <md-dialog-actions>
                <md-button class="md-primary" @click="showDialog = false">Close</md-button>
            </md-dialog-actions>
        </md-dialog>
    </div>
</template>

<script>
  import constants from '../../views/constants';

  export default {
    name: "app-toolbar",
    data () {
      return {
        pageTemplates: [constants.blankPageTemplate],
        showDialog: false,
        newPageIDs: []
      }
    },
    methods: {
      loadTemplates () {
        this.pageTemplates = [constants.blankPageTemplate];
        this.$http.get('/story-template/list').then((response) => {
          this.pageTemplates.push(...response.data);
        }, (error) => {
        }).catch(Vue.handleClientError);
      },
      showPageTemplateModal () {
        console.log("[save:page-template] action trigger ...");
        Vue.$emit('show:page-template-dialog');
      },
      selectPage (index) {
        this.$store.dispatch('selectAMPStory', {
          page: index,
          layer: -1,
          element: -1,
        });
      },
      addNewPage (index) {
        if (index < 0) {
          Vue.alertBox({
            title: 'Template Selection Error',
            text: "Please select one of the existing templates!",
            type: 'error'
          });
        }
        // add page template
        const story = Object.assign({}, this.story);
        // id with timestamp
        const id = Date.now();
        story.data.pages.push({ ...this.pageTemplates[index].data, id: id });
        story.publish = false;

        // save page ids created as new
        this.newPageIDs.push(id);

        // save new page
        this.$store.dispatch('saveAMPStory', story);

        // select page action
        this.$store.dispatch('selectAMPStory', {
          page: (story.data.pages.length - 1),
          layer: -1,
          element: -1,
        });
        // close dialog
        this.showDialog = false;
      },
      isNew (id) {
        return this.newPageIDs.indexOf(id) >= 0;
      },
      isActive (index) {
        return this.story.selected.page === index;
      }
    },
    mounted () {
      this.loadTemplates();
      // app reload story templates action emit receiver from Save Template Component
      Vue.$on("reload:story-templates", () => {
        this.loadTemplates();
      });
      // app publish action emit receiver from EditableHeader Component
      Vue.$on('app:publish', () => {
        this.newPageIDs = [];
      });
      Vue.$on('duplicate:page', (id) => {
        // save page ids created as new
        this.newPageIDs.push(id);
      });
    },
    computed: {
      story () {
        if (!_.isNil(this.$store.state.story)) {
          return this.$store.state.story;
        }
      },
    },
    beforeDestroy () {
      Vue.$off("reload:story-templates");
      Vue.$off('duplicate:page');
      Vue.$off('app:publish');
    },
  }
</script>

<style scoped lang="scss">
    .story-templates-list {
        display: block;
        min-width: 700px;
    }

    .page-template {
        display: inline-block;
        float: left;
        width: 120px;
        padding: 5px;
        text-align: center;
    }

    .page-template-image {
        width: 100%;
        height: 180px;
        border: 1px solid gray;
        margin-bottom: 10px;
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
    }

    .page-template-image:hover {
        box-shadow: 0px 1px 10px 2px rgba(0, 0, 0, 0.2);
    }

    .page-index {
        .md-icon {
            color: red !important;
            font-size: 8px !important;
            position: absolute;
            left: -7px;
            top: -15px;
            z-index: 100;
        }
    }
</style>