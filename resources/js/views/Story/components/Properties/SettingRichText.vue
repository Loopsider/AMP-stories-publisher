<template>
  <div>
    <md-field>
      <label>Id</label>
      <md-input v-model="elementId"></md-input>
    </md-field>
    <md-field>
      <label>Class</label>
      <md-input v-model="elementClass"></md-input>
    </md-field>
    <br>
    <p style="color:#6d6d6d">Rich text</p>

    <md-field>
        <label>Text position</label>
        <md-select v-model="textPosition">
            <md-option v-for="(opt, index) in positions" :value="opt.slug" :key="index">
                {{ opt.name }}
            </md-option>
        </md-select>
    </md-field>
    <ckeditor 
      style="background:red!important"
      v-if="editorConfig.customStyle.options.length > 0"
      :editor="editor"
      v-model="elementHtml"
      :config="editorConfig"
    ></ckeditor>
  </div>
</template>

<script>
import RemoveFormat from '@ckeditor/ckeditor5-remove-format/src/removeformat';
import ClassicEditor from "@ckeditor/ckeditor5-editor-classic/src/classiceditor";
import Essentials from "@ckeditor/ckeditor5-essentials/src/essentials";
import Bold from "@ckeditor/ckeditor5-basic-styles/src/bold";
import Italic from "@ckeditor/ckeditor5-basic-styles/src/italic";
import Heading from "@ckeditor/ckeditor5-heading/src/heading";
import Paragraph from "@ckeditor/ckeditor5-paragraph/src/paragraph";
import Font from "@ckeditor/ckeditor5-font/src/font";
import Alignment from "@ckeditor/ckeditor5-alignment/src/alignment";
import CustomStyle from "../../../../components/common/CK5CustomStyle/customstyle";
import { customStyles } from "../../../../components/common/CK5CustomStyle/utils";
import { parse } from 'node-html-parser';

export default {
  name: "setting-rich-text",
  props: {
    el: Object
  },
  data() {
    return {
      positions:[
        { slug: 'top', name: 'Top' },
        { slug: 'middle', name: 'Middle' },
        { slug: 'bottom', name: 'Bottom' },
      ],
      editor: ClassicEditor,
      editorConfig: {
        fontSize: {
          options: [9, 11, 13, "default", 17, 19, 21,23,25,27]
        },
        customStyle: {
          options: []
        },
        plugins: [
          Essentials,
          Bold,
          Alignment,
          Italic,
          Heading,
          Paragraph,
          Font,
          CustomStyle,
          RemoveFormat,
        ],
        toolbar: {
          items: [
            "heading",
            "bold",
            "italic",
            "alignment",
            "|",
            "fontColor",
            "fontSize",
            "|",
            "customStyle",
            "removeFormat"
          ]
        }
      }
    };
  },
  methods: {
    loadStyle() {
      this.$http
        .get("/story/style")
        .then(
          response => {
            if (response.data.length > 0) {
              const fRichTextStyles = response.data.filter(style => {
                return style.slug === "richtext";
              });
              if (fRichTextStyles.length > 0) {
                const styles = JSON.parse(fRichTextStyles[0].data);
                this.editorConfig.customStyle.options = styles.map(item => {
                  item.style = JSON.parse(item.style);
                  return item;
                });
              } else {
                this.editorConfig.customStyle.options = customStyles;
              }
            }
          },
          error => {}
        )
        .catch(Vue.handleClientError);
    }
  },
  mounted() {
    this.loadStyle();
  },
  computed: {
    elementId: {
      get() {
        if (!_.isNil(this.el && this.el.id)) {
          return this.el.id;
        }
        return "";
      },
      set: _.debounce(function(value) {
        if (this.elementId !== value)
          Vue.$emit("setting:properties", { id: value });
      }, 2000)
    },
    elementClass: {
      get() {
        if (!_.isNil(this.el && this.el.class)) {
          return this.el.class;
        }
        return "";
      },
      set: _.debounce(function(value) {
        if (this.elementClass !== value)
          Vue.$emit("setting:properties", { class: value });
      }, 2000)
    },
    textPosition:{
      get() {
        if (
          !_.isNil(this.el && this.el.properties && this.el.properties.verticalPositioning)
        ) {
          return this.el.properties.verticalPositioning;
        }
        return "top";
      },
      set: _.debounce(function(value) {
        if (this.textPosition !== value)
          Vue.$emit("setting:properties", { properties: { verticalPositioning : value} });
      }, 2000)
    },

    elementHtml: {
      get() {
        if (
          !_.isNil(this.el && this.el.properties && this.el.properties.html)
        ) {
          return this.el.properties.html;
        }
        return "";
      },
      set: _.debounce(function(value) {
        if (this.elementHtml !== value)
          Vue.$emit("setting:properties", { properties: { html: value } });
      }, 2000)
    },
    elementPosition: {
      get() {
        if (
          !_.isNil(this.el && this.el.properties && this.el.properties.position)
        ) {
          return this.el.properties.position;
        }
        return "";
      },
      set: _.debounce(function(value) {
        if (this.elementPosition !== value)
          Vue.$emit("setting:properties", { properties: { position: value } });
      }, 2000)
    }
  },
  // watch:{
  //   textPosition(val){
  //     if(val==="top"){
  //       style = {"position":"absolute","top":"0%"}
  //     }else if(val==="middle"){
  //       style = {"position":"absolute","top":"50%","transform":"translateY(-50%)"}
  //     }else if(val==="bottom"){
  //       style = {"position":"absolute","top":"100%","transform":"translateY(-100%)"}
  //     }
  //   }
  // }

};
</script>
