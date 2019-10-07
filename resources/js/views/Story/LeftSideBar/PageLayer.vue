<template>
    <div class="page-layer">
        <div class="layer-template" :class="{ selected : isLayerSelected()}">
            <img class="layer-template_icon" :src="getLayerTemplateIcon(layer.template)" alt="No Icon"/>
            <div class="layer-template_name" @click="selectLayer">
                {{ getLayerTemplateName(layer.template)}} Template
            </div>
            <div class="btn-tools">
                <span @click="orderUpLayer">
                    <md-icon style="transform: rotate(-90deg);">trending_flat</md-icon>
                </span>
                <span @click="orderDownLayer">
                    <md-icon style="transform: rotate(90deg);">trending_flat</md-icon>
                </span>
                <span @click="removeLayer">
                    <md-icon>delete_outline</md-icon>
                </span>
            </div>
        </div>
        <div class="layer-elements">
            <template v-if="isGridArea(layer.template)">
                <template v-for="(gridArea, gridIndex) of gridAreas">
                    <div class="element-grid-area"
                         :class="{ selected : isGridAreaSelected(gridIndex)}"
                         @click="selectGridArea(gridIndex)" v-html="gridArea.name"></div>
                    <div class="element" v-if="element['grid-area'] === gridArea.slug"
                         v-for="(element, index) of layer.elements" :key="index">
                        <div class="element-type" :class="{ selected : isElementSelected(index)}">
                            [
                            <span class="element-type_label" @click="selectGridElement(gridIndex, index)">
                                {{ getElementName(element.type)}}
                            </span>
                            ]
                            <div class="btn-tools">
                                <span @click="orderGridUpElement(gridIndex, index)">
                                    <md-icon style="transform: rotate(-90deg);">trending_flat</md-icon>
                                </span>
                                <span @click="orderGridDownElement(gridIndex, index)">
                                    <md-icon style="transform: rotate(90deg);">trending_flat</md-icon>
                                </span>
                                <span @click="removeElement(index)"><md-icon>delete_outline</md-icon></span>
                            </div>
                        </div>
                    </div>
                </template>
            </template>
            <template v-else>
                <div class="element" v-for="(element, index) of layer.elements" :key="index">
                    <div class="element-type" :class="{ selected : isElementSelected(index)}">
                        [
                        <span class="element-type_label"
                              @click="selectElement(index)">{{ getElementName(element.type)}}
                        </span>
                        ]
                        <div class="btn-tools">
                            <span @click="orderUpElement(index)">
                                <md-icon style="transform: rotate(-90deg);">trending_flat</md-icon>
                            </span>
                            <span @click="orderDownElement(index)">
                                <md-icon style="transform: rotate(90deg);">trending_flat</md-icon>
                            </span>
                            <span @click="removeElement(index)">
                                <md-icon>delete_outline</md-icon>
                            </span>
                        </div>
                    </div>
                </div>
            </template>
        </div>
    </div>
</template>

