<template>
    <div class="story-slide_view">
        <iframe v-if="storyUrl" :src="storyUrl" width="100%" height="100%"></iframe>
    </div>



    
</template>

<script>

  export default {
    name: "story-view",
    props: {
      slug: String
    },
    components: {},
    data () {
      return {}
    },
    computed: {
      storyUrl () {
        if (this.slug) {
          return `${app_url}/story/${this.slug}`;
        }
        return null;
      },
    }
  }
</script>

<style scoped>

</style>