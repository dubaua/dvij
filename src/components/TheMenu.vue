<template>
  <div class="menu">
    <template v-for="link in items">
      <router-link :key="link.typeId" class="menu__link" :to="link.typeId" @click.native="closeMenu">
        {{ link.title }}
      </router-link>
    </template>
  </div>
</template>

<script>
import { mapActions } from 'vuex';

export default {
  name: 'TheMenu',
  props: {
    items: {
      type: Array,
      required: true,
    },
  },
  methods: {
    ...mapActions(['closeMenu']),
  },
};
</script>

<style lang="scss">
@import '~@/styles/_globals.scss';

.menu {
  background: $color-primary-400;

  @include from('xl') {
    display: flex;
  }

  &__link {
    display: block;
    padding: 12px 16px;
    font-family: $font-title;
    font-size: $font-size-200;
    @include from('xl') {
      letter-spacing: -0.03em;
    }
    @include from('xxl') {
      letter-spacing: 0;
    }
    text-decoration: none;
    color: $color-gray-50;
    cursor: pointer;
    transition: background 160ms $ease-out, color 160ms $ease-out;

    &.router-link-exact-active {
      background: $color-primary-500;
    }

    &:hover {
      background: $color-primary-500;
    }

    &:active {
      background: $color-primary-600;
    }

    @include from('xl') {
      padding-bottom: 16px;
      padding-top: 16px;
    }
  }
}
</style>
