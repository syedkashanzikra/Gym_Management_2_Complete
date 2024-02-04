<template>
  <div class="billing-information q-pb-md">
    <div v-if="loaded" class="q-pt-md row q-col-gutter-md">
      <div class="col-sm-6 col-xs-12">
        <div class="text-label">Legal business name</div>
        <base-input
          @update:model-value="onChange($event, 'company')"
          :model-value="options.company"
        />
      </div>
      <div class="col-xs-12">
        <div class="row q-col-gutter-md">
          <div class="col-xs-12 col-sm-6">
            <div class="text-label">Address line 1</div>
            <base-input
              @update:model-value="onChange($event, 'line1')"
              :model-value="options.line1"
            />
          </div>
          <div class="col-xs-12 col-sm-6">
            <div class="text-label">Address line 2</div>
            <base-input
              @update:model-value="onChange($event, 'line2')"
              :model-value="options.line2"
            />
          </div>
        </div>
      </div>
      <div class="col-sm-12">
        <div class="text-label">Country/region</div>
        <country-select
          @update:model-value="onChange($event, 'country')"
          :model-value="options.country"
          @complete="onChange($event?.iso2, 'country_code')"
        />
      </div>
      <div class="col-xs-12 col-sm-4">
        <div class="text-label">City</div>
        <city-select
          @update:model-value="onChange($event, 'city')"
          :model-value="options.city"
          :state="options.state"
        />
      </div>
      <div class="col-xs-12 col-sm-4">
        <div class="text-label">State</div>
        <state-select
          @update:model-value="onChange($event, 'state')"
          :model-value="options.state"
          :country="options.country"
          @complete="onChange($event?.state_code, 'state_code')"
        />
      </div>
      <div class="col-xs-12 col-sm-4">
        <div class="text-label">Postal code</div>
        <base-input
          @update:model-value="onChange($event, 'postal_code')"
          :model-value="options.postal_code"
        />
      </div>
    </div>
    <base-section-skeleton v-else />
  </div>
</template>

<script>
import CountrySelect from "src/components/address/CountrySelect.vue";
import StateSelect from "src/components/address/StateSelect.vue";
import CitySelect from "src/components/address/CitySelect.vue";
import BaseSectionSkeleton from "../skeleton/BaseSectionSkeleton.vue";

export default {
  components: { CountrySelect, StateSelect, CitySelect, BaseSectionSkeleton },
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
  },
};
</script>
