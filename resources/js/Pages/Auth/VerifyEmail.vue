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
  <authentication-card>
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
        <loading-button :class="'add mb2'" :state="form.processing">
          {{ $t('auth.confirmation_request_another_button') }}
        </loading-button>
      </form>
    </div>
  </authentication-card>
</template>

<script>
import AuthenticationCard from '@/Shared/Layout/AuthenticationCard';
import LoadingButton from '@/Shared/LoadingButton';
import { useForm } from '@inertiajs/inertia-vue3';

export default {
  components: {
    AuthenticationCard,
    LoadingButton,
  },

  data() {
    return {
      form: useForm(),
      resend: false,
    };
  },

  mounted() {
    if (localStorage.success) {
      this.flash(localStorage.success, 'success');
      localStorage.removeItem('success');
    }
  },

  methods: {
    submit() {
      this.form.post(this.route('verification.send'), {
        onFinish: () => {
          this.resend = true;
          this.flash(this.$t('auth.confirmation_fresh'), 'success');
        },
        onError: (error) => {
          if (typeof error.response.data === 'object') {
            this.form.errors = error.response.data;
          } else {
            this.form.errors = [this.$t('app.error_try_again')];
          }
        }
      });
    },
  }
};
</script>
