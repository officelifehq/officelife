<style lang="scss" scoped>
.list li:last-child {
  border-bottom: 0;
}

.type {
  font-size: 12px;
  border: 1px solid transparent;
  border-radius: 2em;
  padding: 3px 10px;
  line-height: 22px;
  color: #0366d6;
  background-color: #f1f8ff;
  top: -1px;

  &:hover {
    background-color: #def;
  }

  a:hover {
    border-bottom: 0;
  }

  &.failed {
    background-color: #ea8383;
    color: #fff;
  }

  span {
    border-radius: 100%;
    background-color: #c8dcf0;
    padding: 0px 5px;
  }
}
</style>

<template>
  <layout title="Home" :notifications="notifications">
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
            <inertia-link :href="'/' + $page.props.auth.company.id + '/account/employees'">{{ $t('app.breadcrumb_account_manage_employees') }}</inertia-link>
          </li>
          <li class="di">
            {{ $t('app.breadcrumb_account_manage_past_archives') }}
          </li>
        </ul>
      </div>

      <!-- BODY -->
      <div class="mw7 center br3 mb5 bg-white box restricted relative z-1">
        <div class="pa3 mt5">
          <h2 class="tc normal mb4">
            Finalize the import {{ report.import_started_at }}

            <help :url="$page.props.help_links.employee_statuses" :top="'1px'" />
          </h2>

          <form action="" class="pa3 ba bb-gray tc mb5">
            <p>Click the button below to finalize the import of those employees in the system.</p>
            <p>We won’t import failed entries, though - only valid ones.</p>
            <loading-button :classes="'btn add w-auto-ns w-100 mb2 pv2 ph3'" :state="loadingState" :text="$t('account.import_employees_archives_finalize_import')" :cypress-selector="'submit-add-news-button'" />
          </form>

          <!-- LIST OF THE FIRST FIVE ENTRIES IN THE REPORT -->
          <p>First five of the {{ report.number_of_entries }} entries of the file </p>
          <div v-if="report.first_five_entries.length > 0" class="center bt br bl br2 bb-gray dt w-100 mb5">
            <div class="dt-row">
              <div class="dtc pv2 ph2 f6 bb bb-gray bg-gray fw5">
                Email
              </div>
              <div class="dtc pv2 ph2 f6 bb bb-gray bg-gray fw5">
                First name
              </div>
              <div class="dtc pv2 ph2 f6 bb bb-gray bg-gray fw5">
                Last name
              </div>
              <div class="dtc pv2 ph2 f6 bb bb-gray bg-gray fw5">
                Status
              </div>
            </div>
            <div v-for="report in report.first_five_entries" :key="report.id" class="dt-row pv3 ph2 bb bb-gray bb-gray-hover pa3">
              <div class="dtc pv3 ph2 bb bb-gray">
                {{ report.employee_email }}
              </div>
              <div class="dtc pv3 ph2 bb bb-gray">
                {{ report.employee_first_name }}
              </div>
              <div class="dtc pv3 ph2 bb bb-gray">
                {{ report.employee_last_name }}
              </div>
              <div v-if="report.skipped_during_upload" class="dtc bb bb-gray">
                <span class="type failed">
                  {{ report.skipped_during_upload_reason }}
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
          <p>All {{ report.failed_entries.length }} entries in error in the file</p>
          <div v-if="report.failed_entries.length > 0" class="center bt br bl br2 bb-gray dt w-100">
            <div class="dt-row">
              <div class="dtc pv2 ph2 f6 bb bb-gray bg-gray fw5">
                Email
              </div>
              <div class="dtc pv2 ph2 f6 bb bb-gray bg-gray fw5">
                First name
              </div>
              <div class="dtc pv2 ph2 f6 bb bb-gray bg-gray fw5">
                Last name
              </div>
              <div class="dtc pv2 ph2 f6 bb bb-gray bg-gray fw5">
                Status
              </div>
            </div>
            <div v-for="report in report.failed_entries" :key="report.id" class="dt-row pv3 ph2 bb bb-gray bb-gray-hover pa3">
              <div class="dtc pv3 ph2 bb bb-gray">
                {{ report.employee_email }}
              </div>
              <div class="dtc pv3 ph2 bb bb-gray">
                {{ report.employee_first_name }}
              </div>
              <div class="dtc pv3 ph2 bb bb-gray">
                {{ report.employee_last_name }}
              </div>
              <div v-if="report.skipped_during_upload" class="dtc bb bb-gray">
                <span class="type failed">
                  {{ report.skipped_during_upload_reason }}
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
    };
  },

  methods: {
    submit() {
      this.loadingState = 'loading';

      axios.post(this.route('account.employees.upload.archive.import', this.$page.props.auth.company.id), this.form)
        .then(response => {
          localStorage.success = this.$t('account.company_news_create_success');
          this.$inertia.visit('/' + response.data.data.company.id + '/account/news');
        })
        .catch(error => {
          this.loadingState = null;
          this.form.errors = error.response.data;
        });
    },
  }
};

</script>
