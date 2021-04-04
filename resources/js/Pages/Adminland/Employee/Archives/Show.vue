<style lang="scss" scoped>
.list li:last-child {
  border-bottom: 0;
}

.type {
  font-size: 12px;
  border: 1px solid transparent;
  border-radius: 6px;
  padding: 2px 6px;
  line-height: 22px;
  color: #006443;
  background-color: #c0f6c5;
  top: -1px;

  &:hover {
    background-color: #def;
  }

  a:hover {
    border-bottom: 0;
  }

  &.failed {
    background-color: #ffe1dc;
    color: #b3004e;
  }

  span {
    border-radius: 100%;
    background-color: #c8dcf0;
    padding: 0px 5px;
  }
}

.arrow {
  width: 20px;
}
</style>

<template>
  <layout :notifications="notifications">
    <div class="ph2 ph0-ns">
      <!-- BREADCRUMB -->
      <div class="mt4-l mt1 mw6 br3 bg-white box center breadcrumb relative z-0 f6 pb2">
        <ul class="list ph0 tc-l tl">
          <li class="di">
            <inertia-link :href="'/' + $page.props.auth.company.id + '/dashboard'">{{ $t('app.breadcrumb_dashboard') }}</inertia-link>
          </li>
          <li class="di">
            ...
          </li>
          <li class="di">
            <inertia-link :href="'/' + $page.props.auth.company.id + '/account/employees/upload/archives'">{{ $t('app.breadcrumb_account_manage_past_archives') }}</inertia-link>
          </li>
          <li class="di">
            {{ $t('app.breadcrumb_account_manage_past_archives_detail') }}
          </li>
        </ul>
      </div>

      <!-- BODY -->
      <div class="mw7 center br3 mb5 bg-white box restricted relative z-1">
        <div class="mt5">
          <div class="pa3 bb bb-gray">
            <!-- Title when import needs to happen -->
            <h2 v-if="report.status == 'uploaded'" class="tc normal mb4">
              {{ $t('account.import_employees_show_title_uploaded') }}

              <help :url="$page.props.help_links.import_employees" :top="'1px'" />
            </h2>

            <!-- Title when import is done -->
            <h2 v-if="report.status == 'imported'" class="tc normal mb4">
              {{ $t('account.import_employees_show_title_imported', { date: report.import_ended_at }) }}

              <help :url="$page.props.help_links.import_employees" :top="'1px'" />
            </h2>

            <div class="flex justify-around items-center mb4">
              <div class="">
                <span class="db gray mb2 f6">
                  {{ $t('account.import_employees_show_title_number_entries') }}
                </span>
                <span class="f3">
                  {{ report.number_of_entries }}
                </span>
              </div>
              <div class="arrow">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                  <path fill-rule="evenodd" d="M12.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-2.293-2.293a1 1 0 010-1.414z" clip-rule="evenodd" />
                </svg>
              </div>
              <div class="">
                <span class="db gray mb2 f6">
                  {{ $t('account.import_employees_show_title_number_entries_errors') }}
                </span>
                <span class="f3">
                  {{ report.number_of_failed_entries }}
                </span>
              </div>
              <div class="arrow">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                  <path fill-rule="evenodd" d="M12.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-2.293-2.293a1 1 0 010-1.414z" clip-rule="evenodd" />
                </svg>
              </div>
              <div class="">
                <span class="db gray mb2 f6">
                  {{ $t('account.import_employees_show_title_number_entries_import') }}
                </span>
                <span class="f3">
                  {{ report.number_of_entries_that_can_be_imported }}
                </span>
              </div>
            </div>

            <!-- action to import the listing -->
            <form v-if="report.status != 'imported'" class="tc mb4" @submit.prevent="submit">
              <loading-button :classes="'btn add w-auto-ns w-100 mb2 pv2 ph3'" :state="loadingState" :text="$t('account.import_employees_archives_finalize_import', { count: report.number_of_entries_that_can_be_imported })" :cypress-selector="'submit-add-news-button'" />
            </form>
          </div>

          <div class="pa3">
            <!-- LIST OF THE FIRST FIVE ENTRIES IN THE REPORT -->
            <p class="f6 mb1">{{ $t('account.import_employees_show_first_five_entries', {count: report.number_of_entries }) }}</p>
            <div v-if="report.first_five_entries.length > 0" class="center bt br bl br2 bb-gray dt w-100 mb5">
              <div class="dt-row">
                <div class="dtc pv2 ph2 f6 bb bb-gray bg-gray fw5">
                  {{ $t('account.import_employees_show_email') }}
                </div>
                <div class="dtc pv2 ph2 f6 bb bb-gray bg-gray fw5">
                  {{ $t('account.import_employees_show_firstname') }}
                </div>
                <div class="dtc pv2 ph2 f6 bb bb-gray bg-gray fw5">
                  {{ $t('account.import_employees_show_lastname') }}
                </div>
                <div class="dtc pv2 ph2 f6 bb bb-gray bg-gray fw5">
                  {{ $t('account.import_employees_show_status') }}
                </div>
              </div>
              <div v-for="entry in report.first_five_entries" :key="entry.id" class="dt-row pv3 ph2 bb bb-gray bb-gray-hover pa3">
                <div class="dtc pv3 ph2 bb bb-gray">
                  {{ entry.employee_email }}
                </div>
                <div class="dtc pv3 ph2 bb bb-gray">
                  {{ entry.employee_first_name }}
                </div>
                <div class="dtc pv3 ph2 bb bb-gray">
                  {{ entry.employee_last_name }}
                </div>
                <div v-if="entry.skipped_during_upload" class="dtc bb bb-gray">
                  <span class="type failed">
                    {{ entry.skipped_during_upload_reason }}
                  </span>
                </div>
                <div v-else class="dtc bb bb-gray">
                  <span class="type">
                    {{ $t('account.import_employees_archives_item_status_ok') }}
                  </span>
                </div>
              </div>
            </div>

            <!-- LIST OF THE ALL ENTRIES IN ERROR -->
            <p class="f6 mb1">{{ $t('account.import_employees_show_entries_errors', { count: report.failed_entries.length }) }}</p>
            <div v-if="report.failed_entries.length > 0" class="center bt br bl br2 bb-gray dt w-100">
              <div class="dt-row">
                <div class="dtc pv2 ph2 f6 bb bb-gray bg-gray fw5">
                  {{ $t('account.import_employees_show_email') }}
                </div>
                <div class="dtc pv2 ph2 f6 bb bb-gray bg-gray fw5">
                  {{ $t('account.import_employees_show_firstname') }}
                </div>
                <div class="dtc pv2 ph2 f6 bb bb-gray bg-gray fw5">
                  {{ $t('account.import_employees_show_lastname') }}
                </div>
                <div class="dtc pv2 ph2 f6 bb bb-gray bg-gray fw5">
                  {{ $t('account.import_employees_show_status') }}
                </div>
              </div>
              <div v-for="entry in report.failed_entries" :key="entry.id" class="dt-row pv3 ph2 bb bb-gray bb-gray-hover pa3">
                <!-- email -->
                <div v-if="entry.employee_email" class="dtc pv3 ph2 bb bb-gray">
                  {{ entry.employee_email }}
                </div>
                <div v-else class="dtc pv3 ph2 bb bb-gray f6 gray">
                  ({{ $t('account.import_employees_archives_finalize_email_missing') }})
                </div>

                <!-- first name -->
                <div class="dtc pv3 ph2 bb bb-gray">
                  {{ entry.employee_first_name }}
                </div>

                <!-- last name -->
                <div class="dtc pv3 ph2 bb bb-gray">
                  {{ entry.employee_last_name }}
                </div>

                <!-- skipped during upload -->
                <div v-if="entry.skipped_during_upload" class="dtc bb bb-gray">
                  <span class="type failed">
                    {{ entry.skipped_during_upload_reason }}
                  </span>
                </div>
                <div v-else class="dtc bb bb-gray">
                  <span class="type">
                    {{ $t('account.import_employees_archives_item_status_ok') }}
                  </span>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </layout>
</template>

<script>
import Layout from '@/Shared/Layout';
import Help from '@/Shared/Help';
import LoadingButton from '@/Shared/LoadingButton';

export default {
  components: {
    Layout,
    Help,
    LoadingButton,
  },

  props: {
    notifications: {
      type: Array,
      default: null,
    },
    report: {
      type: Object,
      default: null,
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

      axios.post(`/${this.$page.props.auth.company.id}/account/employees/upload/archives/${this.report.id}/import`)
        .then(response => {
          localStorage.success = this.$t('account.import_employees_import_success');
          this.$inertia.visit(`/${this.$page.props.auth.company.id}/account/employees/upload/archives`);
        })
        .catch(error => {
          this.loadingState = null;
          this.form.errors = error.response.data;
        });
    },
  }
};

</script>
