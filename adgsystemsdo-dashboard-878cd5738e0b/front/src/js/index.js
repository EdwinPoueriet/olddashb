import Vue from 'vue';
import Report from './Report.vue'
import VerticalMenu from './components/VerticalMenu/VerticalMenu.vue'
import {ClientTable} from 'vue-tables-2';
import Multi from 'vue-multiselect'

const config = require('json!../../../env.json')

if (config.mode === 'debug') {
  Vue.config.devtools = false
  Vue.config.debug = false
  Vue.config.silent = true
}

Vue.use(ClientTable);
Vue.use(Multi);
Vue.component('multiselect',Multi);
Vue.component('vertical-menu',VerticalMenu );

new Vue({
  el: '#reportswrapper',
  components: { Report, VerticalMenu }
});