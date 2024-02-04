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
        <base-section flat bordered head-class="q-pb-sm">
          <template #title>
            <div>
              <div class="text-h6">
                {{ title }}
              </div>
              <q-item-label v-if="!creating">
                {{ enquiry.created_at }}
                <base-status :type="enquiry.status" />
              </q-item-label>
            </div>
          </template>
          <template v-if="creating">
            <div class="col-xs-12">
              <base-label required>{{ $t("subject") }}</base-label>
              <base-input v-model="enquiry.subject" name="subject" />
            </div>
            <template v-if="isAdmin">
              <div class="col-xs-8">
                <base-label required>{{ $t("user") }}</base-label>
                <base-select
                  :placeholder="$t('placeholder.selectMember')"
                  dense
                  outlined
                  v-model="enquiry.user"
                  name="user"
                  :filter-method="getMember"
                  map-options
                  use-filter
                  :multiple="enquiry.bulk"
                  :use-chips="enquiry.bulk"
                  :option-label="(opt) => `${opt.name} (${opt.email})`"
                />
              </div>
              <div class="col-xs-4">
                <base-label>{{ $t("label.status") }}</base-label>
                <enquiry-status v-model="enquiry.status" name="status" />
              </div>
            </template>
            <div class="col-xs-12 col-sm-12">
              <base-label required>{{ $t("message") }}</base-label>
              <base-editor v-model="enquiry.message" name="message" />
            </div>
            <div class="col-xs-12 col-sm-12">
              <base-label>{{ $t("attachment") }}</base-label>
              <base-dropzone v-model="enquiry.media" name="media" />
            </div>
          </template>
          <template v-else>
            <div class="col-xs-12">
              <div class="row">
                <div v-if="isAdmin" class="col-xs-12 col-sm-12">
                  <q-item class="q-px-none">
                    <q-item-section avatar>
                      <base-avatar
                        size="45px"
                        :user="user || { name: enquiry.name }"
                      />
                    </q-item-section>
                    <q-item-section>
                      <q-item-label class="text-subtitle2">
                        {{ enquiry.name }} - {{ enquiry.email }}
                      </q-item-label>
                      <q-item-label v-if="enquiry.phone">
                        {{ enquiry.phone }}
                      </q-item-label>
                    </q-item-section>
                  </q-item>
                </div>
                <div v-else-if="enquiry?.admin" class="col-xs-12 col-sm-12">
                  <q-item class="q-px-none">
                    <q-item-section avatar>
                      <base-avatar size="45px" :user="enquiry.admin" />
                    </q-item-section>
                    <q-item-section>
                      <q-item-label class="text-subtitle2">
                        {{ enquiry.name }}
                      </q-item-label>
                      <q-item-label v-if="enquiry.phone">
                        {{ enquiry.email }}
                      </q-item-label>
                    </q-item-section>
                  </q-item>
                </div>
                <div class="col-xs-12 col-sm-12">
                  <span v-html="enquiry.message"></span>
                </div>
              </div>
            </div>
          </template>

          <template v-if="!creating && enquiry.media.length">
            <div class="col-xs-12">
              <div class="files">
                <div class="row q-col-gutter-sm">
                  <div
                    v-for="media in enquiry.media"
                    :key="media.id"
                    class="col-sm-2 col-xs-12"
                  >
                    <base-media-card :media="media" />
                  </div>
                </div>
              </div>
            </div>
          </template>
          <template v-slot:action>
            <div v-guard:admins v-if="!creating" class="text-right">
              <base-btn
                link
                color="negative"
                icon="fas fa-trash-can"
                :label="$t('delete')"
                @click="onDelete"
              />
            </div>
          </template>
        </base-section>

        <template v-if="enquiry?.order">
          <products-card flat view v-model="enquiry.order.line_items">
            <template v-if="isAdmin" #bottom>
              <base-btn
                link
                label="View order"
                :to="`/orders/${enquiry?.order_id}`"
              />
            </template>
          </products-card>
        </template>

        <base-section
          flat
          bordered
          :title="$t('activities')"
          :description="$t('enquiries.activityDesc')"
          no-row
          v-if="!creating"
          body-class="q-px-lg"
        >
          <q-timeline class="comments" color="secondary">
            <base-enquiry-reply-card
              :module-id="enquiry.id"
              class="comment"
              creating
              @create="onCreateNote"
              :is-admin="isAdmin"
              :archived="isAdmin ? enquiry.is_archived : enquiry.user_archived"
              @update:archived="onUpdateArchived"
            />
            <base-enquiry-reply-card
              class="comment"
              v-for="note in enquiry.replies"
              :key="note.id"
              v-bind="note"
              :module-id="enquiry.id"
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
import { useEnquiryStore } from "stores/enquiry";
import { useMemberStore } from "stores/member";
import EnquiryStatus from "components/EnquiryStatus.vue";
import ProductsCard from "src/components/order/ProductsCard.vue";

