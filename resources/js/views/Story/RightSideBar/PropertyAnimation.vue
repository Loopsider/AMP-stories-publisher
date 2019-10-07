<template>
    <div class="property-animation">
        <md-field>
            <label>Animation In</label>
            <md-select v-model="animationIn">
                <md-option v-for="(opt, index) in animations" :value="opt.slug" :key="index">
                    {{ opt.name }}
                </md-option>
            </md-select>
        </md-field>
        <md-field>
            <label>Animation In Duration(s)</label>
            <md-input v-model="animationInDuration"></md-input>
        </md-field>
        <md-field>
            <label>Animation In Delay(s)</label>
            <md-input v-model="animationInDelay"></md-input>
        </md-field>
        <md-field>
            <label>Animation In After</label>
            <md-input v-model="animationInAfter"></md-input>
        </md-field>
    </div>
</template>

<script>
  import constants from '../../constants';

  export default {
    name: "property-animation",
    props: {
      element: Object,
    },
    data () {
      return {
        animations: constants.animations,
      }
    },
    computed: {
      animationIn: {
        get () {
          if (!_.isNil(this.element && this.element['animate-in'])) {
            return this.element['animate-in'];
          }
          return '';
        },
        set: _.debounce(function (value) {
          if (this.animationIn !== value)
            Vue.$emit('setting:properties', { 'animate-in': value });
        }, 2000),
      },
      animationInDuration: {
        get () {
          if (!_.isNil(this.element && this.element['animate-in-duration'])) {
            return this.element['animate-in-duration'];
          }
          return '';
        },
        set: _.debounce(function (value) {
          if (this.animationInDuration !== value)
            Vue.$emit('setting:properties', { 'animate-in-duration': value });
        }, 2000),
      },
      animationInDelay: {
        get () {
          if (!_.isNil(this.element && this.element['animate-in-delay'])) {
            return this.element['animate-in-delay'];
          }
          return '';
        },
        set: _.debounce(function (value) {
          if (this.animationInDelay !== value)
            Vue.$emit('setting:properties', { 'animate-in-delay': value });
        }, 2000),
      },
      animationInAfter: {
        get () {
          if (!_.isNil(this.element && this.element['animate-in-after'])) {
            return this.element['animate-in-after'];
          }
          return '';
        },
        set: _.debounce(function (value) {
          if (this.animationInAfter !== value)
            Vue.$emit('setting:properties', { 'animate-in-after': value });
        }, 2000),
      },
    },
  }
</script>

<style scoped>

</style>