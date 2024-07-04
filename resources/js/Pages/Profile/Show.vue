<template>
  <layout :no-menu="true">
    <div class="mt4">
      <div class="mw7 center br3 mb5 bg-white box relative z-1">
        <update-profile-information-form :user="$page.props.auth.user" />

        <update-password-form class="mt1" />

        <update-locale :user="$page.props.auth.user" :locales="$page.props.jetstream.languages" class="mt1" />

        <two-factor-authentication-form class="mt1" />

        <logout-other-browser-sessions-form :sessions="sessions" class="mt1" />
      </div>
    </div>
  </layout>
</template>

<script>
import Layout from '@/Shared/Layout.vue';
import LogoutOtherBrowserSessionsForm from '@/Pages/Profile/Partials/LogoutOtherBrowserSessionsForm.vue';
import TwoFactorAuthenticationForm from '@/Pages/Profile/Partials/TwoFactorAuthenticationForm.vue';
import UpdatePasswordForm from '@/Pages/Profile/Partials/UpdatePasswordForm.vue';
import UpdateProfileInformationForm from '@/Pages/Profile/Partials/UpdateProfileInformationForm.vue';
import UpdateLocale from '@/Pages/Profile/Partials/UpdateLocale.vue';

export default {

  components: {
    Layout,
    LogoutOtherBrowserSessionsForm,
    TwoFactorAuthenticationForm,
    UpdatePasswordForm,
    UpdateProfileInformationForm,
    UpdateLocale,
  },

  props: {
    sessions: {
      type: Array,
      default: () => [],
    },
  },
};
</script>
