<template>
  <q-page padding>
    <base-section flat bordered no-row>
      <base-table
        :store="useCategoryStore"
        :module="module"
        :columns="columns"
        :rows="rows"
        :actions="actions"
        :toolbar="toolbar"
        :loading="loading"
        :pagination="pagination"
        @request="onRequest"
        selection="multiple"
        @action-clicked="actionClicked"
        @toolbar-clicked="toolbarClicked"
        @row-clicked="rowClicked"
        :no-data-label="$t('label.noCategoryAvaialble')"
        table-key="category"
        v-model:selected="selected"
      >
        <template v-slot:body-cell-name="props">
          <q-item class="q-pa-none" dense>
            <q-item-section avatar>
              <base-thumbnail :size="40" :media="props.row.thumbnail" />
            </q-item-section>
            <q-item-section avatar>
              <base-btn
                @click.stop
                link
                size="12px"
                tag="a"
                :to="{
                  name: 'Single Category',
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
    </base-section>
  </q-page>
</template>

<script>
import { mapActions, mapState } from "pinia";
import { useCategoryStore } from "src/stores/product/category";

export default {
  data() {
    return {
      loading: false,
      loaded: false,
      selected: [],
      pagination: {
        sortBy: "name",
        descending: false,
        page: 1,
        filter: "",
        deleted: false,
        rowsPerPage: 15,
        rowsNumber: 15,
        loaded: false,
      },
      useCategoryStore: useCategoryStore(),
    };
  },
  methods: {
    ...mapActions(useCategoryStore, ["get"]),
    onRequest(props) {
      console.func(
        "pages/admins/categories/CategoriesPage:methods.onRequest()",
        arguments
      );
      const { page, rowsPerPage, sortBy, descending } = props.pagination;
      this.pagination = props.pagination;
      this.loading = true;

      this.get({
        ...props.pagination,
        descending: descending ? "desc" : "asc",
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
    async actionClicked(action, row) {
      console.func(
        "pages/admins/categories/CategoriesPage:methods.actionClicked()",
        arguments
      );
    },
    async toolbarClicked(action, row) {
      console.func(
        "pages/admins/categories/CategoriesPage:methods.toolbarClicked()",
        arguments
      );
    },
    async rowClicked(evt, row) {
      console.func(
        "pages/admins/categories/CategoriesPage:methods.rowClicked()",
        arguments
      );
      this.$router.push({
        name: "Single Category",
        params: {
          id: row.id,
        },
        query: {
          action: "edit",
        },
      });
    },
  },
  computed: {
    ...mapState(useCategoryStore, [
      "actions",
      "rows",
      "columns",
      "module",
      "toolbar",
      "filters",
    ]),
  },
};
</script>
