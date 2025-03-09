import Vue from 'vue';
import devolucionesReport from './DevolucionesReport.vue'
import { ClientTable } from 'vue-tables-2';
import Multi from 'vue-multiselect'

const config = require('../../../../env.json')

if (config.mode !== 'debug') {
  Vue.config.devtools = false
  Vue.config.debug = false
  Vue.config.silent = true
}

Vue.use(ClientTable);
Vue.use(Multi);
Vue.component('multiselect', Multi);

new Vue({
  el: '#reportswrapper',
  components: { devolucionesReport }
});