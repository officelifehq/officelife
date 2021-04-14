<template>
  <layout :no-menu="true">
    <!-- <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Profile
            </h2>
        </template> -->

    <div>
      <div class="mw7 center br3 mb5 bg-white box relative z-1">
        <div v-if="$page.props.jetstream.canUpdateProfileInformation">
          <update-profile-information-form :user="$page.props.user" />
        </div>

        <div v-if="$page.props.jetstream.canUpdatePassword">
          <update-password-form class="mt-10 sm:mt-0" />
        </div>

        <div v-if="$page.props.jetstream.canManageTwoFactorAuthentication">
          <two-factor-authentication-form class="mt-10 sm:mt-0" />
        </div>

        <!-- <logout-other-browser-sessions-form :sessions="sessions" class="mt-10 sm:mt-0" /> -->

        <template v-if="$page.props.jetstream.hasAccountDeletionFeatures">
          <!-- <delete-user-form class="mt-10 sm:mt-0" /> -->
        </template>
      </div>
    </div>
  </layout>
</template>

<script>
import Layout from '@/Shared/Layout';
//    import DeleteUserForm from './DeleteUserForm'
// import LogoutOtherBrowserSessionsForm from './LogoutOtherBrowserSessionsForm'
import TwoFactorAuthenticationForm from './Partials/TwoFactorAuthenticationForm';
import UpdatePasswordForm from './Partials/UpdatePasswordForm';
import UpdateProfileInformationForm from './Partials/UpdateProfileInformationForm';

export default {

  components: {
    Layout,
    // DeleteUserForm,
    // LogoutOtherBrowserSessionsForm,
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
