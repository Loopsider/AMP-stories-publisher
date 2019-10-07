<template>
    <div>
        <md-field>
            <label>Title</label>
            <md-input v-model="small.title" placeholder=""></md-input>
        </md-field>
        <md-field>
            <label>URL</label>
            <md-input v-model="small.url" placeholder=""></md-input>
        </md-field>
        <br>
        <img v-if="small.image" :src="small.image" class="attachment-image md-elevation-7"/>
        <br>
        <button type="button" class="md-button md-raised md-theme-default px-3 ml-0"
                id="upload_story_bookend_small">
            Select an Image
        </button>
    </div>
</template>

<script>
  export default {
    name: "bookend-small",
    props: {
      data: Object
    },
    data() {
      return {
        small: {
          title: '',
          url: '',
          image: ''
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
              vm.small.image = result.secure_url.substr(0, result.secure_url.lastIndexOf(".")) + ".jpg";
            }
          },
          document.getElementById("upload_story_bookend_small")
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
        const fData = this.data.components.filter(comp => comp.type === 'small');
        if(fData.length > 0) {
          this.small.title = fData[0].title;
          this.small.url = fData[0].url;
          this.small.image = fData[0].image;
        }
      }
    },
    watch: {
      small: {
        handler: function (data) {
          Vue.$emit('story-bookend:settings', {
            type: 'component',
            data: {
              'type': 'small',
              'title': data.title,
              'url': data.url,
              'image': data.image
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