<template>
    <div>
        <story-left-side-bar></story-left-side-bar>
        <div class="story-page_view md-elevation-3">
            <iframe id="story_page_view" width="100%" height="100%"
                    style="visibility:hidden;" onload="this.style.visibility='visible';"
                    allowtransparency="true"
                    ref="storyPageView"
                    v-if="storyPageUrl" :src="storyPageUrl"></iframe>
        </div>
        <story-right-side-bar></story-right-side-bar>
        <md-dialog :md-active.sync="settingDialog">
            <md-dialog-title>Settings</md-dialog-title>
            <md-dialog-content style="min-width: 700px;">
                <md-divider></md-divider>
                <md-tabs class="md-transparent md-no-animation" md-dynamic-height>
                    <md-tab id="tab-story-seo" md-label="SEO">
                        <story-seo></story-seo>
                    </md-tab>
                    <md-tab id="tab-story-url" md-label="URL">
                        <story-url></story-url>
                    </md-tab>
                    <md-tab id="tab-story-bookend" md-label="BOOKEND">
                        <story-bookend></story-bookend>
                    </md-tab>
                    <!--<md-tab id="tab-story-advertising" md-label="ADVERTISING">-->
                    <!--<story-advertising></story-advertising>-->
                    <!--</md-tab>-->
                    <md-tab id="tab-story-analytics" md-label="ANALYTICS">
                        <story-analytics></story-analytics>
                    </md-tab>
                    <md-tab id="tab-story-language" md-label="LANGUAGE">
                        <story-language></story-language>
                    </md-tab>
                    <md-tab id="tab-story-css" md-label="CSS">
                        <story-css></story-css>
                    </md-tab>
                </md-tabs>
            </md-dialog-content>
            <md-dialog-actions>
                <md-button class="md-primary" @click="settingDialog = false">Close</md-button>
                <md-button class="md-primary" @click="saveSettings">Ok</md-button>
            </md-dialog-actions>
        </md-dialog>
        <md-dialog :md-active.sync="saveTemplateDialog">
            <md-dialog-title>Save current page as model</md-dialog-title>
            <md-dialog-content>
                <save-page-template></save-page-template>
            </md-dialog-content>
            <md-dialog-actions>
                <md-button class="md-primary" @click="saveTemplateDialog = false">Close</md-button>
                <md-button class="md-primary" @click="savePageTemplate">Save</md-button>
            </md-dialog-actions>
        </md-dialog>
    </div>
</template>

<script>
  import StoryLeftSideBar from "./LeftSideBar/index";
  import StoryRightSideBar from "./RightSideBar/index";
  import StorySeo from "./components/Settings/StorySEO";
  import StoryAdvertising from "./components/Settings/StoryAdvertising";
  import StoryAnalytics from "./components/Settings/StoryAnalytics";
  import StoryUrl from "./components/Settings/StoryURL";
  import StoryCss from "./components/Settings/StroyCSS";
  import StoryLanguage from "./components/Settings/StoryLanguage";
  import StoryBookend from "./components/Settings/StoryBookend";
  import SavePageTemplate from "./components/SavePageTemplate";
  

  export default {
    name: "story-edit",
    components: {
      SavePageTemplate,
      StoryAnalytics,
      StoryAdvertising,
      StorySeo,
      StoryUrl,
      StoryCss,
      StoryLanguage,
      StoryBookend,
      StoryRightSideBar,
      StoryLeftSideBar,
    },
    data () {
      return {
        settingDialog: false,
        settings: {},
        saveTemplateDialog: false,
        prevPageSelected: 0,
        storyPageUrl: '',
      }
    },
    methods: {
      /**
       * save story global setting
       */
      saveSettings () {
        this.$store.dispatch('saveAMPStory', {
          data: {
            ...this.settings
          },
          publish: true
        });
        this.settingDialog = false;
      },
      /**
       * reload story_page_view iframe
       */
      reloadIframe () {
        if (this.storyPageUrl &&
          this.prevPageSelected === this.$store.state.story.selected.page) {
          document.getElementById('story_page_view').contentWindow.location.reload(true);
        } else {
          this.prevPageSelected = this.$store.state.story.selected.page;
        }
      },

      /**
       * sage page template
       */
      savePageTemplate () {
        Vue.$emit('save:page-template');
        this.saveTemplateDialog = false;
      }
    },
    mounted () {
      // select page action
      this.$store.dispatch('selectAMPStory', {});

      // app publish action emit receiver from EditableHeader Component
      Vue.$on('app:publish', () => {
        this.$store.dispatch('saveAMPStory', { data: {}, publish: true });
      });
      // app setting action emit receiver from EditableHeader Component
      Vue.$on('app:setting', () => {
        this.settingDialog = true;
      });
      // app setting action emit receiver from EditableHeader Component
      Vue.$on('show:page-template-dialog', () => {
        this.saveTemplateDialog = true;
      });
      // story settings action emit receiver from Settings components on the right sidebar
      Vue.$on('story:settings', (data) => {
        this.settings = Object.assign(this.settings, data);
      });
    },
    computed: {
      /**
       * fetch selected page content from the store
       */
      page () {
        if (!_.isNil(this.$store.state.story) &&
          !_.isNil(this.$store.state.story.id) &&
          this.$store.state.story.selected.page >= 0 &&
          this.$store.state.story.data.pages.length > 0 &&
          !_.isNil(this.$store.state.story.data.pages[this.$store.state.story.selected.page])) {
          const storyUrl = `${app_url}/embed/story/${this.$store.state.story.id}/page/${this.$store.state.story.data.pages[this.$store.state.story.selected.page].id}`;
          this.storyPageUrl = storyUrl;
          return this.$store.state.story.data.pages[this.$store.state.story.selected.page];
        }
        return null;
      },
    },
    watch: {
      /**
       * reload story page view whenever selected page content is changed
       */
      page: {
        handler: _.debounce(function (data) {
          this.reloadIframe();
        }, 1000),
        deep: true
      },
    },
    beforeDestroy () {
      Vue.$off('app:publish');
      Vue.$off('app:setting');
      Vue.$off('story:settings');
      Vue.$off('show:page-template-dialog');
    },
  }
</script>