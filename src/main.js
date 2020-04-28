import 'normalize.css';
import './webfonts/stylesheet.css';
import '@/styles/main.scss';
import Vue from 'vue';
import Vue2TouchEvents from 'vue2-touch-events';
import VueFormGenerator from 'vue-form-generator';
import '@/components/globals';
import App from './App';
import router from '@/router.js';
import store from '@/store/index.js';
// import '@/transform.js';

const isDev = process.env.NODE_ENV === 'development';

Vue.config.productionTip = isDev;

Vue.use(VueFormGenerator);
Vue.use(Vue2TouchEvents);

new Vue({
  el: '#app',
  store,
  router,
  render: (h) => h(App),
});
