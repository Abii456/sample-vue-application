import Vue from 'vue';
import App from './App.vue';
import router from './router';
import VueFormulate from '@braid/vue-formulate';
import '@fortawesome/fontawesome-free/css/all.css';
import './shim.js';

Vue.config.productionTip = false;

Vue.use(VueFormulate);

new Vue({
  router,
  render: h => h(App),
}).$mount('#app');
