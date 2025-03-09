import Vue from 'vue';
import UserDevices from './UserDevices.vue'
import DeviceInfoTable  from './components/DeviceInfoTable.vue'
import DeviceSerialsTable  from './components/DeviceSerialsTable.vue'
import TrEdit  from './components/tredit.vue';
import DeviceUpdates  from './components/DeviceUpdates.vue'
Vue.component('device-info-table', DeviceInfoTable)
Vue.component('device-updates', DeviceUpdates)
Vue.component('device-serials-table', DeviceSerialsTable)
Vue.component('tr-edit', TrEdit)

new Vue({
  el: '#deviceswrapper',
  components: { UserDevices }
});
