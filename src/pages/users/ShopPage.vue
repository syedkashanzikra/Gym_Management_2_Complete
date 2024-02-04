<template>
  <q-page padding>
    <base-info rounded class="q-mb-md">
      {{ $t("shopNotice") }}
    </base-info>
    <base-section flat bordered no-row>
      <base-table
        :columns="columns"
        :rows="rows"
        :toolbar="toolbar"
        :loading="loading"
        :pagination="pagination"
        @request="onRequest"
        @row-clicked="rowClicked"
        :no-data-label="$t('products.noData')"
        grid
      >
        <template v-slot:item="props">
          <div class="q-table__grid-item cursor-pointer col-xs-12 col-sm-3">
            <q-card
              @click.stop="rowClicked($event, props.row)"
              flat
              bordered
              v-bind:props="props"
              class=""
            >
              <product-thumbnail :media="props.row.thumbnail" />
              <q-card-section>
                <base-btn
                  @click.stop
                  :to="{
                    name: 'Product',
                    params: {
                      slug: props.row.slug,
                      title: props.row.title,
                    },
                  }"
                  size="lg"
                  link
                >
                  <div class="ellipsis-2-lines">{{ props.row.title }}</div>
                </base-btn>
                <div class="text-subtitle2">
                  {{ props.row.price }}
                </div>
              </q-card-section>
            </q-card>
          </div>
        </template>
      </base-table>
    </base-section>
  </q-page>
</template>

<script>
import { mapState, mapActions } from "pinia";
import { useShopStore } from "stores/shop";
import ProductThumbnail from "components/ProductThumbnail.vue";

export default {
  components: { ProductThumbnail },
  data() {
    return {
      loading: false,
      loaded: false,
      pagination: {
        sortBy: "CREATED_AT_DESC",
        descending: false,
        page: 1,
        filter: "",
        advancedFilter: {},
        deleted: false,
        rowsPerPage: 12,
        rowsNumber: 12,
        active: true,
        loaded: false,
      },
    };
  },
  methods: {
    ...mapActions(useShopStore, ["products", "addToCart"]),
    onRequest(props) {
      console.func("admins/products/ShopPage:methods.onRequest()", arguments);
      const { page, rowsPerPage, sortBy, descending } = props.pagination;
      this.pagination = props.pagination;
      this.loading = true;

      this.products({
        ...props.pagination,
        direction: descending ? "desc" : "asc",
      })
        .then(({ meta }) => {
          // clear out existing data and add new
          // this.rows = this.tableData;
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
          this.loading = false;
        })
        .catch((error) => {
          this.$core.error(error, { title: this.$t("dialog.title.error") });
        });
    },
    async rowClicked(evt, row) {
      console.func("admins/products/ShopPage:methods.rowClicked()", arguments);
      this.$router.push({
        name: "Product",
        params: {
          slug: row.slug,
          title: row.title,
        },
      });
    },
  },
  computed: {
    ...mapState(useShopStore, ["rows", "columns", "toolbar"]),
  },
};
</script>

<style lang="scss">
.toolbar-item .q-item {
  padding: 9.86px 20px;
  font-size: 13px;
}
</style>
