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
      :no-action="!creating"
      :save-label="$t('send')"
    >
      <div class="q-gutter-md">
        <base-section flat bordered :title="title" head-class="q-pb-sm">
          <template v-if="creating">
            <div class="col-xs-8">
              <base-label required>{{ $t("subject") }}</base-label>
              <base-input v-model="task.subject" name="subject" />
            </div>
            <div class="col-xs-4">
              <base-label required>{{ $t("label.status") }}</base-label>
              <task-status v-model="task.status" name="status" />
            </div>
            <div class="col-xs-12">
              <base-label required>{{ $t("modules.staff") }}</base-label>
              <base-select
                :placeholder="$t('placeholder.selectStaff')"
                dense
                outlined
                v-model="task.users"
                name="users"
                :filter-method="getStaff"
                map-options
                use-filter
                multiple
                use-chips
                :option-label="(opt) => `${opt.name} (${opt.email})`"
              />
            </div>
            <div class="col-xs-12">
              <base-label required>{{ $t("message") }}</base-label>
              <base-editor v-model="task.message" name="message" />
            </div>
            <div class="col-xs-12">
              <base-label>{{ $t("attachment") }}</base-label>
              <base-dropzone v-model="task.media" name="media" />
            </div>
          </template>
          <template v-else>
            <div class="col-xs-12 col-sm-12">
              <div
                :class="{
                  'meta-section': true,
                  row: $q.screen.gt.xs,
                }"
              >
                <div class="col">
                  <div class="flex">
                    <div class="subject text-h6">{{ task.subject }}</div>
                  </div>
                  <div class="created-at">
                    {{ task.created_at }} <base-status :type="task.status" />
                  </div>
                </div>
                <div class="col-auto">
                  <div
                    class="relative-position"
                    :style="{
                      height: '40px',
                      width: task.users.length * 35 + 'px',
                    }"
                  >
                    <base-avatar
                      v-for="(item, index) in task.users"
                      name="users"
                      :key="index"
                      class="cursor-pointer"
                      :user="item"
                      tooltip
                      size="40px"
                      :overlapping="index"
                    />
                  </div>
                </div>
              </div>
            </div>
            <div class="col-xs-12 col-sm-12">
              <span v-html="task.message"></span>
            </div>
          </template>

          <template v-if="!creating && task.media.length">
            <div class="col-xs-12">
              <div class="files">
                <div class="row q-col-gutter-sm">
                  <div
                    v-for="media in task.media"
                    :key="media.id"
                    class="col-sm-2 col-xs-12"
                  >
                    <base-media-card :media="media" />
                  </div>
                </div>
              </div>
            </div>
          </template>
        </base-section>
        <base-section
          flat
          bordered
          :title="$t('activities')"
          :description="$t('tasks.activityDesc')"
          no-row
          v-if="!creating"
          body-class="q-px-lg"
        >
          <q-timeline class="comments" color="secondary">
            <base-task-reply-card
              :module-id="task.id"
              class="comment"
              creating
              is-admin
              @create="onCreateNote"
              :archived="task.is_archived"
              @update:archived="onUpdateArchived"
            />
            <base-task-reply-card
              class="comment"
              v-for="note in task.replies"
              :key="note.id"
              v-bind="note"
              :module-id="task.id"
              :user="note.user"
            />
          </q-timeline>
        </base-section>
      </div>
    </base-form>
    <skeleton-single-page v-else />
  </q-page>
</template>

<script>
import { cloneDeep } from "lodash";
import { mapActions } from "pinia";
import SkeletonSinglePage from "components/skeleton/SkeletonSinglePage.vue";
import { useTaskStore } from "stores/task";
import { useStaffStore } from "stores/staff";
import TaskStatus from "components/EnquiryStatus.vue";

const task = {
  media: [],
  status: "Pending",
  message: "",
};

export default {
  components: {
    SkeletonSinglePage,
    TaskStatus,
  },
  data() {
    return {
      default: cloneDeep(task),
      task: cloneDeep(task),
      loaded: false,
      submited: false,
    };
  },
  methods: {
    ...mapActions(useTaskStore, [
      "store",
      "show",
      "update",
      "changeArchived",
      "changeUserArchived",
    ]),
    ...mapActions(useStaffStore, { getStaff: "options", getUser: "show" }),
    onSubmit(props) {
      console.func("pages/core/tasks/TaskPage:methods.onSubmit()", arguments);
      this.submited = true;
      const method = this.creating ? this.store : this.update;
      method(this.task)
        .then(({ data, message }) => {
          this.submited = false;
          this.$q.notify(message);
          this.task = data;
          this.default = cloneDeep(data);
          this.$router.push({
            name: "Single Task",
            params: {
              id: data.id,
            },
            query: {
              action: "edit",
              title: data.subject,
            },
          });
        })
        .catch((error) => {
          this.submited = false;
          this.$core.error(error, { title: this.$t("dialog.title.error") });
        });
    },
    onReset(props) {
      console.func("pages/core/tasks/TaskPage:methods.onReset()", arguments);
      this.loaded = false;
      this.$nextTick(() => {
        this.task = cloneDeep(this.default);
        this.loaded = true;
      });
    },
    onCancel(props) {
      console.func("pages/core/tasks/TaskPage:methods.onCancel()", arguments);
      this.$router.go(-1);
    },
    onCreateNote(props) {
      console.func(
        "pages/core/tasks/TaskPage:methods.onCreateNote()",
        arguments
      );
      if (props) {
        this.task.replies.splice(0, 0, cloneDeep(props));
        this.default.replies.splice(0, 0, cloneDeep(props));
      } else {
        this.show(this.id).then((task) => {
          this.task = task;
          this.default = cloneDeep(task);
        });
      }
    },
    onUpdateArchived() {
      console.func(
        "pages/core/tasks/TaskPage:methods.onUpdateArchived()",
        arguments
      );
      this.changeArchived(this.task).then(() => {
        this.default = cloneDeep(this.task);
      });
    },
  },
  async mounted() {
    if (!this.creating) {
      this.show(this.id)
        .then((data) => {
          this.$app.setTitle(data.subject);
          this.task = data;
          this.default = cloneDeep(data);
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
        JSON.stringify(this.task) === JSON.stringify(this.default)
      );
    },
    resetable() {
      return (
        this.default &&
        JSON.stringify(this.task) !== JSON.stringify(this.default)
      );
    },
    title() {
      if (this.creating) {
        return this.$t("generalInformation");
      }
      return null;
    },
  },
};
</script>
