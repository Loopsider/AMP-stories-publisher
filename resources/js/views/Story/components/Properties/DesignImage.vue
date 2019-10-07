<template>
  <div>
    <md-field>
      <label>Width</label>
      <md-input v-model="elementWidth"></md-input>
    </md-field>
    <md-field>
      <label>Height</label>
      <md-input v-model="elementHeight"></md-input>
    </md-field>
    <md-field>
      <label>Layout</label>
      <md-select v-model="elementLayout">
        <md-option
          v-for="(opt, index) in layoutOptions"
          :value="opt.slug"
          :key="index"
        >{{ opt.name }}</md-option>
      </md-select>
    </md-field>
    <md-field>
      <label>Position</label>
      <md-select v-model="elementPosition">
        <md-option
          v-for="(opt, index) in layoutPosition"
          :value="opt.slug"
          :key="index"
        >{{ opt.name }}</md-option>
      </md-select>
    </md-field>
  </div>
</template>

<script>
import constants from "../../../constants";

export default {
  name: "design-image",
  props: {
    el: Object
  },
  data() {
    return {
      layoutOptions: constants.elementLayouts,
      layoutPosition: constants.positions.img,
    };
  },
  computed: {
    elementWidth: {
      get() {
        if (
          !_.isNil(this.el && this.el.properties && this.el.properties.width)
        ) {
          return this.el.properties.width;
        }
        return "";
      },
      set: _.debounce(function(value) {
        if (this.elementWidth !== value)
          Vue.$emit("setting:properties", { properties: { width: value } });
      }, 2000)
    },
    elementHeight: {
      get() {
        if (
          !_.isNil(this.el && this.el.properties && this.el.properties.height)
        ) {
          return this.el.properties.height;
        }
        return "";
      },
      set: _.debounce(function(value) {
        if (this.elementHeight !== value)
          Vue.$emit("setting:properties", { properties: { height: value } });
      }, 2000)
    },
    elementLayout: {
      get() {
        if (
          !_.isNil(this.el && this.el.properties && this.el.properties.layout)
        ) {
          return this.el.properties.layout;
        }
        return "";
      },
      set: _.debounce(function(value) {
        if (this.elementLayout !== value)
          Vue.$emit("setting:properties", { properties: { layout: value } });
      }, 2000)
    },





    elementPosition: {
      get() {
        if (!_.isNil(this.el && this.el.properties && this.el.properties.verticalPositioning)){
          return this.el.properties.verticalPositioning;
        }
        return "top-left";
      },
      set: _.debounce(function(value) {
        if (this.elementPosition !== value)
          Vue.$emit("setting:properties", {
            properties: { verticalPositioning: value }
          });
      }, 2000)
    }
  }
};
</script>

<style scoped>
</style>