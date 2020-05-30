import Vue from 'vue';
import Router from 'vue-router';
import Delivery from '@/components/Delivery.vue';
import Product from '@/components/product/Product.vue';
import store from '@/store/index.js';

Vue.use(Router);

const router = new Router({
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

router.beforeEach((to, from, next) => {
  if (store.getters.isOverlayed) {
    store.dispatch('closeAll');
  }
  next();
});

export default router;
