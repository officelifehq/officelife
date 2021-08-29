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
      <breadcrumb :with-box="true"
                  :root-url="'/' + $page.props.auth.company.id + '/dashboard'"
                  :root="$t('app.breadcrumb_dashboard')"
                  :previous-url="'/' + $page.props.auth.company.id + '/account/employees/upload/archives'"
                  :previous="$t('app.breadcrumb_account_manage_past_archives')"
      >
        {{ $t('app.breadcrumb_account_manage_past_archives_detail') }}
      </breadcrumb>

      <!-- BODY -->
      <div class="mw7 center br3 mb5 bg-white box restricted relative z-1">
        <div class="mt5">
          <div class="pa3">
            <!-- Title when import is waiting processing -->
            <h2 v-if="localReport.status === 'created'" class="tc normal mb4">
              {{ $t('account.import_employees_show_title_created') }}

              <help :url="$page.props.help_links.import_employees" :top="'1px'" />
            </h2>

            <!-- Title when import has started -->
            <h2 v-else-if="localReport.status === 'started'" class="tc normal mb4">
              {{ $t('account.import_employees_show_title_started', { date: localReport.import_started_at }) }}

              <help :url="$page.props.help_links.import_employees" :top="'1px'" />
            </h2>

            <!-- Title when import needs to happen -->
            <h2 v-else-if="localReport.status === 'uploaded'" class="tc normal mb4">
              {{ $t('account.import_employees_show_title_uploaded') }}

              <help :url="$page.props.help_links.import_employees" :top="'1px'" />
            </h2>

            <!-- Title when import is done -->
            <h2 v-else-if="localReport.status === 'imported' || localReport.status === 'importing'" class="tc normal mb4">
              {{ $t('account.import_employees_show_title_imported', { date: localReport.import_ended_at }) }}

              <help :url="$page.props.help_links.import_employees" :top="'1px'" />
            </h2>

            <!-- Title when import is in error -->
            <h2 v-else-if="localReport.status === 'failed'" class="tc normal mb4">
              {{ $t('account.import_employees_show_title_failed') }}

              <help :url="$page.props.help_links.import_employees" :top="'1px'" />
            </h2>

            <!-- Summary of the different import steps -->
            <div v-if="localReport.status !== 'created' && localReport.status !== 'started'" class="flex justify-around items-center mb4">
              <div class="">
                <span class="db gray mb2 f6">
                  {{ $t('account.import_employees_show_title_number_entries') }}
                </span>
                <span class="f3">
                  {{ localReport.number_of_entries }}
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
                  {{ localReport.number_of_failed_entries }}
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
                  {{ localReport.number_of_entries_that_can_be_imported }}
                </span>
              </div>
            </div>

            <!-- action to import the listing -->
            <form v-if="localReport.status === 'uploaded'" class="tc mb4" @submit.prevent="submit">
              <errors :errors="errors" />
              <loading-button :class="'btn add w-auto-ns w-100 mb2 pv2 ph3'" :state="loadingState" :text="$tc('account.import_employees_archives_finalize_import', localReport.number_of_entries_that_can_be_imported, { count: localReport.number_of_entries_that_can_be_imported })" :cypress-selector="'submit-add-news-button'" />
            </form>
          </div>

          <div v-if="localReport.status === 'uploaded' || localReport.status === 'imported' || localReport.status === 'importing'" class="pa3">
            <!-- LIST OF THE FIRST FIVE ENTRIES IN THE REPORT -->
            <p class="f6 mb1">{{ $t('account.import_employees_show_first_five_entries', {count: localReport.number_of_entries }) }}</p>
            <div v-if="localReport.first_five_entries.length > 0" class="center bt br bl br2 bb-gray dt w-100 mb5">
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
              <div v-for="entry in localReport.first_five_entries" :key="entry.id" class="dt-row pv3 ph2 bb bb-gray bb-gray-hover pa3">
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
            <p class="f6 mb1">{{ $tc('account.import_employees_show_entries_errors', localReport.failed_entries.length, { count: localReport.failed_entries.length }) }}</p>
            <div v-if="localReport.failed_entries.length > 0" class="center bt br bl br2 bb-gray dt w-100">
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
              <div v-for="entry in localReport.failed_entries" :key="entry.id" class="dt-row pv3 ph2 bb bb-gray bb-gray-hover pa3">
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
import Breadcrumb from '@/Shared/Layout/Breadcrumb';
import Help from '@/Shared/Help';
import Errors from '@/Shared/Errors';
import LoadingButton from '@/Shared/LoadingButton';

export default {
  components: {
    Layout,
    Breadcrumb,
    Help,
    Errors,
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
      dataReport: null,
      loadingState: null,
      errors: null,
      refresh: _.debounce(() => this.doRefresh(), 1000),
    };
  },

  computed: {
    localReport() {
      return this.dataReport ?? this.report;
    }
  },

  mounted() {
    this.dataReport = this.report;
    this.refresh();
  },

  unmounted() {
    this.refresh.cancel();
  },

  methods: {
    submit() {
      this.loadingState = 'loading';

      axios.post(this.route('account.employees.upload.archive.import', {
        company: this.$page.props.auth.company.id,
        archive: this.report.id
      }))
        .then(() => {
          localStorage.success = this.$t('account.import_employees_import_success');
          this.$inertia.visit(this.route('account.employees.upload.archive', {company: this.$page.props.auth.company.id}));
        })
        .catch(error => {
          this.loadingState = null;
          this.errors = error.response.data;
        });
    },

    doRefresh() {
      if (this.$page.component === 'Adminland/Employee/Archives/Show'
        && this.loadingState === null
        && this.localReport.status !== 'imported'
        && this.localReport.status !== 'uploaded'
        && this.localReport.status !== 'failed') {
        axios.get(route('account.employees.upload.archive.show', {
          company: this.$page.props.auth.company.id,
          archive: this.localReport.id,
        }), {
          headers: {
            'X-Inertia': true,
            'X-Inertia-Version': this.$page.version,
          }
        })
          .then(response => {
            this.dataReport = response.data.props.report;
            this.refresh();
          });
      }
    },

  }
};

</script>
