<template>
    <div class="story-left-sidebar md-elevation-3">
        <template v-if="story.data.pages.length > 0 && story.selected.page >= 0">
            <div class="header">
                <span class="title">
                  Page {{ story.selected.page + 1 }} of {{ story.data.pages.length }}
                </span>
                <div class="btn-more">
                    <span @click="showPageTools = !showPageTools"><md-icon>more_vert</md-icon></span>
                </div>
                <div class="page-tools md-elevation-3" v-if="showPageTools">
                    <ul>
                        <li @click="movePageToLeft">Move to Left</li>
                        <li @click="movePageToRight">Move to Right</li>
                    </ul>
                    <hr class="m-0">
                    <ul>
                        <li @click="duplicatePage">Duplicate</li>
                        <li @click="showRemovePageDialog=true">Remove</li>
                    </ul>
                </div>
            </div>
            <md-divider></md-divider>
            <div class="template-list">
                <template v-if="story.data.pages[story.selected.page]">
                    <page-layer
                        v-for="(layer, index) of story.data.pages[story.selected.page].layers"
                        :layer="layer"
                        :layerIndex="index"
                        :pageIndex="story.selected.page"
                        :key="index"
                    >
                    </page-layer>
                </template>
            </div>
            <md-button class="btn-add-layer md-fab md-primary" @click="showDialog = true">
                <md-icon>add</md-icon>
            </md-button>
        </template>
        <md-dialog :md-active.sync="showDialog">
            <md-dialog-title>Add a new layer</md-dialog-title>
            <md-dialog-content>
                <div class="story-layer-templates_list">
                    <div class="template"
                         v-for="(template, index) in layerTemplates"
                         @click="addLayer(index)"
                         :key="index">
                        <div class="template-image"
                             :style="{backgroundImage: 'url(' + template.icon + ')'}">
                        </div>
                        <p>{{ template.name }}</p>
                    </div>
                </div>
            </md-dialog-content>
            <md-dialog-actions>
                <md-button class="md-primary" @click="showDialog = false">Close</md-button>
            </md-dialog-actions>
        </md-dialog>
        <md-dialog-confirm
            :md-active.sync="showRemovePageDialog"
            md-title="Do you really want to remove this page?"
            md-confirm-text="Ok"
            md-cancel-text="Cancel"
            @md-cancel="showRemovePageDialog = false"
            @md-confirm="removePage"/>
        <md-dialog-confirm
            :md-active.sync="showRemoveLayerDialog"
            md-title="Do you really want to remove this layer?"
            md-confirm-text="Ok"
            md-cancel-text="Cancel"
            @md-cancel="showRemoveLayerDialog = false"
            @md-confirm="removeLayer"/>
        <md-dialog-confirm
            :md-active.sync="showRemoveElementDialog"
            md-title="Do you really want to remove this element?"
            md-confirm-text="Ok"
            md-cancel-text="Cancel"
            @md-cancel="showRemoveElementDialog = false"
            @md-confirm="removeElement"/>
    </div>
</template>

