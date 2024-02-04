<template>
  <base-table
    ref="bookings"
    :rows="rows"
    :columns="columns"
    hide-top
    hide-pagination
    has-actions
    :rows-per-page-options="[0]"
    :no-data-label="$t('bookings.noData')"
    loaded
  >
    <template v-slot:body-cell-sn="props">
      {{ props.rowIndex + 1 }}
    </template>
    <template v-slot:body-cell-user="props">
      <base-select
        :readonly="hasSignOff"
        :bg-color="props.row.source ? 'green-1' : ''"
        :placeholder="$t('placeholder.selectMember')"
        dense
        borderless
        v-model="props.row.user"
        :filter-method="getMember"
        map-options
        use-filter
        :option-label="(opt) => `${opt.name} (${opt.id})`"
        :query="
          (val) => ({
            filter: val,
            rowsPerPage: 10,
            option: true,
            blocked: true,
          })
        "
        @update:model-value="onChange"
      >
        <template v-if="props.row.user" #after>
          <base-btn
            link
            size="md"
            icon="fas fa-arrow-up-right-from-square"
            color="primary"
            :to="{
              name: 'Single Member',
              params: {
                id: props.row.user.id,
              },
              query: {
                action: 'edit',
              },
            }"
          />
          <base-btn
            link
            size="md"
            v-if="useMessage"
            class="q-ml-sm"
            color="primary"
            icon="fas fa-message"
            :to="{
              name: 'Single Enquiry',
              params: {
                id: 'add',
              },
              query: {
                user: props.row.user.id,
              },
            }"
          />
        </template>
      </base-select>
    </template>
    <template v-slot:body-cell-attendance="props">
      <q-checkbox :disable="hasSignOff" dense v-model="props.row.attendance" />
    </template>
    <template v-slot:actions="props">
      <q-btn
        :disable="hasSignOff"
        @click.stop="onRemove(props)"
        size="sm"
        round
        flat
        color="grey"
        icon="fas fa-trash-can"
      />
    </template>
  </base-table>
</template>

<script>
import { mapActions } from "pinia";
import { useMemberStore } from "stores/member";

export default {
  props: {
    rows: Array,
    allBookings: Array,
    useMessage: Boolean,
    hasSignOff: Boolean,
  },
  data() {
    return {
      columns: [
        {
          name: "sn",
          align: "left",
          label: this.$t("label.sn"),
          field: "sn",
          style: "width: 10px",
          sortable: false,
        },
        {
          name: "user",
          align: "left",
          label: this.$t("label.name"),
          field: "user",
          style: "width: 100px",
          sortable: false,
        },
        {
          name: "user_status",
          align: "left",
          label: this.$t("label.status"),
          field: "user",
          format: (val) => (val ? val.status : ""),
          style: "width: 100px",
          sortable: false,
        },
        {
          name: "user_email",
          align: "left",
          label: this.$t("label.email"),
          field: "user",
          format: (val) => (val ? val.email : ""),
          style: "width: 100px",
          sortable: false,
        },
        {
          name: "user_phone_number",
          align: "left",
          label: this.$t("label.phone"),
          field: "user",
          format: (val) => (val ? val.phone_number : ""),
          style: "width: 100px",
          sortable: false,
        },
        {
          name: "attendance",
          align: "center",
          label: this.$t("label.attendance"),
          field: "attendance",
          format: (val) => (val ? "Yes" : "No"),
          style: "width: 10px",
          sortable: false,
        },
        {
          name: "actions",
          align: "right",
          label: "",
          field: "actions",
        },
      ],
    };
  },
  emits: ["update:rows"],
  methods: {
    ...mapActions(useMemberStore, {
      getMember: "options",
    }),
    onRemove({ rowIndex }) {
      console.func("components/ClassBookings:methods.onRemove()", arguments);
      const rows = this.rows;
      rows.splice(rowIndex, 1);
      this.$emit("update:rows", rows);
    },
    onChange(props) {
      console.func("components/ClassBookings:methods.onChange()", arguments);
      const booking = this.allBookings.filter(
        (item) => item.user.id === props.id
      );
      if (booking.length > 1) {
        this.$core.warning(this.$t("dialog.info.alreadyBooked"));
      }
    },
  },
};
</script>
