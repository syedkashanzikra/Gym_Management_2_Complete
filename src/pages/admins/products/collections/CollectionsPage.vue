<template>
  <q-page padding>
    <base-section flat bordered no-row>
      <base-table
        ref="collection"
        store="collection"
        :module="module"
        :columns="columns"
        :rows="rows"
        :actions="actions"
        :toolbar="toolbar"
        :filters="filters"
        :loading="loading"
        :pagination="pagination"
        @request="onRequest"
        selection="multiple"
        @action-clicked="actionClicked"
        @toolbar-clicked="toolbarClicked"
        @row-clicked="rowClicked"
        :no-data-label="$t('label.noCollectionAvaialble')"
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
                  name: 'Single Collection',
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
import { useCollectionStore } from "src/stores/product/collection";
import { mapActions, mapState } from "pinia";
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
        advancedFilter: {},
        deleted: false,
        rowsPerPage: 15,
        rowsNumber: 15,
        loaded: false,
      },
    };
  },
  methods: {
    ...mapActions(useCollectionStore, ["get"]),
    onRequest(props) {
      console.func(
        "pages/admins/collections/CollectionsPage:methods.onRequest()",
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
          this.$emit("error", error);
        });
    },
    async actionClicked(action, row) {
      console.func(
        "pages/admins/collections/CollectionsPage:methods.actionClicked()",
        arguments
      );
    },
    async toolbarClicked(action, row) {
      console.func(
        "pages/admins/collections/CollectionsPage:methods.toolbarClicked()",
        arguments
      );
    },
    async rowClicked(evt, row) {
      console.func(
        "pages/admins/collections/CollectionsPage:methods.rowClicked()",
        arguments
      );
      this.$router.push({
        name: "Single Collection",
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
    ...mapState(useCollectionStore, [
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
