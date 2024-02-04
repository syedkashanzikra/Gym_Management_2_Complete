<template>
  <base-dialog
    :title="$t(title)"
    body-class="q-pa-none scroll"
    content-style="width: 950px; max-width: 95vw"
    ref="dialog"
    @hide="onDialogHide"
    use-separator
    hide-footer
    persistent
  >
    <template v-slot:body>
      <base-section flat>
        <div class="col-xs-12 col-sm-2">
          <base-label>{{ $t("title") }}</base-label>
          <base-select
            v-model="member.title"
            dense
            outlined
            :options="['Mr', 'Mrs', 'Ms', 'Miss', 'Mx', 'Dr', 'Fr', 'Prof']"
          />
        </div>
        <div class="col-xs-12 col-sm-3">
          <base-label>{{ $t("firstName") }}</base-label>
          <base-input v-model="member.first_name" />
        </div>
        <div class="col-xs-12 col-sm-3">
          <base-label>{{ $t("surname") }}</base-label>
          <base-input v-model="member.last_name" />
        </div>
        <div class="col-xs-12 col-sm-4">
          <base-label>{{ $t("emailAddress") }}</base-label>
          <base-input v-model="member.email" />
        </div>
        <div class="col-xs-12 col-sm-3">
          <base-label>{{ $t("phoneNumber") }}</base-label>
          <base-input v-model="member.phone_number" />
        </div>
        <div class="col-xs-12 col-sm-3">
          <base-label>{{ $t("label.status") }}</base-label>
          <q-select
            dense
            outlined
            v-model="member.status"
            :options="['Active', 'Pending', 'Deactive', 'Lost']"
          />
        </div>
        <div class="col-xs-12">
          <address-fields v-model="member.address" />
        </div>
        <div class="col-xs-12 col-sm-12">
          <base-label>{{ $t("specialNote") }}</base-label>
          <base-input v-model="member.special_note" type="textarea" />
        </div>
        <div class="text-right q-gutter-x-sm col-xs-12">
          <base-btn
            class="main-btn"
            v-if="member?.latest_invoice?.status === 'open'"
            color="positive"
            icon="fas fa-money-bill-1"
            :label="$t('collectPayment')"
            @click="onCollectPayment"
            :loading="paymentLoading"
          />
          <base-btn
            class="main-btn"
            v-if="isEnquiry"
            color="secondary"
            :label="$t('saveAsMember')"
            @click="onSaveAsMember"
          />
          <base-btn
            class="main-btn"
            color="primary"
            :label="$t('update')"
            :disable="disable"
            @click="onSubmit"
          />
        </div>
        <div v-if="isEnquiry" class="col-xs-12">
          <q-expansion-item
            header-class="bg-grey-3"
            icon="fas fa-clipboard-list-check"
            :label="$t('parq')"
            @show="parq = true"
            @hide="parq = false"
          >
            <parq-form
              v-if="parq"
              class="q-pa-md border-all"
              v-model:member="member"
            />
          </q-expansion-item>
        </div>
      </base-section>
      <base-section
        flat
        :title="$t('notes')"
        :description="$t('members.activityDesc')"
        no-row
        body-class="q-px-lg"
        head-class="q-pt-none"
      >
        <q-timeline class="comments" color="secondary">
          <base-note-card
            :module-action="notes"
            :module-id="member.id"
            class="comment"
            creating
            @create="onCreateNote"
          />
          <base-note-card
            class="comment"
            v-for="note in member.notes"
            :key="note.id"
            v-bind="note"
            :module-id="member.id"
            :user="note.admin"
          />
        </q-timeline>
      </base-section>
    </template>
    <template v-slot:loading>
      <q-inner-loading :showing="loading">
        <q-spinner-oval size="50px" color="primary" />
      </q-inner-loading>
    </template>
  </base-dialog>
</template>

<script>
import { cloneDeep } from "lodash";
import { mapActions, mapState } from "pinia";
import { useMemberStore } from "stores/member";
import { useAppStore } from "stores/app";
import ParqForm from "./ParqForm.vue";
import AddressFields from "./address/AddressFields.vue";

export default {
  components: { ParqForm, AddressFields },
  data() {
    return {
      member: {
        address: {},
      },
      default: {
        address: {},
      },
      loading: true,
      parq: false,
      paymentLoading: false,
    };
  },
  props: {
    title: {
      type: String,
      default: "memberInfo",
    },
    id: [Number, String],
    isEnquiry: Boolean,
  },
  emits: ["ok", "hide"],
  methods: {
    ...mapActions(useMemberStore, {
      showMember: "show",
      update: "update",
      notes: "notes",
      markAsPaid: "markAsPaid",
    }),
    async show() {
      console.func("components/MemberDialog:methods.show()", arguments);
      this.loading = true;
      this.$refs.dialog.show();
      this.showMember(this.id)
        .then((member) => {
          this.member = member;
          this.default = cloneDeep(member);
          this.loading = false;
        })
        .catch((error) => {
          this.$core.error(error, { title: this.$t("dialog.title.error") });
          this.hide();
          this.loading = false;
        });
    },
    hide() {
      console.func("components/MemberDialog:methods.close()", arguments);
      this.$refs.dialog.hide();
    },
    onDialogHide() {
      console.func("components/MemberDialog:methods.onDialogHide()", arguments);
      this.$emit("hide");
    },
    onDone() {
      console.func("components/MemberDialog:methods.onDone()", arguments);
      this.$emit("ok");
      this.hide();
    },
    onSubmit(props) {
      console.func("components/MemberDialog:methods.onSubmit()", arguments);
      this.loading = true;
      this.update(this.member)
        .then(({ message }) => {
          this.default = cloneDeep(this.member);
          this.loading = false;
          this.$q.notify(message);
        })
        .catch((error) => {
          this.loading = false;
          this.$core.error(error, { title: this.$t("dialog.title.error") });
        });
    },
    onSaveAsMember() {
      console.func(
        "components/MemberDialog:methods.onSaveAsMember()",
        arguments
      );
      this.loading = true;
      this.update({
        ...this.member,
        status: "Active",
        is_enquiry: false,
      })
        .then(({ message }) => {
          this.loading = false;
          this.$core.success(message);
          this.onDone();
        })
        .catch((error) => {
          this.loading = false;
          this.$core.error(error, { title: this.$t("dialog.title.error") });
        });
    },
    onCreateNote(props) {
      console.func("components/MemberDialog:methods.onCreateNote()", arguments);
      if (props) {
        this.member.notes.splice(0, 0, props);
      } else {
        this.showMember(this.id).then((member) => {
          this.member = member;
          this.default = cloneDeep(member);
          this.loading = false;
        });
      }
    },
    async onCollectPayment(props) {
      console.func(
        "components/MemberDialog:methods.onCollectPayment()",
        arguments
      );
      this.paymentLoading = true;
      await this.markAsPaid(this.member)
        .then(() => {
          this.show();
        })
        .catch(() => {});
      this.paymentLoading = false;
    },
  },
  computed: {
    ...mapState(useAppStore, ["user"]),
    disable() {
      return (
        this.default &&
        JSON.stringify(this.member) === JSON.stringify(this.default)
      );
    },
  },
};
</script>
