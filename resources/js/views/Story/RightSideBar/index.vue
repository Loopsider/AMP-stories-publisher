<template>
  <div class="story-right-sidebar md-elevation-3">
    <template v-if="element && element.type">
      <h4>{{ propertyName }} Properties</h4>
      <md-divider></md-divider>
      <div class="custom-tabs">
        <div class="tab-buttons">
          <md-button
            :class="{'md-primary' : isActive('settings')}"
            @click="activeTab='settings'"
          >SETTINGS</md-button>
          <md-button
            v-if="element && element.type !== 'richtext'"
            :class="{'md-primary' : isActive('design')}"
            @click="activeTab='design'"
          >DESIGN</md-button>
          <md-button
            :class="{'md-primary' : isActive('animation')}"
            @click="activeTab='animation'"
          >ANIMATION</md-button>
        </div>
        <div class="tab-contents">
          <property-settings v-if="isActive('settings')" :element="element"></property-settings>
          <property-design v-if="isActive('design')" :element="element"></property-design>
          <property-animation v-if="isActive('animation')" :element="element"></property-animation>
        </div>
      </div>
    </template>
    <template v-if="(layer && layer.template)">
      <h4>New element</h4>
      <md-divider></md-divider>
      <element-list></element-list>
    </template>
    <template v-if="page">
      <h4>Page Properties</h4>
      <setting-page :page="page"></setting-page>
    </template>
    <div v-if="(layer && layer.template)">
      <h6 class="mt-5">Margin</h6>
      <hr>
      <div class="margin-area">
        <div class="text-center">
          <p>Top (%)</p>
          <input type="number" v-model="marginTop" style="border: 1px solid #d9d9d9">
        </div>
        <div class="clearfix"></div>
        <div class="text-center mt-2">
          <div class="float-left">
            <p>Left (%)</p>
            <input type="number" v-model="marginLeft" style="border: 1px solid #d9d9d9">
          </div>
          <div class="float-right">
            <p>Right (%)</p>
            <input type="number" v-model="marginRight" style="border: 1px solid #d9d9d9">
          </div>
        </div>
        <div class="clearfix"></div>
        <div class="text-center mt-2">
          <p>Bottom (%)</p>
          <input type="number" v-model="marginBottom" style="border: 1px solid #d9d9d9">
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import PropertySettings from "./PropertySettings";
import PropertyDesign from "./PropertyDesign";
import PropertyAnimation from "./PropertyAnimation";
import ElementList from "../components/ElementList";
import utils from "../../utils";
import constants from "../../constants";
import SettingPage from "../components/Properties/SettingPage";

