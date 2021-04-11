<template>
  <layout :notifications="notifications">
    <div class="ph2 ph0-ns">
      <div class="cf mw6 center br3 mb3 bg-white box">
        <div class="pa3">
          <p>{{ $t('auth.invitation_logged_accept_title', { name: $page.props.auth.company.name }) }}</p>
          <form @submit.prevent="submit">
            <loading-button :class="'btn add w-auto-ns w-100 mb2 pv2 ph3'" :state="loadingState" :text="$t('auth.invitation_logged_accept_cta')" />
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

  props: {
    notifications: {
      type: Array,
      default: null,
    },
    invitationLink: {
      type: String,
      default: '',
    },
  },

  data() {
    return {
      loadingState: '',
    };
  },

  methods: {
    submit() {
      this.loadingState = 'loading';

      axios.post(this.route('invitation.join', this.invitationLink))
        .then(response => {
          this.$inertia.visit('/home');
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
