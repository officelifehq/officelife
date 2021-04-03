<style scoped>
  .alert {
    position:relative;
    border:1px solid transparent;
    border-radius:.25rem;
    padding:.75rem 1.25rem;
    margin:20px 0;
    margin-bottom:1rem;
  }
  .alert-success {
    color:#1d643b;
    background-color:#d7f3e3;
    border-color:#c7eed8;
  }
</style>

<template>
  <layout :no-menu="true" :notifications="notifications">
    <div class="ph2 ph0-ns">
      <div class="cf mt4 mw7 center br3 mb3 bg-white box">
        <div class="pa3 tc">
          <h2 class="fw4 mb3">
            {{ $t('auth.confirmation_title') }}
          </h2>

          <img loading="lazy" src="/img/streamline-icon-email-send-3@140x140.png" width="140" height="140" alt="meeting"
               class="mb4"
          />

          <p>
            {{ $t('auth.confirmation_check') }}
          </p>
          <p>
            {{ $t('auth.confirmation_request_another') }}
          </p>

          <form class="di" @submit.prevent="submit">
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
    LoadingButton,
    Layout,
  },

  data() {
    return {
      loadingState: '',
      resend: false,
    };
  },

  mounted() {
    if (localStorage.success) {
      flash(localStorage.success, 'success');
      localStorage.removeItem('success');
    }
  },

  methods: {
    submit() {
      this.loadingState = 'loading';

      axios.post(this.route('verification.resend'))
        .then(response => {
          this.loadingState = null;
          this.resend = true;
          flash(this.$t('auth.confirmation_fresh'), 'success');
        })
        .catch(error => {
          this.loadingState = null;
          if (typeof error.response.data === 'object') {
            this.form.errors = error.response.data;
          } else {
            this.form.errors = [this.$t('app.error_try_again')];
          }
        });
    },
  }
};
</script>
