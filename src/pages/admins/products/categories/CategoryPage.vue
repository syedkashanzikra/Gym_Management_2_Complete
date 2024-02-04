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
              description="A great category title and description has the power to turn a casual shopper into a revenue-generating buyer."
            >
              <div class="col-xs-12">
                <base-label>{{ $t("label.name") }}</base-label>
                <base-input v-model="category.name" />
              </div>
              <div class="col-xs-12">
                <base-label>{{ $t("label.descriptionOptional") }}</base-label>
                <base-editor
                  v-model="category.description"
                  min-height="10rem"
                />
                <!-- <editor v-model="category.description" /> -->
              </div>
            </base-section>
            <base-section v-if="!this.creating" title="Products" no-row>
              <div class="q-mb-md row q-col-gutter-md">
                <div class="col">
                  <base-select
                    prefix="Sort: "
                    map-options
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
              prefix="https://yourshop.com/categories/"
              v-model="category"
              message="Add a name and description to see how this category might appear in a search engine listing"
            />
          </div>
        </div>
        <div class="col-xs-12 col-sm-3">
          <base-section no-row>
            <div class="q-gutter-md">
              <div>
                <base-label>{{ $t("label.categoryStatus") }}</base-label>
                <base-select
                  v-model="category.status"
                  :options="['Active', 'Draft']"
                  dense
                  outlined
                />
              </div>
              <div>
                <base-label>{{ $t("label.parent") }}</base-label>
                <category-select v-model="category.parent" hint="" />
              </div>
              <div>
                <base-label>{{ $t("label.thumbnail") }}</base-label>
                <thumbnail-selector
                  :loadFromServer="true"
                  :dialog-label="$t('label.uploadthumbnail')"
                  icon="fad fa-image"
                  hint="You can only choose images as category thumbnail."
                  v-model="category.thumbnail"
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
import CategorySelect from "components/product/CategorySelect.vue";
// import Editor from 'components/tiptap/editor';
import SeoSection from "components/product/SeoSection.vue";
import ThumbnailSelector from "components/FileSelector.vue";
import SkeletonSinglePage from "components/skeleton/SkeletonSinglePage.vue";
import { useCategoryStore } from "src/stores/product/category";
import { useProductStore } from "src/stores/product";

const category = {
  name: "",
  description: "",
  status: "Draft",
};

export default {
  components: {
    CategorySelect,
    SeoSection,
    SkeletonSinglePage,
    ThumbnailSelector,
  },
  data() {
    return {
      loaded: false,
      submited: false,
      default: cloneDeep(category),
      category: cloneDeep(category),
      options: [],
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
    };
  },
  methods: {
    ...mapActions(useCategoryStore, ["show", "store", "update"]),
    ...mapActions(useProductStore, ["get"]),
    onSubmit(props) {
      console.func("pages/categories/category:methods.onSubmit()", arguments);
      this.submited = true;
      const method = this.creating ? this.store : this.update;
      method(this.category)
        .then(({ data, message }) => {
          this.submited = false;
          this.$q.notify(message);
          this.category = data;
          this.default = cloneDeep(data);
          this.pagination.loading = false;
          this.pagination.loaded = true;
          this.$router.push({
            name: "Single Category",
            params: {
              id: data.id,
            },
            query: {
              action: "edit",
            },
          });
        })
        .catch((error) => {
          this.submited = false;
          this.$core.error(error, { title: this.$t("dialog.title.error") });
        });
    },
    onReset(props) {
      console.func("pages/categories/category:methods.onReset()", arguments);
      this.loaded = false;
      setTimeout(() => {
        this.category = cloneDeep(this.default);
        this.loaded = true;
      }, 1);
    },
    onCancel(props) {
      console.func("pages/categories/category:methods.onCancel()", arguments);
      this.$router.back();
    },
    onRequestProduct(props) {
      console.func("pages/categories/category:methods.onRequest()", arguments);
      const { page, rowsPerPage, sortBy, descending } = props.pagination;
      this.pagination = props.pagination;
      this.pagination.loading = true;
      this.get({
        ...props.pagination,
        descending: descending ? "desc" : "asc",
        category: this.category.id,
      })
        .then(({ meta }) => {
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
    onUpdateSortBy() {
      console.func(
        "pages/categories/category:methods.onUpdateSortBy()",
        arguments
      );
      this.onRequestProduct({
        pagination: this.pagination,
      });
    },
  },
  mounted() {
    if (!this.creating) {
      this.show(this.id)
        .then((category) => {
          this.category = category;
          this.default = cloneDeep(category);
          this.loaded = true;
        })
        .catch((error) => {
          this.$core.error(error, { title: this.$t("dialog.title.error") });
        });
    } else {
      this.loaded = true;
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
        JSON.stringify(this.category) === JSON.stringify(this.default)
      );
    },
    resetable() {
      return (
        this.default &&
        JSON.stringify(this.category) !== JSON.stringify(this.default)
      );
    },
  },
};
</script>
