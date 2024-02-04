<template>
  <q-page padding>
    <base-section flat bordered no-row>
      <base-table
        :store="useEnquiryStore"
        :module="module"
        :actions="actions"
        :columns="getColumns"
        :rows="rows"
        :toolbar="getToolbar"
        :filters="filters"
        :loading="loading"
        :pagination="pagination"
        @request="onRequest"
        @toolbar-clicked="toolbarClicked"
        @row-clicked="rowClicked"
        :no-data-label="$t('enquiries.noData')"
        table-key="enquiries"
      >
        <template v-slot:body-cell-id="props">
          <base-btn
            @click.stop
            link
            tag="a"
            align="left"
            :to="{
              name: 'Single Enquiry',
              params: {
                id: props.row.id,
              },
              query: {
                action: 'edit',
                title: props.row.subject || $t('contactUs'),
              },
            }"
            :style="props.col.style"
          >
            <div class="ellipsis">{{ props.value }}</div>
          </base-btn>
          <base-btn
            v-show="props.row.message"
            class="q-ml-xs"
            size="md"
            link
            color="black"
            icon="fal fa-align-left"
            @click.stop
          >
            <q-popup-proxy>
              <q-banner style="width: 350px">
                <base-label>{{ $t("message") }}</base-label>
                <span v-html="props.row.message"></span>
              </q-banner>
            </q-popup-proxy>
          </base-btn>
        </template>
        <template v-slot:body-cell-name="props">
          <template v-if="props.row.user">
            <base-btn
              @click.stop
              link
              size="12px"
              tag="a"
              :to="{
                name: 'Single Member',
                params: {
                  id: props.row.user.id,
                },
                query: {
                  action: 'edit',
                },
              }"
              :label="props.value"
            />
          </template>
          <template v-else>
            {{ props.value }}
          </template>
        </template>
        <template v-slot:body-cell-status="props">
          <base-status :type="props.value" />
        </template>
        <template v-slot:body-cell-is_archived="props">
          <q-checkbox
            @update:model-value="changeArchived(props.row)"
            size="sm"
            dense
            :model-value="props.row.is_archived"
            color="green"
          />
        </template>
        <template v-slot:body-cell-user_archived="props">
          <q-checkbox
            @update:model-value="changeUserArchived(props.row)"
            size="sm"
            dense
            :model-value="props.row.user_archived"
            color="green"
          />
        </template>
        <template v-slot:body-cell-last_reply="props">
          <base-btn
            v-show="props.row.last_reply"
            class="q-mr-xs"
            size="md"
            link
            color="black"
            icon="fal fa-align-left"
            @click.stop
          >
            <q-popup-proxy>
              <q-banner style="width: 350px">
                <base-label>{{ $t("lastMessage") }}</base-label>
                <span v-html="$core.nl2br(props.row.last_reply.message)"></span>
              </q-banner>
            </q-popup-proxy>
          </base-btn>
          <span>{{ props.value }}</span>
        </template>
      </base-table>
    </base-section>
  </q-page>
</template>

<script>
import { mapState, mapActions } from "pinia";
import { useEnquiryStore } from "stores/enquiry";

export default {
  data() {
    return {
      loading: false,
      loaded: false,
      pagination: {
        sortBy: "last_reply",
        descending: true,
        page: 1,
        filter: "",
        status: this.$route.meta.status,
        advancedFilter: {},
        deleted: false,
        rowsPerPage: 15,
        rowsNumber: 15,
        loaded: false,
      },
      useEnquiryStore: useEnquiryStore(),
    };
  },
  methods: {
    ...mapActions(useEnquiryStore, [
      "get",
      "changeArchived",
      "changeUserArchived",
    ]),
    onRequest(props) {
      console.func(
        "pages/core/enquiries/EnquiriesPage:methods.onRequest()",
        arguments
      );
      const { page, rowsPerPage, sortBy, descending } = props.pagination;
      this.pagination = props.pagination;
      this.loading = true;

      this.get({
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
    async actionClicked(action, row) {
      console.func(
        "pages/core/enquiries/EnquiriesPage:methods.actionClicked()",
        arguments
      );
    },
    async toolbarClicked(action, row) {
      console.func(
        "pages/core/enquiries/EnquiriesPage:methods.toolbarClicked()",
        arguments
      );
    },
    async rowClicked(evt, row) {
      console.func(
        "pages/core/enquiries/EnquiriesPage:methods.rowClicked()",
        arguments
      );
      this.$router.push({
        name: "Single Enquiry",
        params: {
          id: row.id,
        },
        query: {
          action: "edit",
          title: row.subject || "Contact us",
        },
      });
    },
  },
  computed: {
    ...mapState(useEnquiryStore, [
      "actions",
      "rows",
      "columns",
      "module",
      "toolbar",
      "filters",
    ]),
    getColumns() {
      return this.columns.filter((item) => item.guard.includes(this.guard));
    },
    guard() {
      return this.$route.meta.guard;
    },
    getToolbar() {
      return this.toolbar.filter((item) => item.guard.includes(this.guard));
    },
    isAdmin() {
      return this.guard === "admins";
    },
  },
};
</script>
