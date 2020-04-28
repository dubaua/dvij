import Vue from 'vue';
import Router from 'vue-router';
import Delivery from '@/components/Delivery.vue';
import Product from '@/components/product/index.vue';

Vue.use(Router);

export default new Router({
  mode: 'history',
  base: process.env.BASE_URL,
  routes: [
    {
      path: '/delivery',
      name: 'Delivery',
      component: Delivery,
    },
    {
      path: '/:productId',
      name: 'product',
      component: Product,
    },
    {
      path: '/',
      name: 'home',
      component: Product,
    },
  ],
});
