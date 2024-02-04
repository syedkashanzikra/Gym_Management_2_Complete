<template>
  <div class="mail-config q-pb-md">
    <div v-if="loaded" class="q-pt-md row q-col-gutter-md">
      <div class="col-sm-3 col-xs-12">
        <div class="text-label">Email Send Method</div>
        <base-select
          @update:model-value="onChange($event, 'default')"
          readonly
          :model-value="options.default"
          map-options
          emit-value
          dense
          outlined
          :options="[{ label: 'SMTP', value: 'smtp' }]"
        />
      </div>
      <div class="col-sm-3 col-xs-12">
        <div class="text-label">Host</div>
        <base-input
          @update:model-value="onChange($event, 'mailers.smtp.host')"
          placeholder="e.g. smtp.googlemail.com"
          :model-value="options['mailers.smtp.host']"
        />
      </div>
      <div class="col-sm-3 col-xs-12">
        <div class="text-label">Port</div>
        <base-input
          @update:model-value="onChange($event, 'mailers.smtp.port')"
          placeholder="578"
          :model-value="options['mailers.smtp.port']"
        />
      </div>
      <div class="col-sm-3 col-xs-12">
        <div class="text-label">Encryption</div>
        <base-select
          @update:model-value="onChange($event, 'mailers.smtp.encryption')"
          :model-value="options['mailers.smtp.encryption']"
          map-options
          emit-value
          :options="[
            { label: 'SSL', value: 'ssl' },
            { label: 'TLS', value: 'tls' },
            { label: 'None', value: null },
          ]"
          dense
          outlined
        />
      </div>
      <div class="col-sm-3 col-xs-12">
        <div class="text-label">Username</div>
        <base-input
          @update:model-value="onChange($event, 'mailers.smtp.username')"
          :model-value="options['mailers.smtp.username']"
          placeholder="Normally your email address"
        />
      </div>
      <div class="col-sm-3 col-xs-12">
        <div class="text-label">Password</div>
        <base-input
          @update:model-value="onChange($event, 'mailers.smtp.password')"
          :model-value="options['mailers.smtp.password']"
          placeholder="Normally your email password"
        />
      </div>
      <div class="col-sm-12 col-xs-12 self-end">
        <base-btn @click="onTestMail" label="Test Mail" />
      </div>
    </div>
    <base-section-skeleton v-else />
  </div>
</template>

<script>
import BaseSectionSkeleton from "../skeleton/BaseSectionSkeleton.vue";
import TestMailDialog from "./TestMailDialog.vue";

export default {
  components: { BaseSectionSkeleton },
  props: {
    options: Object,
    loaded: Boolean,
  },
  emits: ["update"],
  methods: {
    onChange(val, key) {
      console.func(
        "components/settings/BillingInformation:methods.onChange()",
        arguments
      );
      const address = this.options;
      address[key] = val;
      this.$emit("update", address);
    },
    onTestMail() {
      console.func(
        "components/settings/BillingInformation:methods.onTestMail()",
        arguments
      );
      this.$q.dialog({
        component: TestMailDialog,
        componentProps: {
          mailConfig: this.options,
        },
      });
    },
  },
};
</script>
