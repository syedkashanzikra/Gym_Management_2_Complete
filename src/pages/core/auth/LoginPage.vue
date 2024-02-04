<template>
  <div>
    <div class="text-h6 text-center q-mb-xl">{{ $t($route.meta.title) }}</div>
    <q-form ref="loginForm" @submit="onSubmit">
      <div class="col">
        <base-label>{{ $t("email") }}</base-label>
        <base-input
          v-model="form.email"
          outlined
          dense
          ref="email"
          bottom-slots
          :error-message="'email' in errors ? errors.email.join(', ') : ''"
          :error="'email' in errors"
        />
      </div>

      <div class="col">
        <base-label>{{ $t("password") }}</base-label>
        <base-input
          bottom-slots
          outlined
          dense
          :error-message="
            'password' in errors ? errors.password.join(', ') : ''
          "
          :error="'password' in errors"
          v-model="form.password"
          :type="isPwd ? 'password' : 'text'"
          class="password"
        >
          <template v-slot:append>
            <q-icon
              :name="isPwd ? 'visibility_off' : 'visibility'"
              class="cursor-pointer"
              @click="isPwd = !isPwd"
            />
          </template>
          <template v-slot:counter>
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

      <div class="q-mt-lg q-mb-sm col">
        <q-checkbox dense v-model="form.remember" :label="$t('rememberMe')" />
      </div>

      <q-btn
        :loading="visible"
        :label="$t('login')"
        class="full-width"
        color="secondary"
        align="center"
        type="submit"
        no-caps
      />
      <div v-if="!$route.meta.admin" class="q-mt-md text-center">
        {{ $t("donTHaveAnAccount") }}
        <base-btn
          :to="{ name: 'Sign up' }"
          link
          type="a"
          :label="$t('signUp')"
        />
      </div>
    </q-form>
  </div>
</template>

<script>
import { mapActions } from "pinia";
import { useAppStore } from "stores/app";

export default {
  data() {
    return {
      form: {
        remember: false,
        guard: this.$route.meta.guard,
      },
      isPwd: true,
      errors: {},
      visible: false,
    };
  },
  methods: {
    ...mapActions(useAppStore, ["login"]),
    onSubmit() {
      this.errors = {};
      this.visible = true;
      console.func("pages/login/LoginPage:methods.onSubmit()", arguments);
      this.login(this.form)
        .then((response) => {
          if (this.hasRedirect) {
            this.$router.replace(this.redirectPath);
          } else {
            this.$router.push({ name: "Dashboard" });
          }
          this.visible = false;
        })
        .catch((error) => {
          this.visible = false;
          if (error.errors) {
            this.errors = error.errors;
          } else {
            this.$core.error(error.message);
          }
        });
    },
  },
  mounted() {
    if (process.env.APP_DEBUG === "true") {
      Object.assign(this.form, {
        email: "hello@coderstm.com",
        password: "Gis0ra$$;",
      });
    }
  },
  computed: {
    hasRedirect() {
      return (
        this.$route.query.redirect && this.$route.query.redirect.length > 0
      );
    },
    redirectPath() {
      return this.$route.query.redirect;
    },
  },
};
</script>

<style lang="scss">
.password {
  &.q-field--with-bottom {
    padding-bottom: 0;
  }
  .q-field__bottom {
    display: inline;
    align-items: self-start;
    position: relative;
    .q-field__messages {
      padding-top: 5px;
    }
    .q-field__counter {
      padding: 0;
    }
  }
}
</style>
