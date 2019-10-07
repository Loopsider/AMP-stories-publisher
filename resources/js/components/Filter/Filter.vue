<template>
  <div class="app-filter">
    <div class="tab-navigation">
      <md-tabs class="md-transparent">
        <md-tab id="all" md-label="ALL" @click="selectedUser('all')"></md-tab>
        <md-tab id="by-me" md-label="OWNED BY ME" @click="selectedUser('me')"></md-tab>
      </md-tabs>
    </div>

    <div class="tab-navigation">
      <md-field style="padding:5px;width:300px;">
        <md-icon class="mr-2">language</md-icon>
        <md-select name="language" id="language" v-model="entity_language">
          <md-option
            v-for="(language, index) in entity_languages"
            :value="language.value"
            :key="index"
          >{{ language.label }}</md-option>
        </md-select>
      </md-field>
    </div>

    <div class="tool-box md-transparent">
      <div class="search">
        <md-field class="m-0 md-transparent">
          <md-icon>search</md-icon>
          <label>Search</label>
          <md-input v-model="search"></md-input>
        </md-field>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: "app-filter",
  created(){
    Vue.$on("reset:language",()=> {
      if(this.$store.state.userInfo.language){
        Vue.$emit("search:forEntities", {searchField:this.search,languageField:this.$store.state.userInfo.language});
        this.entity_language=this.$store.state.userInfo.language; 
      }else{
        Vue.$emit("search:forEntities", {searchField:this.search,languageField:"all"});
        this.entity_language="all";
      } 
    })
  },
  data() {
    return {
      entity_language:"all",
      entity_languages: [
        {
          code: "all",
          label: "All",
          value: "all"
        },
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
      ],
      search: ""
    };
  },
  methods: {
    selectedUser(user) {
      Vue.$emit("user:select", user);
    }
  },
  watch: {
    search() {
      Vue.$emit("search:forEntities", {searchField:this.search,languageField:this.entity_language});
    },
    entity_language(){
      Vue.$emit("search:forEntities", {searchField:this.search,languageField:this.entity_language});
    }
  },
  mounted() {
    Vue.$on("search:clear", () => {
      this.search = "";
    });
  },
  beforeDestroy() {
    Vue.$off("search:clear");
  }
};
</script>

<style scoped lang="scss">
</style>