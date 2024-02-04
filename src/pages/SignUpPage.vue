<template>
  <q-page class="row flex-center items-center vertical-middle" padding>
    <div class="signup-container q-pa-xl">
      <div class="text-h5 q-mb-md">{{ $t("Signup Form") }}</div>
      <q-form @submit="onSubmit">
        <div class="row q-col-gutter-md">
          <div class="col-sm-12 col-xs-12">
            <div class="text-h6">{{ $t("Personal details") }}</div>
          </div>
          <div class="col-xs-12 col-sm-2">
            <base-label>{{ $t("title") }}</base-label>
            <base-select
              v-model="form.title"
              :error-message="$core.errorMessage('title', errors)"
              :error="$core.hasError('title', errors)"
              dense
              outlined
              :options="['Mr', 'Mrs', 'Ms', 'Miss', 'Mx', 'Dr', 'Fr', 'Prof']"
              name="title"
            />
          </div>
          <div class="col-sm-5 col-xs-12">
            <base-label>{{ $t("firstName") }}</base-label>
            <base-input
              :error-message="$core.errorMessage('first_name', errors)"
              :error="$core.hasError('first_name', errors)"
              placeholder="Jone"
              v-model="form.first_name"
              name="first_name"
            />
          </div>
          <div class="col-sm-5 col-xs-12">
            <base-label>{{ $t("surname") }}</base-label>
            <base-input
              :error-message="$core.errorMessage('last_name', errors)"
              :error="$core.hasError('last_name', errors)"
              placeholder="Done"
              v-model="form.last_name"
              name="last_name"
            />
          </div>
          <div class="col-xs-12 col-sm-12">
            <div class="row q-col-gutter-md">
              <div class="col-sm-4 col-xs-12">
                <base-label>{{ $t("email") }}</base-label>
                <base-input
                  :error-message="$core.errorMessage('email', errors)"
                  :error="$core.hasError('email', errors)"
                  placeholder="yourname@example.com"
                  v-model="form.email"
                  name="email"
                />
              </div>
              <div class="col-sm-4 col-xs-12">
                <base-label>{{ $t("phoneNumber") }}</base-label>
                <base-input
                  :error-message="$core.errorMessage('phone_number', errors)"
                  :error="$core.hasError('phone_number', errors)"
                  placeholder="+44 1632 960806"
                  v-model="form.phone_number"
                  name="phone_number"
                />
              </div>
            </div>
          </div>
          <div class="col-sm-12 col-xs-12">
            <address-fields v-model="form" />
          </div>
          <div class="col-xs-12 col-sm-12">
            <div class="row q-col-gutter-md">
              <div class="col-sm-4 col-xs-12">
                <base-label>{{ $t("password") }}</base-label>
                <base-input
                  v-model="form.password"
                  :error-message="$core.errorMessage('password', errors)"
                  :error="$core.hasError('password', errors)"
                  :type="isPwd1 ? 'password' : 'text'"
                  name="password"
                >
                  <template v-slot:append>
                    <q-icon
                      :name="isPwd1 ? 'visibility_off' : 'visibility'"
                      class="cursor-pointer"
                      @click="isPwd1 = !isPwd1"
                    />
                  </template>
                </base-input>
              </div>
              <div class="col-sm-4 col-xs-12">
                <base-label>{{ $t("confirmPassword") }}</base-label>
                <base-input
                  v-model="form.password_confirmation"
                  name="password_confirmation"
                  :error-message="
                    $core.errorMessage('password_confirmation', errors)
                  "
                  :error="$core.hasError('password_confirmation', errors)"
                  :type="isPwd2 ? 'password' : 'text'"
                >
                  <template v-slot:append>
                    <q-icon
                      :name="isPwd2 ? 'visibility_off' : 'visibility'"
                      class="cursor-pointer"
                      @click="isPwd2 = !isPwd2"
                    />
                  </template>
                </base-input>
              </div>
            </div>
          </div>
          <div class="col-sm-12 col-xs-12">
            <div class="flex">
              <div class="text-h6">{{ $t("Select a plan") }}</div>
              <q-space />
              <q-toggle
                dense
                v-model="form.interval"
                true-value="year"
                false-value="month"
                :label="$t('yearly')"
                name="interval"
              />
            </div>
            <div class="row q-pt-md q-col-gutter-md">
              <div
                class="col-xs-12 col-sm-4"
                v-for="(plan, index) in plans"
                :key="index"
              >
                <plan-card
                  flat
                  v-bind="plan"
                  :interval="form.interval"
                  :is-custom="plan.is_custom"
                  v-model:current="form.plan"
                  dense
                />
              </div>
            </div>
          </div>
          <div v-if="plan" class="col-sm-12 co-xs-12 plan-actions">
            <div class="plan q-pa-lg">
              <div class="text-h6">{{ $t("Your plan") }}</div>
              <div class="info">
                {{ plan.label }} # {{ $core.money(amount) }}
                {{ intervalLabel }}
              </div>
              <div class="features">
                <ul class="flex">
                  <li v-for="(feature, index) in plan.features" :key="index">
                    <span v-html="feature"></span>
                  </li>
                </ul>
              </div>
            </div>
          </div>
          <div class="col-sm-12 col-xs-12">
            <div class="row q-col-gutter-md">
              <div class="col-sm-12 col-xs-12">
                <div class="text-h6">{{ $t("paymentMethod") }}</div>
              </div>
              <div
                v-for="(method, index) in paymentMethods"
                :key="index"
                class="col-xs-12 col-sm-6 col-md-4"
              >
                <payment-method-card
                  v-model:selected="form.paymentMethod"
                  @update:selected="onSelectPaymentMethod(method)"
                  v-bind="method"
                  pay-mode
                />
              </div>
            </div>
          </div>
          <div
            v-show="form.paymentMethod?.provider === 'stripe'"
            class="col-sm-12 col-xs-12"
          >
            <stripe-custom-card
              :title="$t('cardDetails')"
              ref="stripeCard"
              :disabled="!form.terms"
              class="pay-and-sign-up"
              :amount="amount"
              :processing="processing"
              :billing-details="billingDetails"
              flat
              dense
              @success="onSuccess"
              @confirmed="onConfirmed"
              @failed="onFailed"
            />
          </div>
          <div class="col-xs-12 col-sm-5 offset-sm-4">
            <div class="terms">
              <q-checkbox dense size="sm" v-model="form.terms">
                I have read and agree to the
                <base-btn
                  :href="privacyUrl"
                  @click.stop="$core.openURL(privacyUrl)"
                  size="11px"
                  type="a"
                  link
                  target="_blank"
                >
                  privacy policy
                </base-btn>
                and
                <base-btn
                  :href="termsUrl"
                  @click.stop="$core.openURL(termsUrl)"
                  size="11px"
                  type="a"
                  link
                  target="_blank"
                >
                  terms and conditions
                </base-btn>
              </q-checkbox>
            </div>
          </div>
          <div class="col-sm-3 co-xs-12">
            <base-btn
              style="width: 180px"
              :disabled="!form.terms"
              :loading="processing"
              :label="$t('confirmPayment')"
              type="submit"
            />
          </div>
        </div>
      </q-form>
    </div>
  </q-page>
