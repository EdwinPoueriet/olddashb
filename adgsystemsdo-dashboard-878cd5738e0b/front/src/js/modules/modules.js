import Vue from 'vue';
import Modules from './ModulesComponent.vue'
const config = require('../../../../env.json')

if (config.mode === 'debug') {
    Vue.config.devtools = false
    Vue.config.debug = false
    Vue.config.silent = true
}
Vue.component('modules', Modules)

new Vue({
    el: '#moduleswrapper',
    components: {Modules}
});