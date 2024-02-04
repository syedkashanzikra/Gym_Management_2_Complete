<template>
  <base-section
    class="seo-section"
    :flat="flat"
    :title="title"
    :description="description"
  >
    <template v-slot:action>
      <base-btn
        v-if="!editable"
        link
        @click="editable = true"
        :label="$t('label.editSeo')"
      />
    </template>
    <template v-if="empty">
      <div class="col-xs-12">
        {{ message }}
      </div>
    </template>
    <template v-else>
      <div class="col-xs-12 col-sm-12">
        <div class="text-h5 text-indigo">{{ meta_title }}</div>
        <div class="caption text-green">{{ prefix }}{{ slug }}</div>
        <div class="caption text-grey">{{ meta_description }}</div>
      </div>
    </template>
    <template v-if="editable">
      <div class="col-xs-12">
        <q-separator />
      </div>
      <div class="col-xs-12 col-sm-12">
        <base-label>{{ $t("label.pageTitle") }}</base-label>
        <q-input
          dense
          outlined
          v-model="meta_title"
          @update:model-value="(val) => onChange(val, 'meta_title')"
          counter
          maxlength="70"
          hint="Max 70 characters can be used"
        />
      </div>
      <div class="col-xs-12 col-sm-12">
        <base-label>{{ $t("label.urlHandle") }}</base-label>
        <q-input
          dense
          outlined
          v-model="slug"
          :prefix="prefix"
          @update:model-value="(val) => onChange(val, 'slug')"
        />
      </div>
      <div class="col-xs-12 col-sm-12">
        <base-label>{{ $t("label.metaDescription") }}</base-label>
        <q-input
          dense
          outlined
          v-model="meta_description"
          @update:model-value="(val) => onChange(val, 'meta_description')"
          type="textarea"
          counter
          maxlength="320"
          hint="Max 320 characters can be used"
        />
      </div>
      <div v-if="item.meta_keywords" class="col-xs-12 col-sm-12">
        <base-label>{{ $t("label.metaKeywords") }}</base-label>
        <q-select
          dense
          outlined
          v-model="item.meta_keywords"
          use-input
          use-chips
          multiple
          hide-dropdown-icon
          input-debounce="0"
          new-value-mode="add-unique"
        />
      </div>
    </template>
  </base-section>
</template>

<script>
export default {
  props: {
    modelValue: {
      required: true,
      type: Object,
    },
    flat: {
      type: Boolean,
      default: false,
    },
    creating: {
      type: Boolean,
      default: false,
    },
    title: {
      type: String,
      default: "Search engine listing preview",
    },
    description: String,
    prefix: {
      type: String,
      default: "https://yourshop.com/products/",
    },
    message: {
      type: String,
      default:
        "Add a title and description to see how this product might appear in a search engine listing",
    },
  },
  emits: ["update:model-value"],
  data() {
    return {
      item: this.modelValue,
      editable: false,
    };
  },
  methods: {
    onChange(value, key) {
      console.func("components/product/seo:methods.onChange()", arguments);
      this.item[key] = value;
      this.$emit("update:model-value", this.item);
    },
  },
  computed: {
    meta_title: {
      get() {
        return (
          this.item.meta_title ||
          this.item.title ||
          this.item.name ||
          ""
        ).slice(0, 70);
      },
      set(val) {
        return val;
      },
    },
    slug: {
      get() {
        return (this.item.slug || this.item.title || this.item.name || "")
          .replace(/ /g, "-")
          .replace(/[^\w-]+/g, "");
      },
      set(val) {
        return val;
      },
    },
    meta_description: {
      get() {
        return (
          this.item.meta_description ||
          (this.item.description || "")
            .replace(/(<([^>]+)>)/g, "")
            .slice(0, 320)
        );
      },
      set(val) {
        return val;
      },
    },
    empty() {
      return !this.meta_title;
    },
  },
  watch: {
    modelValue: {
      deep: true,
      handler(val) {
        this.item = val;
      },
    },
  },
};
</script>
