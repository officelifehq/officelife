<template>
  <layout title="Home" :user="user">
    <div class="ph2 ph0-ns">

      <div class="cf mw6 center br3 mb3 bg-white box">
        <div class="pa3">
          <p>{{ $t('auth.invitation_logged_accept_title', { name: company.name }) }}Would you like to join {{ company.name }}?</p>
          <form @submit.prevent="submit">
            <loading-button :classes="'btn add w-auto-ns w-100 mb2 pv2 ph3'" :state="loadingState" :text="$t('auth.invitation_logged_accept_cta')"></loading-button>
          </form>
        </div>
      </div>
    </div>
  </layout>
</template>

<script>

export default {

  props: [
    'company',
    'employee',
    'invitation_link',
    'user',
  ],

  data() {
    return {
      loadingState: '',
    }
  },

  methods: {
    submit() {
      this.loadingState = 'loading'

      axios.post('/invite/employee/' + this.invitation_link + '/accept')
        .then(response => {
          Turbolinks.visit('/home')
        })
        .catch(error => {
          this.loadingState = null
          if (typeof error.response.data === 'object') {
            this.form.errors = _.flatten(_.toArray(error.response.data))
          } else {
            this.form.errors = [this.$t('app.error_try_again')]
          }
        })
    },
  }
}
</script>
