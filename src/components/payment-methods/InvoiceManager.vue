<template>
  <base-section
    flat
    bordered
    no-row
    :title="$t('invoices.historyTitle')"
    :description="$t('invoices.historyDesc')"
    class="invoice-manager"
  >
    <base-table
      :rowsPerPageOptions="[0]"
      :rows="invoices"
      :columns="columns"
      :no-data-label="$t('invoices.noData')"
      v-model:pagination="pagination"
      @request="onRequest"
      :loading="loading"
      hide-top
      has-actions
    >
      <template v-slot:body-cell-status="props">
        <base-status :type="props.row.status" />
      </template>
      <template v-slot:actions="props">
        <base-btn
          link
          color="grey"
          :loading="props.row.loading"
          @click="onViewInvoice(props)"
          :label="$t('invoices.viewLabel')"
        />
      </template>
    </base-table>
  </base-section>
</template>

<script>
import { mapActions, mapState } from "pinia";
import { useSubscriptionStore } from "stores/subscription";
import PdfViewer from "components/PdfViewer.vue";

export default {
  data() {
    return {
      loading: false,
      columns: [
        {
          name: "date",
          label: this.$t("label.date"),
          field: "date",
          align: "left",
        },
        {
          name: "amount",
          label: this.$t("label.amount"),
          field: "amount",
          align: "left",
        },
        {
          name: "status",
          label: this.$t("label.status"),
          field: "status",
          align: "left",
        },
        {
          name: "actions",
          label: "",
          field: "actions",
          align: "right",
        },
      ],
      pagination: {
        page: 1,
        rowsPerPage: 10,
        rowsNumber: 10,
        loaded: false,
      },
    };
  },
  methods: {
    ...mapActions(useSubscriptionStore, ["getInvoices", "downloadInvoice"]),
    onRequest(args) {
      console.func(
        "components/payment-methods/InvoiceManager:methods.onRequest()",
        arguments
      );
      this.loading = true;
      const { page, rowsPerPage } = args.pagination;
      this.getInvoices({
        page,
        rowsPerPage,
      })
        .then((response) => {
          // update rowsCount with appropriate value
          this.pagination.rowsNumber = response.total;

          // don't forget to update local pagination object
          this.pagination.page = page;
          this.pagination.rowsPerPage = rowsPerPage;
          this.pagination.from = response.from;
          this.pagination.to = response.to;
          this.pagination.loaded = true;

          // ...and turn of loading indicator
          this.loading = false;
        })
        .catch((error) => {
          this.$emit("error", error);
        });
    },
    onViewInvoice(args) {
      console.func(
        "components/payment-methods/InvoiceManager:methods.onViewInvoice()",
        arguments
      );
      args.row.loading = true;
      this.downloadInvoice(args?.row.stripe_id)
        .then((data) => {
          args.row.loading = false;
          const blob = new Blob([data], { type: "application/pdf" });

          this.$q
            .dialog({
              component: PdfViewer,
              componentProps: {
                title: `Invoice-${args?.row.number}.pdf`,
                url: URL.createObjectURL(blob),
              },
            })
            .onOk((payload) => {
              URL.revokeObjectURL(payload);
            });
        })
        .catch((error) => {
          args.row.loading = false;
          this.$core.error(error, { title: this.$t("dialog.title.error") });
        });
    },
    onLoad() {
      console.func(
        "components/payment-methods/InvoiceManager:methods.onLoad()",
        arguments
      );
      this.onRequest({
        pagination: this.pagination,
      });
    },
  },
  computed: {
    ...mapState(useSubscriptionStore, ["invoices"]),
  },
};
</script>
