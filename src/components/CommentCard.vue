<template>
  <li
    :class="{
      'q-timeline__entry': true,
      'q-timeline__entry--right': true,
      'q-timeline__entry--icon': has_icon,
    }"
  >
    <div v-if="!editable && can_edit" class="q-timeline__subtitle">
      <span>{{ title }}</span>
      <span v-if="date" class="q-ml-sm text-grey-8 text-weight-medium">{{
        date
      }}</span>
      <base-btn
        color="black"
        :class="{
          'actions absolute-top-right': true,
          active: activeMenu,
        }"
        flat
        dense
        round
        icon="far fa-ellipsis-h"
      >
        <q-menu v-model="activeMenu" anchor="bottom right" self="top right">
          <q-list dense class="q-pa-sm">
            <base-item
              @click="onEdit"
              icon="fal fa-pencil-alt"
              label="Edit comment"
            />
            <base-item
              @click="onDelete"
              class="text-negative"
              icon="fal fa-trash-alt"
              label="Delete comment"
            />
          </q-list>
        </q-menu>
      </base-btn>
    </div>
    <div :class="`q-timeline__dot text-${color}`">
      <img v-if="avatar" class="q-timeline__dot-img" :src="avatar" />
      <q-icon v-else-if="icon" :name="icon" />
      <avatar
        v-else-if="creating"
        class="q-timeline__dot-img"
        :tooltip="false"
        size="30px"
        :user="loged_user"
      />
      <avatar
        v-else-if="user"
        class="q-timeline__dot-img"
        :tooltip="false"
        size="30px"
        :user="user"
      />
    </div>
    <div class="q-timeline__content">
      <template v-if="!editable">
        <q-item style="min-height: auto" dense class="q-pa-none">
          <q-item-section>
            <span v-html="message"></span>
          </q-item-section>
          <q-item-section v-if="!can_edit" side>
            <span v-if="date" class="q-ml-sm text-grey-8 text-weight-medium">{{
              date
            }}</span>
          </q-item-section>
        </q-item>
        <div v-if="comment.media.length" class="q-mt-sm files">
          <div class="row q-col-gutter-sm">
            <div
              v-for="(media, index) in comment.media"
              :key="index"
              class="col-sm-3 col-xs-12"
            >
              <q-item class="q-pa-none rounded-borders border-all">
                <q-item-section avatar>
                  <thumbnail
                    class="border-right"
                    square
                    flat
                    :media="media"
                  ></thumbnail>
                </q-item-section>
                <q-item-section>
                  <q-item-label caption lines="1">{{
                    media.name
                  }}</q-item-label>
                </q-item-section>
              </q-item>
            </div>
          </div>
        </div>
      </template>
      <editor
        v-if="editable"
        v-model="newComment.message"
        placeholder="Leave a comment ..."
        no-header
        has-mention
        :contentStyle="{
          'min-height': '2.75rem',
        }"
        :class="{
          'comment-editor': true,
          'q-mt-sm': updating,
        }"
        ref="editor"
      >
        <template v-slot:actions="{ editor }">
          <div v-if="newComment.media.length" class="q-pa-md files">
            <div class="row q-col-gutter-sm">
              <div
                v-for="(media, index) in newComment.media"
                :key="media.id"
                class="col-sm-3 col-xs-12"
              >
                <q-item class="q-pa-none rounded-borders bordered">
                  <q-item-section avatar>
                    <thumbnail
                      class="border-right"
                      square
                      flat
                      :media="media"
                    ></thumbnail>
                  </q-item-section>
                  <q-item-section>
                    <q-item-label caption lines="1">{{
                      media.name
                    }}</q-item-label>
                  </q-item-section>
                  <q-item-section side>
                    <q-btn
                      @click="newComment.media.splice(index, 1)"
                      flat
                      round
                      size="xs"
                      icon="fal fa-times"
                    />
                  </q-item-section>
                </q-item>
              </div>
            </div>
          </div>
          <div class="absolute-bottom-right">
            <div class="q-gutter-xs">
              <q-btn size="sm" dense round color="grey-5" icon="far fa-at" flat>
                <tooltip>Mention staff</tooltip>
                <q-menu
                  @before-show="onLoadStaff(filter)"
                  anchor="bottom middle"
                  self="top middle"
                >
                  <div class="q-pa-sm">
                    <q-input
                      dense
                      outlined
                      v-model="filter"
                      type="text"
                      placeholder="Search..."
                      autofocus
                      style="min-width: 250px"
                      @update:model-value="onLoadStaff"
                    />
                  </div>
                  <q-separator />
                  <q-list
                    v-if="staffs.length"
                    class="q-pa-sm"
                    style="min-width: 250px"
                  >
                    <q-item
                      v-for="(item, index) in staffs"
                      :key="index"
                      @click="
                        editor
                          .chain()
                          .focus()
                          .insertContent({
                            type: 'mention',
                            attrs: {
                              id: item.id,
                              label: item.name,
                            },
                          })
                          .insertContent(' ')
                          .run()
                      "
                      clickable
                      v-close-popup
                      class="rounded-borders"
                    >
                      <q-item-section>
                        {{ item.name }}
                      </q-item-section>
                    </q-item>
                  </q-list>
                </q-menu>
              </q-btn>
              <!-- <q-btn
                TODO: Need to add the reference page button feature
                size="sm"
                dense
                round
                color="grey-5"
                icon="far fa-hashtag"
                flat
              >
                <tooltip>Reference page</tooltip>
              </q-btn> -->
              <q-btn
                size="sm"
                dense
                round
                color="grey-5"
                icon="far fa-paperclip"
                flat
                @click="onAttachFile"
              >
                <tooltip>Attach file</tooltip>
              </q-btn>
              <q-btn
                color="grey-6"
                label="Cancel"
                flat
                no-caps
                v-if="updating"
                class="bg-grey-3"
                @click="updating = false"
              />
              <q-btn
                color="primary"
                :label="updating ? 'Update' : 'Post'"
                flat
                no-caps
                padding="6.5px 16px"
                class="bg-grey-3"
                @click="onSubmit"
                :disable="editor.isEmpty"
              />
            </div>
          </div>
        </template>
      </editor>
      <div v-if="creating" class="text-right text-grey-7">
        Only you and other staff can see comments
      </div>
    </div>
  </li>
