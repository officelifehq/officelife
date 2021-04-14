<template>
  <action-section>
    <template #title>
      Two Factor Authentication
    </template>

    <template #description>
      Add additional security to your account using two factor authentication.
    </template>

    <template #content>
      <h3 v-if="twoFactorEnabled" class="text-lg font-medium text-gray-900">
        You have enabled two factor authentication.
      </h3>

      <h3 v-else class="text-lg font-medium text-gray-900">
        You have not enabled two factor authentication.
      </h3>

      <div class="mt-3 max-w-xl text-sm text-gray-600">
        <p>
          When two factor authentication is enabled, you will be prompted for a secure, random token during authentication. You may retrieve this token from your phone's Google Authenticator application.
        </p>
      </div>

      <div v-if="twoFactorEnabled">
        <div v-if="qrCode">
          <div class="mt-4 max-w-xl text-sm text-gray-600">
            <p class="font-semibold">
              Two factor authentication is now enabled. Scan the following QR code using your phone's authenticator application.
            </p>
          </div>

          <div class="mt-4 dark:p-4 dark:w-56 dark:bg-white" v-html="qrCode">
          </div>
        </div>

        <div v-if="recoveryCodes.length > 0">
          <div class="mt-4 max-w-xl text-sm text-gray-600">
            <p class="font-semibold">
              Store these recovery codes in a secure password manager. They can be used to recover access to your account if your two factor authentication device is lost.
            </p>
          </div>

          <div class="grid gap-1 max-w-xl mt-4 px-4 py-4 font-mono text-sm bg-gray-100 rounded-lg">
            <div v-for="code in recoveryCodes" :key="code">
              {{ code }}
            </div>
          </div>
        </div>
      </div>

      <div class="mt-5">
        <div v-if="! twoFactorEnabled">
          <confirms-password @confirmed="enableTwoFactorAuthentication">
            <loading-button :state="enabling">
              Enable
            </loading-button>
          </confirms-password>
        </div>

        <div v-else>
          <confirms-password @confirmed="regenerateRecoveryCodes">
            <secondary-button v-if="recoveryCodes.length > 0"
                              class="mr-3"
            >
              Regenerate Recovery Codes
            </secondary-button>
          </confirms-password>

          <confirms-password @confirmed="showRecoveryCodes">
            <secondary-button v-if="recoveryCodes.length === 0" class="mr-3">
              Show Recovery Codes
            </secondary-button>
          </confirms-password>

          <confirms-password @confirmed="disableTwoFactorAuthentication">
            <danger-button
              :class="{ 'opacity-25': disabling }"
              :disabled="disabling"
            >
              Disable
            </danger-button>
          </confirms-password>
        </div>
      </div>
    </template>
  </action-section>
</template>

<script>
import ActionSection from '@/Shared/ActionSection';
import LoadingButton from '@/Shared/LoadingButton';
import ConfirmsPassword from '@/Shared/ConfirmsPassword';
import DangerButton from '@/Shared/DangerButton';
import SecondaryButton from '@/Shared/SecondaryButton';
import { useForm } from '@inertiajs/inertia-vue3';

export default {
  components: {
    ActionSection,
    LoadingButton,
    ConfirmsPassword,
    DangerButton,
    SecondaryButton,
  },

  data() {
    return {
      enabling: false,
      disabling: false,

      qrCode: null,
      recoveryCodes: [],
    };
  },

  computed: {
    twoFactorEnabled() {
      return ! this.enabling && this.$page.props.user.two_factor_enabled;
    }
  },

  methods: {
    enableTwoFactorAuthentication() {
      this.enabling = true;

      useForm().post('/user/two-factor-authentication', {}, {
        preserveScroll: true,
        onSuccess: () => Promise.all([
          this.showQrCode(),
          this.showRecoveryCodes(),
        ]),
        onFinish: () => (this.enabling = false),
      });
    },

    showQrCode() {
      return axios.get('/user/two-factor-qr-code')
        .then(response => {
          this.qrCode = response.data.svg;
        });
    },

    showRecoveryCodes() {
      return axios.get('/user/two-factor-recovery-codes')
        .then(response => {
          this.recoveryCodes = response.data;
        });
    },

    regenerateRecoveryCodes() {
      axios.post('/user/two-factor-recovery-codes')
        .then(response => {
          this.showRecoveryCodes();
        });
    },

    disableTwoFactorAuthentication() {
      this.disabling = true;

      this.$inertia.delete('/user/two-factor-authentication', {
        preserveScroll: true,
        onSuccess: () => (this.disabling = false),
      });
    },
  }
};
</script>
