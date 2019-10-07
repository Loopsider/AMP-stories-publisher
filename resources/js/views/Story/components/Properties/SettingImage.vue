<template>
    <div>
        <md-field>
            <label>Id</label>
            <md-input v-model="elementId"></md-input>
        </md-field>
        <md-field>
            <label>Class</label>
            <md-input v-model="elementClass"></md-input>
        </md-field>
        <img v-if="elementSrc" :src="elementSrc" class="attachment-image md-elevation-7"/>
        <br>
        <button type="button" class="md-button md-raised md-theme-default px-3 ml-0"
                id="upload_property_image">
            Select an Image
        </button>
    </div>
</template>

<script>
  export default {
    name: "setting-image",
    props: {
      el: Object
    },
    data () {
      return {
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
              vm.elementSrc = result.secure_url.substr(0, result.secure_url.lastIndexOf(".")) + ".jpg";
            }
          },
          document.getElementById("upload_property_image")
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
    },
    computed: {
      elementId: {
        get () {
          if (!_.isNil(this.el && this.el.id)) {
            return this.el.id;
          }
          return '';
        },
        set: _.debounce(function (value) {
          if (this.elementId !== value)
            Vue.$emit('setting:properties', { id: value });
        }, 2000),
      },
      elementClass: {
        get () {
          if (!_.isNil(this.el && this.el.class)) {
            return this.el.class;
          }
          return '';
        },
        set: _.debounce(function (value) {
          if (this.elementClass !== value)
            Vue.$emit('setting:properties', { class: value });
        }, 2000),
      },
      elementSrc: {
        get () {
          if (!_.isNil(this.el && this.el.properties && this.el.properties.src)) {
            return this.el.properties.src;
          }
          return '';
        },
        set (value) {
          if (this.elementSrc !== value) {
            Vue.$emit('setting:properties', { properties: { src: value } });
          }
        },
      },
    },
  }
</script>

<style scoped>

</style>