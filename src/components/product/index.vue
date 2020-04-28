<template>
  <div class="product">
    <div class="container" v-if="isLoading">
      <div class="row">
        <div class="col col-xs-12 col-sm-6 col-lg-4 col-xxl-3"><DummyCard /></div>
        <div class="col col-xs-12 col-sm-6 col-lg-4 col-xxl-3"><DummyCard /></div>
        <div class="col col-xs-12 col-sm-6 col-lg-4 col-xxl-3"><DummyCard /></div>
        <div class="col col-xs-12 col-sm-6 col-lg-4 col-xxl-3"><DummyCard /></div>
        <div class="col col-xs-12 col-sm-6 col-lg-4 col-xxl-3"><DummyCard /></div>
        <div class="col col-xs-12 col-sm-6 col-lg-4 col-xxl-3"><DummyCard /></div>
      </div>
    </div>
    <div class="container" v-else-if="products.length !== 0">
      <div class="row">
        <div
          v-for="(card, id, index) in products"
          :key="index"
          class="col col-xs-12 col-sm-6 col-lg-4 col-xxl-3"
          :style="'order: ' + index"
        >
          <card :product="card" />
        </div>
        <div
          v-for="(banner, index) in banners"
          :key="index"
          class="col col-xs-12 col-sm-6 col-lg-4 col-xxl-3"
          :class="getBannerClasses(index)"
        >
          <banner :src="banner.image.path" :background="banner.background" :title="banner.title" />
        </div>
      </div>
    </div>
    <div class="product__no-items" v-else>
      <icon class="product__no-items-icon" glyph="fork" :width="128" :height="128" />
      <div class="product__no-items-text">Здесь пока нет товаров</div>
    </div>
  </div>
</template>

<script>
import Card from './Card.vue';
import DummyCard from './DummyCard.vue';
import { getCollectionByKey } from '@/api/index.js';
import { DEFAULT_PRODUCT_ID } from '@/settings.js';

export default {
  name: 'Product',
  components: {
    Card,
    DummyCard,
  },
  data() {
    return {
      products: [],
      banners: [],
      isLoading: true,
    };
  },
  watch: {
    $route() {
      this.fetchProductsAndBanners();
    },
  },
  created() {
    this.fetchProductsAndBanners();
  },
  methods: {
    getBannerClasses(index) {
      return null;
      if (index) {
        return ordering.map((config) => `col-${config.breakpoint}-order-${config.order}`).join(' ');
      }
      return '';
    },
    async fetchProductsAndBanners() {
      this.isLoading = true;

      this.products = [];
      this.banners = [];

      const productKey = this.$route.params.productId || DEFAULT_PRODUCT_ID;
      const [ productsResult, bannersResult ] = await Promise.all([
        getCollectionByKey(productKey),
        getCollectionByKey('banners'),
      ]);

      this.products = productsResult;
      this.banners = bannersResult;

      this.isLoading = false;
    },
  },
};
</script>

<style lang="scss">
@import '~@/styles/_globals.scss';

.product {
  min-height: 100vh;
  position: relative;

  &__no-items {
    min-height: 100vh;
    display: flex;
    flex-direction: column;
    justify-content: start;
    align-items: center;
  }

  &__no-items-icon {
    margin-top: 20vh;
    fill: $color-gray-400;
  }

  &__no-items-text {
    margin-top: 16px;
    color: $color-gray-400;
    font-family: $font-title;
    font-size: $font-size-300;
    text-align: center;
  }
}
</style>