<style lang="scss" scoped>
.grid {
  display: grid;
}
.gap-1 {
  gap: 0.25rem;
}
</style>

<template>
  <action-section>
    <template #title>
      Two Factor Authentication
    </template>

    <template #description>
      Add additional security to your account using two factor authentication.
    </template>

    <template #content>
      <h3 v-if="twoFactorEnabled" class="fw6">
        You have enabled two factor authentication.
      </h3>

      <h3 v-else class="fw6">
        You have not enabled two factor authentication.
      </h3>

      <div class="mt3 f6">
        <p>
          When two factor authentication is enabled, you will be prompted for a secure, random token during authentication. You may retrieve this token from your phone's Google Authenticator application.
        </p>
      </div>

      <div v-if="twoFactorEnabled">
        <div v-if="qrCode">
          <div class="mt4 f5">
            <p class="fw6">
              Two factor authentication is now enabled. Scan the following QR code using your phone's authenticator application.
            </p>
          </div>

          <div class="mt4" v-html="qrCode">
          </div>
        </div>

        <div v-if="recoveryCodes.length > 0">
          <div class="mt4 f5">
            <p class="fw6">
              Store these recovery codes in a secure password manager. They can be used to recover access to your account if your two factor authentication device is lost.
            </p>
          </div>

          <div class="grid gap-1 mw6 mt4 pa3 br3 code f4 bg-gray">
            <div v-for="code in recoveryCodes" :key="code">
              {{ code }}
            </div>
          </div>
        </div>
      </div>

      <div class="mt2">
        <div v-if="! twoFactorEnabled">
          <confirms-password @confirmed="enableTwoFactorAuthentication">
            <loading-button class="add mb2" type="button" :state="enabling">
              Enable
            </loading-button>
          </confirms-password>
        </div>

        <div v-else>
          <confirms-password @confirmed="regenerateRecoveryCodes">
            <loading-button v-if="recoveryCodes.length > 0" type="button" class="mr3">
              Regenerate Recovery Codes
            </loading-button>
          </confirms-password>

          <confirms-password @confirmed="showRecoveryCodes">
            <loading-button v-if="recoveryCodes.length === 0" type="button" class="mr3">
              Show Recovery Codes
            </loading-button>
          </confirms-password>

          <confirms-password @confirmed="disableTwoFactorAuthentication">
            <loading-button class="destroy mb2" type="button" :state="disabling">
              Disable
            </loading-button>
          </confirms-password>
        </div>
      </div>
    </template>
  </action-section>
</template>

<script>
import ActionSection from '@/Shared/Layout/ActionSection';
import LoadingButton from '@/Shared/LoadingButton';
import ConfirmsPassword from '@/Shared/ConfirmsPassword';

export default {
  components: {
    ActionSection,
    LoadingButton,
    ConfirmsPassword,
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

      this.$inertia.post('/user/two-factor-authentication', {}, {
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
