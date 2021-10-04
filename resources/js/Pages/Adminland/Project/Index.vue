<style scoped>
.list li:last-child {
  border-bottom: 0;
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
        {{ $t('app.breadcrumb_account_manage_project') }}
      </breadcrumb>

      <!-- BODY -->
      <div class="mw7 center br3 mb5 bg-white box restricted relative z-1">
        <div class="pa3 mt5">
          <h2 class="tc normal mb4">
            {{ $t('account.project_management_title') }}
          </h2>

          <p class="relative adminland-headline">
            <a class="btn absolute-l relative dib-l db right-0" data-cy="add-position-button" @click.prevent="displayAddModal">
              {{ $t('account.project_management_cta') }}
            </a>
          </p>

          <!-- MODAL TO ADD AN ISSUE TYPE -->
          <form v-show="modal" class="mb3 pa3 ba br2 bb-gray bg-gray" @submit.prevent="submit">
            <errors :errors="form.errors" />

            <div class="cf">
              <div class="fl w-100 w-70-ns mb3 mb0-ns">
                <text-input :ref="'newIssueType'"
                            v-model="form.name"
                            :errors="$page.props.errors.name"
                            :extra-class-upper-div="'mb0'"
                            @esc-key-pressed="modal = false"
                />
              </div>
              <div class="fl w-30-ns w-100 tr">
                <a class="btn dib-l db mb2 mb0-ns mr2-ns" @click.prevent="modal = false">
                  {{ $t('app.cancel') }}
                </a>
                <loading-button :class="'btn add w-auto-ns w-100 pv2 ph3'" :state="loadingState" :text="$t('app.add')" />
              </div>
            </div>
          </form>

          <!-- LIST OF EXISTING ISSUE TYPE -->
          <ul v-show="localIssueTypes.length != 0" class="list pl0 mv0 center ba br2 bb-gray" data-cy="positions-list">
            <li v-for="(issueType) in localIssueTypes" :key="issueType.id" class="pv3 ph2 bb bb-gray bb-gray-hover">
              {{ issueType.title }}

              <!-- RENAME ISSUE TYPE FORM -->
              <div v-show="idToUpdate == issueType.id" class="cf mt3">
                <form @submit.prevent="update(issueType.id)">
                  <div class="fl w-100 w-70-ns mb3 mb0-ns">
                    <text-input :id="'name-' + issueType.id"
                                :ref="'name' + issueType.id"
                                v-model="form.name"
                                :custom-ref="'title' + issueType.id"
                                :errors="$page.props.errors.name"
                                required
                                :extra-class-upper-div="'mb0'"
                                @esc-key-pressed="idToUpdate = 0"
                    />
                  </div>
                  <div class="fl w-30-ns w-100 tr">
                    <a class="btn dib-l db mb2 mb0-ns mr2-ns" @click.prevent="idToUpdate = 0">
                      {{ $t('app.cancel') }}
                    </a>
                    <loading-button :class="'btn add w-auto-ns w-100 mb2 pv2 ph3'" :state="loadingState" :text="$t('app.update')" />
                  </div>
                </form>
              </div>

              <!-- LIST OF ACTIONS FOR EACH ISSUE TYPE -->
              <ul v-show="idToUpdate != issueType.id" class="list pa0 ma0 di-ns db fr-ns mt2 mt0-ns f6">
                <!-- RENAME A ISSUE TYPE -->
                <li class="di mr2">
                  <a class="bb b--dotted bt-0 bl-0 br-0 pointer" :data-cy="'list-rename-button-' + issueType.id" @click.prevent="displayUpdateModal(issueType) ; form.title = issueType.title">{{ $t('app.rename') }}</a>
                </li>

                <!-- DELETE A ISSUE TYPE -->
                <li v-if="idToDelete == issueType.id" class="di">
                  {{ $t('app.sure') }}
                  <a class="c-delete mr1 pointer" @click.prevent="destroy(issueType.id)">
                    {{ $t('app.yes') }}
                  </a>
                  <a class="pointer" @click.prevent="idToDelete = 0">
                    {{ $t('app.no') }}
                  </a>
                </li>
                <li v-else class="di">
                  <a class="bb b--dotted bt-0 bl-0 br-0 pointer c-delete" @click.prevent="idToDelete = issueType.id">
                    {{ $t('app.delete') }}
                  </a>
                </li>
              </ul>
            </li>
          </ul>

          <!-- BLANK STATE -->
          <div v-show="localIssueTypes.length == 0" class="pa3 mt5">
            <p class="tc measure center mb4 lh-copy">
              {{ $t('account.issueTypes_blank') }}
            </p>
            <img loading="lazy" class="db center mb4" alt="add a issueType symbol" srcset="/img/company/account/blank-issueType-1x.png,
                                          /img/company/account/blank-issueType-2x.png 2x"
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

export default {
  components: {
    Layout,
    Breadcrumb,
    TextInput,
    Errors,
    LoadingButton,
  },

  props: {
    notifications: {
      type: Array,
      default: null,
    },
    data: {
      type: Array,
      default: null,
    },
  },

  data() {
    return {
      localIssueTypes: [],
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

  watch: {
    localIssueTypes: {
      handler(value) {
        this.localIssueTypes = value;
      },
      deep: true
    }
  },

  mounted() {
    this.localIssueTypes = this.data.issue_types;
  },

  methods: {
    displayAddModal() {
      this.modal = true;

      this.$nextTick(() => {
        this.$refs.newIssueType.focus();
      });
    },

    displayUpdateModal(issueType) {
      this.idToUpdate = issueType.id;

      this.$nextTick(() => {
        this.$refs[`title${issueType.id}`].focus();
      });
    },

    submit() {
      this.loadingState = 'loading';

      axios.post(this.route('positions.store', this.$page.props.auth.company.id), this.form)
        .then(response => {
          this.flash(this.$t('account.position_success_new'), 'success');

          this.loadingState = null;
          this.form.title = null;
          this.modal = false;
          this.localIssueTypes.push(response.data.data);
        })
        .catch(error => {
          this.loadingState = null;
          this.form.errors = error.response.data;
        });
    },

    update(id) {
      axios.put(this.route('positions.update', [this.$page.props.auth.company.id, id]), this.form)
        .then(response => {
          this.flash(this.$t('account.position_success_update'), 'success');

          this.idToUpdate = 0;
          this.form.title = null;

          this.localIssueTypes[this.localIssueTypes.findIndex(x => x.id === id)] = response.data.data;
        })
        .catch(error => {
          this.form.errors = error.response.data;
        });
    },

    destroy(id) {
      axios.delete(this.route('positions.destroy', [this.$page.props.auth.company.id, id]))
        .then(response => {
          this.flash(this.$t('account.position_success_destroy'), 'success');

          this.idToDelete = 0;
          id = this.localIssueTypes.findIndex(x => x.id === id);
          this.localIssueTypes.splice(id, 1);
        })
        .catch(error => {
          this.form.errors = error.response.data;
        });
    },
  }
};

</script>
