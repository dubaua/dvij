<template>
  <div class="delivery">
    <iframe :src="ymapIframeLink" frameborder="0" class="delivery__map"></iframe>
    <div class="delivery__overlay">
      <h1>{{ title }}</h1>
      <div class="typography" v-html="content"></div>
    </div>
  </div>
</template>

<script>
import { getSingletonByKey } from '@/api/index.js';

export default {
  name: 'Delivery',
  data() {
    return {
      content: null,
    };
  },
  async created() {
    const { title, content, ymapIframeLink } = await getSingletonByKey('delivery');
    this.title = title;
    this.content = content;
    this.ymapIframeLink = ymapIframeLink;
  },
};
</script>

<style lang="scss">
@import '~@/styles/_globals.scss';
.delivery {
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
  &__overlay {
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
