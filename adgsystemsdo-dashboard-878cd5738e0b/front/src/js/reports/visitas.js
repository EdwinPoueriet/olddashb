import Vue from 'vue';
import visitasReport from './VisitasEfectivas.vue'
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
    components: { visitasReport }
});