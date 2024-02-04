<template>
  <q-page padding>
    <base-page-header
      class="q-mb-md"
      :title="creating ? $t('createNew') : member.name"
      :hint="$t('hint.member')"
      :tabs="['Overview', 'Billing']"
      v-model="tab"
      :no-tabs="creating"
    />
    <q-tab-panels class="base-tab-panels" v-model="tab">
      <q-tab-panel name="overview">
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
            <div class="col-xs-12 col-sm-8">
              <div class="q-gutter-md">
                <base-section
                  flat
                  bordered
                  :title="$t('generalInformation')"
                  :description="$t('members.generalDesc')"
                >
                  <div class="col-xs-12 col-sm-4">
                    <base-label>{{ $t("title") }}</base-label>
                    <base-select
                      v-model="member.title"
                      dense
                      outlined
                      :options="[
                        'Mr',
                        'Mrs',
                        'Ms',
                        'Miss',
                        'Mx',
                        'Dr',
                        'Fr',
                        'Prof',
                      ]"
                      name="title"
                    />
                  </div>
                  <div class="col-xs-12 col-sm-4">
                    <base-label>{{ $t("firstName") }}</base-label>
                    <base-input v-model="member.first_name" name="first_name" />
                  </div>
                  <div class="col-xs-12 col-sm-4">
                    <base-label>{{ $t("surname") }}</base-label>
                    <base-input v-model="member.last_name" name="last_name" />
                  </div>
                  <div class="col-xs-12 col-sm-4">
                    <base-label>{{ $t("emailAddress") }}</base-label>
                    <base-input v-model="member.email" name="email" />
                  </div>
                  <div class="col-xs-12 col-sm-4">
                    <base-label>{{ $t("phoneNumber") }}</base-label>
                    <base-input
                      v-model="member.phone_number"
                      name="phone_number"
                      type="tel"
                    />
                  </div>
                  <div v-if="memberOnly" class="col-xs-12 col-sm-4">
                    <base-label>{{ $t("startsAt") }}</base-label>
                    <base-date-input
                      outlined
                      dense
                      readonly
                      v-model="member.starts_at"
                      no-range
                      name="starts_at"
                    />
                  </div>
                  <div v-if="memberOnly" class="col-xs-12 col-sm-4">
                    <base-label>{{ $t("endsAt") }}</base-label>
                    <base-date-input
                      outlined
                      dense
                      v-model="member.ends_at"
                      only-future
                      no-range
                      name="ends_at"
                      :hint="$t('hint.endsAt')"
                    />
                  </div>
                  <div class="col-xs-12 col-sm-4">
                    <base-label>{{ $t("label.status") }}</base-label>
                    <q-select
                      dense
                      outlined
                      v-model="member.status"
                      :options="[
                        'Active',
                        'Pending',
                        'Deactive',
                        'Hold',
                        'Lost',
                      ]"
                      @update:model-value="onChangeStatus"
                      name="status"
                    />
                  </div>
                  <div
                    v-if="member.status === 'Hold'"
                    class="col-xs-12 col-sm-4"
                  >
                    <base-label>{{ $t("releaseAt") }}</base-label>
                    <base-date-input
                      outlined
                      dense
                      v-model="member.release_at"
                      name="release_at"
                    />
                  </div>
                  <div class="col-xs-12 col-sm-4">
                    <base-label>{{ $t("collectionsID") }}</base-label>
                    <base-input name="collect_id" v-model="member.collect_id" />
                  </div>
                  <div class="col-xs-12 col-sm-4">
                    <base-label>{{ $t("keyFob") }}</base-label>
                    <base-input name="rfid" v-model="member.rfid" />
                  </div>
                  <div v-if="!creating" class="col-xs-12 col-sm-12">
                    <q-expansion-item
                      header-class="bg-grey-3"
                      icon="fas fa-clipboard-list-check"
                      :label="$t('parqStatus', { status: statusParq })"
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
                  bordered
                  :title="$t('billingAddress')"
                  :description="$t('members.billingDesc')"
                  no-row
                >
                  <address-fields v-model="member.address" />
                </base-section>
                <base-section
                  flat
                  bordered
                  v-if="!member.is_enquiry"
                  :title="$t('security')"
                  :description="$t('members.securityDesc')"
                >
                  <div class="col-xs-12 col-sm-4">
                    <base-label>{{ $t("password") }}</base-label>
                    <base-input
                      :type="isPwd ? 'password' : 'text'"
                      v-model="member.password"
                      name="password"
                      color="blue-grey-14"
                    >
                      <template v-slot:append>
                        <q-icon
                          :name="isPwd ? 'fal fa-eye-slash' : 'fal fa-eye'"
                          class="cursor-pointer"
                          @click="isPwd = !isPwd"
                          size="16px"
                        />
                      </template>
                    </base-input>
                  </div>
                  <div class="col-xs-12 col-sm-4">
                    <base-label>{{ $t("confirmPassword") }}</base-label>
                    <base-input
                      :type="isConfPwd ? 'password' : 'text'"
                      v-model="member.password_confirmation"
                      name="password_confirmation"
                      color="blue-grey-14"
                    >
                      <template v-slot:append>
                        <q-icon
                          :name="isConfPwd ? 'fal fa-eye-slash' : 'fal fa-eye'"
                          class="cursor-pointer"
                          @click="isConfPwd = !isConfPwd"
                          size="16px"
                        />
                      </template>
                    </base-input>
                  </div>
                </base-section>
                <base-section
                  flat
                  bordered
                  v-if="hasClasses"
                  title="Class Schedules"
                  no-row
                >
                  <user-class-schedules
                    no-action
                    class="q-mt-sm"
                    :module-id="member.id"
                  />
                </base-section>
                <base-section
                  flat
                  bordered
                  v-if="!creating"
                  :title="$t('notes')"
                  :description="$t('members.activityDesc')"
                  no-row
                  body-class="q-px-lg"
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
              </div>
            </div>
            <div class="col-xs-12 col-sm-4">
              <current-plan-card
                v-if="!creating"
                class="q-mb-md"
                :user-id="member?.id"
                no-upgrade
              />
              <base-section flat bordered :title="$t('specialNote')">
                <div class="col-xs-12">
                  <base-input
                    name="note"
                    v-model="member.note"
                    type="textarea"
                  />
                </div>
                <template #bottom>
                  <base-btn
                    link
                    color="primary"
                    icon="fas fa-message"
                    :label="$t('sendMessage')"
                    :to="{
                      name: 'Single Enquiry',
                      params: {
                        id: 'add',
                      },
                      query: {
                        user: member.id,
                      },
                    }"
                  />
                </template>
              </base-section>
              <base-section flat bordered class="q-mt-md" :title="$t('avatar')">
                <div class="col-xs-12">
                  <file-selector
                    accept="image/*"
                    icon="fad fa-image"
                    dialog-label="uploadAvatar"
                    :hint="$t('hint.images', { type: 'avatar' })"
                    v-model="member.avatar"
                    name="avatar"
                  />
                </div>
              </base-section>
            </div>
          </div>
        </base-form>
        <skeleton-single-page v-else />
      </q-tab-panel>
      <q-tab-panel name="billing">
        <member-billing :user="member" />
      </q-tab-panel>
    </q-tab-panels>
  </q-page>
