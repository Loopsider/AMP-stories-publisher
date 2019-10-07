<template>
    <div>
        <md-field>
            <label>Template Name</label>
            <md-input v-model="templateTitle"></md-input>
        </md-field>
        <div class="md-layout">
            <div class="md-layout-item">
                <p>Template Image</p>
                <button type="button"
                        class="md-button md-raised md-theme-default px-3"
                        id="upload_page_template_image">Select a file
                </button>
            </div>
            <div class="md-layout-item">
                <img v-if="templateImageUrl" :src="templateImageUrl" class="attachment-image md-elevation-7"/>
            </div>
        </div>
    </div>
</template>

<script>
  export default {
    name: "save-page-template",
    data () {
      return {
        cloudinaryInfo: null,
        templateTitle: '',
        templateImageUrl: '',
      }
    },
    methods: {
      /**
       * Save page template as model
       */
      saveTemplate () {
        const data = {
          title: this.templateTitle,
          image_url: this.templateImageUrl,
          data: this.$store.state.story.data.pages[this.$store.state.story.selected.page]
        };
        Vue.block();
        this.$http.post('/story-template/create', data).then((response) => {
          Vue.$emit("reload:story-templates");
          Vue.unBlock();
          Vue.alertBox({
            title: 'Success',
            text: "Successfully saved current page template as model",
            type: 'success'
          });
        }, (error) => {
          Vue.unBlock();
        }).catch(Vue.handleClientError);
      },

      /**
       * Generate Media Libraries
       */
      generateMediaLibraries () {
        const vm = this;
        cloudinary.createMediaLibrary(
          { ...vm.cloudinaryInfo },
          {
            insertHandler: function (data) {
              let result = data.assets[0];
              vm.templateImageUrl = result.secure_url.substr(0, result.secure_url.lastIndexOf(".")) + ".jpg";
            }
          },
          document.getElementById("upload_page_template_image")
        );
      },
    },
    mounted () {

      this.cloudinaryInfo = {
        cloud_name: this.$store.state.cloudinary.cloudName,
        api_key: this.$store.state.cloudinary.apiKey,
        username: this.$store.state.cloudinary.userName,
        timestamp: this.$store.state.cloudinary.timestamp,
        signature: this.$store.state.cloudinary.signature,
        button_class: 'md-button md-raised md-theme-default px-3',
        button_caption: 'Select a file',
        multiple: false
      };

      this.generateMediaLibraries();

      Vue.$on('save:page-template', () => {
        this.saveTemplate();
      });
    },
    beforeDestroy () {
      Vue.$off('save:page-template');
    },
  }
</script>

<style scoped>

</style>