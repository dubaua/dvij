<template>
  <div class="order">
    <form v-if="isOrderAvailable && !isSent" ref="form" class="form" @submit.prevent="send">
      <vue-form-generator :schema="schema" :model="model" :options="formOptions" />
      <base-button size="wide" :disabled="isSending || isSent">Оформить заказ</base-button>
    </form>
    <div v-if="isSent" class="order__sent">
      Ваш заказ отправлен
    </div>
  </div>
</template>

<script>
// TODO disable form while sending

import { sendForm } from '@/api/index.js';
import { mapActions, mapMutations } from 'vuex';
import animate from '@/utils/animate.js';
import progressFromTo from '@/utils/progressFromTo.js';
import * as ez from '@/utils/easings.js';

export default {
  name: 'Order',
  data() {
    return {
      isSending: false,
      isSent: false,
      model: {
        name: 'Владимир',
        phone: '+7 908 048 14 10',
        address: 'пр. победы 386 Б',
        comment: 'Что-то с чем-то',
        // name: '',
        // phone: '',
        // address: '',
        // comment: '',
        paymentMethod: 'cash',
        changeAmount: '',
      },
      schema: {
        fields: [
          {
            type: 'input',
            inputType: 'text',
            label: 'Имя',
            model: 'name',
            id: 'name',
            placeholder: 'Имя',
            required: true,
          },
          {
            type: 'input',
            inputType: 'text',
            label: 'Телефон',
            model: 'phone',
            id: 'phone',
            placeholder: 'Телефон',
            required: true,
          },
          {
            type: 'input',
            inputType: 'text',
            label: 'Адрес',
            model: 'address',
            id: 'address',
            placeholder: 'Адрес',
            required: true,
          },
          {
            type: 'textArea',
            label: 'Комментарий к заказу',
            model: 'comment',
            id: 'comment',
            rows: 4,
            max: 500,
            placeholder: 'Комментарий к заказу',
          },
          // {
          //   type: 'radios',
          //   label: 'Оплата',
          //   model: 'paymentMethod',
          //   values: [
          //     {
          //       name: 'Наличными',
          //       value: 'cash',
          //     },
          //     {
          //       name: 'Картой',
          //       value: 'onCard',
          //     },
          //   ],
          // },
          // {
          //   type: 'input',
          //   inputType: 'text',
          //   label: 'Сдача с',
          //   model: 'changeAmount',
          //   id: 'changeAmount',
          //   placeholder: 'Сдача с',
          //   required: false,
          //   visible: function(model) {
          //     return model && model.paymentMethod === 'cash';
          //   },
          //   validator(value, field, model) {
          //     if (model.paymentMethod !== 'cash') {
          //       return true;
          //     }
          //     return !!value;
          //   },
          // },
        ],
      },
      formOptions: {
        validateAfterLoad: true,
        validateAfterChanged: true,
      },
      formHeight: 0,
    };
  },
  computed: {
    isOrderAvailable() {
      return this.$store.getters.totalCost !== 0;
    },
    formData() {
      // first send to email via phpmailer
      const cart = this.$store.state.cart;

      const order = Object.keys(cart)
        .map((key) => cart[key])
        .map(({ amount, productId, categoryId, sizeIndex }) => ({
          amount,
          productId,
          categoryId,
          sizeIndex,
        }));

      return {
        name: this.model.name,
        phone: this.model.phone,
        address: this.model.address,
        comment: this.model.comment,
        order,
      };
    },
  },
  methods: {
    ...mapMutations(['clearCart']),
    ...mapActions(['closeCart']),
    clearForm() {
      this.model = {
        name: '',
        phone: '',
        comment: '',
        address: '',
      };
    },
    async send() {
      this.isSending = true;

      const { success } = await sendForm(this.formData);
      this.isSending = false;

      this.isSent = success;
    },
    animateDelivery() {
      setTimeout(() => {
        this.clearForm();
        this.clearCart();
        this.closeCart();
        this.isSent = false;
      }, 3000);
    },
  },
  watch: {
    isSent(nextState) {
      if (nextState) {
        this.animateDelivery();
      }
    },
  },
};
</script>

<style lang="scss">
@import '~@/styles/_globals.scss';

$minFormHeight: 250px;

.order {
  &__sent {
    font-size: $font-size-300;
    font-family: $font-title;
    text-align: center;
  }
}

.form {
  min-height: $minFormHeight;
  padding: 0 #{$font-size-300} 16px;
  & .form-group + .form-group {
    margin-top: 12px;
  }

  & fieldset {
    border: 0;
    padding: 0;
    margin: 0 0 12px;
  }
  & label {
    display: none;
  }
  & input,
  & textarea {
    width: 100%;
    padding: 4px 8px;
    box-sizing: border-box;
    color: inherit;
    background: $--color-gray-50;
    border: 0;
    border-bottom: 1px solid $--color-gray-300;
    border-radius: 0;

    &:focus {
      outline: none;
      border-color: $--color-primary-400;
    }
  }
  & textarea {
    display: block;
    resize: vertical;
  }
  .field-radios label {
    display: block;
  }

  .radio-list label,
  .radio-list input {
    display: inline-block;
    white-space: nowrap;
    width: auto;
  }
}

.delivery {
  display: flex;
  align-items: center;
  justify-content: center;
  min-height: $minFormHeight;
  overflow: hidden;
  &__van {
    display: block;
    transform-origin: left bottom;
  }

  &__word {
    display: inline-block;
    font-family: 'Sangha Kali';
    font-size: 48px;
    color: white;
    transform-origin: right bottom;

    &--cabin {
      margin-right: 0.3em;
    }
    &--product {
      transform: translate(100vh, 0);
    }
  }
}
</style>
