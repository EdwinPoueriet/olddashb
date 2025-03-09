import Vue from 'vue';
import ventasProductosReport from './VentasProductosReporte'
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
  components: { ventasProductosReport }
});