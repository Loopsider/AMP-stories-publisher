<template>
    <div>
        <md-field class="mt-3">
            <label>Textbox</label>
            <md-textarea v-model="text" rows="5" ></md-textarea>
        </md-field>
    </div>
</template>

<script>
  export default {
    name: "bookend-textbox",
    props: {
      data: Object
    },
    data() {
      return {
        text: ''
      }
    },
    mounted () {
      if(!_.isNil(this.data) && !_.isNil(this.data.components)) {
        const fData = this.data.components.filter(comp => comp.type === 'textbox');
        if(fData.length > 0) {
          fData[0].text.forEach(item => {
            this.text = this.text + '\n' + item;
          });
        }
      }
    },
    watch: {
      text (value) {
        Vue.$emit('story-bookend:settings', {
          type: 'component',
          data: {
            'type': 'textbox',
            'text': value.split(/\r*\n/)
          }
        });
      },
    }
  }
</script>

<style scoped>

</style>