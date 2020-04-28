<template>
  <div class="special">
    <div class="special__content typography" v-html="contact" />
  </div>
</template>

<script>
import { getSingletonByKey } from '@/api/index.js';

export default {
  name: 'Special',
  data() {
    return {
      contact: null,
    };
  },
  async created() {
    const { content } = await getSingletonByKey('special');
    this.contact = content;
  },
};
</script>

<style lang="scss">
@import '~@/styles/_globals.scss';
.contact {
  position: relative;
  overflow: hidden;
  @media screen and (min-height: 668px) {
    height: calc(100vh - 56px);
  }
  display: flex;
  flex-direction: column;

  &__map {
    flex-grow: 1;
    width: 100%;
    height: 50vh;

    @media screen and (min-height: 668px) {
      height: calc(100vh - 66px);
    }
  }
  &__info {
    padding: 16px 16px * 2;
    background-color: $--color-gray-50;
    @include from('md') {
      width: 320px;
      position: absolute;
      left: 5vw;
      top: 10vh;

      border-radius: 2px;
      box-shadow: 0 1px 3px rgba($--color-shade, 0.08);
      overflow: hidden;
    }
  }
}
</style>
