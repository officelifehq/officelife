<style scoped>
</style>

<template>
  <layout title="Home" :user="user">
    <div class="ph2 ph0-ns">

      <!-- BREADCRUMB -->
      <div class="mt4-l mt1 mw6 br3 bg-white box center breadcrumb relative z-0 f6 pb2">
        <ul class="list ph0 tc-l tl">
          <li class="di"><a :href="'/' + company.id + '/dashboard'">{{ company.name }}</a></li>
          <li class="di">...</li>
          <li class="di"><a :href="'/' + company.id + '/account/employees'">{{ $t('app.breadcrumb_account_manage_employees') }}</a></li>
          <li class="di">{{ $t('app.breadcrumb_account_add_employee') }}</li>
        </ul>
      </div>

      <!-- BODY -->
      <div class="mw7 center br3 mb5 bg-white box restricted relative z-1">
        <div class="pa3 mt5 measure center">
          <h2 class="tc normal mb4">{{ $t('account.employee_new_title', { name: company.name}) }}</h2>

          <!-- Form Errors -->
          <errors :errors="form.errors"></errors>

          <form @submit.prevent="submit">
            <!-- First name -->
            <div class="mb3">
              <label class="db fw4 lh-copy f6" for="first_name">{{ $t('account.employee_new_firstname') }}</label>
              <input type="text" id="first_name" name="first_name" class="br2 f5 w-100 ba b--black-40 pa2 outline-0" v-model="form.first_name" required>
            </div>

            <!-- Last name -->
            <div class="mb3">
              <label class="db fw4 lh-copy f6" for="last_name">{{ $t('account.employee_new_lastname') }}</label>
              <input type="text" id="last_name" name="last_name" class="br2 f5 w-100 ba b--black-40 pa2 outline-0" v-model="form.last_name" required>
            </div>

            <!-- Email -->
            <div class="mb3">
              <label class="db fw4 lh-copy f6" for="email">{{ $t('account.employee_new_email') }}</label>
              <input type="email" id="email" name="email" class="br2 f5 w-100 ba b--black-40 pa2 outline-0" v-model="form.email" required>
            </div>

            <hr>

            <!-- Permission level -->
            <div class="mb3">
              <p>{{ $t('account.employee_new_permission_level') }}</p>

              <div class="db">
                <input type="radio" class="mr1" name="permission_level" v-model="form.permission_level" id="administrator" value="100">
                <label for="administrator" class="pointer">{{ $t('account.employee_new_administrator') }}</label>
                <p>{{ $t('account.employee_new_administrator_desc') }}</p>
              </div>

              <div class="db">
                <input type="radio" class="mr1" name="permission_level" v-model="form.permission_level" id="hr" value="200">
                <label for="hr" class="pointer">{{ $t('account.employee_new_hr') }}</label>
                <p>{{ $t('account.employee_new_hr_desc') }}</p>
              </div>

              <div class="db">
                <input type="radio" class="mr1" name="permission_level" v-model="form.permission_level" id="user" value="300">
                <label for="user" class="pointer">{{ $t('account.employee_new_user') }}</label>
                <p>{{ $t('account.employee_new_user_desc') }}</p>
              </div>
            </div>

            <hr>

            <!-- Invite user -->
            <div class="mb3">
              <div class="flex items-center mb2">
                <input class="mr2" type="checkbox" name="send_email" value="" @click="popup()">
                <label for="send_email" class="lh-copy">{{ $t('account.employee_new_send_email') }}</label>
              </div>
            </div>

            <!-- Actions -->
            <div class="mv4">
              <div class="flex-ns justify-between">
                <div>
                  <a :href="'/' + company.id + '/account/employees'" class="btn btn-secondary dib tc w-auto-ns w-100 mb2 pv2 ph3">{{ $t('app.cancel') }}</a>
                </div>
                <loading-button :classes="'btn add w-auto-ns w-100 mb2 pv2 ph3'" :state="loadingState" :text="$t('app.save')" :cypress-selector="'submit-add-employee-button'"></loading-button>
              </div>
            </div>
          </form>

        </div>
      </div>
    </div>
  </layout>
</template>

<script>

export default {

  data() {
    return {
      form: {
        first_name: null,
        last_name: null,
        email: null,
        permission_level: null,
        errors: [],
      },
      loadingState: '',
      errorTemplate: Error,
    }
  },

  props: [
    'company',
    'user',
  ],

  methods: {
    submit() {
      this.loadingState = 'loading'

      axios.post('/' + this.company.id + '/account/employees', this.form)
        .then(response => {
          localStorage.success = 'The employee has been added'
          Turbolinks.visit('/' + response.data.company_id + '/account/employees')
        })
        .catch(error => {
          this.loadingState = null
          this.form.errors = _.flatten(_.toArray(error.response.data))
        })
    },
  }
}

</script>
