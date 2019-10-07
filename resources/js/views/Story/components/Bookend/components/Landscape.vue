<template>
    <div>
        <md-field>
            <label>Title</label>
            <md-input v-model="landscape.title" placeholder=""></md-input>
        </md-field>
        <md-field>
            <label>URL</label>
            <md-input v-model="landscape.url" placeholder=""></md-input>
        </md-field>
        <md-field>
            <label>Category</label>
            <md-input v-model="landscape.category" placeholder=""></md-input>
        </md-field>
        <br>
        <img v-if="landscape.image" :src="landscape.image" class="attachment-image md-elevation-7"/>
        <br>
        <button type="button" class="md-button md-raised md-theme-default px-3 ml-0"
                id="upload_story_bookend_landscape">
            Select an Image
        </button>
    </div>
</template>

<script>
  export default {
    name: "bookend-landscape",
    props: {
      data: Object
    },
    data() {
      return {
        landscape: {
          title: '',
          url: '',
          image: '',
          category: '',
        },
        cloudinaryInfo: null
      }
    },
    methods: {
      generateMediaLibraries () {
        const vm = this;
        cloudinary.createMediaLibrary(
          { ...vm.cloudinaryInfo },
          {
            insertHandler: function (data) {
              let result = data.assets[0];
              vm.landscape.image = result.secure_url.substr(0, result.secure_url.lastIndexOf(".")) + ".jpg";
            }
          },
          document.getElementById("upload_story_bookend_landscape")
        );
      }
    },
    mounted () {
      this.cloudinaryInfo = {
        cloud_name: this.$store.state.cloudinary.cloudName,
        api_key: this.$store.state.cloudinary.apiKey,
        username: this.$store.state.cloudinary.userName,
        timestamp: this.$store.state.cloudinary.timestamp,
        signature: this.$store.state.cloudinary.signature,
        button_class: 'md-button md-raised md-theme-default px-3 ml-0',
        button_caption: 'Select an Image',
        multiple: false
      };
      this.generateMediaLibraries();
      if(!_.isNil(this.data) && !_.isNil(this.data.components)) {
        const fData = this.data.components.filter(comp => comp.type === 'landscape');
        if(fData.length > 0) {
          this.landscape.title = fData[0].title;
          this.landscape.url = fData[0].url;
          this.landscape.image = fData[0].image;
          this.landscape.category = fData[0].category;
        }
      }
    },
    watch: {
      landscape: {
        handler: function (data) {
          Vue.$emit('story-bookend:settings', {
            type: 'component',
            data: {
              'type': 'landscape',
              'title': data.title,
              'url': data.url,
              'image': data.image,
              'category': data.category
            }
          });
        },
        deep: true
      }
    }
  }
</script>

<style scoped>

</style>