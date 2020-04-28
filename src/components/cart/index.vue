<template>
  <div v-if="totalAmount > 0" class="cart">
    <div class="cart__filled">
      <div class="cart__contents">
        <position v-for="position in cart" :key="position.id" :position="position" />
      </div>
      <div class="cart__total">
        <div class="cart__total-label">Всего</div>
        <div class="cart__total-sum">{{ totalCost }} ₽</div>
      </div>
    </div>
  </div>
</template>

<script>
import { mapActions, mapGetters, mapState } from 'vuex';
import Position from './Position';

export default {
  name: 'Cart',
  components: {
    Position,
  },
  computed: {
    ...mapGetters(['totalCost', 'totalAmount']),
    ...mapState(['cart']),
    isCartOpen() {
      return this.$store.state.page.isCartOpen;
    },
  },
  methods: {
    ...mapActions(['changeAmount', 'closeCart']),
  },
};
</script>

<style lang="scss">
@import '~@/styles/_globals.scss';
.cart {
  display: flex;
  flex-direction: column;
  padding: 20px 24px;

  &__empty {
    text-align: center;
  }

  &__total {
    display: flex;
    justify-content: space-between;
    margin: 16px 0;
    font-weight: 500;
  }
}
</style>
