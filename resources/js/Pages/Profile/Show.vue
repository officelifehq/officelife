<template>
  <layout :no-menu="true">
    <div class="mt4">
      <div class="mw7 center br3 mb5 bg-white box relative z-1">
        <div v-if="$page.props.jetstream.canUpdateProfileInformation">
          <update-profile-information-form :user="$page.props.user" />
        </div>

        <div v-if="$page.props.jetstream.canUpdatePassword">
          <update-password-form class="mt1" />
        </div>

        <div v-if="$page.props.jetstream.canManageTwoFactorAuthentication">
          <two-factor-authentication-form class="mt1" />
        </div>

        <logout-other-browser-sessions-form :sessions="sessions" class="mt1" />
      </div>
    </div>
  </layout>
</template>

<script>
import Layout from '@/Shared/Layout';
import LogoutOtherBrowserSessionsForm from './Partials/LogoutOtherBrowserSessionsForm';
import TwoFactorAuthenticationForm from './Partials/TwoFactorAuthenticationForm';
import UpdatePasswordForm from './Partials/UpdatePasswordForm';
import UpdateProfileInformationForm from './Partials/UpdateProfileInformationForm';

export default {

  components: {
    Layout,
    // DeleteUserForm,
    LogoutOtherBrowserSessionsForm,
    TwoFactorAuthenticationForm,
    UpdatePasswordForm,
    UpdateProfileInformationForm,
  },
  props: {
    sessions: {
      type: Array,
      default: () => [],
    },
  },
};
</script>
