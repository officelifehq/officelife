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
  <div class="ph2 ph0-ns">
    <div class="cf mt4 mw7 center br3 mb3 bg-white box">
      <div class="pa3 tc">
        <h2>{{ $t('auth.confirmation_title') }}</h2>

        <div v-show="resend" class="alert alert-success" role="alert">
          {{ $t('auth.confirmation_fresh') }}
        </div>

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
</template>

<script>
import LoadingButton from '@/Shared/LoadingButton';

export default {
  components: {
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
