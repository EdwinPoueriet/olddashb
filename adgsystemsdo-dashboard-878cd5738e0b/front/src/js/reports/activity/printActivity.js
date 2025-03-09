import Vue from 'vue';
import printActivity from './printActivity.vue'
const config = require('../../../../../env.json')

if (config.mode !== 'debug') {
  Vue.config.devtools = false
  Vue.config.debug = false
  Vue.config.silent = true
}


new Vue({
  el: '#vueprint',
  components: { printActivity }
});
