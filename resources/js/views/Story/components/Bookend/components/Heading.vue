<template>
    <div>
        <md-field>
            <label>Text</label>
            <md-input v-model="heading" placeholder="Read More"></md-input>
        </md-field>
    </div>
</template>

<script>
  export default {
    name: "bookend-heading",
    props: {
      data: Object
    },
    data () {
      return {
        heading: ''
      }
    },
    mounted () {
      if(!_.isNil(this.data) && !_.isNil(this.data.components)) {
        const fData = this.data.components.filter(comp => comp.type === 'heading');
        if(fData.length > 0) {
          this.heading = fData[0].text;
        }
      }
    },
    watch: {
      heading (value) {
        Vue.$emit('story-bookend:settings', {
          type: 'component',
          data: {
            'type': 'heading',
            'text': value
          }
        });
      },
    }
  }
</script>

<style scoped>

</style>