</template>

<script>
import Editor from "./tiptap/editor";
import FileSelector from "./file-selector-dialog";

export default {
  components: { Editor },
  props: {
    avatar: {
      type: [Boolean, String],
      required: false,
      default: false,
    },
    user: {
      type: [Boolean, Object],
      required: false,
      default: false,
    },
    icon: {
      type: [Boolean, String],
      required: false,
      default: false,
    },
    creating: {
      type: [Boolean],
      required: false,
      default: false,
    },
    message: {
      type: [String],
    },
    date: {
      type: [String],
    },
    comment: {
      type: [Object],
      required: false,
      default: () => ({ message: "", media: [] }),
    },
    module: {
      type: [String],
      required: true,
    },
    moduleId: {
      required: true,
    },
  },
  computed: {
    color() {
      return this.$parent.color || "primary";
    },
    editable() {
      return this.updating || this.creating;
    },
    title() {
      return this.user ? this.user.name : "";
    },
    can_edit() {
      return this.user ? true : false;
    },
    loged_user() {
      return this.$store.state.app.user;
    },
    has_icon() {
      return this.icon || this.avatar || this.user || this.creating;
    },
  },
  data() {
    return {
      newComment: this.comment,
      updating: false,
      activeMenu: false,
      staffs: [],
      filter: "",
    };
  },
  emits: ["update", "create", "remove"],
  methods: {
    onSubmit() {
      console.func("components/comment-card:methods.onSubmit()", arguments);
      this.newComment.moduleId = this.moduleId;
      this.$store
        .dispatch(this.module, this.newComment)
        .then(({ data, message }) => {
          this.$q.notify(message);
          if (this.newComment.id) {
            this.newComment = data;
            this.$emit("update", data);
            this.updating = false;
          } else {
            this.newComment = { message: "", media: [] };
            this.$emit("create", data);
          }
        })
        .catch((error) => {
          this.$core.error(error, { title: "Error" });
        });
    },
    onEdit() {
      console.func("components/comment-card:methods.onEdit()", arguments);
      this.updating = true;
    },
    onDelete() {
      console.func("components/comment-card:methods.onDelete()", arguments);
      this.$core
        .confirm(
          "Deleting this comment can’t be reversed. It will no longer be shown in the activity.",
          {
            title: "Delete comment",
            ok: "Delete",
            cancel: "Cancel",
            okColor: "negative",
          }
        )
        .then(() => {
          this.$store
            .dispatch("log/Delete", this.newComment)
            .then(({ message }) => {
              this.$q.notify(message);
              this.$emit("remove");
            })
            .catch((error) => {
              this.$core.error(error, { title: "Error" });
            });
        });
    },
    onAttachFile() {
      console.func("components/comment-card:methods.onAttachFile()", arguments);
      this.$q
        .dialog({
          component: FileSelector,
          componentProps: {
            title: "Attach file",
            multiple: true,
          },
        })
        .onOk((payload) => {
          console.func(
            "components/comment-card:methods.onAttachFile.onOk()",
            payload
          );
          this.newComment.media = payload;
        });
    },
    onLoadStaff(val) {
      console.func("components/comment-card:methods.onLoadStaff()", arguments);
      this.$store
        .dispatch("staff/List", {
          filter: val,
          limit: 5,
        })
        .then(({ data }) => {
          this.staffs = data;
        });
    },
  },
};
</script>
<style scoped lang="sass">
.q-timeline__subtitle
  text-transform: none
  opacity: 1
  margin: 0
  padding: 0
.q-timeline__entry
  .actions
    visibility: hidden
  &:hover .actions, .active
      visibility: visible
</style>
<style lang="sass">
.comment-editor
  .q-editor__content .ProseMirror
    min-height: auto
</style>
