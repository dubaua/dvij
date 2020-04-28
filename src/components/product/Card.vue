<template>
  <div class="card">
    <div class="card__preview">
      <div v-if="tags.length" class="card__tags">
        <div v-for="tag in tags" :key="tag" class="card__tag" :class="getTagClassName(tag)">{{ tag }}</div>
      </div>
      <img ref="productImage" class="card__zoom" :src="photoUrl" :alt="product.title" @click="zoomImage" />
    </div>
    <div class="card__details">
      <h2>{{ product.title }}</h2>
      <p>{{ product.description }}</p>
      <template v-if="isSizeSwitchable">
        <div class="switch">
          <button
            v-for="(productSize, sizeIndex) in product.sizes"
            :key="productSize.sizeName"
            @click="setSizeIndex(sizeIndex)"
            class="switch__option"
          >
            {{ productSize.sizeName }}
          </button>
        </div>
        <div v-if="chosenSize.options" class="switch">
          <button
            v-for="(option, optionIndex) in chosenSize.options"
            :key="option.optionName"
            @click="setOptionIndex(optionIndex)"
            class="switch__option"
          >
            {{ option.optionName }}
          </button>
        </div>
      </template>
      <p v-else>{{ chosenSize.sizeName }}</p>

      <div class="card__action">
        <div class="card__price">{{ chosenSize.price }} ₽</div>
        <drive-button class="card__button button--wide" :active="amount > 0" @click="handleAddToCard(product)">
          <template #initial>
            Добавить
          </template>
          <template #active>
            {{ `В корзине ${amount}` }}
          </template>
        </drive-button>
      </div>
    </div>
  </div>
</template>

<script>
import { mapActions, mapState } from 'vuex';
import { numberWithSpaces } from '@/utils/index.js';
import { EventBus } from '@/utils/index.js';
import { DEFAULT_PRODUCT_ID } from '@/settings.js';

/* eslint-disable no-underscore-dangle */
export default {
  name: 'Card',
  props: {
    product: Object,
  },
  data() {
    return {
      sizeIndex: 0,
    };
  },
  computed: {
    ...mapState(['page']),
    isSizeSwitchable() {
      return this.product.sizes.length > 1;
    },
    chosenSize() {
      return this.product.sizes[this.sizeIndex];
    },
    photoUrl() {
      return this.chosenSize.photo ? this.chosenSize.photo.path : '';
    },
    cartProductId() {
      return `${this.product.categoryId}-${this.product._id}-${this.sizeIndex}`;
    },
    amount() {
      return this.$store.getters.getAmountInCardById(this.cartProductId);
    },
    tags() {
      return this.product.tags ? this.product.tags.filter((tag) => tag !== '') : [];
    },
  },
  methods: {
    ...mapActions(['addToCart']),
    zoomImage() {
      if (!this.page.isMobile) {
        EventBus.$emit('zoom-image', this.$refs.productImage);
      }
    },
    getTagClassName(tag) {
      return `card__tag--${tag}`;
    },
    setSizeIndex(sizeIndex) {
      this.sizeIndex = sizeIndex;
    },
    handleAddToCard(product) {
      const { _id, categoryId, title } = product;
      const {
        cartProductId,
        sizeIndex,
        chosenSize: { price, priceNew, sizeName },
      } = this;
      this.addToCart({
        cartProductId,
        productId: _id,
        categoryId,
        sizeIndex,
        title,
        price: priceNew || price,
        sizeName,
      });
    },
  },
};
</script>

<style lang="scss">
@import '~@/styles/_globals.scss';

.card {
  $block: &;
  display: flex;
  flex-direction: column;
  box-sizing: border-box;
  height: calc(100% - 20px);
  margin: 0 0 20px;

  &__preview {
    position: relative;
    & img {
      border-radius: 2px 2px 0 0;
      max-width: 100%;
      height: auto;
      display: block;
    }
  }

  &__zoom {
    cursor: zoom-in;
  }

  &__details {
    flex-grow: 1;
    display: flex;
    flex-direction: column;
    padding: 10px 20px;

    h2 {
      margin: 0 0 10px;
      font-family: $font-title;
      font-size: $font-size-300;
      line-height: 1.2;
      font-weight: normal;
    }
    p {
      margin: 0 0 10px;
      color: $color-gray-800;
      font: normal 16px/1.38 $font-body;
    }
  }

  &__action {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-top: auto;
  }

  &__price {
    font-size: $font-size-300;
    max-width: 38%;
  }

  &__button {
    max-width: 62%;
  }

  &__tags {
    position: absolute;
    right: 8px;
    bottom: 8px;
    display: flex;
  }

  &__tag {
    background: $color-gray-50;
    color: $color-gray-900;
    font-size: $font-size-75;
    text-transform: uppercase;
    letter-spacing: 0.1em;
    font-weight: bold;
    width: 16px * 2.5;
    text-align: center;
    padding: 16px * 0.87 0;
    margin-left: 16px * 0.5;
    border-radius: 50%;

    &--hit {
      background-color: $color-gray-50;
    }

    &--new {
      background-color: $color-primary-400;
    }
  }
}
</style>