const enquiry = {
  media: [],
  message: "",
  status: "Pending",
  is_archived: false,
  user_archived: false,
};

export default {
  components: {
    SkeletonSinglePage,
    EnquiryStatus,
    ProductsCard,
  },
  data() {
    return {
      default: cloneDeep(enquiry),
      enquiry: cloneDeep(enquiry),
      loaded: false,
      submited: false,
    };
  },
  methods: {
    ...mapActions(useEnquiryStore, [
      "store",
      "show",
      "update",
      "delete",
      "changeArchived",
      "changeUserArchived",
    ]),
    ...mapActions(useMemberStore, {
      getMember: "options",
      getUser: "show",
      getUsers: "shows",
    }),
    onSubmit(props) {
      console.func(
        "pages/core/enquiries/EnquiryPage:methods.onSubmit()",
        arguments
      );
      this.submited = true;
      const method = this.creating ? this.store : this.update;
      method({ ...this.enquiry, admin: this.isAdmin })
        .then(({ data, message }) => {
          this.submited = false;
          this.$q.notify(message);
          if (this.enquiry.bulk) {
            this.default = cloneDeep(this.enquiry);
            this.$router.back();
            return false;
          }
          this.enquiry = data;
          this.default = cloneDeep(data);
          this.$router.push({
            name: "Single Enquiry",
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
    onDelete(props) {
      console.func(
        "pages/core/enquiries/EnquiryPage:methods.onDelete()",
        arguments
      );
      this.$core.confirm(this.$t("dialog.info.warning")).then(() => {
        this.delete(this.id)
          .then(() => {
            this.$router.push({
              name: "Enquiries",
            });
          })
          .catch((error) => {
            this.$core.error(error, { title: this.$t("dialog.title.error") });
          });
      });
    },
    onReset(props) {
      console.func(
        "pages/core/enquiries/EnquiryPage:methods.onReset()",
        arguments
      );
      this.loaded = false;
      this.$nextTick(() => {
        this.enquiry = cloneDeep(this.default);
        this.loaded = true;
      });
    },
    onCancel(props) {
      console.func(
        "pages/core/enquiries/EnquiryPage:methods.onCancel()",
        arguments
      );
      this.$router.go(-1);
    },
    onCreateNote(props) {
      console.func(
        "pages/core/enquiries/EnquiryPage:methods.onCreateNote()",
        arguments
      );
      if (props) {
        this.enquiry.replies.splice(0, 0, cloneDeep(props));
        this.default.replies.splice(0, 0, cloneDeep(props));
      } else {
        this.show(this.id).then((enquiry) => {
          this.enquiry = enquiry;
          this.default = cloneDeep(enquiry);
        });
      }
    },
    onUpdateArchived() {
      console.func(
        "pages/core/enquiries/EnquiryPage:methods.onUpdateArchived()",
        arguments
      );
      const method = this.isAdmin
        ? this.changeArchived
        : this.changeUserArchived;
      method(this.enquiry).then(() => {
        this.default = cloneDeep(this.enquiry);
      });
    },
  },
  async mounted() {
    if (!this.creating) {
      this.show(this.id)
        .then((data) => {
          this.$app.setTitle(data.subject || "Contact us");
          this.enquiry = data;
          this.default = cloneDeep(data);
          this.loaded = true;
        })
        .catch((error) => {
          this.$emit("error", error);
        });
    } else {
      if (this.user) {
        await this.getUser(this.user).then((user) => {
          this.enquiry.user = {
            name: user.name,
            email: user.email,
            id: user.id,
          };
          this.default = cloneDeep(this.enquiry);
        });
      } else if (this.users) {
        await this.getUsers({
          ids: this.users.split(","),
        }).then((users) => {
          this.enquiry.bulk = true;
          this.enquiry.user = users.map((user) => ({
            name: user.name,
            email: user.email,
            id: user.id,
          }));
          this.default = cloneDeep(this.enquiry);
        });
      }
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
    user() {
      return this.$route.query.user;
    },
    users() {
      return this.$route.query.users;
    },
    disable() {
      return (
        this.default &&
        JSON.stringify(this.enquiry) === JSON.stringify(this.default)
      );
    },
    guard() {
      return this.$route.meta.guard;
    },
    isAdmin() {
      return this.guard === "admins";
    },
    resetable() {
      return (
        this.default &&
        JSON.stringify(this.enquiry) !== JSON.stringify(this.default)
      );
    },
    title() {
      if (this.creating) {
        return this.$t("generalInformation");
      }
      return this.enquiry.subject || this.$t("contactUs");
    },
  },
};
</script>
