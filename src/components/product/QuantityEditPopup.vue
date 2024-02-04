<template>
  <q-popup-edit
    label-set="Save"
    class="q-pa-none"
    style="width: 300px; max-width: 95vw"
    content-class="base-popup-edit"
    v-model="available"
    v-slot="scope"
    @before-show="
      quantity_update = 0;
      quantity_update_type = 'adjust';
    "
    @update:model-value="(val) => $emit('update:model-value', val)"
  >
    <div class="q-pa-md flex row">
      <q-toolbar-title class="text-subtitle1 text-weight-medium">
        Update quantity
      </q-toolbar-title>
    </div>
    <q-separator />
    <div class="q-pa-md">
      <q-option-group
        class="q-mb-sm"
        inline
        dense
        v-model="quantity_update_type"
        :options="quantity_update_types"
      />
      <q-input type="number" dense v-model="quantity_update" outlined autofocus>
      </q-input>
      <div v-if="quantity_update" class="text-subtitle2 q-mt-xs">
        <span>New quantity:</span>
        <span
          v-if="quantity_update_type === 'replace'"
          class="q-ml-xs text-primary"
        >
          {{ quantity_update }}
        </span>
        <span v-else class="q-ml-xs text-primary">{{
          parseInt(quantity_update) + parseInt(scope.value)
        }}</span>
        <span class="q-ml-xs no-inventory">{{ scope.value }}</span>
      </div>
      <div class="text-subtitle2 q-mt-xs" v-else>
        <span>Original quantity:</span>
        <span class="q-ml-xs">{{ scope.value }}</span>
      </div>
    </div>
    <q-separator />
    <div class="q-pa-md text-right">
      <div class="q-gutter-sm">
        <q-btn
          size="12px"
          @click="scope.cancel()"
          no-caps
          color="grey"
          outline
          :label="$t('label.cancel')"
        />
        <q-btn
          size="12px"
          @click="
            scope.value =
              quantity_update_type === 'replace'
                ? quantity_update
                : parseInt(quantity_update) + parseInt(scope.value);
            scope.set();
          "
          no-caps
          color="positive"
          :label="$t('label.save')"
        />
      </div>
    </div>
  </q-popup-edit>
</template>

<script>
export default {
  props: {
    modelValue: [Number, String],
  },
  emits: ["update:model-value"],
  data() {
    return {
      available: this.modelValue,
      quantity_update: 0,
      quantity_update_type: "adjust",
      quantity_update_types: [
        {
          label: "Adjust",
          value: "adjust",
        },
        {
          label: "Replace",
          value: "replace",
        },
      ],
    };
  },
};
</script>
