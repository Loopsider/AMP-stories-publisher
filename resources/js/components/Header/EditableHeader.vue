<template>
  <header class="app-header navbar">
    <b-link class="navbar-brand" to="/backoffice">
      <img src="/assets/logo.png">
    </b-link>
    <div class="editable-header-title">
      <input type="text" class="width-dynamic" v-model="title" :disabled="!editable">
    </div>
    <div class="editable-tools mr-3">
      <span class="icon-group">
        <span v-if="editable" @click="setting">
          <md-icon>settings</md-icon>
        </span>
      </span>

      <md-button class="md-raised" v-if="editable" @click="publish">
        <md-icon>save</md-icon>save
      </md-button>

      <md-button class="md-raised" v-if="editable && $route.name=='Story'" @click="showPublishDiaglog()">
        <md-icon>publish</md-icon>publish
      </md-button>

      <md-button class="md-raised" @click="toggleEdit">
        <md-icon>{{ editable ? 'visibility' : 'edit' }}</md-icon>
        {{ editable ? 'View' : 'Edit' }}
      </md-button>
    </div>
    <b-navbar-nav class="ml-auto">
      <HeaderDropdownAccnt/>
    </b-navbar-nav>

    <!-- Publish popup -->
    <md-dialog :md-active.sync="publishDialog.value">
      <md-dialog-title>Publish ?</md-dialog-title>
      <md-dialog-content style="min-width: 500px;">
        <div style="height: 80px;">
          <p v-if="$store.state.story.data.posterPortraitSrc=='' || $store.state.story.data.publisher=='' || $store.state.story.data.publisherLogoSrc==''">
            The following must be provided:
            <ul>
              <li v-if="$store.state.story.data.posterPortraitSrc==''">
                Poster portrait image
              </li>
              <li v-if="$store.state.story.data.publisher==''">
                Publisher
              </li>
              <li v-if="$store.state.story.data.publisherLogoSrc==''">
                Publisher Logo
              </li>
            </ul>
          </p>
          <p v-else>
            You're about to publish this story.
            <br>Are you sure?
          </p>
        </div>
      </md-dialog-content>
      <md-dialog-actions>
        <md-button class="md-primary" @click="closePublishDiaglog()">cancel</md-button>
        <md-button 
          v-if="$store.state.story.data.posterPortraitSrc!='' && 
                $store.state.story.data.publisher!='' && 
                $store.state.story.data.publisherLogoSrc!=''" 
          class="md-primary" @click="publishStory()">publish</md-button>
          <md-button v-if="$store.state.story.data.posterPortraitSrc=='' || $store.state.story.data.publisher=='' || $store.state.story.data.publisherLogoSrc==''" class="md-primary" @click="closePublishDiaglog();setting()">next</md-button>
      </md-dialog-actions>
    </md-dialog>
  </header>
</template>

<script>
import HeaderDropdownAccnt from "./HeaderDropdownAccnt.vue";
import { setTimeout } from 'timers';

export default {
  name: "app-editable-header",
  data() {
    return {
      publishDialog:null
    };
  },

  components: {
    HeaderDropdownAccnt
  },

  created(){
    this.initData();
  },
  mounted() {
    $.fn.textWidth = function(text, font) {
      if (!$.fn.textWidth.fakeEl)
        $.fn.textWidth.fakeEl = $("<span>")
          .hide()
          .appendTo(document.body);
      $.fn.textWidth.fakeEl
        .text(text || this.val() || this.text() || this.attr("placeholder"))
        .css("font", font || this.css("font"));
      return $.fn.textWidth.fakeEl.width();
    };

    $(".width-dynamic")
      .on("input", function() {
        const inputWidth = $(this).textWidth();
        $(this).css({
          width: inputWidth + 15
        });
      })
      .trigger("input");
  },
  methods: {
    initData(){
      this.publishDialog = {
        value: false,
      }
    },
    showPublishDiaglog() {
      this.initData();
      this.publishDialog.value = true;
    },
    closePublishDiaglog() {
      this.publishDialog.value = false;
    },
    publishStory() {
      if(this.$route.params.id){
        Vue.$http.post('/story/publish', {id:this.$route.params.id}).then((response) => {
          this.closePublishDiaglog();
            Vue.alertBox({
            title: "Success",
            text: "Successfully updated this video!",
            type: "success"
          });
        });
      }
    },
    toggleEdit() {
      this.$store.dispatch("updateAppEditable", !this.editable);
    },
    publish() {
      console.log("[app:publish] action trigger ...");
      Vue.$emit("app:publish");
      setTimeout(()=>{
        if(this.$store.state.story.id){
          this.$router.push({
            params:{
              id:this.$store.state.story.id
            }
          });
          location.reload();
        }
},1000);


    },
    setting() {
      console.log("[app:setting] action trigger ...");
      Vue.$emit("app:setting");
    }
  },
  computed: {
    title: {
      get() {
        return this.$store.state.app.title;
      },
      set(value) {
        this.$store.dispatch("updateAppTitle", value);
      }
    },
    editable() {
      return this.$store.state.app.editable;
    }
  }
};
</script>
