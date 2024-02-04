<template>
  <q-page padding>
    <base-form
      v-if="loaded"
      @submit="onSubmit"
      @cancel="onCancel"
      @reset="onReset"
      :resetable="resetable"
      :disable="disable"
      :submited="submited"
    >
      <div class="row q-col-gutter-md">
        <div class="col-xs-12 col-sm-9">
          <div class="q-gutter-md">
            <base-section
              title="General information"
              description="A great collection title and description has the power to turn a casual shopper into a revenue-generating buyer."
            >
              <div class="col-xs-12">
                <base-label>{{ $t("label.name") }}</base-label>
                <q-input
                  dense
                  outlined
                  v-model="collection.name"
                  type="text"
                  placeholder="e.g. Summer collection, Under $100, Staff picks"
                />
              </div>
              <div class="col-xs-12">
                <base-label>{{ $t("label.descriptionOptional") }}</base-label>
                <base-editor
                  v-model="collection.description"
                  min-height="10rem"
                />
                <!-- <editor v-model="collection.description" /> -->
              </div>
            </base-section>
            <base-section title="Collection type">
              <div class="col-xs-12">
                <base-radio
                  @update:model-value="onUpdateType"
                  v-model="collection.type"
                  :label="$t('label.manual')"
                  val="manual"
                >
                  <template v-slot:hint>
                    Add products to this collection one by one. Learn more about
                    <a href="javascript:void(0)">manual collections</a>.
                  </template>
                </base-radio>
                <base-radio
                  @update:model-value="onUpdateType"
                  v-model="collection.type"
                  :label="$t('label.automated')"
                  val="automated"
                >
                  <template v-slot:hint>
                    Existing and future products that match the conditions you
                    set will automatically be added to this collection. Learn
                    more about
                    <a href="javascript:void(0)">automated collections</a>.
                  </template>
                </base-radio>
                <div
                  v-if="collection.type === 'automated'"
                  class="conditions-box q-mt-sm"
                >
                  <div class="conditions-type">
                    <div class="text-label">
                      Conditions (products must match)
                    </div>
                    <div>
                      <q-option-group
                        v-model="collection.conditions_type"
                        @update:model-value="onUpdateConditionType"
                        inline
                        dense
                        size="sm"
                        :options="[
                          {
                            label: 'all conditions',
                            value: 'all',
                          },
                          {
                            label: 'any conditions',
                            value: 'any',
                          },
                        ]"
                      />
                    </div>
                  </div>
                  <div class="conditions-list q-mt-md">
                    <div
                      v-for="(condition, index) in collection.conditions"
                      :key="index"
                      class="q-mb-sm condition"
                    >
                      <q-item class="q-pa-none">
                        <q-item-section>
                          <q-select
                            map-options
                            options-dense
                            emit-value
                            v-model="condition.type"
                            :options="condition_types_options"
                            dense
                            outlined
                            debounce="500"
                            @update:model-value="onUpdateCondition"
                          />
                        </q-item-section>
                        <q-item-section>
                          <q-select
                            map-options
                            options-dense
                            emit-value
                            v-model="condition.relation"
                            :options="condition_relations_options"
                            dense
                            outlined
                            debounce="500"
                            @update:model-value="onUpdateCondition"
                          />
                        </q-item-section>
                        <q-item-section>
                          <q-input
                            map-options
                            options-dense
                            emit-value
                            v-model="condition.value"
                            :options="condition_relations_options"
                            dense
                            outlined
                            debounce="500"
                            @update:model-value="onUpdateCondition"
                          />
                        </q-item-section>
                        <q-item-section
                          style="padding-left: 8px"
                          v-if="collection.conditions.length > 1"
                          side
                        >
                          <q-btn
                            size="sm"
                            padding="11px"
                            outline
                            color="negative"
                            icon="fad fa-trash-alt"
                            dense
                            @click="onRemoveCondition(index)"
                          />
                        </q-item-section>
                      </q-item>
                    </div>
                    <q-btn
                      class="q-mt-sm"
                      no-caps
                      outline
                      :label="$t('label.addAnotherCondition')"
                      @click="
                        onAddCondition({
                          id: false,
                          type: 'title',
                          relation: 'EQUALS',
                          value: null,
                        })
                      "
                    />
                  </div>
                </div>
              </div>
            </base-section>
            <base-section
              v-if="
                !this.creating ||
                (this.creating && collection.type === 'automated')
              "
              title="Products"
              no-row
            >
              <div class="q-mb-md row q-col-gutter-md">
                <div v-if="collection.type === 'manual'" class="col">
                  <base-input
                    placeholder="Search products"
                    debounce="500"
                    @update:model-value="onBrowseProduct"
                  >
                    <template v-slot:after>
                      <q-btn
                        @click="onBrowseProduct('')"
                        no-caps
                        outline
                        padding="8px"
                        :style="{
                          width: '100px',
                        }"
                        color="primary"
                        :label="$t('label.browse')"
                      />
                    </template>
                  </base-input>
                </div>
                <div
                  :class="{
                    'col-auto': collection.type === 'manual',
                    col: collection.type !== 'manual',
                  }"
                >
                  <q-select
                    :style="{
                      width: collection.type !== 'manual' ? '100%' : '200px',
                    }"
                    prefix="Sort: "
                    map-options
                    options-dense
                    emit-value
                    v-model="pagination.sortBy"
                    :options="sortOptions"
                    dense
                    outlined
                    @update:model-value="onUpdateSortBy"
                  />
                </div>
              </div>
              <div>
                <base-table
                  :columns="columns"
                  :rows="rows"
                  :loading="pagination.loading"
                  :pagination="pagination"
                  hide-top
                  @request="onRequestProduct"
                  :no-data-label="$t('label.noProductAvaialble')"
                >
                  <template v-slot:body-cell-title="props">
                    <q-item class="q-pa-none" dense>
                      <q-item-section avatar>
                        <base-thumbnail
                          :size="40"
                          :media="props.row.thumbnail"
                        />
                      </q-item-section>
                      <q-item-section avatar>
                        <base-btn
                          @click.stop
                          link
                          size="12px"
                          tag="a"
                          :to="{
                            name: 'Single Product',
                            params: {
                              id: props.row.id,
                            },
                            query: {
                              action: 'edit',
                            },
                          }"
                        >
                          {{ props.value }}
                        </base-btn>
                      </q-item-section>
                    </q-item>
                  </template>
                  <template v-slot:body-cell-status="props">
                    <base-status :type="props.value" />
                  </template>
                </base-table>
              </div>
            </base-section>
            <seo-section
              prefix="http://yourshop.com/collections/"
              v-model="collection"
              message="Add a name and description to see how this collection might appear in a search engine listing"
            />
          </div>
        </div>
        <div class="col-xs-12 col-sm-3">
          <base-section no-row>
            <div class="q-gutter-md">
              <div>
                <base-label>{{ $t("label.collectionStatus") }}</base-label>
                <q-select
                  options-dense
                  v-model="collection.status"
                  :options="['Active', 'Draft']"
                  dense
                  outlined
                />
              </div>
              <div>
                <base-label>{{ $t("label.thumbnail") }}</base-label>
                <thumbnail-selector
                  :loadFromServer="true"
                  :dialog-label="$t('label.uploadthumbnail')"
                  hint="You can only choose images as collection thumbnail."
                  v-model="collection.thumbnail"
                />
              </div>
            </div>
          </base-section>
        </div>
      </div>
    </base-form>
    <skeleton-single-page v-else />
  </q-page>
