<template>
  <transition name="fade" @after-leave="unfreeze" @before-enter="freeze">
    <div v-if="isOverlayed" class="overlay" @click.self="onOverlayClick">
      <icon class="overlay__icon" glyph="cross" :width="48" :height="48" />
    </div>
  </transition>
</template>

<script>
import { mapActions, mapGetters } from 'vuex';
import { EventBus } from '@/utils/index.js';
import toggleScroll from '@/utils/toggleScroll.js';

export default {
  name: 'Overlay',
  computed: {
    ...mapGetters(['isOverlayed']),
  },
  methods: {
    ...mapActions(['closeAll']),
    onOverlayClick() {
      this.closeAll();
      EventBus.$emit('zoom-out');
    },
    freeze() {
      toggleScroll(true);
    },
    unfreeze() {
      toggleScroll(false);
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
