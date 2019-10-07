<template>
    <div>
        <div class="element-media-group mt-2">
            <md-icon>arrow_drop_down</md-icon>
            Media
            <div class="child-elements">
                <amp-element v-for="(slug, index) in mediaElements" :slug="slug" :key="index"></amp-element>
            </div>
        </div>
        <amp-element slug="richtext" class="mt-2"></amp-element>
    </div>
</template>

<script>
  import AmpElement from "./AMPElement";

  export default {
    name: "element-list",
    components: { AmpElement },
    data () {
      return {
        mediaElements: [
          'img',
          'video',
          'audio'
        ],
      }
    },

  }
</script>

<style scoped>
    .child-elements {
        margin-left: 20px;
    }
</style>