export default {
  name: "story-right-side-bar",
  components: {
    SettingPage,
    ElementList,
    PropertyAnimation,
    PropertyDesign,
    PropertySettings
  },
  data() {
    return {
      propertyName: "",
      activeTab: "settings"
    };
  },
  methods: {
    /**
     * get Element Name from slug
     *
     * @param slug
     */
    getPropertyName(slug) {
      const element = utils.getElement(slug);
      return element ? element.name : "";
    },
    /**
     * whether property enable
     *
     * @param slug
     * @returns {boolean}
     */
    isActive(slug) {
      return this.activeTab === slug;
    }
  },
  mounted() {
    // set properties emit receiver from Properties Components
    Vue.$on("setting:properties", data => {
      const element = _.merge(this.element, data);
      const pages = Object.assign([], this.$store.state.story.data.pages);
      const selected = this.$store.state.story.selected;
      pages[selected.page].layers[selected.layer].elements[
        selected.element
      ] = element;

      this.$store.dispatch("saveAMPStory", {
        data: {
          pages: pages
        },
        publish: false
      });
    });
    // set page emit receiver from setting page Component
    Vue.$on("setting:page", data => {
      const page = _.merge(this.page, data);
      const pages = Object.assign([], this.$store.state.story.data.pages);
      const selected = this.$store.state.story.selected;
      pages[selected.page] = page;
      this.$store.dispatch("saveAMPStory", {
        data: {
          pages: pages
        },
        publish: false
      });
    });

    // set page emit receiver from setting page Component
    Vue.$on("setting:layer", data => {
      const layer = _.merge(this.layer, data);
      const pages = Object.assign([], this.$store.state.story.data.pages);
      const selected = this.$store.state.story.selected;
      pages[selected.page].layers[selected.layer] = layer;

      this.$store.dispatch("saveAMPStory", {
        data: {
          pages: pages
        },
        publish: false
      });
    });

    // add element emit receiver from AMPElement Component
    Vue.$on("add:element", slug => {
      const pages = Object.assign([], this.$store.state.story.data.pages);
      const selected = this.$store.state.story.selected;
      let newElement = { type: slug, properties: { id: "", class: "" } };

      if (pages[selected.page].layers[selected.layer].template === "thirds") {
        if (selected.gridArea < 0) {
          Vue.alertBox({
            title: "Warning",
            text: "Please select one of 3 grid areas!",
            type: "warning"
          });
          return;
        }
        newElement = {
          "grid-area": constants.gridAreas[selected.gridArea].slug,
          ...newElement
        };
      }

      pages[selected.page].layers[selected.layer].elements.push(newElement);
      selected.element =
        pages[selected.page].layers[selected.layer].elements.length - 1;

      this.$store.dispatch("saveAMPStory", {
        data: {
          pages: pages
        },
        publish: false,
        selected
      });
    });
    // select element emit receiver from Each elements
    Vue.$on("select:element", () => {
      this.activeTab = "settings";
    });
  },
  computed: {
    element() {
      const selected = this.$store.state.story.selected;
      if (selected.element >= 0) {
        const el = this.$store.state.story.data.pages[selected.page].layers[
          selected.layer
        ].elements[selected.element];
        this.propertyName = !_.isNil(el) ? this.getPropertyName(el.type) : null;
        return el;
      }
      return null;
    },
    gridArea() {
      const selected = this.$store.state.story.selected;
      if (selected.layer >= 0 && selected.element < 0) {
        return this.$store.state.story.data.pages[selected.page].layers[
          selected.layer
        ];
      }
      return null;
    },
    layer() {
      const selected = this.$store.state.story.selected;
      if (selected.layer >= 0 && selected.element < 0) {
        return this.$store.state.story.data.pages[selected.page].layers[
          selected.layer
        ];
      }
      return null;
    },
    page() {
      const selected = this.$store.state.story.selected;
      if (selected.page >= 0 && selected.layer < 0 && selected.element < 0) {
        return this.$store.state.story.data.pages[selected.page];
      }
      return null;
    },
    marginLeft: {
      get() {
        if (
          !_.isNil(
            this.layer && this.layer["margin"] && this.layer["margin"].left
          )
        ) {
          return this.layer["margin"].left;
        }
        return "";
      },
      set(value) {
        if (this.marginLeft !== value) {
          // Vue.$emit("setting:page", { margin: { left: value } });
          Vue.$emit("setting:layer", { margin: { left: value } });
        }
      }
    },
    marginRight: {
      get() {
        if (
          !_.isNil(
            this.layer && this.layer["margin"] && this.layer["margin"].right
          )
        ) {
          return this.layer["margin"].right;
        }
        return "";
      },
      set(value) {
        if (this.marginRight !== value) {
          // Vue.$emit("setting:page", { margin: { left: value } });
          Vue.$emit("setting:layer", { margin: { right: value } });
        }
      }
    },
    marginTop: {
      get() {
        if (
          !_.isNil(
            this.layer && this.layer["margin"] && this.layer["margin"].top
          )
        ) {
          return this.layer["margin"].top;
        }
        return "";
      },
      set(value) {
        if (this.marginTop !== value) {
          // Vue.$emit("setting:page", { margin: { left: value } });
          Vue.$emit("setting:layer", { margin: { top: value } });
        }
      }
    },
    marginBottom: {
      get() {
        if (
          !_.isNil(
            this.layer && this.layer["margin"] && this.layer["margin"].bottom
          )
        ) {
          return this.layer["margin"].bottom;
        }
        return "";
      },
      set(value) {
        if (this.marginBottom !== value) {
          // Vue.$emit("setting:page", { margin: { left: value } });
          Vue.$emit("setting:layer", { margin: { bottom: value } });
        }
      }
    }
  },
  beforeDestroy() {
    Vue.$off("setting:properties");
    Vue.$off("setting:page");
    Vue.$off("add:element");
    Vue.$off("select:element");
  }
};
</script>

<style scoped>
</style>