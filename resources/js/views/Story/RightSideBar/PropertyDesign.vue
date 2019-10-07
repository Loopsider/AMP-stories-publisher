<template>
    <div class="property-design">
        <template v-if="element">
            <design-rich-text v-if="this.element.type === 'richtext'" :el="element"></design-rich-text>
            <design-image v-if="this.element.type === 'img'" :el="element"></design-image>
            <design-audio v-if="this.element.type === 'audio'" :el="element"></design-audio>
            <design-video v-if="this.element.type === 'video'" :el="element"></design-video>
        </template>
    </div>
</template>

<script>
  import DesignImage from "../components/properties/DesignImage";
  import DesignAudio from "../components/properties/DesignAudio";
  import DesignVideo from "../components/properties/DesignVideo";
  import DesignRichText from "../components/properties/DesignRichText";

  export default {
    name: "property-design",
    components: {
      DesignRichText,
      DesignVideo,
      DesignAudio,
      DesignImage,
    },
    props: {
      element: Object
    },
  }
</script>

<style scoped>

</style>