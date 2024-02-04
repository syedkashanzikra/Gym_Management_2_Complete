<template>
  <base-dialog
    ref="dialog"
    :title="title"
    body-style=""
    body-class="q-pa-none"
    persistent
    @hide="onDialogHide"
    use-separator
  >
    <template v-slot:body>
      <q-card-section>
        <q-input
          dense
          outlined
          type="text"
          v-model="filter"
          clearable
          debounce="500"
          placeholder="Search collections"
          @update:model-value="(val) => onBrowse(val, 'filter')"
        />
      </q-card-section>
      <q-separator />
      <q-list
        class="bordered scroll"
        style="max-height: 50vh; min-height: 50vh"
      >
        <template v-if="!loading">
          <q-item
            v-for="(collection, index) in collections"
            :key="index"
            class=""
            tag="label"
          >
            <q-item-section style="min-width: auto" avatar>
              <q-checkbox
                size="sm"
                dense
                v-model="selected"
                :val="collection"
              />
            </q-item-section>
            <q-item-section style="min-width: auto" avatar>
              <thumbnail :size="50" :media="collection.thumbnail" />
            </q-item-section>
            <q-item-section>
              <q-item-label class="label">{{ collection.name }}</q-item-label>
              <status :type="collection.status" />
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
              onLoad({
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
              onLoad({
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
          <q-btn no-caps outline :label="$t('label.cancel')" color="grey" v-close-popup />
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
import { mapActions } from "pinia";
import { useCollectionStore } from "src/stores/product/collection";

export default {
  data() {
    return {
      selected: this.modelValue,
      collections: [],
      current_page: 1,
      last_page: 1,
      loading: false,
      filter: this.query,
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
      default: "Select collections",
    },
    loadFromServer: {
      type: [Boolean],
      required: false,
      default: true,
    },
  },
  emits: ["ok", "hide"],
  methods: {
    ...mapActions(useCollectionStore, ["get"]),
    onLoad(args) {
      console.func(
        "components/product/CollectionSelector:methods.onLoad()",
        arguments
      );
      this.loading = true;
      this.current_page = args.page;
      this.get({
        ...args,
        filter: this.filter,
        limit: 10,
        order: "name",
        descending: "asc",
        options: true,
      })
        .then(({ data, meta }) => {
          this.collections = data;
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
        "components/product/CollectionSelector:methods.show()",
        arguments
      );
      if (this.loadFromServer) {
        await this.onLoad({
          page: this.current_page,
        });
      }
      this.$refs.dialog.show();
    },
    hide() {
      console.func(
        "components/product/CollectionSelector:methods.close()",
        arguments
      );
      this.$refs.dialog.hide();
    },
    onDialogHide() {
      console.func(
        "components/product/CollectionSelector:methods.onDialogHide()",
        arguments
      );
      this.$emit("hide");
    },
    onDone() {
      console.func(
        "components/product/CollectionSelector:methods.onDone()",
        arguments
      );
      this.$emit("ok", this.selected);
      this.hide();
    },
    onBrowse(value) {
      console.func(
        "components/product/CollectionSelector:methods.onBrowse()",
        arguments
      );
      this.filter = value;
      this.onLoad({
        page: 1,
      });
    },
  },
  computed: {
    disable() {
      return this.selected === this.modelValue;
    },
  },
};
</script>
