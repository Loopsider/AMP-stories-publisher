<template>
  <div class="story-language">
    <!-- Story language -->
    <span>Story language</span>
    <md-field>
      <md-icon class="mr-2">language</md-icon>
      <md-select v-model="story_language" name="language" id="language">
        <md-option
          v-for="(language, index) in story_languages"
          :value="language.value"
          :key="index"
        >{{ language.label }}</md-option>
      </md-select>
    </md-field>
  </div>
</template>

<script>
export default {
  name: "story-language",
  mounted() {
    if (this.$store.state.story) {
      this.story_language = this.$store.state.story.story_language;
    }else{
      if (this.$store.state.userInfo.language) {
        this.story_language = this.$store.state.userInfo.language;
      } else {
        this.story_language = "french";
      }
    }
  },
  data() {
    return {
      story_language: null,
      story_languages: [
        {
          code: "fr",
          label: "French",
          value: "french"
        },
        {
          code: "en",
          label: "English",
          value: "english"
        },
        {
          code: "es",
          label: "Spanish",
          value: "spanish"
        }
      ]
    };
  },
  watch: {
      story_language (value) {
        Vue.$emit('story:settings', { story_language: value });
      },
    }
};
</script>

<style scoped>
.story-language {
  margin-top: 20px;
}
</style>

