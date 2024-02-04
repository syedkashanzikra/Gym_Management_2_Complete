<template>
  <q-page padding>
    <base-page-header
      :title="$t('appSettings')"
      :hint="$t('appSettingsDesc')"
      :tabs="['Locations', 'Taxes', 'Payments']"
      use-route
    />
    <div class="q-gutter-y-md q-pt-md">
      <base-section flat bordered no-row title="App details">
        <div class="config">
          <div class="row q-col-gutter-md">
            <div class="col-xs-12 col-sm-3">
              <base-label>Company name</base-label>
              <base-input
                placeholder="NitroFIT28"
                hint="Appears on your website"
                v-model="config.name"
              />
            </div>
            <div class="col-xs-12 col-sm-3">
              <base-label>Email</base-label>
              <base-input
                placeholder="info@company.com"
                hint="The email your app uses to send emails to your members"
                v-model="config.email"
              />
            </div>
            <div class="col-xs-12 col-sm-3">
              <base-label>Phone</base-label>
              <base-input placeholder="+1123456789" v-model="config.phone" />
            </div>
            <div class="col-xs-12 col-sm-3">
              <base-label>Country</base-label>
              <country-select v-model="config.country" />
            </div>
            <div class="col-xs-12 col-sm-3">
              <base-label>Timezone</base-label>
              <base-select
                :options="timezones"
                emit-value
                map-options
                outlined
                dense
                use-filter
                :filter-method="onFilterTimezone"
                v-model="config.timezone"
              />
            </div>
            <div class="col-xs-12 col-sm-3">
              <base-label>{{ $t("lang") }}</base-label>
              <base-select
                :options="[
                  { label: 'English', value: 'en-US' },
                  { label: 'हिंदी', value: 'hi_IN' },
                  { label: 'Français', value: 'fr' },
                  { label: 'Romanian (Romania)', value: 'ro_RO' },
                ]"
                map-options
                emit-value
                @update:model-value="$i18n.locale = $event"
                outlined
                dense
                v-model="config.lang"
              />
            </div>
            <div class="col-xs-12 col-sm-3">
              <base-label>Currency</base-label>
              <base-select
                :options="currencies"
                :option-label="optionLabel"
                option-value="code"
                outlined
                dense
                map-options
                emit-value
                v-model="config.currency"
              />
            </div>
            <div class="col-xs-12 col-sm-12">
              <base-checkbox
                v-model="config.shop"
                label="Selling product online?"
              />
            </div>
            <div class="col-xs-12 col-sm-12 self-end flex">
              <q-space />
              <div class="q-gutter-sm">
                <base-btn
                  color="primary"
                  icon="fas fa-save"
                  :loading="loading"
                  :label="$t('save')"
                  @click="onUpdateConfig"
                />
              </div>
            </div>
          </div>
        </div>
      </base-section>
      <base-section
        flat
        bordered
        no-row
        title="Application Configs"
        description="Here you can configure your opening times, billing information and frontend."
      >
        <div class="q-gutter-y-xs">
          <q-expansion-item
            class="base-settings-item"
            header-class="bg-grey-4 text-subtitle2 text-black"
            expand-icon-class="text-black"
            v-for="(item, index) in settings"
            :key="index"
            :icon="item.icon"
            :label="item.label"
            :disable="canSave"
            @before-show="onLoad(item)"
            :default-opened="index === 0"
            group="settings"
          >
            <template v-slot:header="{ expanded }">
              <q-item-section class="header-label">
                <q-item-label class="flex items-center">
                  <q-icon size="19px" :name="item.icon" class="q-mr-sm" />
                  {{ item.label }}
                </q-item-label>
              </q-item-section>
              <q-item-section
                :class="{ expanded: expanded, actions: true }"
                v-show="expanded"
                side
              >
                <q-item-label>
                  <q-btn
                    color="primary"
                    @click.stop="onAdd(item)"
                    icon="fad fa-circle-plus"
                    dense
                    round
                    flat
                    v-if="item.canAdd"
                  />
                  <q-btn
                    color="primary"
                    @click.stop="onSave(item)"
                    icon="fad fa-save"
                    dense
                    round
                    flat
                    v-if="canSave"
                    :loading="save"
                  />
                </q-item-label>
              </q-item-section>
            </template>
            <component
              v-bind="item"
              v-bind:options="options"
              :is="item.component"
              :loaded="loaded"
              v-if="currentSetting === item.key"
              @remove="onRemove"
              @update="onUpdate"
            />
          </q-expansion-item>
        </div>
      </base-section>
    </div>
  </q-page>
