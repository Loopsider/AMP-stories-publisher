<template>
  <div class="story-management">
    <div class="rich-text-styles mb-3">
      <div class="style">
        <div class="mr-3" style="width:300px;">
          <md-field>
            <label>Default publisher</label>
            <md-input v-model="defaultPublisherName"></md-input>
          </md-field>
        </div>

        <div class="mr-3" style="width:700px;">
          <md-field>
            <md-input placeholder="Default publisher logo" v-model="publisherLogoSrc" readonly></md-input>
          </md-field>
        </div>

        <div class="mt-2" style="width:200px;">
          <button
            type="button"
            class="md-button md-raised md-theme-default px-3"
            id="upload_story_publisher_logo"
          >Select a file</button>
        </div>
      </div>
    </div>

    <p>Default CSS</p>
    <div class="code-editor">
      <div class="code-editor_index">
        <div v-for="i in lines" :key="i">{{i}}</div>
      </div>
      <textarea v-model="setting"></textarea>
    </div>
    <p class="mt-3">Rich Text Editor Custom Styles</p>
    <div class="rich-text-styles">
      <div class="style" v-for="(item, index) of richTextStyles" :index="index" :key="index">
        <md-field>
          <label>Type</label>
          <md-input v-model="item.type"></md-input>
        </md-field>
        <md-field>
          <label>Label</label>
          <md-input v-model="item.label"></md-input>
        </md-field>
        <md-field>
          <label>Style</label>
          <md-input v-model="item.style"></md-input>
        </md-field>
        <span class="remove-style" @click="showRemoveStyleDialog(index)">
          <md-icon>delete_outline</md-icon>
          <md-tooltip md-direction="top">Remove</md-tooltip>
        </span>
      </div>
      <md-button class="md-primary" @click="addStyle">Add a Custom Style</md-button>
    </div>
    <md-button class="md-raised md-primary my-3 float-right" @click="saveStyle">SAVE</md-button>
    <md-dialog-confirm
      :md-active.sync="showRemoveDialog"
      md-title="Do you really want to remove this custom style?"
      md-confirm-text="Ok"
      md-cancel-text="Cancel"
      @md-cancel="showRemoveDialog = false"
      @md-confirm="removeStyle"
    />
  </div>
</template>

<script>
export default {
  name: "story-management",
  data() {
    return {
      lines: 1,
      setting: "",
      richTextStyles: [{ type: "", label: "", style: "" }],
      showRemoveDialog: false,
      removeStyleIndex: null,
      defaultPublisherName: null,
      publisherLogoSrc: null
    };
  },
  methods: {
    generateMediaLibraries() {
      const vm = this;
      cloudinary.createMediaLibrary(
        { ...vm.cloudinaryInfo },
        {
          insertHandler: function(data) {
            let result = data.assets[0];
            vm.posterPortraitSrc =
              result.secure_url.substr(0, result.secure_url.lastIndexOf(".")) +
              ".jpg";
          }
        },
        document.getElementById("upload_poster_portrait_image")
      );
      cloudinary.createMediaLibrary(
        { ...vm.cloudinaryInfo },
        {
          insertHandler: function(data) {
            let result = data.assets[0];
            vm.posterSquareSrc =
              result.secure_url.substr(0, result.secure_url.lastIndexOf(".")) +
              ".jpg";
          }
        },
        document.getElementById("upload_poster_square_image")
      );
      cloudinary.createMediaLibrary(
        { ...vm.cloudinaryInfo },
        {
          insertHandler: function(data) {
            let result = data.assets[0];
            vm.posterLandscapeSrc =
              result.secure_url.substr(0, result.secure_url.lastIndexOf(".")) +
              ".jpg";
          }
        },
        document.getElementById("upload_poster_landscape_image")
      );
      cloudinary.createMediaLibrary(
        { ...vm.cloudinaryInfo },
        {
          insertHandler: function(data) {
            let result = data.assets[0];
            vm.publisherLogoSrc =
              result.secure_url.substr(0, result.secure_url.lastIndexOf(".")) +
              ".jpg";
          }
        },
        document.getElementById("upload_story_publisher_logo")
      );
    },
    serializeStyle(data) {
      if (data.length > 0) {
        const fPublisher = data.filter(style => {
          return style.slug === "publisher";
        });
        const fpublisherLogoSrc = data.filter(style => {
          return style.slug === "publisherLogoSrc";
        });
        
        const fSettings = data.filter(style => {
          return style.slug === "setting";
        });
        const fRichTextStyles = data.filter(style => {
          return style.slug === "richtext";
        });

        if (fPublisher.length > 0) {
          this.defaultPublisherName = fPublisher[0].data;
        }
        if (fpublisherLogoSrc.length > 0) {
          this.publisherLogoSrc = fpublisherLogoSrc[0].data;
        }


        if (fSettings.length > 0) {
          this.setting = fSettings[0].data;
        }
        if (fRichTextStyles.length > 0) {
          this.richTextStyles = JSON.parse(fRichTextStyles[0].data);
        }
      }
    },
    addStyle() {
      this.richTextStyles.push({ type: "", label: "", style: "" });
    },
    removeStyle() {
      this.richTextStyles.splice(this.removeStyleIndex, 1);
    },
    saveStyle() {
      const data = {
        setting: this.setting,
        richtext: JSON.stringify(this.richTextStyles),
        publisher: this.defaultPublisherName,
        publisherLogoSrc: this.publisherLogoSrc
      };
      Vue.block();
      this.$http
        .post("/admin/save-style-stories", data)
        .then(
          response => {
            Vue.unBlock();
            this.serializeStyle(response.data);
          },
          error => {
            Vue.unBlock();
          }
        )
        .catch(Vue.handleClientError);
    },
    // saveSettings() {
    //   this.$store.dispatch("saveAMPStory", {
    //     data: {
    //       ...this.settings
    //     },
    //     publish: true
    //   });
    //   this.settingDialog = false;
    // },

    loadStyle() {
      Vue.block();
      this.$http
        .get("/story/style")
        .then(
          response => {
            Vue.unBlock();
            this.serializeStyle(response.data);
          },
          error => {
            Vue.unBlock();
          }
        )
        .catch(Vue.handleClientError);
    },
    showRemoveStyleDialog(index) {
      event.stopPropagation();
      this.showRemoveDialog = true;
      this.removeStyleIndex = index;
    }
  },
  mounted() {
    this.loadStyle();
    setTimeout(() => {
      if (this.$store.state.cloudinary) {
        this.cloudinaryInfo = {
          cloud_name: this.$store.state.cloudinary.cloudName,
          api_key: this.$store.state.cloudinary.apiKey,
          username: this.$store.state.cloudinary.userName,
          timestamp: this.$store.state.cloudinary.timestamp,
          signature: this.$store.state.cloudinary.signature,
          button_class: "md-button md-raised md-theme-default px-3",
          button_caption: "Select a file",
          multiple: false
        };
        this.generateMediaLibraries();
      }
    }, 1000);
  },
  watch: {
    setting(value) {
      if (value) {
        const lines = value.split(/\r*\n/);
        this.lines = lines.length;
      }
    }
  }
};
</script>

