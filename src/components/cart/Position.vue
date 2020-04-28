<template>
  <div class="position">
    <div class="position__title" :title="position.title">{{ position.title }} {{ position.sizeName }}</div>
    <div class="position__status">
      <button class="position__button" @click="changeAmount({ cartProductId: position.cartProductId, modifier: -1 })">
        –
      </button>
      <span class="position__amount">{{ position.amount }}</span>
      <button class="position__button" @click="changeAmount({ cartProductId: position.cartProductId, modifier: 1 })">
        +
      </button>
      <span>&times; {{ numberWithSpaces(position.price) }} ₽</span>
    </div>
  </div>
</template>

<script>
import { numberWithSpaces } from '@/utils/index.js';
import { mapActions } from 'vuex';

export default {
  name: 'Position',
  props: {
    position: {
      type: Object,
      required: true,
    },
  },
  methods: {
    numberWithSpaces,
    ...mapActions(['changeAmount']),
  },
};
</script>

<style lang="scss">
@import '~@/styles/_globals.scss';

.position {
  $block: &;

  display: flex;
  justify-content: space-between;
  align-items: baseline;
  flex-wrap: wrap;

  & + & {
    margin-top: 12px;
  }

  &__title,
  &__status {
    margin: 4px 0;
    display: inline-block;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
  }

  &__status {
    margin-left: auto;
  }

  &__amount {
    display: inline-block;
    margin: 0 0.2em;
  }

  &__button {
    margin: 0;
    padding: 0;
    border: 0;
    width: 20px;
    height: 20px;
    background: $--color-gray-300;
    color: inherit;
    border-radius: 10px;
    text-align: center;
    cursor: pointer;

    &:hover {
      background: $--color-gray-400;
    }

    &:active {
      background: $--color-gray-500;
    }

    &:focus {
      outline: none;
    }
  }
}
</style>
