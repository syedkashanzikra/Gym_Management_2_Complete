<template>
  <div class="tags-select">
    <q-select
      options-dense
      :model-value="modelValue"
      placeholder="Vintage, cotton, summer"
      :options="options"
      ref="tags"
      use-input
      map-options
      hide-selected
      input-debounce="500"
      debounce="500"
      option-label="name"
      option-value="name"
      dense
      outlined
      multiple
      use-chips
      @update:model-value="onChange"
      @filter="onTagLoad"
      @new-value="createTag"
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
                tags.filter((item) => item.name === opt.name).length
              "
              @update:model-value="toggleTag(opt)"
            />
          </q-item-section>
        </q-item>
      </template>
    </q-select>
    <div v-if="modelValue" class="q-mt-sm">
      <div class="q-gutter-xs">
        <q-chip
          square
          v-for="(item, index) in modelValue"
          :key="index"
          removable
          @remove="removeTag(index)"
        >
          {{ item.name }}
        </q-chip>
      </div>
    </div>
  </div>
</template>

<script>
import { mapActions } from "pinia";
import { useTagStore } from "src/stores/product/tag";

export default {
  props: {
    modelValue: {},
  },
  emits: ["update:model-value"],
  data() {
    return {
      options: [],
    };
  },
  methods: {
    ...mapActions(useTagStore, ["get"]),
    onTagLoad(val, update) {
      console.func(
        "components/product/TagsSelect:methods.onTagLoad()",
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
    createTag(val, done) {
      console.func(
        "components/product/TagsSelect:methods.createTag()",
        arguments
      );
      if (val.length > 0) {
        const tags = (this.modelValue || []).slice();
        val
          .split(/[,;|]+/)
          .map((v) => v.trim())
          .filter((v) => v.length > 0)
          .forEach((v) => {
            if (!tags.find((item) => item.name === v)) {
              tags.push({
                name: v,
              });
            }
          });
        done(null);
        this.$refs.tags.updateInputValue("", true);
        this.$emit("update:model-value", tags);
      }
    },
    toggleTag(tag) {
      console.func(
        "components/product/TagsSelect:methods.toggleTag()",
        arguments
      );
      const tags = this.modelValue || [];
      const index = this.$_.findIndex(tags, {
        name: tag.name,
      });
      if (index > -1) {
        this.removeTag(index);
      } else {
        tags.push(tag);
      }
      this.$emit("update:model-value", tags);
    },
    removeTag(index) {
      console.func(
        "components/product/TagsSelect:methods.removeTag()",
        arguments
      );
      const tags = this.modelValue || [];
      tags.splice(index, 1);
      this.$emit("update:model-value", tags);
    },
    onChange(val) {
      console.func(
        "components/product/TagsSelect:methods.onChange()",
        arguments
      );
      this.$emit("update:model-value", val);
    },
  },
};
</script>
