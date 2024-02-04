<template>
  <base-form
    :disable="disable"
    :no-action="readonly"
    :save-label="$t('submit')"
    :cancelable="false"
    class="parq-form"
    @submit="onSubmit"
    no-events
  >
    <q-banner class="bg-green-1">
      {{ $t("parqInfo") }}
      <template v-slot:action>
        <base-checkbox
          :readonly="readonly"
          left-label
          dense
          v-model="parq.accept"
          :label="$t('iAccept')"
        />
      </template>
    </q-banner>
    <div class="q-my-md q-gutter-y-sm info">
      <div class="">{{ $t("generalInformation") }}</div>
      <base-input
        :readonly="readonly"
        v-model="parq.name"
        :placeholder="$t('placeholder.name')"
      />
      <base-input
        :readonly="readonly"
        v-model="parq.email"
        :placeholder="$t('placeholder.emailAddress')"
      />
      <div class="q-pt-sm row q-col-gutter-sm">
        <div class="col-xs-12">{{ $t("emergencyContactNumber") }}</div>
        <div class="col-xs-12 col-sm-6">
          <base-input
            :readonly="readonly"
            v-model="parq.emergency_contact_name"
            :placeholder="$t('placeholder.name')"
          />
        </div>
        <div class="col-xs-12 col-sm-6">
          <base-input
            :readonly="readonly"
            v-model="parq.emergency_contact_phone_number"
            :placeholder="$t('placeholder.contactNumber')"
          />
        </div>
      </div>
      <base-input
        :readonly="readonly"
        v-model="parq.allergies"
        type="textarea"
        :placeholder="$t('placeholder.parqHealth')"
      />
      <div v-if="parq.updated_by">
        <div>{{ $t("name") }}: {{ completedBy }}</div>
        <div>{{ $t("date") }}: {{ parq.updated_by.date_time }}</div>
      </div>
      <div v-else>
        <div>{{ $t("name") }}: {{ user.name }}</div>
        <div>
          {{ $t("date") }}: {{ $core.formatDate(new Date(), "DD/MM/YYYY") }}
        </div>
      </div>
    </div>
    <div v-for="(item, index) in parq.questions" :key="index">
      <q-toolbar class="q-my-sm bg-green-1">
        <q-toolbar-title class="text-center text-body1">
          {{ item.label }}
        </q-toolbar-title>
      </q-toolbar>
      <div class="q-pt-sm q-gutter-y-sm">
        <div v-for="(item, index) in item.list" :key="index">
          <base-checkbox
            :readonly="readonly"
            v-model="item.value"
            :label="item.label"
          />
        </div>
      </div>
    </div>
    <template #after-actions>
      <div
        class="text-right"
        v-html="$t('parqIConfirmedBy', { name: parq.name + ' ' })"
      ></div>
    </template>
  </base-form>
</template>

<script>
import { cloneDeep } from "lodash";
import { mapActions, mapState } from "pinia";
import { useAppStore } from "stores/app";
import { useMemberStore } from "stores/member";

