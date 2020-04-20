<style scoped>
input[type=checkbox] {
  top: 5px;
}
input[type=radio] {
  top: -2px;
}
</style>

<template>
  <layout title="Home" :notifications="notifications">
    <div class="ph2 ph0-ns">
      <!-- BREADCRUMB -->
      <div class="mt4-l mt1 mw6 br3 bg-white box center breadcrumb relative z-0 f6 pb2">
        <ul class="list ph0 tc-l tl">
          <li class="di">
            <inertia-link :href="'/' + $page.auth.company.id + '/dashboard'">{{ $t('app.breadcrumb_dashboard') }}</inertia-link>
          </li>
          <li class="di">
            ...
          </li>
          <li class="di">
            <inertia-link :href="'/' + $page.auth.company.id + '/account/employees'">{{ $t('app.breadcrumb_account_manage_employees') }}</inertia-link>
          </li>
          <li class="di">
            {{ $t('app.breadcrumb_account_add_employee') }}
          </li>
        </ul>
      </div>

      <!-- BODY -->
      <div class="mw7 center br3 mb5 bg-white box restricted relative z-1">
        <div class="">
          <h2 class="pa3 mt5 center tc normal mb2">
            {{ $t('account.employee_new_title', { name: $page.auth.company.name}) }}
          </h2>

          <form @submit.prevent="submit">
            <errors :errors="form.errors" />

            <!-- Basic information -->
            <div class="cf pa3 bb bb-gray pb4">
              <div class="fl-ns w-third-ns w-100 mb3 mb0-ns">
                <strong>{{ $t('account.employee_new_basic_information') }}</strong>
              </div>
              <div class="fl-ns w-two-thirds-ns w-100">
                <!-- First name -->
                <text-input :id="'first_name'"
                            v-model="form.first_name"
                            :name="'first_name'"
                            :errors="$page.errors.first_name"
                            :label="$t('account.employee_new_firstname')"
                            :required="true"
                />

                <!-- Last name -->
                <text-input :id="'last_name'"
                            v-model="form.last_name"
                            :name="'last_name'"
                            :errors="$page.errors.last_name"
                            :label="$t('account.employee_new_lastname')"
                            :required="true"
                />

                <!-- Email -->
                <text-input :id="'email'"
                            v-model="form.email"
                            :name="'email'"
                            :type="'email'"
                            :errors="$page.errors.email"
                            :label="$t('account.employee_new_email')"
                            :required="true"
                />

                <div class="flex items-start relative">
                  <input id="send_email" v-model="form.send_invitation" data-cy="send-email" class="mr2 relative pointer" type="checkbox"
                         name="send_email"
                  />
                  <label for="send_email" class="lh-copy ma0 pointer">
                    {{ $t('account.employee_new_send_email') }}
                    <span class="f6">
                      {{ $t('account.employee_new_send_email_optional') }}
                    </span>
                  </label>
                </div>
              </div>
            </div>

            <!-- Permission level -->
            <div class="cf pa3 bb-gray bb pt4">
              <div class="fl-ns w-third-ns w-100 mb3 mb0-ns">
                <strong>{{ $t('account.employee_new_permission_level') }}</strong>
              </div>
              <div class="fl-ns w-two-thirds-ns w-100">
                <div class="db relative">
                  <input id="administrator" v-model="form.permission_level" type="radio" class="mr1 relative" name="permission_level"
                         value="100"
                  />
                  <label for="administrator" class="pointer">
                    {{ $t('account.employee_new_administrator') }}
                  </label>
                  <p class="ma0 lh-copy f6 mb3">
                    {{ $t('account.employee_new_administrator_desc') }}
                  </p>
                </div>

                <div class="db relative">
                  <input id="hr" v-model="form.permission_level" type="radio" class="mr1 relative" name="permission_level"
                         value="200"
                  />
                  <label for="hr" class="pointer">
                    {{ $t('account.employee_new_hr') }}
                  </label>
                  <p class="ma0 lh-copy f6 mb3">
                    {{ $t('account.employee_new_hr_desc') }}
                  </p>
                </div>

                <div class="db relative">
                  <input id="user" v-model="form.permission_level" type="radio" class="mr1 relative" name="permission_level"
                         value="300"
                  />
                  <label for="user" class="pointer">
                    {{ $t('account.employee_new_user') }}
                  </label>
                  <p class="ma0 lh-copy f6 mb3">
                    {{ $t('account.employee_new_user_desc') }}
                  </p>
                </div>
              </div>
            </div>

            <!-- Actions -->
            <div class="cf pa3">
              <div class="flex-ns justify-between">
                <div>
                  <inertia-link :href="'/' + $page.auth.company.id + '/account/employees'" class="btn dib tc w-auto-ns w-100 pv2 ph3 mb0-ns mb2">
                    {{ $t('app.cancel') }}
                  </inertia-link>
                </div>
                <loading-button :classes="'btn add w-auto-ns w-100 pv2 ph3'" :state="loadingState" :text="$t('app.save')" :cypress-selector="'submit-add-employee-button'" />
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </layout>
</template>

<script>
import TextInput from '@/Shared/TextInput';
import Errors from '@/Shared/Errors';
import LoadingButton from '@/Shared/LoadingButton';
import Layout from '@/Shared/Layout';

export default {
  components: {
    Layout,
    TextInput,
    Errors,
    LoadingButton,
  },

  props: {
    notifications: {
      type: Array,
      default: null,
    },
  },

  data() {
    return {
      form: {
        first_name: null,
        last_name: null,
        email: null,
        permission_level: null,
        send_invitation: false,
        errors: [],
      },
      loadingState: '',
      errorTemplate: Error,
    };
  },

  methods: {
    submit() {
      this.loadingState = 'loading';

      axios.post('/' + this.$page.auth.company.id + '/account/employees', this.form)
        .then(response => {
          localStorage.success = 'The employee has been added';
          this.$inertia.visit('/' + response.data.company_id + '/account/employees');
        })
        .catch(error => {
          this.loadingState = null;
          this.form.errors = _.flatten(_.toArray(error.response.data));
        });
    },
  }
};

</script>
