<template>
    <component :data="data" :is="component" v-if="component" />
</template>
<script>
  export default {
    name: 'dynamic-bookend-component',
    props: {
      type: String,
      data: Object
    },
    data() {
      return {
        component: null,
      }
    },
    computed: {
      loader() {
        if (!this.type) {
          return null
        }
        return () => import(
          /* webpackMode: "eager" */
          `./components/${this.type}`)
      },
    },
    mounted() {
      this.loader()
        .then(() => {
          this.component = () => this.loader()
        })
        .catch(() => {
          this.component = () => import(`../Default`)
        })
    },
  }
</script>