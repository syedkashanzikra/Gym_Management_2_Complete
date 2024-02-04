<template>
  <div class="row q-col-gutter-md">
    <div v-if="hasField('name')" class="col-sm-6 col-xs-12">
      <div class="text-label">{{ $t("firstName") }}</div>
      <base-input
        @update:model-value="onChange($event, 'first_name')"
        :error-message="errorMessage('first_name', errors)"
        :error="hasError('first_name', errors)"
        :model-value="modelValue.first_name"
      />
    </div>
    <div v-if="hasField('name')" class="col-sm-6 col-xs-12">
      <div class="text-label">{{ $t("surname") }}</div>
      <base-input
        @update:model-value="onChange($event, 'last_name')"
        :error-message="errorMessage('last_name', errors)"
        :error="hasError('last_name', errors)"
        :model-value="modelValue.last_name"
      />
    </div>
    <div v-if="hasField('email')" class="col-sm-12 col-xs-12">
      <div class="text-label">{{ $t("email") }}</div>
      <base-input
        @update:model-value="onChange($event, 'email')"
        :error-message="errorMessage('email', errors)"
        :error="hasError('email', errors)"
        :model-value="modelValue.email"
      />
    </div>
    <div class="col-xs-12 col-sm-6">
      <div class="text-label">{{ $t("addressLine1") }}</div>
      <base-input
        @update:model-value="onChange($event, 'line1')"
        :error-message="errorMessage('line1', errors)"
        :error="hasError('line1', errors)"
        :model-value="modelValue.line1"
      />
    </div>
    <div class="col-xs-12 col-sm-6">
      <div class="text-label">{{ $t("addressLine2") }}</div>
      <base-input
        @update:model-value="onChange($event, 'line2')"
        :error-message="errorMessage('line2', errors)"
        :error="hasError('line2', errors)"
        :model-value="modelValue.line2"
      />
    </div>
    <div class="col-xs-12 col-sm-4">
      <div class="text-label">{{ $t("country") }}</div>
      <country-select
        @update:model-value="onChange($event, 'country')"
        :error-message="errorMessage('country', errors)"
        :error="hasError('country', errors)"
        :model-value="modelValue.country"
        @complete="onCompleteCountry"
      />
    </div>
    <div class="col-xs-12 col-sm-4">
      <div class="text-label">State</div>
      <state-select
        @update:model-value="onChange($event, 'state')"
        :model-value="modelValue.state"
        :country-code="country?.iso2"
        :has-country-states="hasCountryStates"
        :error-message="errorMessage('state', errors)"
        :error="hasError('state', errors)"
        @complete="onCompleteState"
      />
    </div>
    <div class="col-xs-12 col-sm-4">
      <div class="text-label">{{ $t("city") }}</div>
      <city-select
        @update:model-value="onChange($event, 'city')"
        :model-value="modelValue.city"
        :error-message="errorMessage('city', errors)"
        :error="hasError('city', errors)"
        :state="modelValue.state"
        :country="modelValue.country"
      />
    </div>
    <div class="col-xs-12 col-sm-4">
      <div class="text-label">{{ $t("postcodeZip") }}</div>
      <base-input
        @update:model-value="onChange($event, 'postal_code')"
        :error-message="errorMessage('postal_code', errors)"
        :error="hasError('postal_code', errors)"
        :model-value="modelValue.postal_code"
      />
    </div>
    <div v-if="hasField('phone')" class="col-xs-12 col-sm-6">
      <div class="text-label">{{ $t("phoneNumber") }}</div>
      <base-input
        @update:model-value="onChange($event, 'phone_number')"
        :error-message="errorMessage('phone_number', errors)"
        :error="hasError('phone_number', errors)"
        :model-value="modelValue.phone_number"
      />
    </div>
  </div>
</template>

<script>
import { mapActions } from "pinia";
import CountrySelect from "src/components/address/CountrySelect.vue";
import StateSelect from "src/components/address/StateSelect.vue";
import CitySelect from "src/components/address/CitySelect.vue";
import { useCountryStore } from "src/stores/country";

export default {
  components: { CountrySelect, StateSelect, CitySelect },
  props: {
    modelValue: {
      type: Object,
      required: true,
    },
    errors: {
      type: Object,
      default: () => ({}),
    },
    keys: {
      type: Array,
      default: () => [],
    },
    all: Boolean,
    prefix: {
      type: String,
      default: "",
    },
  },
  emits: ["update:model-value"],
  data() {
    return {
      country: null,
    };
  },
  methods: {
    ...mapActions(useCountryStore, ["find"]),
    onChange(val, key) {
      console.func(
        "components/order/AddressFields:methods.onChange()",
        arguments
      );
      const modelValue = this.modelValue;
      modelValue[key] = val;
      this.$emit("update:model-value", modelValue);
    },
    onCompleteCountry(val) {
      this.country = val;
      this.$emit("update:model-value", {
        ...this.modelValue,
        country_code: val?.iso2,
      });
    },
    onCompleteState(val) {
      this.$emit("update:model-value", {
        ...this.modelValue,
        state_code: val?.state_code,
      });
    },
    hasField(key) {
      return this.all ?? this.keys.includes(key);
    },
    errorMessage(field) {
      return this.$core.errorMessage(this.prefix + field, this.errors);
    },
    hasError(field) {
      return this.$core.hasError(this.prefix + field, this.errors);
    },
  },
  computed: {
    countryCode() {
      return this.country?.iso2;
    },
    hasCountryStates() {
      return this.country?.states_count > 0;
    },
  },
  watch: {
    modelValue: {
      deep: true,
      handler(val, old) {
        if (!this.country) {
          this.$core.debounce(() => {
            this.find({
              code: val?.country_code,
              name: val?.country,
            }).then((data) => {
              this.country = data;
            });
          }, 500);
        }
      },
    },
  },
};
</script>
