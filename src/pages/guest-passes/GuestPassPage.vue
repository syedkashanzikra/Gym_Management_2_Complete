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
    >
      <div class="q-gutter-md">
        <base-section flat bordered :title="$t('guestInformation')">
          <div class="col-xs-12 col-sm">
            <base-label required>{{ $t("title") }}</base-label>
            <base-select
              v-model="guestPass.title"
              dense
              outlined
              :options="['Mr', 'Mrs', 'Ms', 'Miss', 'Mx', 'Dr', 'Fr', 'Prof']"
            />
          </div>
          <div class="col-xs-12 col-sm-5">
            <base-label required>{{ $t("firstName") }}</base-label>
            <base-input v-model="guestPass.first_name" />
          </div>
          <div class="col-xs-12 col-sm-5">
            <base-label required>{{ $t("surname") }}</base-label>
            <base-input v-model="guestPass.last_name" />
          </div>
          <div class="col-xs-12 col-sm-4">
            <base-label required>{{ $t("emailAddress") }}</base-label>
            <base-input v-model="guestPass.email" />
          </div>
          <div class="col-xs-12 col-sm-4">
            <base-label required>{{ $t("phoneNumber") }}</base-label>
            <base-input v-model="guestPass.phone_number" />
          </div>
          <div class="col-xs-12 col-sm-12">
            <base-label>{{ $t("note") }}</base-label>
            <base-input v-model="guestPass.note" type="textarea" />
          </div>
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
import { useGuestPassStore } from "stores/guest-pass";

const guestPass = {};

export default {
  components: {
    SkeletonSinglePage,
  },
  data() {
    return {
      default: cloneDeep(guestPass),
      guestPass: cloneDeep(guestPass),
      loaded: false,
      submited: false,
    };
  },
  methods: {
    ...mapActions(useGuestPassStore, ["store", "show", "update"]),
    onSubmit(props) {
      console.func(
        "pages/guest-passes/GuestPassPage:methods.onSubmit()",
        arguments
      );
      this.submited = true;
      const method = this.creating ? this.store : this.update;
      method(this.guestPass)
        .then(({ data, message }) => {
          this.submited = false;
          this.$q.notify(message);
          this.guestPass = data;
          this.default = cloneDeep(data);
          this.$router.push({
            name: "Single Guest Pass",
            params: {
              id: data.id,
              title: data.name,
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
        "pages/guest-passes/GuestPassPage:methods.onReset()",
        arguments
      );
      this.loaded = false;
      this.$nextTick(() => {
        this.guestPass = cloneDeep(this.default);
        this.loaded = true;
      });
    },
    onCancel(props) {
      console.func(
        "pages/guest-passes/GuestPassPage:methods.onCancel()",
        arguments
      );
      this.$router.go(-1);
    },
  },
  async mounted() {
    if (!this.creating) {
      this.show(this.id)
        .then((data) => {
          this.guestPass = data;
          this.default = cloneDeep(data);
          this.$app.setTitle(data.name);
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
        JSON.stringify(this.guestPass) === JSON.stringify(this.default)
      );
    },
    resetable() {
      return (
        this.default &&
        JSON.stringify(this.guestPass) !== JSON.stringify(this.default)
      );
    },
  },
};
</script>
