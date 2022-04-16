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
      {{ $t('profile.2fa_title') }}
    </template>

    <template #description>
      {{ $t('profile.2fa_description') }}
    </template>

    <template #content>
      <p v-if="twoFactorEnabled" class="fw6 mt0">
        {{ $t('profile.2fa_message_enabled') }}
      </p>

      <p v-else class="fw6 mt0">
        {{ $t('profile.2fa_message_disabled') }}
      </p>

      <div class="mt3 f6">
        <p class="lh-copy">
          {{ $t('profile.2fa_disclaimer') }}
        </p>
      </div>

      <div v-if="twoFactorEnabled">
        <div v-if="qrCode">
          <div class="mt4 f5">
            <p class="fw6">
              {{ $t('profile.2fa_activated') }}
            </p>
          </div>

          <div class="mt4" v-html="qrCode">
          </div>
        </div>

        <div v-if="recoveryCodes.length > 0">
          <div class="mt4 f5">
            <p class="fw6">
              {{ $t('profile.2fa_recovery_codes') }}
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
              {{ $t('app.enable') }}
            </loading-button>
          </confirms-password>
        </div>

        <div v-else>
          <confirms-password @confirmed="regenerateRecoveryCodes">
            <loading-button v-if="recoveryCodes.length > 0" type="button" class="mr3">
              {{ $t('profile.2fa_regenerate_recovery_codes_action') }}
            </loading-button>
          </confirms-password>

          <confirms-password @confirmed="showRecoveryCodes">
            <loading-button v-if="recoveryCodes.length === 0" type="button" class="mr3">
              {{ $t('profile.2fa_show_recovery_codes_action') }}
            </loading-button>
          </confirms-password>

          <confirms-password @confirmed="disableTwoFactorAuthentication">
            <loading-button class="destroy mb2" type="button" :state="disabling">
              {{ $t('app.disable') }}
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
