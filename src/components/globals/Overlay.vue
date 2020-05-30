<template>
  <transition name="fade" @after-leave="enableDocumentScroll" @before-enter="disableDocumentScroll">
    <div v-if="isOverlayed" class="overlay" @click.self="onOverlayClick">
      <icon class="overlay__icon" glyph="cross" :width="48" :height="48" />
    </div>
  </transition>
</template>

<script>
import { mapActions, mapGetters } from 'vuex';
import { disableDocumentScroll, enableDocumentScroll } from '@/utils/documentScroll.js';

export default {
  name: 'Overlay',
  computed: {
    ...mapGetters(['isOverlayed']),
  },
  methods: {
    disableDocumentScroll,
    enableDocumentScroll,
    onOverlayClick() {
      this.$store.dispatch('closeAll');
    },
  },
};
</script>

<style lang="scss">
@import '~@/styles/_globals.scss';

.overlay {
  $overlay: &;

  @include fixed-overlay;

  background: rgba(0, 0, 0, 0.85);
  backdrop-filter: blur(5px);
  cursor: pointer;
  &__icon {
    position: absolute;
    right: 16px;
    top: 16px;
    fill: $--color-gray-700;
    transition: color $timing-fast linear;
  }

  &:hover #{$overlay}__icon {
    fill: $--color-gray-400;
  }
}
</style>