<script>
  import constants from '../../constants';
  import PageLayer from "./PageLayer";

  export default {
    name: "story-left-side-bar",
    components: { PageLayer },
    data () {
      return {
        showDialog: false,
        layerTemplates: constants.layerTemplates,
        showRemovePageDialog: false,
        showRemoveLayerDialog: false,
        showRemoveElementDialog: false,
        removeLayerIndex: -1,
        removeElementIndex: -1,
        showPageTools: false,
      }
    },
    methods: {
      /**
       * duplicate selected page
       */
      duplicatePage () {
        const pages = Object.assign([], this.story.data.pages);
        const id = Date.now();
        pages.push({ ...pages[this.story.selected.page], id: id });
        this.$store.dispatch('saveAMPStory', {
          data: {
            pages: pages,
          },
          publish: false,
          selected: {
            page: this.story.data.pages.length
          }
        });
        Vue.$emit("duplicate:page", id);
        this.showPageTools = false;
      },
      /**
       * remove selected page from story
       */
      removePage () {
        const pages = Object.assign([], this.story.data.pages);
        pages.splice(this.story.selected.page, 1);
        this.$store.dispatch('saveAMPStory', {
          data: {
            pages: pages
          },
          publish: false,
          selected: {}
        });
        this.showPageTools = false;
      },
      /**
       * add new layer to the selected page
       */
      addLayer (index) {
        const pages = Object.assign([], this.story.data.pages);
        pages[this.story.selected.page].layers.push(this.layerTemplates[index].data);
        this.$store.dispatch('saveAMPStory', {
          data: {
            pages: pages
          },
          publish: false,
          selected: {
            page: this.story.selected.page,
            layer: (pages[this.story.selected.page].layers.length - 1),
          }
        });
        this.showDialog = false;
      },
      /**
       * remove selected layer from selected page
       */
      removeLayer () {
        const pages = Object.assign([], this.story.data.pages);
        pages[this.story.selected.page].layers.splice(this.removeLayerIndex, 1);
        this.$store.dispatch('saveAMPStory', {
          data: {
            pages: pages
          },
          publish: false,
          selected: {
            page: this.story.selected.page,
          }
        });
      },
      /**
       * remove selected element from selected layer
       */
      removeElement () {
        const pages = Object.assign([], this.story.data.pages);
        pages[this.story.selected.page].layers[this.removeLayerIndex].elements.splice(this.removeElementIndex, 1);

        this.$store.dispatch('saveAMPStory', {
          data: {
            pages: pages
          },
          publish: false,
          selected: {
            page: this.story.selected.page,
          }
        });
      },
      /**
       * move page to left
       */
      movePageToLeft () {
        if (this.story.selected.page > 0) {
          const pages = Object.assign([], this.story.data.pages);
          const temp = pages[this.story.selected.page - 1];
          pages[this.story.selected.page - 1] = pages[this.story.selected.page];
          pages[this.story.selected.page] = temp;
          this.$store.dispatch('saveAMPStory', {
            data: {
              pages: pages
            },
            publish: false,
            selected: {
              page: (this.story.selected.page - 1),
            }
          });
          this.showPageTools = false;
        }
      },
      /**
       * move page to left
       */
      movePageToRight () {
        if ((this.story.selected.page + 1) < this.story.data.pages.length) {
          const pages = Object.assign([], this.story.data.pages);
          const temp = pages[this.story.selected.page + 1];
          pages[this.story.selected.page + 1] = pages[this.story.selected.page];
          pages[this.story.selected.page] = temp;
          this.$store.dispatch('saveAMPStory', {
            data: {
              pages: pages
            },
            publish: false,
            selected: {
              page: (this.story.selected.page + 1)
            }
          });
          this.showPageTools = false;
        }
      },
      /**
       * Order Up Layer
       */
      orderUpLayer (layerIndex) {
        if (layerIndex > 0) {
          const pages = Object.assign([], this.story.data.pages);
          const temp = pages[this.story.selected.page].layers[layerIndex - 1];
          pages[this.story.selected.page].layers[layerIndex - 1] = pages[this.story.selected.page].layers[layerIndex];
          pages[this.story.selected.page].layers[layerIndex] = temp;

          this.$store.dispatch('saveAMPStory', {
            data: {
              pages: pages
            },
            publish: false,
            selected: {
              page: this.story.selected.page,
              layer: (layerIndex - 1),
            }
          });
        }
      },
      /**
       * Order Down Layer
       */
      orderDownLayer (layerIndex) {
        if ((layerIndex + 1) < this.story.data.pages[this.story.selected.page].layers.length) {
          const pages = Object.assign([], this.story.data.pages);
          const temp = pages[this.story.selected.page].layers[layerIndex + 1];
          pages[this.story.selected.page].layers[layerIndex + 1] = pages[this.story.selected.page].layers[layerIndex];
          pages[this.story.selected.page].layers[layerIndex] = temp;

          this.$store.dispatch('saveAMPStory', {
            data: {
              pages: pages
            },
            publish: false,
            selected: {
              page: this.story.selected.page,
              layer: (layerIndex + 1),
            }
          });
        }
      },
      /**
       * Order Up Element
       */
      orderUpElement (layerIndex, elementIndex) {
        if (elementIndex > 0) {
          const pages = Object.assign([], this.story.data.pages);
          const temp = pages[this.story.selected.page].layers[layerIndex].elements[elementIndex - 1];
          pages[this.story.selected.page].layers[layerIndex].elements[elementIndex - 1] =
            pages[this.story.selected.page].layers[layerIndex].elements[elementIndex];
          pages[this.story.selected.page].layers[layerIndex].elements[elementIndex] = temp;

          this.$store.dispatch('saveAMPStory', {
            data: {
              pages: pages
            },
            publish: false,
            selected: {
              page: this.story.selected.page,
              layer: layerIndex,
              element: elementIndex - 1,
            }
          });
        }
      },
      /**
       * Order Down Element
       */
      orderDownElement (layerIndex, elementIndex) {
        if ((elementIndex + 1) < this.story.data.pages[this.story.selected.page].layers[layerIndex].elements.length) {
          const pages = Object.assign([], this.story.data.pages);
          const temp = pages[this.story.selected.page].layers[layerIndex].elements[elementIndex + 1];
          pages[this.story.selected.page].layers[layerIndex].elements[elementIndex + 1] =
            pages[this.story.selected.page].layers[layerIndex].elements[elementIndex];
          pages[this.story.selected.page].layers[layerIndex].elements[elementIndex] = temp;

          this.$store.dispatch('saveAMPStory', {
            data: {
              pages: pages
            },
            publish: false,
            selected: {
              page: this.story.selected.page,
              layer: layerIndex,
              element: elementIndex + 1,
            }
          });
        }
      },

      /**
       * Order Up Element
       */
      orderUpGridElement (layerIndex, gridIndex, elementIndex) {
        const pages = Object.assign([], this.story.data.pages);
        const filteredIndexes = [];
        let filteredIndex = -1;
        const filteredElements = pages[this.story.selected.page].layers[layerIndex].elements.filter((element, index) => {
          const isGridArea = element['grid-area'] === constants.gridAreas[gridIndex].slug;
          if (isGridArea) {
            filteredIndexes.push(index);
            if (elementIndex === index) {
              filteredIndex = filteredIndexes.length - 1;
            }
          }
          return isGridArea;
        });
        console.log(filteredElements, filteredIndex);
        if (filteredIndex > 0) {
          const temp = pages[this.story.selected.page].layers[layerIndex].elements[filteredIndexes[filteredIndex - 1]];
          pages[this.story.selected.page].layers[layerIndex].elements[filteredIndexes[filteredIndex - 1]] =
            pages[this.story.selected.page].layers[layerIndex].elements[elementIndex];
          pages[this.story.selected.page].layers[layerIndex].elements[elementIndex] = temp;

          this.$store.dispatch('saveAMPStory', {
            data: {
              pages: pages
            },
            publish: false,
            selected: {
              page: this.story.selected.page,
              layer: layerIndex,
              gridArea: gridIndex,
              element: filteredIndexes[filteredIndex - 1],
            }
          });
        }
      },
      /**
       * Order Down GridElement
       */
      orderDownGridElement (layerIndex, gridIndex, elementIndex) {
        const pages = Object.assign([], this.story.data.pages);
        const filteredIndexes = [];
        let filteredIndex = -1;
        const filteredElements = pages[this.story.selected.page].layers[layerIndex].elements.filter((element, index) => {
          const isGridArea = element['grid-area'] === constants.gridAreas[gridIndex].slug;
          if (isGridArea) {
            filteredIndexes.push(index);
            if (elementIndex === index) {
              filteredIndex = filteredIndexes.length - 1;
            }
          }
          return isGridArea;
        });

        console.log(filteredElements, filteredIndex);
        if ((filteredIndex + 1) < filteredElements.length) {
          const temp = pages[this.story.selected.page].layers[layerIndex].elements[filteredIndexes[filteredIndex + 1]];
          pages[this.story.selected.page].layers[layerIndex].elements[filteredIndexes[filteredIndex + 1]] =
            pages[this.story.selected.page].layers[layerIndex].elements[elementIndex];
          pages[this.story.selected.page].layers[layerIndex].elements[elementIndex] = temp;

          this.$store.dispatch('saveAMPStory', {
            data: {
              pages: pages
            },
            publish: false,
            selected: {
              page: this.story.selected.page,
              layer: layerIndex,
              gridArea: gridIndex,
              element: filteredIndexes[filteredIndex + 1],
            }
          });
        }
      }
    },
    mounted () {
      // remove layer emit receiver from PageLayer Component
      Vue.$on("remove:layer", (index) => {
        this.removeLayerIndex = index;
        this.showRemoveLayerDialog = true;
      });
      // remove element emit receiver from PageLayer Component
      Vue.$on("remove:element", (data) => {
        this.removeLayerIndex = data.layerIndex;
        this.removeElementIndex = data.elementIndex;
        this.showRemoveElementDialog = true;
      });
      // order up layer emit from PageLayer Component
      Vue.$on("order-up:layer", (data) => {
        this.orderUpLayer(data.layerIndex);
      });
      // order down layer emit from PageLayer Component
      Vue.$on("order-down:layer", (data) => {
        this.orderDownLayer(data.layerIndex);
      });
      // order up element emit from PageLayer Component
      Vue.$on("order-up:element", (data) => {
        this.orderUpElement(data.layerIndex, data.elementIndex);
      });
      // order down element emit from PageLayer Component
      Vue.$on("order-down:element", (data) => {
        this.orderDownElement(data.layerIndex, data.elementIndex);
      });
      // order up grid element emit from PageLayer Component
      Vue.$on("order-up:grid-element", (data) => {
        this.orderUpGridElement(data.layerIndex, data.gridIndex, data.elementIndex);
      });
      // order down grid element emit from PageLayer Component
      Vue.$on("order-down:grid-element", (data) => {
        this.orderDownGridElement(data.layerIndex, data.gridIndex, data.elementIndex);
      });
    },
    computed: {
      story () {
        return this.$store.state.story;
      },
    },
    beforeDestroy () {
      Vue.$off("remove:layer");
      Vue.$off("remove:element");
      Vue.$off("order-up:layer");
      Vue.$off("order-down:layer");
      Vue.$off("order-up:element");
      Vue.$off("order-down:element");
      Vue.$off("order-up:grid-element");
      Vue.$off("order-down:grid-element");
    }
  }
</script>

<style scoped>
</style>