<script>
  import utils from '../../utils';
  import constants from '../../constants';

  export default {
    name: "page-layers",
    props: {
      layer: Object,
      layerIndex: Number,
      pageIndex: Number,
    },
    data () {
      return {
        toggleSetting: false,
        layerTemplate: null,
        layerElements: null,
        gridAreas: constants.gridAreas
      }
    },
    methods: {

      /**
       * Order Up Layer
       */
      orderUpLayer () {
        Vue.$emit("order-up:layer", {
          layerIndex: this.layerIndex,
        });
      },

      /**
       * Order Down Layer
       */
      orderDownLayer () {
        Vue.$emit("order-down:layer", {
          layerIndex: this.layerIndex,
        });
      },

      /**
       * Order Up Element
       */
      orderUpElement (index) {
        Vue.$emit("order-up:element", {
          layerIndex: this.layerIndex,
          elementIndex: index,
        });
      },

      /**
       * Order Down Element
       */
      orderDownElement (index) {
        Vue.$emit("order-down:element", {
          layerIndex: this.layerIndex,
          elementIndex: index,
        });
      },

      /**
       * Order Up GridElement
       */
      orderGridUpElement (gridIndex, elementIndex) {
        Vue.$emit("order-up:grid-element", {
          layerIndex: this.layerIndex,
          gridIndex: gridIndex,
          elementIndex: elementIndex,
        });
      },

      /**
       * Order Down GridElement
       */
      orderGridDownElement (gridIndex, elementIndex) {
        Vue.$emit("order-down:grid-element", {
          layerIndex: this.layerIndex,
          gridIndex: gridIndex,
          elementIndex: elementIndex,
        });
      },

      /**
       * remove element
       *
       * @param elementIndex
       */
      removeElement (elementIndex) {
        Vue.$emit("remove:element", {
          layerIndex: this.layerIndex,
          elementIndex: elementIndex,
        });
      },

      /**
       * remove layer
       */
      removeLayer () {
        Vue.$emit("remove:layer", this.layerIndex);
      },

      /**
       * current layer is selected ?
       *
       * @returns {boolean}
       */
      isLayerSelected () {
        if (this.$store.state.story.selected.page !== this.pageIndex) {
          return false;
        }
        if (this.$store.state.story.selected.layer !== this.layerIndex) {
          return false;
        }
        if (this.$store.state.story.selected.gridArea >= 0) {
          return false;
        }
        if (this.$store.state.story.selected.element >= 0) {
          return false;
        }
        return true;
      },

      /**
       * current grid-area is selected by index
       *
       * @param index
       * @returns {boolean}
       */
      isGridAreaSelected (index) {
        if (this.$store.state.story.selected.page !== this.pageIndex) {
          return false;
        }
        if (this.$store.state.story.selected.layer !== this.layerIndex) {
          return false;
        }
        if (this.$store.state.story.selected.gridArea !== index) {
          return false;
        }
        if (this.$store.state.story.selected.element >= 0) {
          return false;
        }
        return true;
      },

      /**
       * current element is selected by index
       *
       * @param index
       * @returns {boolean}
       */
      isElementSelected (index) {
        if (this.$store.state.story.selected.page !== this.pageIndex) {
          return false;
        }
        if (this.$store.state.story.selected.layer !== this.layerIndex) {
          return false;
        }
        if (this.$store.state.story.selected.element !== index) {
          return false;
        }
        return true;
      },

      /**
       * select layer
       *
       * @param index
       */
      selectLayer (index) {
        this.$store.dispatch('selectAMPStory', {
          page: this.pageIndex,
          layer: this.layerIndex,
        });
      },

      /**
       * select grid-area
       *
       * @param index
       */
      selectGridArea (index) {
        this.$store.dispatch('selectAMPStory', {
          page: this.pageIndex,
          layer: this.layerIndex,
          gridArea: index,
        });
      },

      /**
       * select grid element
       *
       * @param gridIndex, elementIndex
       */
      selectGridElement (gridIndex, elementIndex) {
        Vue.$emit("select:element");
        this.$store.dispatch('selectAMPStory', {
          page: this.pageIndex,
          layer: this.layerIndex,
          gridArea: gridIndex,
          element: elementIndex,
        });
      },
      /**
       * select element
       *
       * @param index
       */
      selectElement (index) {
        Vue.$emit("select:element");
        this.$store.dispatch('selectAMPStory', {
          page: this.pageIndex,
          layer: this.layerIndex,
          element: index,
        });
      },

      /**
       * get Layer Template name by slug
       *
       * @param slug
       * @returns {string}
       */
      getLayerTemplateName (slug) {
        const template = utils.getLayerTemplate(slug);
        return template ? template.name : '';
      },

      /**
       * get Layer Template Icon by slug
       *
       * @param slug
       * @returns {string}
       */
      getLayerTemplateIcon (slug) {
        const template = utils.getLayerTemplate(slug);
        return template ? template.icon : '';
      },

      /**
       * get Element name by slug
       *
       * @param slug
       * @returns {string}
       */
      getElementName (slug) {
        const element = utils.getElement(slug);
        return element ? element.name : '';
      },

      /**
       * get GridArea name by slug
       *
       * @param slug
       * @returns {string}
       */
      getGridAreaName (slug) {
        const grid = utils.getGridArea(slug);
        return grid ? grid.name : '';
      },

      /**
       * whether current element is in GridArea on thirds Template
       *
       * @param slug
       * @returns {*}
       */
      isGridArea (slug) {
        return utils.isGridArea(slug)
      },
    },
  }
</script>

<style scoped>

</style>