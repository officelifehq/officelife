<style lang="scss" scoped>
.radio {
  top: 3px;
}

.optional-badge {
  border-radius: 4px;
  color: #283e59;
  background-color: #edf2f9;
  padding: 3px 4px;
}
</style>

<template>
  <layout :notifications="notifications">
    <div class="ph2 ph0-ns">
      <breadcrumb :with-box="true"
                  :root-url="'/' + $page.props.auth.company.id + '/dashboard'"
                  :root="$t('app.breadcrumb_dashboard')"
                  :previous-url="'/' + $page.props.auth.company.id + '/account/employees'"
                  :previous="$t('app.breadcrumb_employee_list')"
      >
        {{ $t('app.breadcrumb_account_employee_lock') }}
      </breadcrumb>

      <!-- BODY -->
      <div class="mw7 center br3 mb5 bg-white box restricted relative z-1">
        <div class="mt5">
          <h2 class="pa3 tc normal mb0">
            {{ $t('account.employee_permission_title', { name: employee.name}) }}

            <help :url="$page.props.help_links.account_employee_permission" :top="'1px'" />
          </h2>

          <form @submit.prevent="submit">
            <errors :errors="form.errors" />

            <div class="cf pa3 bb-gray bb">
              <!-- regular employee -->
              <div class="db flex items-start relative mb3">
                <!-- radio button -->
                <input id="user" v-model="form.permission_level" type="radio" class="mr1 relative radio" name="permission_level"
                       value="300"
                />

                <!-- description -->
                <div class="ml2">
                  <label for="user" class="pointer fw6">
                    {{ $t('account.employee_new_user') }}

                    <span class="ml2 optional-badge f7">
                      {{ $t('account.employee_permission_safest') }}
                    </span>
                  </label>
                  <p class="ma0 lh-copy f6 mb3">
                    {{ $t('account.employee_new_user_desc') }}
                  </p>
                </div>
              </div>

              <!-- hr -->
              <div class="db flex items-start relative mb3">
                <!-- radio button -->
                <input id="hr" v-model="form.permission_level" type="radio" class="mr1 relative radio" name="permission_level"
                       value="200"
                />

                <!-- description -->
                <div class="ml2">
                  <label for="hr" class="pointer fw6">
                    {{ $t('account.employee_new_hr') }}
                  </label>
                  <p class="ma0 lh-copy f6 mb3">
                    {{ $t('account.employee_new_hr_desc') }}
                  </p>
                </div>
              </div>

              <!-- administrator -->
              <div class="db flex items-start relative">
                <!-- radio button -->
                <input id="administrator" v-model="form.permission_level" type="radio" class="mr1 relative radio" name="permission_level"
                       value="100"
                />

                <!-- description -->
                <div class="ml2">
                  <label for="administrator" class="pointer fw6">
                    {{ $t('account.employee_new_administrator') }}
                  </label>
                  <p class="ma0 lh-copy f6 mb3">
                    {{ $t('account.employee_new_administrator_desc') }}
                  </p>
                </div>
              </div>
            </div>

            <!-- Actions -->
            <div class="cf pa3 bb-gray bb">
              <div class="flex-ns justify-between">
                <div>
                  <inertia-link :href="'/' + $page.props.auth.company.id + '/account/employees'" class="btn dib tc w-auto-ns w-100 pv2 ph3 mb0-ns mb2">
                    {{ $t('app.cancel') }}
                  </inertia-link>
                </div>
                <loading-button :class="'btn add w-auto-ns w-100 pv2 ph3'" :state="loadingState" :text="$t('app.update')" />
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </layout>
</template>

<script>
import Layout from '@/Shared/Layout';
import Breadcrumb from '@/Shared/Layout/Breadcrumb';
import Errors from '@/Shared/Errors';
import LoadingButton from '@/Shared/LoadingButton';
import Help from '@/Shared/Help';

export default {
  components: {
    Layout,
    Breadcrumb,
    Errors,
    LoadingButton,
    Help,
  },

  props: {
    notifications: {
      type: Array,
      default: null,
    },
    employee: {
      type: Object,
      default: null,
    },
  },

  data() {
    return {
      form: {
        permission_level: 0,
        errors: [],
      },
      loadingState: '',
    };
  },

  mounted() {
    this.form.permission_level = this.employee.permission_level;
  },

  methods: {
    submit() {
      this.loadingState = 'loading';

      axios.post(`${this.$page.props.auth.company.id}/account/employees/${this.employee.id}/permissions`, this.form)
        .then(response => {
          localStorage.success = this.$t('account.employee_permission_success');
          this.$inertia.visit('/' + this.$page.props.auth.company.id + '/account/employees');
        })
        .catch(error => {
          this.loadingState = null;
          this.form.errors = error.response.data;
        });
    },
  }
};

</script>
