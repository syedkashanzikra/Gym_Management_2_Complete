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
      ref="registration"
    >
      <div class="row q-col-gutter-md">
        <div class="col-xs-12 col-sm-9">
          <div class="q-gutter-md">
            <base-section
              flat
              bordered
              :title="`${registration.class?.name}`"
              head-class="q-pb-sm"
              gutter=""
            >
              <div class="col-xs-12 col-md-3 col-sm-4">
                <base-item
                  icon="fad fa-chalkboard-user"
                  no-hover
                  dense
                  :label="registration.instructor?.name"
                />
              </div>
              <div class="col-xs-12 col-md-3 col-sm-4">
                <base-item
                  icon="fad fa-calendar-day"
                  no-hover
                  dense
                  :label="`${registration.date_at_formated} (${registration.day})`"
                />
              </div>
              <div class="col-xs-12 col-md-3 col-sm-4">
                <base-item
                  icon="fad fa-clock"
                  no-hover
                  dense
                  :label="`${registration.time}`"
                />
              </div>
            </base-section>
            <base-section
              flat
              bordered
              :title="
                $t('activeBookingsOutOf', {
                  count: registration.total_active_bookings,
                  capacity: registration.capacity,
                })
              "
              no-row
            >
              <template v-slot:action>
                <base-btn
                  link
                  size="lg"
                  color="primary"
                  icon="fad fa-messages"
                  @click="onSendBulkMessage"
                />
                <base-btn
                  link
                  size="lg"
                  class="q-ml-sm"
                  :disable="registration.has_sign_off || !registration.bookable"
                  color="primary"
                  icon="fad fa-circle-plus"
                  @click="onAddActive"
                />
              </template>
              <template v-slot:bottom>
                <base-btn
                  :disable="registration.has_sign_off || !registration.bookable"
                  color="primary"
                  icon="fad fa-circle-plus"
                  link
                  :label="$t('addMember')"
                  @click="onAddActive"
                />
              </template>
              <class-bookings
                :all-bookings="allBookings"
                class="q-mt-sm"
                use-message
                :has-sign-off="registration.has_sign_off"
                :rows="registration.active_bookings"
              />
            </base-section>
            <base-section
              flat
              bordered
              :title="
                $t('standbyBookingsOutOf', {
                  count: registration.total_stand_by_bookings,
                  capacity: 5,
                })
              "
              no-row
            >
              <template v-slot:action>
                <base-btn
                  link
                  size="lg"
                  class="q-ml-sm"
                  :disable="registration.has_sign_off || !registration.bookable"
                  color="primary"
                  icon="fad fa-circle-plus"
                  @click="onAddStandby"
                />
              </template>
              <template v-slot:bottom>
                <base-btn
                  :disable="registration.has_sign_off || !registration.bookable"
                  color="primary"
                  icon="fad fa-circle-plus"
                  link
                  :label="$t('addStandby')"
                  @click="onAddStandby"
                />
              </template>
              <class-bookings
                :all-bookings="allBookings"
                class="q-mt-sm"
                :has-sign-off="registration.has_sign_off"
                :rows="registration.stand_by_bookings"
              />
            </base-section>
            <base-section
              flat
              bordered
              :title="$t('activities')"
              :description="
                $t('moduleActivityDesc', {
                  module: $t('modules.singular.registration'),
                })
              "
              no-row
              body-class="q-px-lg"
            >
              <q-timeline class="comments" color="secondary">
                <base-note-card
                  :module-action="logs"
                  :module-id="registration.id"
                  class="comment"
                  creating
                  @create="onCreateNote"
                  no-rag
                />
                <base-note-card
                  class="comment"
                  v-for="note in registration.logs"
                  :key="note.id"
                  v-bind="note"
                  :module-id="registration.id"
                  :user="note.admin"
                />
              </q-timeline>
            </base-section>
          </div>
        </div>
        <div class="col-xs-12 col-sm-3">
          <div class="q-gutter-md">
            <base-section flat bordered :title="$t('remote')">
              <div class="col-xs-12">
                <base-label>{{ $t("link") }}</base-label>
                <base-input
                  placeholder="https://"
                  v-model="registration.remote_link"
                  name="remote_link"
                  type="url"
                />
              </div>
              <div class="col-xs-12">
                <base-label>{{ $t("code") }}</base-label>
                <base-input
                  placeholder="XXXXXXXX-XXX"
                  v-model="registration.remote_code"
                  name="remote_code"
                />
              </div>
            </base-section>
            <base-section flat bordered no-row :title="$t('statsAndSettings')">
              <template #bottom>
                <div class="q-gutter-sm">
                  <base-btn
                    link
                    :label="$t('markAllAttended')"
                    @click="onMarkAsAttended"
                    icon="fad fa-square-check"
                  />
                  <base-btn
                    link
                    icon="fad fa-print"
                    :label="$t('print')"
                    @click="onViewPdf"
                  />
                </div>
              </template>
              <div class="q-gutter-y-md">
                <div>
                  <strong>Attendance: </strong>
                  <class-at
                    :has-sign-off="registration.has_sign_off"
                    :value="calAttendance(registration)"
                  />%
                  <strong class="q-ml-sm">Capacity: </strong>
                  {{ calCapacity(registration) }}
                </div>
                <div>
                  <base-checkbox dense v-model="registration.has_sign_off">
                    <div>Admin Sign off</div>
                    <div
                      v-if="registration.has_sign_off && registration.admin"
                      class="text-grey"
                    >
                      {{ registration.admin.name }} on
                      {{ registration.sign_off_at }}
                    </div>
                  </base-checkbox>
                </div>
              </div>
            </base-section>
            <base-section flat bordered :title="$t('note')">
              <div class="col-xs-12">
                <base-input
                  :placeholder="$t('placeholder.noteInstructor')"
                  type="textarea"
                  v-model="registration.note"
                  name="note"
                />
              </div>
            </base-section>
          </div>
        </div>
      </div>
    </base-form>
    <skeleton-single-page v-else />
  </q-page>
