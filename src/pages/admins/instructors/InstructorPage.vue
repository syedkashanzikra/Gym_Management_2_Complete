<template>
  <q-page padding>
    <base-page-header
      class="q-mb-md"
      :title="creating ? $t('createNew') : instructor.name"
      :hint="$t('hint.instructor')"
      no-tabs
    />
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
        <div class="col-xs-12 col-sm-9">
          <div class="q-gutter-md">
            <base-section flat bordered :title="$t('generalInformation')">
              <div class="col-xs-12 col-sm-4">
                <base-label required>{{ $t("firstName") }}</base-label>
                <base-input name="first_name" v-model="instructor.first_name" />
              </div>
              <div class="col-xs-12 col-sm-4">
                <base-label required>{{ $t("surname") }}</base-label>
                <base-input name="last_name" v-model="instructor.last_name" />
              </div>
              <div class="col-xs-12 col-sm-4">
                <base-label required>{{ $t("emailAddress") }}</base-label>
                <base-input v-model="instructor.email" name="email" />
              </div>
              <div class="col-xs-12 col-sm-4">
                <base-label required>{{ $t("phoneNumber") }}</base-label>
                <base-input
                  v-model="instructor.phone_number"
                  name="phone_number"
                  type="tel"
                />
              </div>
              <div class="col-xs-12 col-sm-2">
                <base-label>{{ $t("hoursPW") }}</base-label>
                <base-input
                  type="number"
                  step="1"
                  min="1"
                  v-model="instructor.hourspw"
                  name="hourspw"
                />
              </div>
              <div class="col-xs-12 col-sm-2">
                <base-label>{{ $t("rentPW") }}</base-label>
                <base-price-input v-model="instructor.rentpw" name="rentpw" />
              </div>
              <div class="col-xs-12 col-sm-12">
                <base-label required>{{ $t("profileInfo") }}</base-label>
                <base-input
                  type="textarea"
                  v-model="instructor.description"
                  name="description"
                />
              </div>
              <div class="col-xs-12">
                <url-list v-model="instructor.urls" />
              </div>
            </base-section>
            <base-section flat bordered :title="$t('menus.classes')" no-row>
              <base-input
                class="q-mb-md"
                :placeholder="$t('placeholder.searchClasses')"
                debounce="500"
                @update:model-value="onBrowseClass"
              >
                <template v-slot:after>
                  <q-btn
                    @click="onBrowseClass('')"
                    no-caps
                    outline
                    padding="9px"
                    :style="{
                      width: '100px',
                    }"
                    color="primary"
                    :label="$t('browse')"
                  />
                </template>
              </base-input>
              <div>
                <base-table
                  ref="classes"
                  :columns="classes_columns"
                  :rows="instructor.classes"
                  hide-top
                  hide-pagination
                  :rows-per-page-options="[0]"
                  :no-data-label="$t('classes.noData')"
                  loaded
                >
                  <template v-slot:body-cell-name="props">
                    <base-btn
                      @click.stop
                      link
                      size="12px"
                      tag="a"
                      :to="{
                        name: 'Single Class',
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
                  <template v-slot:body-cell-cost="props">
                    <base-price-input
                      dense
                      borderless
                      @click.stop
                      v-model="props.row.pivot.cost"
                    />
                  </template>
                  <template v-slot:actions="props">
                    <q-btn
                      @click.stop="onRemoveClass(props)"
                      size="sm"
                      round
                      flat
                      color="grey"
                      icon="fas fa-trash-can"
                    />
                  </template>
                </base-table>
              </div>
            </base-section>
          </div>
        </div>
        <div class="col-xs-12 col-sm-3">
          <base-section flat bordered :title="$t('profileSettings')">
            <div class="col-xs-12">
              <base-label>{{ $t("avatar") }}</base-label>
              <file-selector
                accept="image/*"
                icon="fad fa-image"
                dialog-label="uploadAvatar"
                :hint="$t('hint.images', { type: 'avatar' })"
                load-from-server
                v-model="instructor.avatar"
                :query="
                  (args) => ({
                    ...args,
                    type: 'image',
                  })
                "
              />
            </div>
            <div class="col-xs-12">
              <base-label>{{ $t("label.status") }}</base-label>
              <q-select
                v-model="instructor.status"
                :options="['Active', 'Deactive', 'Hold']"
                dense
                outlined
              />
            </div>
            <div class="col-xs-12">
              <q-checkbox
                dense
                v-model="instructor.is_pt"
                :label="$t('personalTrainer')"
              />
            </div>
            <div class="col-xs-12">
              <base-checkbox no-inline dense v-model="instructor.insurance">
                <div>{{ $t("insurance") }}</div>
              </base-checkbox>
              <file-selector
                use-controler
                inline
                class="q-mt-sm"
                v-show="instructor.insurance"
                accept="application/pdf"
                icon="fad fa-file-pdf"
                dialog-label="uploadInsurance"
                :hint="$t('hint.pdfs', { type: 'insurance' })"
                load-from-server
                v-model="instructor.insurance_file"
                :query="
                  (args) => ({
                    ...args,
                    type: 'application/pdf',
                  })
                "
              />
            </div>
            <div class="col-xs-12">
              <base-checkbox no-inline dense v-model="instructor.qualification">
                <div>{{ $t("qualification") }}</div>
              </base-checkbox>
              <file-selector
                use-controler
                inline
                class="q-mt-sm"
                v-show="instructor.qualification"
                accept="application/pdf"
                icon="fad fa-file-pdf"
                dialog-label="uploadQualification"
                :hint="$t('hint.pdfs', { type: 'qualification' })"
                load-from-server
                v-model="instructor.qualification_file"
                :query="
                  (args) => ({
                    ...args,
                    type: 'application/pdf',
                  })
                "
              />
            </div>
            <div class="col-xs-12">
              <base-checkbox no-inline dense v-model="instructor.document">
                <div>{{ $t("document") }}</div>
              </base-checkbox>
              <file-selector
                use-controler
                inline
                class="q-mt-sm"
                v-show="instructor.document"
                accept="application/pdf"
                icon="fad fa-file-pdf"
                dialog-label="uploadDocument"
                :hint="$t('hint.pdfs', { type: 'document' })"
                load-from-server
                v-model="instructor.document_file"
                :query="
                  (args) => ({
                    ...args,
                    type: 'application/pdf',
                  })
                "
              />
            </div>
          </base-section>
        </div>
      </div>
    </base-form>
    <skeleton-single-page v-else />
  </q-page>
