<template>
    <div class="story">
        <template v-if="editable">
            <story-edit></story-edit>
        </template>
        <template v-else>
            <story-view :slug="slug"></story-view>
        </template>
    </div>
</template>

<script>
  import StoryEdit from "./StoryEdit";
  import StoryView from "./StoryView";

  export default {
    name: "story",
    components: { StoryView, StoryEdit },
    data () {
      return {
        slug: '',
      }
    },
    methods: {
      /**
       * load story by story_id
       *
       * @param id
       */
      loadStory (id) {
        Vue.block();
        this.$http.get('/story/' + id).then((response) => {
          Vue.unBlock();
          this.$store.dispatch('updateAMPStory', response.data);
          this.$store.dispatch('updateAppTitle', response.data.title);
          this.slug = response.data.slug;
        }, (error) => {
          Vue.unBlock();
        }).catch(Vue.handleClientError);
      }
    },
    mounted () {
      if (this.$route.params.id) {
        this.loadStory(this.$route.params.id);
        // action to go to story view once load by story id
        this.$store.dispatch('updateAppEditable', false);
      } else {
        // action to go to story creation page once clicking new story button
        this.$store.dispatch('updateAppTitle', 'Untitled Story');
        this.$store.dispatch('updateAppEditable', true);
        this.$store.dispatch('clearAMPStory');
        this.$store.dispatch('fetchStoryStyle');
      }
    },
    beforeDestroy () {
      // action to remove temporary story data from redis once living this container
      this.$store.dispatch('deleteAMPStoryFromRedis');
    },
    computed: {
      editable () {
        return this.$store.state.app.editable;
      }
    }
  }
</script>

<style scoped>

</style>