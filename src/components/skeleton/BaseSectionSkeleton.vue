<template>
  <q-card class="base-section-skeletion">
    <div class="q-pa-lg">
      <div class="q-pb-lg">
        <q-skeleton :animation="animation" width="200px" type="rect" />
        <q-skeleton :animation="animation" width="50%" type="text" />
      </div>
      <slot>
        <div class="row q-col-gutter-sm">
          <div v-for="item in 2" :key="item" class="col-xs-12 col-sm-6">
            <payment-method-placeholder />
          </div>
        </div>
      </slot>
    </div>
    <q-separator />
    <div class="flex q-pa-md">
      <q-space />
      <q-skeleton :animation="animation" width="150px" type="text" />
    </div>
  </q-card>
</template>

<script>
import PaymentMethodPlaceholder from "./PaymentMethodPlaceholder.vue";
export default {
  components: { PaymentMethodPlaceholder },
  props: {
    loading: {
      type: Boolean,
      default: true,
    },
  },
  computed: {
    animation() {
      return this.loading ? "wave" : "none";
    },
  },
};
</script>
