<style lang="scss" scoped>
.type-list {
  li:first-child:hover {
    border-top-left-radius: 10px;
    border-top-right-radius: 10px;
  }

  li:last-child {
    border-bottom: 0;

    &:hover {
      border-bottom-left-radius: 10px;
      border-bottom-right-radius: 10px;
    }
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
        {{ $t('app.breadcrumb_account_manage_project') }}
      </breadcrumb>

      <!-- BODY -->
      <div class="mw7 center br3 mb5 bg-white box restricted relative z-1">
        <div class="pa3 mt5">
          <h2 class="tc normal mb4">
            {{ $t('account.project_management_title') }}
          </h2>

          <div class="relative adminland-headline">
            <p v-if="localIssueTypes.length > 0">All the issue types</p>
            <a class="btn absolute-l relative dib-l db right-0" @click.prevent="displayAddModal">
              {{ $t('account.project_management_cta') }}
            </a>
          </div>

          <!-- MODAL TO ADD AN ISSUE TYPE -->
          <form v-show="modal" class="mb3 pa3 ba br2 bb-gray bg-gray" @submit.prevent="submit">
            <errors :errors="form.errors" />

            <div class="cf">
              <div class="fl w-100 w-70-ns mb3 mb0-ns">
                <div class="flex justify-between">
                  <text-input :ref="'newIssueType'"
                              v-model="form.name"
                              :label="$t('account.issue_type_name')"
                              :required="true"
                              :errors="$page.props.errors.name"
                              :extra-class-upper-div="'mb0 mr2'"
                              @esc-key-pressed="modal = false"
                  />
                  <text-input :ref="'newIssueType'"
                              v-model="form.icon_hex_color"
                              :label="$t('account.issue_type_color')"
                              :help="$t('account.issue_type_color_help')"
                              :placeholder="'#ae4111'"
                              :maxlength="7"
                              :required="true"
                              :errors="$page.props.errors.name"
                              :extra-class-upper-div="'mb0'"
                              @esc-key-pressed="modal = false"
                  />
                </div>
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
          <ul v-show="localIssueTypes.length != 0" class="list pl0 mv0 center ba br2 bb-gray type-list">
            <li v-for="(issueType) in localIssueTypes" :key="issueType.id" class="pv3 ph2 bb bb-gray bb-gray-hover relative">
              <icon-issue-type :background-color="issueType.icon_hex_color" />

              {{ issueType.name }}

              <!-- RENAME ISSUE TYPE FORM -->
              <div v-if="idToUpdate == issueType.id" class="cf mt3">
                <form @submit.prevent="update(issueType)">
                  <div class="fl w-100 w-70-ns mb3 mb0-ns">
                    <div class="flex justify-between">
                      <text-input :ref="'newIssueType'"
                                  v-model="form.name"
                                  :label="'Name'"
                                  :required="true"
                                  :errors="$page.props.errors.name"
                                  :extra-class-upper-div="'mb0 mr2'"
                                  @esc-key-pressed="updateModal = false"
                      />
                      <text-input :ref="'newIssueType'"
                                  v-model="form.icon_hex_color"
                                  :label="'Color of the icon'"
                                  :help="'Represented in hexadecimal.'"
                                  :placeholder="'#ae4111'"
                                  :maxlength="7"
                                  :required="true"
                                  :errors="$page.props.errors.name"
                                  :extra-class-upper-div="'mb0'"
                                  @esc-key-pressed="updateModal = false"
                      />
                    </div>
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
                  <a class="bb b--dotted bt-0 bl-0 br-0 pointer" @click.prevent="displayUpdateModal(issueType)">{{ $t('app.rename') }}</a>
                </li>

                <!-- DELETE A ISSUE TYPE -->
                <li v-if="idToDelete == issueType.id" class="di">
                  {{ $t('app.sure') }}
                  <a class="c-delete mr1 pointer" @click.prevent="destroy(issueType)">
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
              {{ $t('account.project_management_blank') }}
            </p>

            <img loading="lazy" class="db center mb4" alt="team" src="/img/streamline-icon-package-worker-5@100x100.png" />
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
import IconIssueType from '@/Shared/IconIssueType';
import Layout from '@/Shared/Layout';
import Breadcrumb from '@/Shared/Layout/Breadcrumb';

export default {
  components: {
    Layout,
    Breadcrumb,
    TextInput,
    IconIssueType,
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
        icon_hex_color: null,
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
      this.form.name = issueType.name;
      this.form.icon_hex_color = issueType.icon_hex_color;
    },

    submit() {
      this.loadingState = 'loading';

      axios.post(this.data.url_create, this.form)
        .then(response => {
          this.flash(this.$t('account.project_management_success_new'), 'success');

          this.loadingState = null;
          this.form.name = null;
          this.modal = false;
          this.localIssueTypes.push(response.data.data);
        })
        .catch(error => {
          this.loadingState = null;
          this.form.errors = error.response.data;
        });
    },

    update(type) {
      axios.put(type.url.update, this.form)
        .then(response => {
          this.flash(this.$t('account.project_management_success_update'), 'success');

          this.idToUpdate = 0;
          this.form.name = null;

          this.localIssueTypes[this.localIssueTypes.findIndex(x => x.id === type.id)] = response.data.data;
        })
        .catch(error => {
          this.form.errors = error.response.data;
        });
    },

    destroy(type) {
      axios.delete(type.url.destroy)
        .then(response => {
          this.flash(this.$t('account.project_management_success_destroy'), 'success');

          this.idToDelete = 0;
          var id = this.localIssueTypes.findIndex(x => x.id === type.id);
          this.localIssueTypes.splice(id, 1);
        })
        .catch(error => {
          this.form.errors = error.response.data;
        });
    },
  }
};

</script>
