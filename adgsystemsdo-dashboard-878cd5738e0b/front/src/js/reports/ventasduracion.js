import Vue from 'vue';
import ventasReport from './VentasDuracion.vue'
import Multi from 'vue-multiselect'

const config = require('../../../../env.json')

if (config.mode !== 'debug') {
  Vue.config.devtools = false
  Vue.config.debug = false
  Vue.config.silent = true
}

Vue.use(Multi);
Vue.component('multiselect',Multi);

new Vue({
  el: '#reportswrapper',
  components: { ventasReport }
});