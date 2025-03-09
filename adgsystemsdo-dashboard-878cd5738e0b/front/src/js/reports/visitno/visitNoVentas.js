import Vue from 'vue';
import visitno from './visitNoVentas.vue'
const config = require('../../../../../env.json')

if (config.mode !== 'debug') {
  Vue.config.devtools = false
  Vue.config.debug = false
  Vue.config.silent = true
}


new Vue({
  el: '#reportswrapper',
  components: { visitno }
});