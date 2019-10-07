<template>
    <div>
        <md-field>
            <label>Button Text</label>
            <md-input v-model="cta.text" placeholder=""></md-input>
        </md-field>
        <md-field>
            <label>Button Url</label>
            <md-input v-model="cta.url" placeholder=""></md-input>
        </md-field>
    </div>
</template>

<script>
  export default {
    name: "bookend-cta-link",
    props: {
      data: Object
    },
    data() {
      return {
        cta: {
          text: '',
          url: ''
        },
      }
    },
    mounted () {
      if(!_.isNil(this.data) && !_.isNil(this.data.components)) {
        const fData = this.data.components.filter(comp => comp.type === 'cta-link');
        if(fData.length > 0) {
          this.cta.text = fData[0].links[0].text;
          this.cta.url = fData[0].links[0].url;
        }
      }
    },
    watch: {
      cta: {
        handler: function (data) {
          Vue.$emit('story-bookend:settings', {
            type: 'component',
            data: {
              'type': 'cta-link',
              'links': [
                {
                  'text': data.text,
                  'url': data.url
                }
              ]
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