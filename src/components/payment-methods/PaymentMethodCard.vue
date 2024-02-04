<template>
  <q-card
    @click.stop="$emit('update:selected', { ...$props })"
    :class="{ 'payment-method-card cursor-pointer': true, selected: isCurrent }"
    bordered
    flat
  >
    <q-card-section>
      <q-item>
        <q-item-section class="card-brand" top avatar>
          <q-icon
            size="md"
            :name="icon ? icon : `fab fa-cc-${brand || 'visa'}`"
          />
        </q-item-section>
        <q-item-section>
          <q-item-label lines="1" class="ellipsis-left">
            {{ $t(card_number) }}
          </q-item-label>
          <q-item-label caption lines="2">
            {{ $t(exp_date) }}
            <q-badge
              v-if="isDefault"
              transparent
              color="teal-1"
              text-color="teal"
              class="text-bold"
              :label="$t('default')"
              align="middle"
              rounded
            />
          </q-item-label>
        </q-item-section>
        <q-item-section v-if="!payMode" side top>
          <q-btn round flat dense icon="fal fa-ellipsis-v" @click.stop>
            <q-menu dense>
              <q-list class="q-pa-sm" dense style="min-width: 150px">
                <base-item
                  v-show="!isDefault"
                  @click="onDefaultPaymentMethod"
                  :label="$t('markAsDefault')"
                />
                <base-item
                  @click="onRemovePaymentMethod"
                  :label="$t('remove')"
                />
              </q-list>
            </q-menu>
          </q-btn>
        </q-item-section>
      </q-item>
    </q-card-section>
  </q-card>
</template>

<script>
import { mapActions } from "pinia";
import { useSubscriptionStore } from "stores/subscription";

export default {
  emits: ["updated", "removed", "update:selected"],
  props: {
    id: [Number, String],
    selected: {
      type: Object,
      default: () => ({}),
    },
    card_number: String,
    icon: String,
    provider: String,
    brand: String,
    exp_date: String,
    isDefault: Boolean,
    payMode: Boolean,
  },
  methods: {
    ...mapActions(useSubscriptionStore, [
      "updateDefaultPaymentMethod",
      "removePaymentMethod",
    ]),
    onRemovePaymentMethod() {
      console.func(
        "components/payment-methods/PaymentCard:methods.onRemovePaymentMethod()",
        arguments
      );
      this.$core.confirm(this.$t("dialog.info.remove")).then(() => {
        this.removePaymentMethod(this.id)
          .then(({ message }) => {
            this.$core.success(message, {
              title: this.$t("dialog.title.success"),
            });
          })
          .catch((error) => {
            this.$core.error(error, { title: this.$t("dialog.title.error") });
          });
      });
    },
    onDefaultPaymentMethod() {
      console.func(
        "components/payment-methods/PaymentCard:methods.onDefaultPaymentMethod()",
        arguments
      );
      this.updateDefaultPaymentMethod(this.id)
        .then(({ message }) => {
          this.$core.success(message, {
            title: this.$t("dialog.title.success"),
          });
        })
        .catch((error) => {
          this.$core.error(error, { title: this.$t("dialog.title.error") });
        });
    },
  },
  computed: {
    isCurrent() {
      return this.payMode && this.selected?.id === this.id;
    },
  },
};
</script>

<style lang="scss">
.payment-method-card.selected {
  border-color: $primary;
  background-color: rgba($primary, 0.2);
}
</style>