</template>

<script>
import { cloneDeep } from "lodash";
import { mapActions, mapState } from "pinia";
// import Editor from "components/tiptap/editor";
import SeoSection from "components/product/SeoSection.vue";
import ThumbnailSelector from "components/FileSelector.vue";
import ProductSelector from "components/product/ProductSelector.vue";
import SkeletonSinglePage from "components/skeleton/SkeletonSinglePage.vue";
import { useCategoryStore } from "src/stores/product/category";
import { useProductStore } from "src/stores/product";
import { useCollectionStore } from "src/stores/product/collection";

const collection = {
  name: "",
  description: "",
  status: "Draft",
  type: "manual",
  conditions: [
    {
      id: false,
      type: "title",
      relation: "EQUALS",
      value: null,
    },
  ],
};

export default {
  components: {
    SeoSection,
    // Editor,
    ThumbnailSelector,
    SkeletonSinglePage,
  },
  data() {
    return {
      loaded: false,
      submited: false,
      default: cloneDeep(collection),
      collection: cloneDeep(collection),
      pagination: {
        sortBy: "TITLE_ASC",
        descending: false,
        page: 1,
        filter: "",
        rowsPerPage: 15,
        rowsNumber: 15,
        loaded: false,
        loading: false,
      },
      options: [],
      condition_types_options: [
        { label: "Product title", value: "title" },
        { label: "Product category", value: "category:name" },
        { label: "Product vendor", value: "vendor:name" },
        { label: "Product price", value: "variants:price" },
        { label: "Product tag", value: "tags:name" },
        { label: "Compare at price", value: "variants:compare_at_price" },
        { label: "Weight", value: "variants:weight" },
        { label: "Inventory stock", value: "stocks" },
        { label: "Variant’s title", value: "variants:title" },
      ],
      condition_relations_options: [
        { label: "is equal to", value: "EQUALS" },
        { label: "is not equal to", value: "NOT_EQUALS" },
        { label: "is greater than", value: "GREATER_THAN" },
        { label: "is less than", value: "LESS_THAN" },
        { label: "starts with", value: "STARTS_WITH" },
        { label: "ends with", value: "ENDS_WITH" },
        { label: "contains", value: "CONTAINS" },
        { label: "does not contain", value: "NOT_CONTAINS" },
        { label: "is not empty", value: "IS_NULL" },
        { label: "is empty", value: "IS_NOT_NULL" },
      ],
    };
  },
  methods: {
    ...mapState(useCategoryStore, { columns: "productColumns" }),
    ...mapActions(useCollectionStore, [
      "show",
      "store",
      "update",
      "addProducts",
    ]),
    ...mapActions(useProductStore, ["get"]),
    onSubmit(props) {
      console.func(
        "pages/admins/collections/CollectionPage:methods.onSubmit()",
        arguments
      );
      this.submited = true;
      const method = this.creating ? this.store : this.update;
      method(this.collection)
        .then(({ data, message }) => {
          this.submited = false;
          this.$q.notify(message);
          this.collection = data;
          this.default = cloneDeep(data);
          this.$router.push({
            name: "Single Collection",
            params: {
              id: data.id,
            },
            query: {
              action: "edit",
            },
          });
          this.onRequestProduct({
            pagination: this.pagination,
          });
        })
        .catch((error) => {
          this.submited = false;
          this.$core.error(error, { title: "Error" });
        });
    },
    onReset(props) {
      console.func(
        "pages/admins/collections/CollectionPage:methods.onReset()",
        arguments
      );
      this.loaded = false;
      setTimeout(() => {
        this.collection = cloneDeep(this.default);
        this.loaded = true;
        this.onRequestProduct({
          pagination: this.pagination,
        });
      }, 1);
    },
    onCancel(props) {
      console.func(
        "pages/admins/collections/CollectionPage:methods.onCancel()",
        arguments
      );
      this.$router.back();
    },
    onAddCondition(condition) {
      console.func(
        "pages/admins/collections/CollectionPage:method.onAddCondition()",
        arguments
      );
      this.collection.conditions.push(condition);
    },
    onRemoveCondition(index) {
      console.func(
        "pages/admins/collections/CollectionPage:method.onRemoveCondition()",
        arguments
      );
      this.collection.conditions.splice(index, 1);
      this.onRequestProduct({
        pagination: this.pagination,
      });
    },
    onRequestProduct(props) {
      console.func(
        "pages/admins/collections/CollectionPage:methods.onRequest()",
        arguments
      );
      const { page, rowsPerPage, sortBy, descending } = props.pagination;
      this.pagination = props.pagination;
      const args = {
        ...props.pagination,
        descending: descending ? "desc" : "asc",
        collection: this.collection.id,
      };
      if (this.collection.type === "automated") {
        args.conditions_type = this.collection.conditions_type;
        args.conditions = this.collection?.conditions
          ?.filter((item) => item.value)
          .map((item) => ({
            type: item.type,
            relation: item.relation,
            value: item.value,
          }));
      }
      this.get(args)
        .then(({ data, meta }) => {
          // clear out existing data and add new
          // this.products.rows = data;
          // update rowsCount with appropriate value
          this.pagination.rowsNumber = meta.total;

          // don't forget to update local pagination object
          this.pagination.page = page;
          this.pagination.rowsPerPage = rowsPerPage;
          this.pagination.sortBy = sortBy;
          this.pagination.descending = descending;
          this.pagination.loaded = true;
          this.pagination.from = meta.from;
          this.pagination.to = meta.to;

          // ...and turn of loading indicator
          this.pagination.loading = false;
        })
        .catch(() => {
          this.pagination.loading = false;
        });
    },
    onUpdateType(val) {
      console.func(
        "pages/admins/collections/CollectionPage:methods.onUpdateType()",
        arguments
      );
      this.collection.type = val;
      this.onRequestProduct({
        pagination: this.pagination,
      });
    },
    onUpdateConditionType(val) {
      console.func(
        "pages/admins/collections/CollectionPage:methods.onUpdateConditionType()",
        arguments
      );
      this.collection.conditions_type = val;
      this.onRequestProduct({
        pagination: this.pagination,
      });
    },
    onUpdateCondition() {
      console.func(
        "pages/admins/collections/CollectionPage:methods.onUpdateCondition()",
        arguments
      );
      this.onRequestProduct({
        pagination: this.pagination,
      });
    },
    onUpdateSortBy() {
      console.func(
        "pages/admins/collections/CollectionPage:methods.onUpdateSortBy()",
        arguments
      );
      this.onRequestProduct({
        pagination: this.pagination,
      });
    },
    onBrowseProduct(value) {
      console.func(
        "pages/admins/collections/CollectionPage:methods.onBrowseProduct()",
        arguments
      );
      this.$q
        .dialog({
          component: ProductSelector,
          componentProps: {
            query: value,
          },
        })
        .onOk((payload) => {
          console.func(
            "pages/admins/collections/CollectionPage:methods.onBrowseProduct.onOk()",
            payload
          );
          this.addProducts({
            id: this.collection.id,
            products: payload,
          })
            .then(({ message }) => {
              this.$q.notify(message);
              this.pagination.page = 1;
              this.onRequestProduct({
                pagination: this.pagination,
              });
            })
            .catch((error) => {
              this.$core.error(error, { title: "Error" });
            });
        });
    },
  },
  mounted() {
    if (!this.creating) {
      this.show(this.id)
        .then((collection) => {
          this.collection = collection;
          this.default = cloneDeep(collection);
          this.loaded = true;
          this.onRequestProduct({
            pagination: this.pagination,
          });
        })
        .catch((error) => {
          this.$core.error(error, { title: this.$t("dialog.title.error") });
        });
    } else {
      this.loaded = true;
      this.pagination.loaded = true;
      this.pagination.loading = false;
    }
  },
  computed: {
    ...mapState(useProductStore, ["rows", "sortOptions"]),
    ...mapState(useCategoryStore, { columns: "productColumns" }),
    edit() {
      return ["add", "edit"].includes(this.action) || this.creating;
    },
    creating() {
      return this.id === "add";
    },
    id() {
      return this.$route.params.id;
    },
    action() {
      return this.$route.query.action;
    },
    disable() {
      return (
        this.default &&
        JSON.stringify(this.collection) === JSON.stringify(this.default)
      );
    },
    resetable() {
      return (
        this.default &&
        JSON.stringify(this.collection) !== JSON.stringify(this.default)
      );
    },
  },
};
</script>
