import core from "./core";
import { sumBy, cloneDeep } from "lodash";
import DocumentsList from "components/settings/DocumentsList.vue";
import OpeningTimes from "components/settings/OpeningTimes.vue";
import HomeBanners from "components/settings/HomeBanners.vue";
import BillingInformation from "components/settings/BillingInformation.vue";
import MailConfig from "components/settings/MailConfig.vue";

export const SettingsMixins = {
  components: {
    DocumentsList,
    OpeningTimes,
    HomeBanners,
    BillingInformation,
    MailConfig,
  },
};

export const useReportMixin = {
  methods: {
    getTotal(key, money = true) {
      const total = sumBy(cloneDeep(this.rows), (o) =>
        o[key] ? parseFloat(o[key]) : 0
      );
      if (money) return core.money(total);
      return total;
    },
  },
};
