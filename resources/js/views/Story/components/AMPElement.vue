<template>
    <div class="amp-element" @click="addElement">
        <md-icon>layers</md-icon>
        {{ elementName }}
    </div>
</template>

<script>
  import utils from "../../utils";

  export default {
    name: "amp-element",
    props: {
      slug: String
    },
    data () {
      return {
        elementName: ''
      }
    },
    methods: {
      getElementName () {
        const el = utils.getElement(this.slug);
        this.elementName = el ? el.name : '';
      },
      addElement () {
        if (this.slug) {
          Vue.$emit("add:element", this.slug);
        }
      },
    },
    mounted () {
      if (this.slug) {
        this.getElementName();
      }
    }
  }
</script>

<style scoped lang="scss">
    .md-icon {
        font-size: 15px !important;
    }

    .amp-element {
        cursor: pointer;
        &:hover {
            background-color: #ccdcec;
        }
    }
</style>