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
  <layout :notifications="notifications">
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
            {{ $t('app.breadcrumb_account_manage_employee_statuses') }}
          </li>
        </ul>
      </div>

      <!-- BODY -->
      <div class="mw7 center br3 mb5 bg-white box restricted relative z-1">
        <div class="pa3 mt5">
          <h2 class="tc normal mb4">
            {{ $t('account.employee_statuses_title', { company: $page.props.auth.company.name}) }}

            <help :url="$page.props.help_links.employee_statuses" :top="'1px'" />
          </h2>

          <p class="relative adminland-headline">
            <span class="dib mb3 di-l" :class="localSoftwares.length == 0 ? 'white' : ''">
              {{ $tc('account.employee_statuses_number_positions', localSoftwares.length, { company: $page.props.auth.company.name, count: localSoftwares.length}) }}
            </span>
            <inertia-link :href="localSoftwares.url_new" class="btn absolute-l relative dib-l db right-0">
              Add a software
            </inertia-link>
          </p>

          <!-- LIST OF EXISTING SOFTWARES -->
          <ul v-if="localSoftwares.length != 0" class="list pl0 mv0 center ba br2 bb-gray">
            <li v-for="software in localSoftwares" :key="software.id" class="pv3 ph2 bb bb-gray bb-gray-hover flex justify-between">
              <div class="di">
                {{ software.name }}

                <!-- DELETE A EMPLOYEE STATUS -->
                <div v-if="idToDelete == status.id" class="mt2 db">
                  {{ $t('app.sure') }}
                  <a class="c-delete mr1 pointer" :data-cy="'list-delete-confirm-button-' + status.id" @click.prevent="destroy(status.id)">{{ $t('app.yes') }}</a>
                  <a class="pointer" :data-cy="'list-delete-cancel-button-' + status.id" @click.prevent="idToDelete = 0">{{ $t('app.no') }}</a>
                </div>
                <div v-else class="di">
                  <a class="bb b--dotted bt-0 bl-0 br-0 pointer c-delete" :data-cy="'list-delete-button-' + status.id" @click.prevent="idToDelete = status.id">
                    {{ $t('app.delete') }}
                  </a>
                </div>
              </div>

              <!-- LIST OF ACTIONS FOR EACH EMPLOYEE STATUS -->
              <div v-show="idToUpdate != status.id" class="list pa0 ma0 di-ns db fr-ns mt2 mt0-ns f6">
                <!-- RENAME A EMPLOYEE STATUS -->
                <div class="di mr2">
                  <a class="bb b--dotted bt-0 bl-0 br-0 pointer" :data-cy="'list-rename-button-' + status.id" @click.prevent="displayUpdateModal(status) ; form.name = status.name">{{ $t('app.update') }}</a>
                </div>
              </div>
            </li>
          </ul>
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
    statuses: {
      type: Array,
      default: null,
    },
  },

  data() {
    return {
      localSoftwares: [],
      form: {
        errors: [],
      },
    };
  },

  mounted() {
    this.localSoftwares = this.softwares;
  },

  methods: {
    submit() {
      this.loadingState = 'loading';

      axios.post(this.route('account_employeestatuses.employeestatuses.store', this.$page.props.auth.company.id), this.form)
        .then(response => {
          this.flash(this.$t('account.employee_statuses_success_new'), 'success');

          this.loadingState = null;
          this.form.name = null;
          this.form.type = 'internal';
          this.modal = false;
          this.localSoftwares.push(response.data.data);
        })
        .catch(error => {
          this.loadingState = null;
          this.form.errors = error.response.data;
        });
    },

    update(id) {
      axios.put(this.route('account_employeestatuses.employeestatuses.update', [this.$page.props.auth.company.id, id]), this.form)
        .then(response => {
          this.flash(this.$t('account.employee_statuses_success_update'), 'success');

          this.idToUpdate = 0;
          this.form.name = null;
          this.form.type = 'internal';

          this.localSoftwares[this.localSoftwares.findIndex(x => x.id === id)] = response.data.data;
        })
        .catch(error => {
          this.form.errors = error.response.data;
        });
    },

    destroy(id) {
      axios.delete(this.route('account_employeestatuses.employeestatuses.destroy', [this.$page.props.auth.company.id, id]))
        .then(response => {
          this.flash(this.$t('account.employee_statuses_success_destroy'), 'success');

          this.idToDelete = 0;
          var changedId = this.localSoftwares.findIndex(x => x.id === id);
          this.localSoftwares.splice(changedId, 1);
        })
        .catch(error => {
          this.form.errors = error.response.data;
        });
    },
  }
};

</script>
