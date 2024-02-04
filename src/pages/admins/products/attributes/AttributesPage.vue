<template>
  <q-page padding>
    <base-section flat bordered no-row>
      <base-table
        ref="attribute"
        store="attribute"
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
        :no-data-label="$t('label.noAttributeAvaialble')"
        v-model:selected="selected"
      >
        <template v-slot:body-cell-name="props">
          <base-btn
            @click.stop
            link
            size="12px"
            tag="a"
            :to="{
              name: 'Single Attribute',
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
        </template>
      </base-table>
    </base-section>
  </q-page>
</template>

<script>
import { mapActions, mapState } from "pinia";
import { useAttributeStore } from "src/stores/product/attribute";
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
    ...mapActions(useAttributeStore, ["get"]),
    onRequest(props) {
      console.func(
        "pages/admins/attributes/AttributesPage.vue:methods.onRequest()",
        arguments
      );
      const { page, rowsPerPage, sortBy, descending } = props.pagination;
      this.loading = true;
      this.pagination = props.pagination;

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
        "pages/admins/attributes/AttributesPage.vue:methods.actionClicked()",
        arguments
      );
    },
    async toolbarClicked(action, row) {
      console.func(
        "pages/admins/attributes/AttributesPage.vue:methods.toolbarClicked()",
        arguments
      );
    },
    async rowClicked(evt, row) {
      console.func(
        "pages/admins/attributes/AttributesPage.vue:methods.rowClicked()",
        arguments
      );
      this.$router.push({
        name: "Single Attribute",
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
    ...mapState(useAttributeStore, [
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
