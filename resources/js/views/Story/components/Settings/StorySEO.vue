<template>
  <div>
    <div class="md-layout">
      <div class="md-layout-item">
        <p>Poster Portrait Image</p>
        <button
          type="button"
          class="md-button md-raised md-theme-default px-3"
          id="upload_poster_portrait_image"
        >Select a file</button>
      </div>
      <div class="md-layout-item">
        <img
          v-if="posterPortraitSrc"
          :src="posterPortraitSrc"
          class="attachment-image md-elevation-7"
        >
      </div>
    </div>
    <div class="md-layout">
      <div class="md-layout-item">
        <p>Poster Square Image</p>
        <button
          type="button"
          class="md-button md-raised md-theme-default px-3"
          id="upload_poster_square_image"
        >Select a file</button>
      </div>
      <div class="md-layout-item">
        <img v-if="posterSquareSrc" :src="posterSquareSrc" class="attachment-image md-elevation-7">
      </div>
    </div>
    <div class="md-layout">
      <div class="md-layout-item">
        <p>Poster Landscape Image</p>
        <button
          type="button"
          class="md-button md-raised md-theme-default px-3"
          id="upload_poster_landscape_image"
        >Select a file</button>
      </div>
      <div class="md-layout-item">
        <img
          v-if="posterLandscapeSrc"
          :src="posterLandscapeSrc"
          class="attachment-image md-elevation-7"
        >
      </div>
    </div>
    <!--<md-switch v-model="supportsLandscape">Supports Landscape</md-switch>-->
    <md-field>
      <label>Publisher</label>
      <md-input v-model="publisher"></md-input>
    </md-field>
    <div class="md-layout">
      <div class="md-layout-item">
        <p>Publisher Logo</p>
        <button
          type="button"
          class="md-button md-raised md-theme-default px-3"
          id="upload_story_publisher_logo"
        >Select a file</button>
      </div>
      <div class="md-layout-item">
        <img
          v-if="publisherLogoSrc"
          :src="publisherLogoSrc"
          class="attachment-image md-elevation-7"
        >
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: "story-seo",
  data() {
    return {
      cloudinaryInfo: null,
      publisher: "",
      publisherLogoSrc: "",
      posterPortraitSrc: "",
      supportsLandscape: true,
      backgroundAudio: "",
      posterSquareSrc: "",
      posterLandscapeSrc: "",
      css: ""
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
    }
  },
  mounted() {
    

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

    if (this.$store.state.story) {
      if (this.publisher.trim() === "") {
        this.publisher = this.$store.state.story.data.publisher;
      }
      this.posterPortraitSrc = this.$store.state.story.data.posterPortraitSrc;
      this.posterSquareSrc = this.$store.state.story.data.posterSquareSrc;
      this.supportsLandscape = this.$store.state.story.data.supportsLandscape;
      this.publisherLogoSrc = this.$store.state.story.data.publisherLogoSrc;
      this.posterLandscapeSrc = this.$store.state.story.data.posterLandscapeSrc;
    }

    this.$http.get("/story/style").then(
      response => {
        response.data.forEach(element => {
          if (
            element.slug === "publisher" &&
            element.data &&
            (this.publisher == "Publisher" || this.publisher.trim() == "")
          ) {
            this.publisher = element.data;
          }
          if (element.slug === "publisherLogoSrc" && element.data && this.publisherLogoSrc.trim()=="") {
            this.publisherLogoSrc = element.data;
          }
        });
      },
      error => {}
    );
  },
  watch: {
    posterPortraitSrc(value) {
      Vue.$emit("story:settings", { posterPortraitSrc: value });
    },
    posterSquareSrc(value) {
      Vue.$emit("story:settings", { posterSquareSrc: value });
    },
    posterLandscapeSrc(value) {
      Vue.$emit("story:settings", { posterLandscapeSrc: value });
    },
    supportsLandscape(value) {
      Vue.$emit("story:settings", { supportsLandscape: value });
    },
    publisher(value) {
      Vue.$emit("story:settings", { publisher: value });
    },
    publisherLogoSrc(value) {
      Vue.$emit("story:settings", { publisherLogoSrc: value });
    }
  }
};
</script>

<style scoped>
.md-layout {
  padding-bottom: 20px;
}
</style>