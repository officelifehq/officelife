<style scoped>
.list li:last-child {
  border-bottom: 0;
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
            <inertia-link :href="'/' + $page.auth.company.id + '/account'">{{ $t('app.breadcrumb_account_home') }}</inertia-link>
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
            {{ $t('account.employee_statuses_title', { company: $page.auth.company.name}) }}
          </h2>

          <p class="relative adminland-headline">
            <span class="dib mb3 di-l" :class="statuses.length == 0 ? 'white' : ''">
              {{ $tc('account.employee_statuses_number_positions', statuses.length, { company: $page.auth.company.name, count: statuses.length}) }}
            </span>
            <a class="btn absolute-l relative dib-l db right-0" data-cy="add-status-button" @click.prevent="displayAddModal">
              {{ $t('account.employee_statuses_cta') }}
            </a>
          </p>

          <!-- MODAL TO ADD AN EMPLOYEE STATUS -->
          <form v-show="modal" class="mb3 pa3 ba br2 bb-gray bg-gray" @submit.prevent="submit">
            <errors :errors="form.errors" />

            <div class="cf">
              <div class="fl w-100 w-70-ns mb0-ns">
                <text-input
                  :ref="'newStatus'"
                  v-model="form.name"
                  :errors="$page.errors.name"
                  :datacy="'add-title-input'"
                  required
                  :placeholder="$t('account.employee_statuses_placeholder')"
                  :extra-class-upper-div="'mb0'"
                />
              </div>
              <div class="fl w-30-ns w-100 tr">
                <a class="btn dib-l db mb2 mb0-ns" @click.prevent="modal = false ; form.name = ''">
                  {{ $t('app.cancel') }}
                </a>
                <loading-button :classes="'btn add w-auto-ns w-100 mb2 pv2 ph3'" data-cy="modal-add-cta" :state="loadingState" :text="$t('app.add')" />
              </div>
            </div>
          </form>

          <!-- LIST OF EXISTING EMPLOYEE STATUSES -->
          <ul v-show="statuses.length != 0" class="list pl0 mv0 center ba br2 bb-gray" data-cy="statuses-list">
            <li v-for="status in statuses" :key="status.id" class="pv3 ph2 bb bb-gray bb-gray-hover">
              {{ status.name }}

              <!-- RENAME POSITION FORM -->
              <div v-show="idToUpdate == status.id" class="cf mt3">
                <form @submit.prevent="update(status.id)">
                  <div class="fl w-100 w-70-ns mb3 mb0-ns">
                    <text-input :id="'name-' + status.id"
                                :ref="'name' + status.id"
                                v-model="form.name"
                                :custom-ref="'name' + status.id"
                                :datacy="'list-rename-input-name-' + status.id"
                                :errors="$page.errors.name"
                                required
                                :extra-class-upper-div="'mb0'"
                                @esc-key-pressed="idToUpdate = 0"
                    />
                  </div>
                  <div class="fl w-30-ns w-100 tr">
                    <a class="btn dib-l db mb2 mb0-ns" :data-cy="'list-rename-cancel-button-' + status.id" @click.prevent="idToUpdate = 0">
                      {{ $t('app.cancel') }}
                    </a>
                    <loading-button :classes="'btn add w-auto-ns w-100 mb2 pv2 ph3'" :data-cy="'list-rename-cta-button-' + status.id" :state="loadingState" :text="$t('app.update')" />
                  </div>
                </form>
              </div>

              <!-- LIST OF ACTIONS FOR EACH EMPLOYEE STATUS -->
              <ul v-show="idToUpdate != status.id" class="list pa0 ma0 di-ns db fr-ns mt2 mt0-ns f6">
                <!-- RENAME A EMPLOYEE STATUS -->
                <li class="di mr2">
                  <a class="bb b--dotted bt-0 bl-0 br-0 pointer" :data-cy="'list-rename-button-' + status.id" @click.prevent="displayUpdateModal(status) ; form.name = status.name">{{ $t('app.rename') }}</a>
                </li>

                <!-- DELETE A EMPLOYEE STATUS -->
                <li v-if="idToDelete == status.id" class="di">
                  {{ $t('app.sure') }}
                  <a class="c-delete mr1 pointer" :data-cy="'list-delete-confirm-button-' + status.id" @click.prevent="destroy(status.id)">{{ $t('app.yes') }}</a>
                  <a class="pointer" :data-cy="'list-delete-cancel-button-' + status.id" @click.prevent="idToDelete = 0">{{ $t('app.no') }}</a>
                </li>
                <li v-else class="di">
                  <a class="bb b--dotted bt-0 bl-0 br-0 pointer c-delete" :data-cy="'list-delete-button-' + status.id" @click.prevent="idToDelete = status.id">
                    {{ $t('app.delete') }}
                  </a>
                </li>
              </ul>
            </li>
          </ul>

          <!-- BLANK STATE -->
          <div v-show="statuses.length == 0" class="pa3 mt5">
            <p class="tc measure center mb4 lh-copy">
              {{ $t('account.employee_statuses_blank') }}
            </p>
            <img class="db center mb4" alt="add a position symbol" srcset="/img/company/account/blank-position-1x.png,
                                          /img/company/account/blank-position-2x.png 2x" loading="lazy"
            />
          </div>
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
    statuses: {
      type: Array,
      default: null,
    },
  },

  data() {
    return {
      modal: false,
      deleteModal: false,
      updateModal: false,
      loadingState: '',
      updateModalId: 0,
      idToUpdate: 0,
      idToDelete: 0,
      form: {
        name: null,
        errors: [],
      },
    };
  },

  methods: {
    displayAddModal() {
      this.modal = true;
      this.form.name = '';

      this.$nextTick(() => {
        this.$refs['newStatus'].$refs['input'].focus();
      });
    },

    displayUpdateModal(status) {
      this.idToUpdate = status.id;

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

      axios.post('/' + this.$page.auth.company.id + '/account/employeestatuses', this.form)
        .then(response => {
          flash(this.$t('account.employee_statuses_success_new'), 'success');

          this.loadingState = null;
          this.form.name = null;
          this.modal = false;
          this.statuses.push(response.data.data);
        })
        .catch(error => {
          this.loadingState = null;
          this.form.errors = _.flatten(_.toArray(error.response.data));
        });
    },

    update(id) {
      axios.put('/' + this.$page.auth.company.id + '/account/employeestatuses/' + id, this.form)
        .then(response => {
          flash(this.$t('account.employee_statuses_success_update'), 'success');

          this.idToUpdate = 0;
          this.form.name = null;

          var changedId = this.statuses.findIndex(x => x.id === id);
          this.$set(this.statuses, changedId, response.data.data);
        })
        .catch(error => {
          this.form.errors = _.flatten(_.toArray(error.response.data));
        });
    },

    destroy(id) {
      axios.delete('/' + this.$page.auth.company.id + '/account/employeestatuses/' + id)
        .then(response => {
          flash(this.$t('account.employee_statuses_success_destroy'), 'success');

          this.idToDelete = 0;
          var changedId = this.statuses.findIndex(x => x.id === id);
          this.statuses.splice(changedId, 1);
        })
        .catch(error => {
          this.form.errors = _.flatten(_.toArray(error.response.data));
        });
    },
  }
};

</script>
