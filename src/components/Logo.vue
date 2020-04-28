<template>
  <router-link class="logo" to="/">
    <icon glyph="logo" :width="320" :height="141" />
  </router-link>
</template>

<script>
export default {
  name: 'Logo',
};
</script>

<style lang="scss">
.logo {
  margin-top: 32px;
  display: inline-block;
  line-height: 0;
  text-align: center;
  color: inherit;
  .icon {
    margin-left: auto;
    margin-right: auto;
  }
}
</style>