const parg = {
  accept: false,
  questions: [
    {
      label: "Medical questions",
      list: [
        {
          label:
            "Do you know of any reason you should not exercise or increase your physical activity",
          value: false,
        },
        {
          label: "Are you recovering from an illness, injury or operation",
          value: false,
        },
        {
          label: "Are you pregnant",
          value: false,
        },
        {
          label: "Not used to being physically active",
          value: false,
        },
        {
          label: "Do you suffer from Asthma",
          value: false,
        },
        {
          label:
            "Has a Doctor said that you have a heart condition and you should only do physical activity recommended by a Doctor",
          value: false,
        },
        {
          label:
            "When you perform physical activity, do you feel a pain in your chest",
          value: false,
        },
        {
          label:
            "When not performing physical activity, have you recently suffered chest pain",
          value: false,
        },
        {
          label:
            "Do you have bone or joint problems that may be made worse with physical activity",
          value: false,
        },
        {
          label:
            "Are you currently on any medication for blood pressure or a heart condition",
          value: false,
        },
        {
          label:
            "I am aware of other reasons why I should not take part in physical activity in a fitness centre environment",
          value: false,
        },
      ],
    },
    {
      label: "Disclaimers",
      list: [
        {
          label:
            "I accept the statements below by ticking the checkbox next to each statement.",
          feature: false,
          value: false,
          required: true,
        },
        {
          label: "I confirm that my responses are accurate",
          value: false,
          required: true,
        },
        {
          label:
            "If I have highlighted any health concerns detailed above I understand that I am required to discuss my exercise programme within a gym environment with my Doctor or Health Professional and to take advice on activities which are safe to participate in.",
          value: false,
          required: true,
        },
        {
          label:
            "In the event that I have been advised to seek medical clearance prior to undertaking exercise, I agree to contact my doctor and take responsibility for obtaining written permission prior to the commencement of my exercise programme in a gym environment.",
          value: false,
          required: true,
        },
        {
          label:
            "Should any change in my Health or unusual symptoms occur at any point, I will cease participation and inform a Doctor of these symptoms.",
          value: false,
          required: true,
        },
        {
          label:
            "I understand that I must notify you immediately of any changes in my health.",
          value: false,
          required: true,
        },
        {
          label:
            "Assumption of Risk: I hereby state that I have read, understood and answered honestly the questions above. I also state that I wish to participate in activities, which may include aerobic exercise, resistance exercise and stretching. I realise that my participation in these activities involves the risk of injury and even the possibility of death. Furthermore, I hereby confirm that I am voluntarily engaging in an acceptable level of exercise, which has been recommended to me",
          value: false,
          required: true,
        },
        {
          label:
            "We assume no liability for persons who undertake physical activity. Should you be in any doubt after completing this questionnaire you agree to consult your doctor prior to undertaking physical activity.",
          value: false,
          required: true,
        },
      ],
    },
  ],
};

export default {
  data() {
    return {
      parq: cloneDeep(parg),
      default: cloneDeep(parg),
    };
  },
  props: {
    member: Object,
    requestParq: Boolean,
  },
  emits: ["done"],
  methods: {
    ...mapActions(useMemberStore, ["update_parq"]),
    ...mapActions(useAppStore, {
      update_request_parq: "update_parq",
    }),
    async onSubmit(args) {
      console.func("components/ParqForm:methods.onSubmit()", arguments);
      let cansubmit = true;
      if (this.noRequires) {
        await this.$core
          .confirm(
            "You have ticked to one of the medical questions. Can you please confirm you can manage this area yourself and have the enquiry of your doctor to carry out exercise."
          )
          .catch(() => {
            cansubmit = false;
          });
      }
      if (!cansubmit) return false;

      const method = this.requestParq
        ? this.update_request_parq
        : this.update_parq;
      method({
        ...this.parq,
        id: this.member.id,
      })
        .then(({ message, data }) => {
          this.$emit("done", this.parq);
          this.$core.success(message);
          this.parq = data;
        })
        .catch((error) => {
          this.$core.error(error, { title: this.$t("dialog.title.error") });
        });
    },
  },
  computed: {
    ...mapState(useAppStore, ["user"]),
    disable() {
      return (
        !this.parq.accept ||
        this.requires.length > 0 ||
        JSON.stringify(this.parq) === JSON.stringify(this.default)
      );
    },
    requires() {
      return this.parq.questions.filter(
        (item) =>
          item.list.filter((question) => question.required && !question.value)
            .length
      );
    },
    noRequires() {
      return (
        this.parq.questions.filter(
          (item) =>
            item.list.filter((question) => !question.required && question.value)
              .length
        ).length > 0
      );
    },
    readonly() {
      return "created_at" in this.parq;
    },
    completedBy() {
      if (this.parq.updated_by.admin) {
        return this.parq.updated_by.admin.name;
      }
      return this.$t("completedByMember");
    },
  },
  watch: {
    member: {
      deep: true,
      handler(val) {
        this.parq = {
          ...this.parq,
          ...val,
        };
      },
    },
  },
  mounted() {
    if (this.member.parq) {
      this.parq = cloneDeep(this.member.parq);
    } else {
      Object.assign(this.parq, {
        name: this.member.name,
        email: this.member.email,
      });
    }
    this.default = cloneDeep(this.parq);
  },
};
</script>
