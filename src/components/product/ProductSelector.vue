<template>
  <base-dialog
    :title="title"
    body-class="q-pa-none"
    body-style=""
    ref="dialog"
    persistent
    @hide="onDialogHide"
    use-separator
  >
    <template v-slot:body>
      <q-card-section class="q-col-gutter-md row">
        <div class="col">
          <q-input
            dense
            outlined
            type="text"
            v-model="filter"
            clearable
            debounce="500"
            placeholder="Search products"
            @update:model-value="(val) => onBrowseProduct(val, 'filter')"
          />
        </div>
        <div class="col-auto">
          <q-select
            :style="{
              width: '200px',
            }"
            prefix="Sort: "
            map-options
            options-dense
            emit-value
            v-model="sortBy"
            :options="sortOptions"
            dense
            outlined
            @update:model-value="(val) => onBrowseProduct(val, 'sortBy')"
          />
        </div>
      </q-card-section>
      <q-separator />
      <q-list
        class="bordered scroll"
        style="max-height: 50vh; min-height: 50vh"
      >
        <template v-if="!loading">
          <q-item
            v-for="(product, index) in products"
            :key="index"
            class=""
            tag="label"
          >
            <q-item-section style="min-width: auto" avatar>
              <q-checkbox
                size="sm"
                dense
                v-model="selected"
                :val="product.id"
              />
            </q-item-section>
            <q-item-section style="min-width: auto" avatar>
              <base-thumbnail :size="50" :media="product.thumbnail" />
            </q-item-section>
            <q-item-section>
              <q-item-label class="label">{{ product.title }}</q-item-label>
              <q-item-label class="status">
                <base-status :type="product.status" />
              </q-item-label>
            </q-item-section>
          </q-item>
        </template>
        <q-inner-loading :showing="loading">
          <q-spinner-oval size="50px" color="primary" />
        </q-inner-loading>
      </q-list>
    </template>
    <template v-slot:footer>
      <q-card-section class="flex">
        <template v-if="loadFromServer">
          <q-btn
            :disable="current_page <= 1 || loading"
            @click="
              onLoadProduct({
                page: current_page - 1 >= 1 ? current_page - 1 : 1,
              })
            "
            dense
            round
            flat
            color="primary"
            icon="fal fa-angle-left"
          />
          <q-btn
            :disable="current_page == last_page || loading"
            @click="
              onLoadProduct({
                page: current_page + 1 <= last_page ? current_page + 1 : 1,
              })
            "
            dense
            round
            flat
            color="primary"
            icon="fal fa-angle-right"
          />
        </template>
        <q-space />
        <div class="q-gutter-sm">
          <q-btn
            no-caps
            outline
            :label="$t('label.cancel')"
            color="grey"
            v-close-popup
          />
          <q-btn
            :disable="disable"
            no-caps
            :label="$t('label.done')"
            color="primary"
            @click="onDone"
          />
        </div>
      </q-card-section>
    </template>
  </base-dialog>
</template>

<script>
import { mapState, mapActions } from "pinia";
import { useProductStore } from "src/stores/product";

export default {
  data() {
    return {
      selected: this.modelValue,
      products: [],
      current_page: 1,
      last_page: 1,
      loading: false,
      filter: this.query,
      sortBy: "TITLE_ASC",
    };
  },
  props: {
    modelValue: {
      required: false,
      type: [Array],
      default: () => [],
    },
    hint: [String],
    query: [String],
    title: {
      type: [String],
      default: "Select products",
    },
    loadFromServer: {
      type: [Boolean],
      required: false,
      default: true,
    },
  },
  emits: ["ok", "hide"],
  methods: {
    ...mapActions(useProductStore, ["get"]),
    onLoadProduct(args) {
      console.func(
        "components/product/ProductSelector:methods.onLoadProduct()",
        arguments
      );
      this.loading = true;
      this.current_page = args.page;
      this.get({
        ...args,
        filter: this.filter,
        order: this.sortBy,
        limit: 10,
        order: "title",
        descending: "asc",
        options: true,
      })
        .then(({ data, meta }) => {
          this.products = data;
          this.current_page = meta.current_page;
          this.last_page = meta.last_page;
          this.loading = false;
        })
        .catch(() => {
          this.loading = false;
        });
    },
    async show() {
      console.func(
        "components/product/ProductSelector:methods.show()",
        arguments
      );
      if (this.loadFromServer) {
        await this.onLoadProduct({
          page: this.current_page,
        });
      }
      this.$refs.dialog.show();
    },
    hide() {
      console.func(
        "components/product/ProductSelector:methods.close()",
        arguments
      );
      this.$refs.dialog.hide();
    },
    onDialogHide() {
      console.func(
        "components/product/ProductSelector:methods.onDialogHide()",
        arguments
      );
      this.$emit("hide");
    },
    onDone() {
      console.func(
        "components/product/ProductSelector:methods.onDone()",
        arguments
      );
      this.$emit("ok", this.selected);
      this.hide();
    },
    onBrowseProduct(value) {
      console.func(
        "components/product/ProductSelector:methods.onBrowseProduct()",
        arguments
      );
      this.filter = value;
      this.onLoadProduct({
        page: 1,
      });
    },
  },
  computed: {
    ...mapState(useProductStore, ["sortOptions"]),
    disable() {
      return this.selected === this.modelValue;
    },
  },
};
</script>
