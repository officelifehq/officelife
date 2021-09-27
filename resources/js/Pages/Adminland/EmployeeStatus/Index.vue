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
      <breadcrumb :with-box="true"
                  :root-url="'/' + $page.props.auth.company.id + '/dashboard'"
                  :root="$t('app.breadcrumb_dashboard')"
                  :previous-url="'/' + $page.props.auth.company.id + '/account'"
                  :previous="$t('app.breadcrumb_account_home')"
                  :has-more="false"
      >
        {{ $t('app.breadcrumb_account_manage_employee_statuses') }}
      </breadcrumb>

      <!-- BODY -->
      <div class="mw7 center br3 mb5 bg-white box restricted relative z-1">
        <div class="pa3 mt5">
          <h2 class="tc normal mb4">
            {{ $t('account.employee_statuses_title', { company: $page.props.auth.company.name}) }}

            <help :url="$page.props.help_links.employee_statuses" :top="'1px'" />
          </h2>

          <p class="relative adminland-headline">
            <span class="dib mb3 di-l" :class="localStatuses.length == 0 ? 'white' : ''">
              {{ $tc('account.employee_statuses_number_positions', localStatuses.length, { company: $page.props.auth.company.name, count: localStatuses.length}) }}
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
                  :errors="$page.props.errors.name"
                  :datacy="'add-title-input'"
                  required
                  :placeholder="$t('account.employee_statuses_placeholder')"
                  :extra-class-upper-div="'mb3'"
                  @esc-key-pressed="modal = false; form.name = ''"
                />

                <checkbox
                  :datacy="'external-employee-checkbox'"
                  :label="$t('account.employee_statuses_new_external')"
                  :help="$t('account.employee_statuses_new_external_help')"
                  :extra-class-upper-div="'mb0 relative'"
                  :required="false"
                  @update:model-value="updateType($event)"
                />
              </div>
              <div class="fl w-30-ns w-100 tr">
                <a class="btn dib-l db mb2 mb0-ns mr2" @click.prevent="modal = false ; form.name = ''">
                  {{ $t('app.cancel') }}
                </a>
                <loading-button :class="'btn add w-auto-ns w-100 mb2 mb0-ns pv2 ph3'" data-cy="modal-add-cta" :state="loadingState" :text="$t('app.add')" />
              </div>
            </div>
          </form>

          <!-- LIST OF EXISTING EMPLOYEE STATUSES -->
          <ul v-show="localStatuses.length != 0" class="list pl0 mv0 center ba br2 bb-gray" data-cy="statuses-list" :data-cy-items="localStatuses.map(n => n.id)">
            <li v-for="status in localStatuses" :key="status.id" class="pv3 ph2 bb bb-gray bb-gray-hover" :data-cy="'status-item-' + status.id">
              <div v-if="idToUpdate != status.id" class="di">
                {{ status.name }} <span class="type">{{ $t('account.employee_statuses_'+status.type) }}</span>
              </div>

              <!-- UPDATE POSITION FORM -->
              <div v-if="idToUpdate == status.id" class="cf">
                <form @submit.prevent="update(status.id)">
                  <div class="fl w-100 w-70-ns mb3 mb0-ns">
                    <text-input :id="'name-' + status.id"
                                :ref="'name' + status.id"
                                v-model="form.name"
                                :custom-ref="'name' + status.id"
                                :datacy="'list-rename-input-name-' + status.id"
                                :errors="$page.props.errors.name"
                                required
                                :extra-class-upper-div="'mb3'"
                                @esc-key-pressed="idToUpdate = 0"
                    />

                    <checkbox
                      :id="'home'"
                      v-model="form.checked"
                      :datacy="'external-employee-checkbox-' + status.id"
                      :label="$t('account.employee_statuses_new_external')"
                      :help="$t('account.employee_statuses_new_external_help')"
                      :extra-class-upper-div="'mb0 relative'"
                      :required="false"
                      @update:model-value="updateType($event)"
                    />
                  </div>
                  <div class="fl w-30-ns w-100 tr">
                    <a class="btn dib-l db mb2 mb0-ns mr2" :data-cy="'list-rename-cancel-button-' + status.id" @click.prevent="idToUpdate = 0">
                      {{ $t('app.cancel') }}
                    </a>
                    <loading-button :class="'btn add w-auto-ns w-100 mb2 pv2 ph3'" :data-cy="'list-rename-cta-button-' + status.id" :state="loadingState" :text="$t('app.update')" />
                  </div>
                </form>
              </div>

              <!-- LIST OF ACTIONS FOR EACH EMPLOYEE STATUS -->
              <ul v-show="idToUpdate != status.id" class="list pa0 ma0 di-ns db fr-ns mt2 mt0-ns f6">
                <!-- RENAME A EMPLOYEE STATUS -->
                <li class="di mr2">
                  <a class="bb b--dotted bt-0 bl-0 br-0 pointer" :data-cy="'list-rename-button-' + status.id" @click.prevent="displayUpdateModal(status) ; form.name = status.name">{{ $t('app.update') }}</a>
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
          <div v-show="localStatuses.length == 0" class="pa3 mt5">
            <p class="tc measure center mb4 lh-copy">
              {{ $t('account.employee_statuses_blank') }}
            </p>
            <img loading="lazy" class="db center mb4" alt="add a position symbol" srcset="/img/company/account/blank-position-1x.png,
                                          /img/company/account/blank-position-2x.png 2x"
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
import Breadcrumb from '@/Shared/Layout/Breadcrumb';
import Checkbox from '@/Shared/Checkbox';
import Help from '@/Shared/Help';

export default {
  components: {
    Layout,
    Breadcrumb,
    TextInput,
    Errors,
    LoadingButton,
    Checkbox,
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

  watch: {
    statuses: {
      handler(value) {
        this.localStatuses = value;
      },
      deep: true
    }
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
        this.$refs.newStatus.focus();
      });
    },

    displayUpdateModal(status) {
      this.idToUpdate = status.id;
      this.form.checked = status.type == 'internal' ? false : true;

      this.$nextTick(() => {
        this.$refs[`name${status.id}`].focus();
      });
    },

    submit() {
      this.loadingState = 'loading';

      axios.post(this.route('account_employeestatuses.employeestatuses.store', this.$page.props.auth.company.id), this.form)
        .then(response => {
          this.flash(this.$t('account.employee_statuses_success_new'), 'success');

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
          this.flash(this.$t('account.employee_statuses_success_update'), 'success');

          this.idToUpdate = 0;
          this.form.name = null;
          this.form.type = 'internal';

          this.localStatuses[this.localStatuses.findIndex(x => x.id === id)] = response.data.data;
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