</template>

<script>
import { cloneDeep, orderBy } from "lodash";
import { mapActions } from "pinia";
import SkeletonSinglePage from "components/skeleton/SkeletonSinglePage.vue";
import { useWeekScheduleStore } from "stores/week-schedule";
import ClassBookings from "components/ClassBookings.vue";
import ClassAt from "components/ClassAt.vue";

const registration = {
  is_active: true,
  stand_by_bookings: [],
  active_bookings: [],
};

const sortBy = (data) => orderBy(data, ["user.last_name"], ["asc"]);

const processData = (data) =>
  Object.assign(data, {
    active_bookings: sortBy(data.active_bookings),
  });

const defaultBooking = {
  user_id: null,
  schedule_id: null,
  is_stand_by: false,
  attendance: false,
  status: false,
  source: false,
  canceled_at: null,
};

export default {
  components: {
    SkeletonSinglePage,
    ClassBookings,
    ClassAt,
  },
  data() {
    return {
      default: cloneDeep(registration),
      registration: cloneDeep(registration),
      loaded: false,
      submited: false,
    };
  },
  methods: {
    ...mapActions(useWeekScheduleStore, [
      "store",
      "show",
      "update",
      "calAttendance",
      "calCapacity",
      "pdf",
      "logs",
    ]),
    onSubmit(props) {
      console.func(
        "pages/admins/week-schedules/WeekSchedulePage:methods.onSubmit()",
        arguments
      );
      this.submited = true;
      const method = this.creating ? this.store : this.update;
      method(this.registration)
        .then(({ data, message }) => {
          this.submited = false;
          this.$q.notify(message);
          const response = processData(data);
          this.registration = response;
          this.default = cloneDeep(response);
          this.$router.push({
            name: "Single Week Schedule",
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
        "pages/admins/week-schedules/WeekSchedulePage:methods.onReset()",
        arguments
      );
      this.loaded = false;
      this.$nextTick(() => {
        this.registration = cloneDeep(this.default);
        this.loaded = true;
      });
    },
    onCancel(props) {
      console.func(
        "pages/admins/week-schedules/WeekSchedulePage:methods.onCancel()",
        arguments
      );
      this.$router.go(-1);
    },
    onMarkAsAttended() {
      console.func(
        "pages/admins/week-schedules/WeekSchedulePage:methods.onMarkAsAttended()",
        arguments
      );
      this.registration.active_bookings = this.registration.active_bookings.map(
        (item) => ({
          ...item,
          attendance: true,
        })
      );
      this.registration.stand_by_bookings =
        this.registration.stand_by_bookings.map((item) => ({
          ...item,
          attendance: true,
        }));
      this.onSubmit();
    },
    onAddActive(props) {
      console.func(
        "pages/admins/week-schedules/WeekSchedulePage:methods.onAddActive()",
        arguments
      );
      if (this.activeRemaining <= 0) {
        this.$core.warning("No bookings is available for active slot.");
        return false;
      }
      this.registration.active_bookings.push({ ...defaultBooking });
    },
    onAddStandby(props) {
      console.func(
        "pages/admins/week-schedules/WeekSchedulePage:methods.onAddStandby()",
        arguments
      );
      if (this.standByRemaining <= 0) {
        this.$core.warning("No bookings is available for standby slot.");
        return false;
      }
      this.registration.stand_by_bookings.push({
        ...defaultBooking,
        is_stand_by: true,
      });
    },
    onViewPdf() {
      console.func(
        "pages/admins/week-schedules/WeekSchedulePage:methods.onViewPdf()",
        arguments
      );
      this.pdf(this.registration);
    },
    onSendBulkMessage() {
      console.func(
        "pages/admins/week-schedules/WeekSchedulePage:methods.onSendBulkMessage()",
        arguments
      );
      this.$router.push({
        name: "Single Enquiry",
        params: {
          id: "add",
        },
        query: {
          users: this.registration.active_bookings
            .map((item) => item.user_id)
            .join(","),
        },
      });
    },
    onCreateNote(props) {
      console.func(
        "pages/admins/week-schedules/WeekSchedulePage:methods.onCreateNote()",
        arguments
      );
      if (props) {
        this.registration.logs.splice(0, 0, cloneDeep(props));
        this.default.logs.splice(0, 0, cloneDeep(props));
      } else {
        this.show(this.id).then((registration) => {
          this.registration = registration;
          this.default = cloneDeep(registration);
        });
      }
    },
  },
  async mounted() {
    if (!this.creating) {
      this.show(this.id)
        .then((data) => {
          const response = processData(data);
          this.$app.setTitle(data.class?.name);
          this.registration = response;
          this.default = cloneDeep(response);
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
    edit() {
      return ["add", "edit"].includes(this.action) || this.id === "add";
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
        JSON.stringify(this.registration) === JSON.stringify(this.default)
      );
    },
    resetable() {
      return (
        this.default &&
        JSON.stringify(this.registration) !== JSON.stringify(this.default)
      );
    },
    activeRemaining() {
      return (
        this.registration.capacity - this.registration.active_bookings.length
      );
    },
    standByRemaining() {
      return 5 - this.registration.stand_by_bookings.length;
    },
    allBookings() {
      return this.registration.active_bookings.concat(
        this.registration.stand_by_bookings
      );
    },
  },
};
</script>
