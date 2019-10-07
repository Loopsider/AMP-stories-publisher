<template>
    <div class="story-bookend">
        <ul class="story-bookend_share-providers">
            <li v-for="(share, compIndex) of shareProviders" :index="compIndex" :key="compIndex">
                <img :src="getShareProviderIcon(share.slug)"/>
                <md-checkbox v-model="share.value" class="md-primary"></md-checkbox>
            </li>
        </ul>
        <share-providers :providers="shareProviders" :data="$store.state.story.data.bookend">
        </share-providers>
        <div class="clearfix"></div>
        <div class="story-bookend_components mt-3" :class="{flex: components.length === 0}">
            <div v-if="components.length > 0 && renderComponents" class="components mb-3">

                <div v-for="(comp, compIndex) of components" :index="compIndex" :key="compIndex"
                     class="story-bookend_component">
                    <div class="component-icon mt-3">
                        <img :src="comp.icon"/>
                    </div>
                    <div class="component-content">
                        <dynamic-bookend-component :data="$store.state.story.data.bookend"
                                                   :type="getUcString(comp.slug)"/>
                    </div>
                    <div class="component-tools mt-3">
                        <span v-if="isOrderUp(compIndex)" @click="orderUpComponent(compIndex)">
                            <md-icon style="transform: rotate(-90deg);">trending_flat</md-icon>
                        </span>
                        <span v-if="isOrderDown(compIndex)" @click="orderDownComponent(compIndex)">
                            <md-icon style="transform: rotate(90deg);">trending_flat</md-icon>
                        </span>
                        <span @click="openRemoveDialog(compIndex)">
                            <md-icon>delete_outline</md-icon>
                        </span>
                    </div>
                </div>
                
            </div>



            <md-button class="btn-add-component md-fab md-primary" @click="showBookendComponents()">
                <md-icon>add</md-icon>
            </md-button>
            <ul v-if="bookendComponentsDisplayed" class="available-components">
              <li v-for="(comp, compIndex) of availableComponents" :index="compIndex" :key="compIndex" @click="addBookendComponent(comp)">
                  <div class="component-icon">
                      <img :src="comp.icon"/>
                  </div>
                  <div class="component-name">{{comp.name}}</div>
              </li>
            </ul>


            <!-- <md-button class="btn-add-component md-fab md-primary" @click="addComponent">
                <md-icon>add</md-icon>
            </md-button>
            <ul v-if="components.length === 0" class="available-components">
                <li v-for="(comp, compIndex) of availableComponents" :index="compIndex" :key="compIndex">
                    <div class="component-icon">
                        <img :src="comp.icon"/>
                    </div>
                    <div class="component-name">{{comp.name}}</div>
                </li>
            </ul> -->



        </div>
        <md-dialog-confirm
            :md-active.sync="showRemoveDialog"
            md-title="Do you really want to remove this component?"
            md-confirm-text="Ok"
            md-cancel-text="Cancel"
            @md-cancel="showRemoveDialog = false"
            @md-confirm="removeComponent"/>
    </div>
</template>

