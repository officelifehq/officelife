<style lang="scss" scoped>
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
        {{ $t('app.breadcrumb_account_employee_delete') }}
      </breadcrumb>

      <!-- BODY -->
      <div class="mw7 center br3 mb5 bg-white box restricted relative z-1">
        <div class="mt5">
          <h2 class="pa3 tc normal mb0">
            {{ $t('account.employee_delete_title', { name: employee.name}) }}

            <help :url="$page.props.help_links.account_employee_delete" :datacy="'help-icon-employee-delete'" :top="'1px'" />
          </h2>

          <form @submit.prevent="submit">
            <errors :errors="form.errors" />

            <div class="cf pa3 bb-gray bb">
              <p class="lh-copy">{{ $t('account.employee_delete_description', { name: employee.name}) }}</p>
            </div>

            <!-- disable actions if instance is in demo mode -->
            <div v-if="$page.props.demo_mode" class="cf pa3 tc">
              <span class="mr1">
                ⚠️
              </span> {{ $t('app.demo_mode_deactivated') }}
            </div>

            <!-- Actions -->
            <div v-if="!$page.props.demo_mode" class="cf pa3 bb-gray bb">
              <div class="flex-ns justify-between">
                <div>
                  <inertia-link :href="'/' + $page.props.auth.company.id + '/account/employees'" class="btn dib tc w-auto-ns w-100 pv2 ph3 mb0-ns mb2" data-cy="cancel-button">
                    {{ $t('app.cancel') }}
                  </inertia-link>
                </div>
                <loading-button :class="'btn destroy w-auto-ns w-100 pv2 ph3'" :state="loadingState" :text="$t('app.delete')" :cypress-selector="'submit-delete-employee-button'" />
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
        errors: [],
      },
      loadingState: '',
    };
  },

  methods: {
    submit() {
      this.loadingState = 'loading';

      axios.delete('/' + this.$page.props.auth.company.id + '/account/employees/' + this.employee.id)
        .then(response => {
          localStorage.success = this.$t('account.employee_delete_success');
          this.$inertia.visit('/' + response.data.company_id + '/account/employees');
        })
        .catch(error => {
          this.loadingState = null;
          this.form.errors = error.response.data;
        });
    },
  }
};

</script>
