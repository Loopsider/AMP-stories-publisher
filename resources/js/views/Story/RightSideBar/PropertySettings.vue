<template>
    <div class="property-settings">
        <template v-if="element">
            <setting-rich-text v-if="this.element.type === 'richtext'" :el="element"></setting-rich-text>
            <setting-image v-if="this.element.type === 'img'" :el="element"></setting-image>
            <setting-video v-if="this.element.type === 'video'" :el="element"></setting-video>
            <setting-audio v-if="this.element.type === 'audio'" :el="element"></setting-audio>
        </template>
    </div>
</template>

<script>
  import SettingRichText from "../components/properties/SettingRichText";
  import SettingImage from "../components/properties/SettingImage";
  import SettingVideo from "../components/properties/SettingVideo";
  import SettingAudio from "../components/properties/SettingAudio";

  export default {
    name: "property-settings",
    components: {
      SettingAudio,
      SettingVideo,
      SettingImage,
      SettingRichText
    },
    props: {
      element: Object
    },
    methods: {},
  }
</script>

<style scoped>

</style>