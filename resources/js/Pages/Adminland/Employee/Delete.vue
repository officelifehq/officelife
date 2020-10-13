<style lang="scss" scoped>
</style>

<template>
  <layout title="Home" :notifications="notifications">
    <div class="ph2 ph0-ns">
      <!-- BREADCRUMB -->
      <div class="mt4-l mt1 mw6 br3 bg-white box center breadcrumb relative z-0 f6 pb2">
        <ul class="list ph0 tc-l tl">
          <li class="di">
            <inertia-link :href="route('dashboard', $page.auth.company.id)">{{ $t('app.breadcrumb_dashboard') }}</inertia-link>
          </li>
          <li class="di">
            ...
          </li>
          <li class="di">
            <inertia-link :href="route('account.employees.index', $page.auth.company.id)">{{ $t('app.breadcrumb_employee_list') }}</inertia-link>
          </li>
          <li class="di">
            {{ $t('app.breadcrumb_account_employee_delete') }}
          </li>
        </ul>
      </div>

      <!-- BODY -->
      <div class="mw7 center br3 mb5 bg-white box restricted relative z-1">
        <div class="mt5">
          <h2 class="pa3 tc normal mb0">
            {{ $t('account.employee_delete_title', { name: employee.name}) }}

            <help :url="$page.help_links.account_employee_delete" :datacy="'help-icon-employee-delete'" :top="'1px'" />
          </h2>

          <form @submit.prevent="submit">
            <errors :errors="form.errors" />

            <div class="cf pa3 bb-gray bb">
              <p class="lh-copy">{{ $t('account.employee_delete_description', { name: employee.name}) }}</p>
            </div>

            <!-- Actions -->
            <div class="cf pa3 bb-gray bb">
              <div class="flex-ns justify-between">
                <div>
                  <inertia-link :href="route('account.employees.index', $page.auth.company.id)" class="btn dib tc w-auto-ns w-100 pv2 ph3 mb0-ns mb2" data-cy="cancel-button">
                    {{ $t('app.cancel') }}
                  </inertia-link>
                </div>
                <loading-button :classes="'btn destroy w-auto-ns w-100 pv2 ph3'" :state="loadingState" :text="$t('app.delete')" :cypress-selector="'submit-delete-employee-button'" />
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
import Errors from '@/Shared/Errors';
import LoadingButton from '@/Shared/LoadingButton';
import Help from '@/Shared/Help';

export default {
  components: {
    Layout,
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

      axios.delete(this.route('account.employees.destroy', [this.$page.auth.company.id, this.employee.id]))
        .then(response => {
          localStorage.success = this.$t('account.employee_delete_success');
          this.$inertia.visit(this.route('account.employees.index', response.data.company_id));
        })
        .catch(error => {
          this.loadingState = null;
          this.form.errors = _.flatten(_.toArray(error.response.data));
        });
    },
  }
};

</script>