</template>

<script>
import { mapActions, mapState } from "pinia";
import { useAppStore } from "stores/app";
import { cloneDeep } from "lodash";
import { SettingsMixins } from "services/mixins";
import { timezones, onFilterTimezone } from "src/services/timezones";
import { currencies, optionLabel } from "src/services/currencies";
import CountrySelect from "src/components/address/CountrySelect.vue";

export default {
  mixins: [SettingsMixins],
  components: { CountrySelect },
  data() {
    return {
      options: [],
      default: [],
      timezones,
      currencies,
      loaded: false,
      loading: false,
      save: false,
      currentSetting: null,
    };
  },
  methods: {
    onFilterTimezone,
    ...mapActions(useAppStore, ["getSettings", "updateSettings"]),
    onLoad({ key }) {
      this.currentSetting = key;
      this.loaded = false;
      this.options = [];
      this.default = [];
      this.getSettings(key)
        .then((options) => {
          this.options = options;
          this.default = cloneDeep(options);
          this.loaded = true;
        })
        .catch((error) => {
          this.$core.error(error, { title: this.$t("dialog.title.error") });
          this.loaded = true;
        });
    },
    onUpdate({ value, rowIndex }) {
      console.func("pages/admins/SettingsPage:methods.onUpdate()", arguments);
      if (rowIndex) {
        Object.assign(this.options[rowIndex], value);
      }
    },
    onRemove(rowIndex) {
      console.func("pages/admins/SettingsPage:methods.onRemove()", arguments);
      this.options.splice(rowIndex, 1);
    },
    onAdd({ option }) {
      console.func("pages/admins/SettingsPage:methods.onAdd()", arguments);
      this.options.push({
        ...option,
        id: Date.now(),
      });
    },
    onSave({ key }) {
      console.func("pages/admins/SettingsPage:methods.onSave()", arguments);
      this.save = true;
      this.updateSettings({
        key,
        options: this.options,
      })
        .then(({ message }) => {
          this.$q.notify(message);
          this.loaded = false;
          this.$nextTick(() => {
            this.save = false;
            this.default = cloneDeep(this.options);
            this.loaded = true;
          });
        })
        .catch((error) => {
          this.save = false;
          this.$core.error(error, { title: this.$t("dialog.title.error") });
        });
    },
    onUpdateConfig() {
      console.func(
        "pages/admins/SettingsPage:methods.onUpdateConfig()",
        arguments
      );
      this.loading = true;
      this.updateSettings({
        key: "config",
        options: this.config,
      })
        .then(({ message }) => {
          this.$q.notify(message);
          this.loading = false;
        })
        .catch((error) => {
          this.loading = false;
          this.$core.error(error, { title: this.$t("dialog.title.error") });
        });
    },
    optionLabel,
  },
  mounted() {
    this.onLoad(this.settings[0]);
  },
  computed: {
    ...mapState(useAppStore, ["config"]),
    canSave() {
      return (
        this.default &&
        JSON.stringify(this.options) !== JSON.stringify(this.default)
      );
    },
    settings() {
      return [
        {
          label: "Mail Configuration",
          key: "mail",
          icon: "fas fa-envelope",
          component: "mail-config",
        },
        {
          label: this.$t("label.openingTimes"),
          key: "opening-times",
          icon: "fas fa-clock",
          component: "opening-times",
        },
        {
          label: "Billing Information",
          key: "address",
          icon: "fad fa-address-book",
          component: "billing-information",
        },
        {
          label: this.$t("label.documents"),
          key: "documents",
          icon: "fas fa-file",
          component: "documents-list",
          canAdd: true,
          option: {
            is_active: false,
            member: false,
          },
        },
        {
          label: this.$t("label.banners"),
          key: "banners",
          icon: "fas fa-image",
          component: "home-banners",
          canAdd: true,
          option: {
            is_active: false,
          },
        },
      ];
    },
  },
};
</script>
