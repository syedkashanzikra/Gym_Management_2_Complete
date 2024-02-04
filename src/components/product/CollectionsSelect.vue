<template>
  <div class="collection-select">
    <q-select
      hint="Add this product to a collection so it’s easy to find in your store."
      style="padding-bottom: 30px"
      placeholder="Search for collections"
      :model-value="modelValue"
      :options="options"
      use-input
      map-options
      input-debounce="500"
      debounce="500"
      option-label="name"
      option-value="name"
      dense
      outlined
      multiple
      popup-content-class="q-pa-sm base-dropdown"
      @filter="onCollectionLoad"
      @update:model-value="onChange"
      :display-value="null"
    >
      <template v-slot:option="{ itemProps, opt }">
        <q-item v-bind="itemProps">
          <q-item-section>
            <q-item-label>
              <span v-html="opt.name"></span>
            </q-item-label>
          </q-item-section>
          <q-item-section side>
            <q-toggle
              dense
              size="xs"
              :true-value="1"
              :false-value="0"
              :model-value="
                modelValue.filter((item) => item.name === opt.name).length
              "
              @update:model-value="toggleCollection(opt)"
            />
          </q-item-section>
        </q-item>
      </template>
    </q-select>
    <div v-if="modelValue">
      <q-list class="bordered" dense>
        <q-item v-for="(item, index) in modelValue" :key="index">
          <q-item-section>
            <q-item-label>{{ item.name }}</q-item-label>
          </q-item-section>
          <q-item-section side>
            <q-btn
              size="xs"
              color="primary"
              icon="close"
              round
              flat
              @click="removeCollection(index)"
            />
          </q-item-section>
        </q-item>
      </q-list>
    </div>
  </div>
</template>

<script>
import { findIndex } from "lodash";
import { mapActions } from "pinia";
import { useCollectionStore } from "src/stores/product/collection";

export default {
  props: {
    modelValue: {
      required: true,
    },
  },
  emits: ["update:model-value"],
  data() {
    return {
      options: [],
    };
  },
  methods: {
    ...mapActions(useCollectionStore, ["get"]),
    onCollectionLoad(val, update) {
      console.func(
        "components/product/CollectionsSelect:methods.onCollectionLoad()",
        arguments
      );
      this.options = [];
      this.get({
        filter: val,
        rowsPerPage: 5,
        sortBy: "name",
      }).then(({ data }) => {
        update(() => {
          this.options = data;
        });
      });
    },
    removeCollection(index) {
      console.func(
        "components/product/CollectionsSelect:methods.removeCollection()",
        arguments
      );
      const collections = this.modelValue || [];
      collections.splice(index, 1);
      this.$emit("update:model-value", collections);
    },
    toggleCollection(collection) {
      console.func(
        "components/product/CollectionsSelect:methods.toggleCollection()",
        arguments
      );
      const collections = this.modelValue || [];
      const index = findIndex(collections, {
        name: collection.name,
      });
      if (index > -1) {
        this.removeCollection(index);
      } else {
        collections.push(collection);
        this.$emit("update:model-value", collections);
      }
    },
    onChange(val) {
      console.func(
        "components/product/CollectionsSelect:methods.onChange()",
        arguments
      );
      this.$emit("update:model-value", val);
    },
  },
};
</script>
<style lang="sass" scoped>
.q-list--dense.bordered > .q-item, .q-item--dense.bordered
  padding: 2px 5px
</style>
