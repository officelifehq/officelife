<template>
  <layout title="Home" :notifications="notifications">
    <div class="ph2 ph0-ns">
      <div class="cf mw6 center br3 mb3 bg-white box">
        <div class="pa3">
          <h2>{{ $t('auth.confirmation_title') }}</h2>

          <div v-show="resend" class="alert alert-success" role="alert">
            {{ $t('auth.confirmation_fresh') }}
          </div>

          <p>
            {{ $t('auth.confirmation_check') }}
          </p>
          <form class="di" @submit.prevent="submit">
            {{ $t('auth.confirmation_request_another') }}
            <loading-button :classes="'btn add w-auto-ns w-100 mb2 pv2 ph3'" :state="loadingState" :text="$t('auth.confirmation_request_another_button')" />
          </form>
        </div>
      </div>
    </div>
  </layout>
</template>

<script>
import LoadingButton from '@/Shared/LoadingButton';
import Layout from '@/Shared/Layout';

export default {
  components: {
    Layout,
    LoadingButton,
  },

  data() {
    return {
      loadingState: '',
      resend: false,
    };
  },

  methods: {
    submit() {
      this.loadingState = 'loading';

      axios.post(this.$route('verification.resend'))
        .then(response => {
          this.loadingState = null;
          this.resend = true;
        })
        .catch(error => {
          this.loadingState = null;
          if (typeof error.response.data === 'object') {
            this.form.errors = _.flatten(_.toArray(error.response.data));
          } else {
            this.form.errors = [this.$t('app.error_try_again')];
          }
        });
    },
  }
};
</script>
