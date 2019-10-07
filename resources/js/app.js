// The Vue build version to load with the `import` command
// (runtime-only or standalone) has been set in webpack.base.conf with an alias.
import './bootstrap';
import Vue from 'vue'
import store from './store'
import VueAxios from 'vue-axios'
import axios from './axios'
import BootstrapVue from 'bootstrap-vue'
import VueAnalytics from 'vue-analytics'
import VueMaterial from 'vue-material'
import 'vuetify/dist/vuetify.min.css' 
import 'vue-material/dist/vue-material.min.css'
import AlertBoxMixin from './components/common/mixins/AlertBox';
import CKEditor from '@ckeditor/ckeditor5-vue';
import TreeView from "vue-json-tree-view"
import Vuetify from 'vuetify'

import Index from './Index'
import router from './router'

Vue.use(Vuetify)
Vue.use(BootstrapVue);
Vue.use(VueAxios, axios);
Vue.use(VueMaterial);
Vue.use(CKEditor);
Vue.use(TreeView);
Vue.use(VueAnalytics, {
  id: 'UA-131939849-1',
  router,
  debug: {
    sendHitTask: false
  }
});
/* eslint-disable no-new */
new Vue({
  el: '#app',
  created: function () {
    window.Vue = this;
    window.VueGlobal = Vue;
  },
  router,
  store,
  axios,
  template: '<Index/>',
  components: {
    Index
  },
  mixins: [AlertBoxMixin]
})