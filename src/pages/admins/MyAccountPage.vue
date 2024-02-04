<template>
  <q-page padding>
    <div class="q-gutter-y-lg">
      <base-section flat bordered no-row :title="$t('profileInformation')">
        <q-form @submit="onSaveProfile">
          <div class="row q-col-gutter-md">
            <div class="col-sm-3 col-xs-12">
              <base-label>{{ $t("firstName") }}</base-label>
              <base-input
                :error-message="$core.errorMessage('first_name', errors)"
                :error="$core.hasError('first_name', errors)"
                v-model="user.first_name"
              />
            </div>
            <div class="col-sm-3 col-xs-12">
              <base-label>{{ $t("surname") }}</base-label>
              <base-input
                :error-message="$core.errorMessage('last_name', errors)"
                :error="$core.hasError('last_name', errors)"
                v-model="user.last_name"
              />
            </div>
            <div class="col-sm-3 col-xs-12">
              <base-label>{{ $t("email") }}</base-label>
              <base-input
                :error-message="$core.errorMessage('email', errors)"
                :error="$core.hasError('email', errors)"
                v-model="user.email"
              />
            </div>
            <div class="col-sm-3 col-xs-12">
              <base-label>{{ $t("phoneNumber") }}</base-label>
              <base-input
                :error-message="$core.errorMessage('phone_number', errors)"
                :error="$core.hasError('phone_number', errors)"
                v-model="user.phone_number"
              />
            </div>

            <div class="col-sm-3 col-xs-12">
              <base-label>{{ $t("password") }}</base-label>
              <base-input
                type="password"
                readonly
                bottom-slots
                modelValue="**********"
              >
                <template v-slot:hint>
                  <base-btn
                    size="11px"
                    color="grey"
                    :to="{ name: 'Forget Password' }"
                    link
                    type="a"
                    :label="$t('forgottenPassword')"
                  />
                </template>
              </base-input>
            </div>

            <div class="col-xs-12">
              <address-fields
                prefix="address."
                :errors="errors"
                v-model="user.address"
              />
            </div>

            <div class="col-sm-12 col-xs-12 text-right self-end">
              <base-btn
                class="main-btn"
                :loading="visible"
                style="width: 100px"
                :label="$t('save')"
                type="submit"
              />
            </div>
          </div>
        </q-form>
      </base-section>
    </div>
  </q-page>
</template>

<script>
import { mapActions, mapState } from "pinia";
import { useAppStore } from "stores/app";
import AddressFields from "src/components/address/AddressFields.vue";

export default {
  components: { AddressFields },
  data() {
    return {
      visible: false,
      errors: {},
    };
  },
  methods: {
    ...mapActions(useAppStore, ["update", "currentUser"]),
    onSaveProfile() {
      console.func("pages/SignUpPage:methods.onSaveProfile()", arguments);
      this.visible = true;
      this.errors = {};
      this.update({
        ...this.user,
        guard: this.$route.meta.guard,
      })
        .then(() => {
          this.visible = false;
        })
        .catch(({ errors, message }) => {
          this.visible = false;
          if (errors) {
            this.errors = errors;
          } else {
            this.$core.error(message);
          }
        });
    },
  },
  computed: {
    ...mapState(useAppStore, ["user"]),
  },
};
</script>