</template>

<script>
import { cloneDeep } from "lodash";
import { mapActions, mapState } from "pinia";
import { useMemberStore } from "stores/member";
import { useAppStore } from "stores/app";
import UserClassSchedules from "components/UserClassSchedules.vue";
import SkeletonSinglePage from "components/skeleton/SkeletonSinglePage.vue";
import CurrentPlanCard from "components/subscription/CurrentPlanCard.vue";
import ParqForm from "components/ParqForm.vue";
import FileSelector from "components/FileSelector.vue";
import MemberBilling from "components/MemberBilling.vue";
import AddressFields from "src/components/address/AddressFields.vue";

const member = {
  is_enquiry: true,
  status: "Pending",
  title: "Mr",
  address: {},
};

export default {
  components: {
    SkeletonSinglePage,
    UserClassSchedules,
    ParqForm,
    FileSelector,
    MemberBilling,
    CurrentPlanCard,
    AddressFields,
  },
  data() {
    return {
      default: cloneDeep(member),
      member: cloneDeep(member),
      isPwd: true,
      isConfPwd: true,
      loaded: false,
      submited: false,
      parq: false,
      tab: "overview",
    };
  },
  methods: {
    ...mapActions(useMemberStore, ["store", "show", "update", "notes"]),
    onSubmit(props) {
      console.func(
        "pages/admins/members/MemberPage:methods.onSubmit()",
        arguments
      );
      this.submited = true;
      const method = this.creating ? this.store : this.update;
      method(this.member)
        .then(({ data, message }) => {
          this.submited = false;
          this.$q.notify(message);
          this.member = data;
          this.default = cloneDeep(data);
          this.$router.push({
            name: "Single Member",
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
      console.func(
        "pages/admins/members/MemberPage:methods.onReset()",
        arguments
      );
      this.loaded = false;
      this.$nextTick(() => {
        this.member = cloneDeep(this.default);
        this.loaded = true;
      });
    },
    onCancel(props) {
      console.func(
        "pages/admins/members/MemberPage:methods.onCancel()",
        arguments
      );
      this.$router.go(-1);
    },
    onChangeStatus(value) {
      console.func(
        "pages/admins/members/MemberPage:methods.onChangeStatus()",
        arguments
      );
      this.member.is_enquiry = value !== "Active";
      if (value !== "Hold") {
        this.member.release_at = null;
      }
    },
    onCreateNote(props) {
      console.func(
        "pages/admins/members/MemberPage:methods.onCreateNote()",
        arguments
      );
      if (props) {
        this.member.notes.splice(0, 0, cloneDeep(props));
        this.default.notes.splice(0, 0, cloneDeep(props));
      } else {
        this.show(this.id).then((member) => {
          this.member = member;
          this.default = cloneDeep(member);
        });
      }
    },
  },
  async mounted() {
    if (!this.creating) {
      this.show(this.id)
        .then(async (member) => {
          this.member = member;
          this.default = cloneDeep(member);
          this.loaded = true;
        })
        .catch((error) => {
          this.$emit("error", error);
        });
    } else {
      this.loaded = true;
    }
  },
  computed: {
    ...mapState(useAppStore, ["user"]),
    edit() {
      return ["add", "edit"].includes(this.action) || this.id === "add";
    },
    creating() {
      return this.id === "add";
    },
    memberOnly() {
      return !this.creating && !this.member.is_enquiry;
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
        JSON.stringify(this.member) === JSON.stringify(this.default)
      );
    },
    resetable() {
      return (
        this.default &&
        JSON.stringify(this.member) !== JSON.stringify(this.default)
      );
    },
    hasClasses() {
      return !this.creating && !this.member.is_enquiry;
    },
    statusParq() {
      return this.member.has_parq ? "Completed" : "Required";
    },
  },
};
</script>
