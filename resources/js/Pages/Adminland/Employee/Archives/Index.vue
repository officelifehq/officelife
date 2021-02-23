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

  &:hover {
    background-color: #def;
  }

  a:hover {
    border-bottom: 0;
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
            <inertia-link :href="'/' + $page.props.auth.company.id + '/account'">{{ $t('app.breadcrumb_account_home') }}</inertia-link>
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
            {{ $t('account.import_employees_archives_title') }}

            <help :url="$page.props.help_links.employee_statuses" :top="'1px'" />
          </h2>

          <p class="relative adminland-headline">
            <a class="btn absolute-l relative dib-l db right-0" data-cy="add-status-button" @click.prevent="displayAddModal">
              Import a new list
            </a>
          </p>

          <!-- LIST OF JOB REPORTS -->
          <ul class="list pl0 mv0 center ba br2 bb-gray" data-cy="statuses-list" :data-cy-items="importJobs.map(n => n.id)">
            <li v-for="job in importJobs" :key="job.id" class="pv3 ph2 bb bb-gray bb-gray-hover ">
              <div class="di">
                <span class="db">{{ job.number_of_entries }} entries <span class="type">{{ job.status }}</span></span>
                <span class="db">{{ job.import_started_at }} by {{ job.author.name }}</span>
              </div>

              <!-- LIST OF ACTIONS FOR EACH REPORT -->
              <ul class="list pa0 ma0 di-ns db fr-ns mt2 mt0-ns f6">
                <li><inertia-link :href="job.url">{{ $t('app.view') }}</inertia-link></li>
              </ul>
            </li>
          </ul>

          <!-- BLANK STATE -->
          <div v-show="importJobs.length == 0" class="pa3 mt5">
            <p class="tc measure center mb4 lh-copy">
              Import all employees using a csv
            </p>
            <img loading="lazy" src="/img/streamline-icon-document-box-3@140x140.png" alt="add email symbol" class="db center mb4" height="80"
                 width="80"
            />
          </div>
        </div>
      </div>
    </div>
  </layout>
</template>

<script>
import Layout from '@/Shared/Layout';
import Help from '@/Shared/Help';

export default {
  components: {
    Layout,
    Help,
  },

  props: {
    notifications: {
      type: Array,
      default: null,
    },
    importJobs: {
      type: Array,
      default: null,
    },
  },

  data() {
    return {
      localStatuses: [],
      modal: false,
      deleteModal: false,
      updateModal: false,
      loadingState: '',
      updateModalId: 0,
      idToUpdate: 0,
      idToDelete: 0,
      form: {
        name: null,
        checked: false,
        type: 'internal',
        errors: [],
      },
    };
  },

  mounted() {
    this.localStatuses = this.statuses;
  },

  methods: {
    updateType(event) {
      if (event) {
        this.form.type = 'external';
      } else {
        this.form.type = 'internal';
      }
    },

    displayAddModal() {
      this.modal = true;
      this.form.name = '';
      this.form.type = 'internal';
      this.form.errors = null;

      this.$nextTick(() => {
        this.$refs['newStatus'].$refs['input'].focus();
      });
    },

    displayUpdateModal(status) {
      this.idToUpdate = status.id;
      this.form.checked = status.type == 'internal' ? false : true;

      this.$nextTick(() => {
        // this is really barbaric, but I need to do this to
        // first: target the TextInput with the right ref attribute
        // second: target within the component, the refs of the input text
        // this is because we try here to access $refs from a child component
        this.$refs[`name${status.id}`][0].$refs[`name${status.id}`].focus();
      });
    },

    submit() {
      this.loadingState = 'loading';

      axios.post(this.route('account_employeestatuses.employeestatuses.store', this.$page.props.auth.company.id), this.form)
        .then(response => {
          flash(this.$t('account.employee_statuses_success_new'), 'success');

          this.loadingState = null;
          this.form.name = null;
          this.form.type = 'internal';
          this.modal = false;
          this.localStatuses.push(response.data.data);
        })
        .catch(error => {
          this.loadingState = null;
          this.form.errors = error.response.data;
        });
    },

    update(id) {
      axios.put(this.route('account_employeestatuses.employeestatuses.update', [this.$page.props.auth.company.id, id]), this.form)
        .then(response => {
          flash(this.$t('account.employee_statuses_success_update'), 'success');

          this.idToUpdate = 0;
          this.form.name = null;
          this.form.type = 'internal';

          var changedId = this.localStatuses.findIndex(x => x.id === id);
          this.$set(this.localStatuses, changedId, response.data.data);
        })
        .catch(error => {
          this.form.errors = error.response.data;
        });
    },

    destroy(id) {
      axios.delete(this.route('account_employeestatuses.employeestatuses.destroy', [this.$page.props.auth.company.id, id]))
        .then(response => {
          flash(this.$t('account.employee_statuses_success_destroy'), 'success');

          this.idToDelete = 0;
          var changedId = this.localStatuses.findIndex(x => x.id === id);
          this.localStatuses.splice(changedId, 1);
        })
        .catch(error => {
          this.form.errors = error.response.data;
        });
    },
  }
};

</script>
