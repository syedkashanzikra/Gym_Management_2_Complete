<template>
  <products
    title="Products"
    :modelValue="modelValue"
    :view="view"
    @update:model-value="(val) => $emit('update:model-value', val)"
  >
    <template v-if="hasBottom" v-slot:bottom>
      <div class="q-mb-sm">
        <span class="text-grey">Location:</span>
        <span class="q-pl-sm">
          {{ location.name }}
        </span>
      </div>
    </template>
    <template v-if="canEdit" v-slot:action>
      <base-btn
        @click="onChangeLocation"
        link
        icon="fas fa-map-marker-edit"
        label="Change location"
        class="q-ml-md"
      />
    </template>
  </products>
</template>

<script>
import { cloneDeep } from "lodash";
import Products from "./ProductsCard.vue";
import ChangeLocation from "./ChangeLocation.vue";

export default {
  components: { Products },
  props: {
    modelValue: {
      type: Array,
      required: true,
    },
    canEdit: Boolean,
    creating: Boolean,
    view: Boolean,
    location: {
      type: Object,
      required: false,
    },
  },
  emits: ["changed-location", "update:model-value"],
  methods: {
    onChangeLocation() {
      console.func(
        "components/order/UnfulfillmentCard:methods.onChangeLocation()",
        arguments
      );
      this.$q
        .dialog({
          component: ChangeLocation,
          componentProps: {
            modelValue: this.location,
          },
        })
        .onOk((payload) => {
          this.$emit("changed-location", cloneDeep(payload));
        });
    },
  },
  computed: {
    hasBottom() {
      return !this.creating && this.location;
    },
  },
};
</script>
