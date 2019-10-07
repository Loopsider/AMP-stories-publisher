<template>
    <div>
        <template v-if="providers" v-for="(provider, index) of providers">
            <template v-if="provider.slug === 'facebook' && provider.value">
                <md-field>
                    <label>Facebook App Id</label>
                    <md-input v-model="facebook.app_id" placeholder=""></md-input>
                </md-field>
            </template>
        </template>
    </div>
</template>

<script>
  export default {
    name: "share-providers",
    props: {
      providers: Array,
      data: Object
    },
    data () {
      return {
        facebook: {
          app_id: ''
        }
      }
    },
    mounted () {
      if (!_.isNil(this.data) && !_.isNil(this.data.shareProviders)) {
        const fData = this.data.shareProviders.filter(comp => {
          return (typeof comp === 'object') && comp.provider === 'facebook';
        });
        if (fData.length > 0) {
          this.facebook.app_id = fData[0].app_id;
        }
      }
    },
    watch: {
      facebook: {
        handler: function (data) {
          Vue.$emit('story-bookend:settings', {
            type: 'provider',
            data: {
              'provider': 'facebook',
              'app_id': data.app_id
            }
          });
        },
        deep: true
      },
    }
  }
</script>

<style scoped>

</style>