<script>
  import constants from '../../../constants';
  import utils from '../../../utils';
  import DynamicBookendComponent from "../Bookend/DynamicBookendComponent";
  import ShareProviders from "../Bookend/shareProviders/index";

  export default {
    name: "story-bookend",
    components: { ShareProviders, DynamicBookendComponent },
    data () {
      return {
        shareProviders: constants.bookend.shareProviders,
        components: [],
        availableComponents: constants.bookend.components,
        renderComponents: true, // force reload after changing components content
        removeIndex: null,
        showRemoveDialog: false,
        temp: null,
        bookendComponentsDisplayed:false
      }
    },
    methods: {
      /**
       * initial loading
       */


      init (data) {
        if (!_.isNil(data.components)) {
          this.renderComponents = false;
          data.components.forEach(comp => {
            this.components.push(utils.getBookendComponent(comp.type));
          });
          this.$nextTick(() => {
            this.renderComponents = true;
          });
        }
        if (!_.isNil(data.shareProviders)) {
          data.shareProviders.forEach(comp => {
            const slug = (typeof comp === 'object') ? comp.provider : comp;
            const index = this.shareProviders.findIndex(provider => {
              return provider.slug === slug
            });
            if (index >= 0) {
              this.shareProviders[index].value = true;
            }
          });
        }
        this.temp = Object.assign({}, this.$store.state.story.data.bookend);
      },



      /**
       * serialize Components and Providers
       */


      serialize () {
        const results = {
          bookendVersion: this.temp.bookendVersion,
          components: [],
          shareProviders: [],
        };

        if (!_.isNil(this.components)) {
          this.components.forEach(comp => {
            const fData = this.temp.components.filter(item => item.type === comp.slug);
            if(fData.length > 0) {
              results.components.push(fData[0]);
            }
          });
        }

        if (!_.isNil(this.shareProviders)) {
          this.shareProviders.forEach(provider => {
            if(provider.value && !provider.required) {
              results.shareProviders.push(provider.slug);
            }
            if(provider.value && provider.required) {
              const fData = this.temp.shareProviders.filter(item => item.provider === provider.slug);
              if(fData.length > 0) {
                results.shareProviders.push(fData[0]);
              }
            }
          });
        }
        return results;
      },


      /**
       * set current bookend info temporarily before save
       */


      setBookend (payload) {
        if (payload.type === 'component') {
          const compIndex = this.temp.components.findIndex(item => {
            return item.type === payload.data.type
          });
          if (compIndex >= 0) {
            this.temp.components[compIndex] = payload.data;
          } else {
            this.temp.components.push(payload.data);
          }
        }
        if (payload.type === 'provider') {
          const providerIndex = this.temp.shareProviders.findIndex(item => {
            if ((typeof payload.data) === 'object' && (typeof item) === 'object') {
              return item.provider === payload.data.provider;
            }
            return false;
          });

          if (providerIndex >= 0) {
            this.temp.shareProviders[providerIndex] = payload.data;
          } else {
            this.temp.shareProviders.push(payload.data);
          }
        }

        Vue.$emit('story:settings', {
          bookend: this.serialize()
        });
      },


      /**
       * add a component to bookend
       */

      showBookendComponents(){
        this.bookendComponentsDisplayed=true;
      },

      
      addBookendComponent(comp){
        if(this.components.indexOf(comp)==-1){
          this.components.push(comp);
          this.bookendComponentsDisplayed=false;
        }
      },


      // addComponent () {
      //   if (this.components.length < this.availableComponents.length) {
      //     for (let comb of this.availableComponents) {
      //       console.log(comb);
      //       if (this.components.findIndex(item => item.slug === comb.slug) === -1) {
      //         this.components.push(comb);
      //         break;
      //       }
      //     }
      //   }
      // },


      /**
       *  open remove dialog
       */
      openRemoveDialog (index) {
        this.showRemoveDialog = true;
        this.removeIndex = index;
      },
      /**
       * remove a component from bookend
       */
      removeComponent () {
        this.components.splice(this.removeIndex, 1);
        this.showRemoveDialog = false;
        Vue.$emit('story:settings', {
          bookend: this.serialize()
        });
      },
      /**
       * order up selected component
       * @param index
       */
      orderUpComponent (index) {
        this.renderComponents = false;
        const temp = this.components[index - 1];
        this.components[index - 1] = this.components[index];
        this.components[index] = temp;
        this.$nextTick(() => {
          this.renderComponents = true;
        });
        Vue.$emit('story:settings', {
          bookend: this.serialize()
        });
      },
      /**
       * order down selected component
       * @param index
       */
      orderDownComponent (index) {
        this.renderComponents = false;
        const temp = this.components[index + 1];
        this.components[index + 1] = this.components[index];
        this.components[index] = temp;
        this.$nextTick(() => {
          this.renderComponents = true;
        });
        Vue.$emit('story:settings', {
          bookend: this.serialize()
        });
      },
      /**
       * get share provider icon from given slug
       * @param slug
       * @returns {string}
       */
      getShareProviderIcon (slug) {
        const share = utils.getBookendShareProvider(slug);
        return share ? share.icon : ''
      },
      /**
       * get UpperCase string from given string
       * @param str
       * @returns {string}
       */
      getUcString (str) {
        str = str.replace('-', '');
        return str.charAt(0).toUpperCase() + str.slice(1);
      },
      /**
       * Is OrderUp available
       * @param index
       * @returns {boolean}
       */
      isOrderUp (index) {
        return this.components.length > 1 && index > 0;
      },
      /**
       * Is OrderDown available
       * @param index
       * @returns {boolean}
       */
      isOrderDown (index) {
        return this.components.length > 1 && index < (this.components.length - 1);
      }
    },
    watch: {
      shareProviders: {
        handler: function (data) {
          Vue.$emit('story:settings', {
            bookend: this.serialize()
          });
        },
        deep: true
      },
    },
    mounted () {
      if (!_.isNil(this.$store.state.story) && !_.isNil(this.$store.state.story.data.bookend)) {
        this.init(this.$store.state.story.data.bookend);
      }
      Vue.$on('story-bookend:settings', data => {
        this.setBookend(data);
      })
    },
    beforeDestroy () {
      Vue.$off('story-bookend:settings');
    }
  }
</script>

<style scoped>

</style>