</template>

<script>
import { mapActions, mapState } from "pinia";
import { useAppStore } from "stores/app";
import { useSubscriptionStore } from "stores/subscription";
import StripeCustomCard from "components/payment-methods/StripeCustomCard.vue";
import PlanCard from "components/subscription/PlanCard.vue";
import PaymentMethodCard from "components/payment-methods/PaymentMethodCard.vue";
import AddressFields from "src/components/address/AddressFields.vue";

const guard = "users";

export default {
  components: {
    StripeCustomCard,
    PlanCard,
    PaymentMethodCard,
    AddressFields,
  },
  data() {
    return {
      form: {
        title: "Mr",
        plan: null,
        interval: "month",
        terms: false,
        paymentMethod: null,
        guard,
      },
      errors: {},
      processing: false,
      isPwd1: true,
      isPwd2: true,
    };
  },
  methods: {
    ...mapActions(useAppStore, ["signUp", "currentUser"]),
    ...mapActions(useSubscriptionStore, ["confirm", "subscribe", "getPlans"]),
    onSubmit() {
      console.func("pages/SignUpPage:methods.onSubmit()", arguments);
      if (this.form.manual_payment) {
        this.onManualPayment();
      } else {
        this.$refs.stripeCard.submit();
      }
    },
    onReset() {
      this.errors = {};
      this.processing = true;
    },
    onSuccess({ id, card }) {
      console.func("pages/SignUpPage:methods.onSuccess()", arguments);
      this.onReset();
      this.processing = true;
      const { last4 } = card;
      this.signUp(this.form)
        .then((resonse) => {
          console.log("pages/SignUpPage:methods.signUp().then()", resonse);
          this.subscribe({
            plan: this.form.plan,
            interval: this.form.interval,
            payment_method: id,
            last_four_digit: last4,
          })
            .then((resonse) => {
              console.log(
                "pages/SignUpPage:methods.signUp().then().subscribe().then()",
                resonse
              );
              const { message, requiresAction, paymentIntent } = resonse;
              if (requiresAction) {
                // this.$core.error('Please confirm your payment.', { title: 'Error' });
                this.$refs.stripeCard.confirmPaymentMethod(paymentIntent);
              } else {
                this.currentUser("users").then(() => {
                  this.$core.warning(message, {
                    title: this.$t("dialog.title.success"),
                  });
                  this.$router.push({ name: "Dashboard" });
                  this.processing = false;
                });
              }
            })
            .catch((error) => {
              this.$router.push({ name: "Dashboard" });
              this.$core.error(error, { title: this.$t("dialog.title.error") });
              this.processing = false;
            });
        })
        .catch(({ errors, message }) => {
          this.processing = false;
          if (errors) {
            this.errors = errors;
          } else {
            this.$core.error(message);
          }
        });
    },
    onSelectPaymentMethod(val) {
      console.func(
        "pages/SignUpPage:methods.onSelectPaymentMethod()",
        arguments
      );
      this.form.manual_payment = val.provider === "manual";
    },
    onManualPayment() {
      console.func("pages/SignUpPage:methods.onManualPayment()", arguments);
      this.onReset();
      this.processing = true;
      this.signUp(this.form)
        .then((resonse) => {
          console.log("pages/SignUpPage:methods.signUp().then()", resonse);
          this.subscribe({
            plan: this.form.plan,
            interval: this.form.interval,
            payment_method: "manual",
          })
            .then((resonse) => {
              console.log(
                "pages/SignUpPage:methods.signUp().then().subscribe().then()",
                resonse
              );
              const { message } = resonse;
              this.currentUser("users").then(() => {
                this.$core.warning(message, {
                  title: this.$t("dialog.title.payment"),
                });
                this.$router.push({ name: "Dashboard" });
                this.processing = false;
              });
            })
            .catch((error) => {
              this.$router.push({ name: "Dashboard" });
              this.$core.error(error, { title: this.$t("dialog.title.error") });
              this.processing = false;
            });
        })
        .catch(({ errors, message }) => {
          this.processing = false;
          if (errors) {
            this.errors = errors;
          } else {
            this.$core.error(message);
          }
        });
    },
    onFailed(error) {
      console.func("pages/SignUpPage:methods.onFailed()", arguments);
      this.$core.error(error, { title: this.$t("dialog.title.error") });
      this.processing = false;
    },
    onConfirmed(args) {
      console.func("pages/SignUpPage:methods.onConfirmed()", arguments);
      this.confirm({
        plan: this.plan.key,
        payment_intent: args.id,
      })
        .then(({ message }) => {
          this.$core.success(message, {
            title: this.$t("dialog.title.success"),
          });
          this.$router.push({ name: "Dashboard" });
          this.processing = false;
        })
        .catch((error) => {
          this.$router.push({ name: "Dashboard" });
          this.$core.error(error, { title: this.$t("dialog.title.error") });
          this.processing = false;
        });
    },
  },
  computed: {
    ...mapState(useAppStore, ["paymentMethods"]),
    ...mapState(useSubscriptionStore, ["plans"]),
    plan() {
      return this.plans.find(({ id }) => id === this.form.plan);
    },
    price() {
      if (this.plan?.is_custom) {
        return this.plan?.prices[0];
      }
      return this.plan?.prices.find(
        ({ interval }) => interval === this.form.interval
      );
    },
    intervalLabel() {
      return this.$core.priceToInterval(this.price, false, true);
    },
    amount() {
      return this.price?.amount || 0;
    },
    billingDetails() {
      return {
        name: `${this.form.first_name} ${this.form.last_name}`,
        email: this.form.email,
        phone: this.form.phone_number,
        address: {
          line1: this.form.line1,
          line2: this.form.line2,
          city: this.form.city,
          postal_code: this.form.postal_code,
        },
      };
    },
    queryPlan() {
      return Number(this.$route.query.plan);
    },
    hasQueryPlan() {
      return Boolean(
        this.plans.filter(({ id }) => id === this.queryPlan).length
      );
    },
    privacyUrl() {
      return process.env.PRIVACY_URL;
    },
    termsUrl() {
      return process.env.TERMS_URL;
    },
  },
  async mounted() {
    await this.getPlans();
    this.$nextTick(() => {
      Object.assign(this.form, {
        plan: this.hasQueryPlan ? this.queryPlan : this.plans[0]?.id,
        interval: this.$route.query?.interval || "month",
        paymentMethod: this.paymentMethods[0],
      });
      this.onSelectPaymentMethod(this.paymentMethods[0]);
    });
  },
};
</script>