</template>

<script>
import { cloneDeep } from "lodash";
import { mapActions } from "pinia";
import SkeletonSinglePage from "components/skeleton/SkeletonSinglePage.vue";
import { useInstructorStore } from "stores/instructor";
import UrlList from "components/UrlList.vue";
import ClassSelector from "components/ClassSelector.vue";
import FileSelector from "components/FileSelector.vue";

const instructor = {
  status: "Active",
  avatar: null,
  urls: [],
  is_pt: false,
  is_admin: false,
  insurance: false,
  qualification: false,
  has_onlinefolder: false,
  document: false,
};

export default {
  components: {
    SkeletonSinglePage,
    UrlList,
    FileSelector,
  },
  data() {
    return {
      default: cloneDeep(instructor),
      instructor: cloneDeep(instructor),
      loaded: false,
      submited: false,
      classes_columns: [
        {
          name: "name",
          align: "left",
          label: this.$t("label.class"),
          field: "name",
          style: "width: 100px",
          sortable: true,
        },
        {
          name: "cost",
          align: "left",
          label: this.$t("label.cost"),
          field: (row) => row.pivot.cost,
          format: (val) => this.$core.money(val),
          sortable: true,
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
  methods: {
    ...mapActions(useInstructorStore, ["store", "show", "update"]),
    onSubmit(props) {
      console.func(
        "pages/admins/instructors/InstructorPage:methods.onSubmit()",
        arguments
      );
      this.submited = true;
      const method = this.creating ? this.store : this.update;
      method(this.instructor)
        .then(({ data, message }) => {
          this.submited = false;
          this.$q.notify(message);
          this.instructor = data;
          this.default = cloneDeep(data);
          this.$router.push({
            name: "Single Instructor",
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
        "pages/admins/instructors/InstructorPage:methods.onReset()",
        arguments
      );
      this.loaded = false;
      this.$nextTick(() => {
        this.instructor = cloneDeep(this.default);
        this.loaded = true;
      });
    },
    onCancel(props) {
      console.func(
        "pages/admins/instructors/InstructorPage:methods.onCancel()",
        arguments
      );
      this.$router.go(-1);
    },
    onBrowseClass(value) {
      console.func(
        "pages/admins/instructors/InstructorPage:methods.onBrowseClass()",
        arguments
      );
      this.$q
        .dialog({
          component: ClassSelector,
          componentProps: {
            query: value,
            modelValue: this.instructor.classes,
          },
        })
        .onOk((payload) => {
          console.func(
            "pages/admins/instructors/InstructorPage:methods.onBrowseClass.onOk()",
            payload
          );
          this.instructor.classes = payload;
        });
    },
    onRemoveClass({ rowIndex }) {
      console.func(
        "pages/admins/instructors/InstructorPage:methods.onRemoveClass()",
        arguments
      );
      this.instructor.classes.splice(rowIndex, 1);
    },
  },
  async mounted() {
    if (!this.creating) {
      this.show(this.id)
        .then((instructor) => {
          this.instructor = instructor;
          this.default = cloneDeep(instructor);
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
        JSON.stringify(this.instructor) === JSON.stringify(this.default)
      );
    },
    resetable() {
      return (
        this.default &&
        JSON.stringify(this.instructor) !== JSON.stringify(this.default)
      );
    },
  },
};
</script>
