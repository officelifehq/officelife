<style style="scss" scoped>
  .question-item:last-child {
    border-bottom: 0;
    padding-bottom: 0;
  }
</style>

<template>
  <layout title="Home" :notifications="notifications">
    <div class="ph2 ph0-ns">
      <!-- BREADCRUMB -->
      <div class="mt4-l mt1 mw6 br3 bg-white box center breadcrumb relative z-0 f6 pb2">
        <ul class="list ph0 tc-l tl">
          <li class="di">
            <inertia-link :href="'/' + $page.auth.company.id + '/dashboard'">{{ $page.auth.company.name }}</inertia-link>
          </li>
          <li class="di">
            <inertia-link :href="'/' + $page.auth.company.id + '/account'">{{ $t('app.breadcrumb_account_home') }}</inertia-link>
          </li>
          <li class="di">
            {{ $t('app.breadcrumb_account_manage_questions') }}
          </li>
        </ul>
      </div>

      <!-- BODY -->
      <div class="mw7 center br3 mb5 bg-white box restricted relative z-1">
        <!-- WHEN THERE ARE QUESTIONS -->
        <div class="pa3 mt5">
          <h2 class="tc normal mb4">
            {{ $t('account.questions_title', { company: $page.auth.company.name}) }}
          </h2>

          <!-- add a question -->
          <div class="relative mb4">
            <span v-show="questions.length != 0" class="dib mb3 di-l">
              {{ $tc('account.questions_number_questions', questions.length, { company: $page.auth.company.name, count: questions.length}) }}
            </span>
            <a data-cy="add-team-button" class="btn tc absolute-l relative dib-l db right-0" @click.prevent="displayAddModal">
              {{ $t('account.questions_cta') }}
            </a>
          </div>

          <!-- MODAL TO ADD A QUESTION -->
          <form v-show="modal" class="mb3 pa3 ba br2 bb-gray bg-gray" @submit.prevent="submit">
            <errors :errors="form.errors" />

            <div class="cf">
              <div class="fl w-100 w-70-ns mb3 mb0-ns">
                <text-input :ref="'newPositionModal'"
                            v-model="form.title"
                            :placeholder="'Marketing coordinator'"
                            :datacy="'add-title-input'"
                            :errors="$page.errors.first_name"
                            :extra-class-upper-div="'mb0'"
                            @esc-key-pressed="modal = false"
                />
              </div>
              <div class="fl w-30-ns w-100 tr">
                <a class="btn dib-l db mb2 mb0-ns" @click.prevent="modal = false">
                  {{ $t('app.cancel') }}
                </a>
                <loading-button :classes="'btn add w-auto-ns w-100 pv2 ph3'" data-cy="modal-add-cta" :state="loadingState" :text="$t('app.add')" />
              </div>
            </div>
          </form>

          <!-- LIST OF QUESTIONS -->
          <ul v-show="questions.length != 0" class="list pl0 mt0 center">
            <li
              v-for="question in questions" :key="question.id"
              class="pa3-l pa1 ph0-l bb b--black-10 question-item"
            >
              <!-- normal case (ie not rename mode) -->
              <template v-if="questionToRename.id != question.id && questionToDelete.id != question.id">
                <span class="db b mb2" :data-cy="'list-question-' + question.id">
                  {{ question.title }}
                </span>

                <!-- list of actions -->
                <ul class="f6 list pl0">
                  <li class="di pr2">
                    <a href="#" class="bb b--dotted bt-0 bl-0 br-0 pointer" :data-cy="'question-activate-link-' + question.id" @click.prevent="showActivateModal(team)">{{ $t('app.rename') }}</a>
                  </li>
                  <li class="di pr2">
                    <a href="#" class="bb b--dotted bt-0 bl-0 br-0 pointer" :data-cy="'question-rename-link-' + question.id" @click.prevent="showRenameModal(team)">{{ $t('app.rename') }}</a>
                  </li>
                  <li class="di">
                    <a href="#" class="bb b--dotted bt-0 bl-0 br-0 pointer c-delete" :data-cy="'question-destroy-link-' + question.id" @click.prevent="showDeletionModal(team)">{{ $t('app.delete') }}</a>
                  </li>
                </ul>
              </template>

              <!-- rename modal -->
              <template v-if="questionToRename.id == question.id && renameMode">
                <template v-if="form.errors.length > 0">
                  <div class="cf pb1 w-100 mb2">
                    <errors :errors="form.errors" />
                  </div>
                </template>

                <!-- form -->
                <form class="flex" @submit.prevent="update(team)">
                  <div class="w-100 w-70-ns mb3 mb0-ns mt3">
                    <text-input :id="'name-' + question.id"
                                :ref="'name' + question.id"
                                v-model="form.name"
                                :placeholder="'Product team'"
                                :custom-ref="'name' + question.id"
                                :datacy="'list-rename-input-name-' + question.id"
                                :errors="$page.errors.name"
                                required
                                :extra-class-upper-div="'mb0'"
                                @esc-key-pressed="questionToRename = 0"
                    />
                  </div>

                  <!-- actions -->
                  <div class="w-30-ns w-100 tr">
                    <a class="btn dib tc w-auto-ns w-100 mb2 pv2 ph3" :data-cy="'list-rename-cancel-button-' + question.id" @click.prevent="questionToRename = 0">
                      {{ $t('app.cancel') }}
                    </a>
                    <loading-button :classes="'btn add w-auto-ns w-100 mb2 pv2 ph3'" :data-cy="'list-rename-cta-button-' + question.id" :state="loadingState" :text="$t('app.update')" />
                  </div>
                </form>
              </template>

              <!-- deletion modal -->
              <template v-if="questionToDelete.id == question.id && deletionMode">
                <template v-if="form.errors.length > 0">
                  <div class="cf pb1 w-100 mb2">
                    <errors :errors="form.errors" />
                  </div>
                </template>

                <!-- form -->
                <form @submit.prevent="destroy(team)">
                  <p class="lh-copy">{{ $t('account.team_confirm_deletion', {name: question.title}) }}</p>
                  <a class="btn dib tc w-auto-ns w-100 mb2 pv2 ph3 mr3" :data-cy="'list-destroy-cancel-button-' + question.id" @click.prevent="questionToDelete = 0">
                    {{ $t('app.cancel') }}
                  </a>
                  <loading-button :classes="'btn destroy w-auto-ns w-100 mb2 pv2 ph3'" :data-cy="'list-destroy-cta-button-' + question.id" :state="loadingState" :text="$t('app.delete')" />
                </form>
              </template>
            </li>
          </ul>
        </div>

        <!-- NO questions -->
        <div v-show="questions.length == 0" class="pa3">
          <p class="tc measure center mb4 lh-copy">
            {{ $t('account.questions_blank') }}
          </p>
          <img class="db center mb4" alt="team" srcset="/img/company/account/blank-team-1x.png,
                                        /img/company/account/blank-team-2x.png 2x"
          />
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
    questions: {
      type: Array,
      default: null,
    },
    notifications: {
      type: Array,
      default: null,
    },
  },

  data() {
    return {
      modal: false,
      renameMode: false,
      deletionMode: false,
      questionToRename: Object,
      questionToDelete: Object,
      form: {
        title: null,
        errors: [],
      },
      loadingState: '',
      errorTemplate: Error,
    };
  },

  methods: {
    showRenameModal(team) {
      this.form.errors = [];
      this.renameMode = true;
      this.questionToRename = team;
      this.form.name = question.title;

      this.$nextTick(() => {
        // this is really barbaric, but I need to do this to
        // first: target the TextInput with the right ref attribute
        // second: target within the component, the refs of the input text
        // this is because we try here to access $refs from a child component
        this.$refs[`name${question.id}`][0].$refs[`name${question.id}`].focus();
      });
    },

    showDeletionModal(team) {
      this.form.errors = [];
      this.deletionMode = true;
      this.questionToDelete = team;
    },

    displayAddModal() {
      this.modal = !this.modal;
      this.form.errors = [];

      this.$nextTick(() => {
        this.$refs['newTeam'].$refs['input'].focus();
      });
    },

    submit() {
      this.loadingState = 'loading';

      axios.post('/' + this.$page.auth.company.id + '/account/questions', this.form)
        .then(response => {
          flash(this.$t('account.team_creation_success'), 'success');

          this.loadingState = null;
          this.form.name = null;
          this.modal = false;
          this.questions.push(response.data.data);
        })
        .catch(error => {
          this.loadingState = null;
          this.form.errors = _.flatten(_.toArray(error.response.data));
        });
    },

    update(team) {
      axios.put('/' + this.$page.auth.company.id + '/account/questions/' + question.id, this.form)
        .then(response => {
          flash(this.$t('account.team_update_success'), 'success');

          this.questionToRename = 0;
          this.form.name = null;

          var id = this.questions.findIndex(x => x.id == question.id);
          this.$set(this.questions, id, response.data.data);
        })
        .catch(error => {
          this.form.errors = _.flatten(_.toArray(error.response.data));
        });
    },

    destroy(team) {
      axios.delete('/' + this.$page.auth.company.id + '/account/questions/' + question.id)
        .then(response => {
          flash(this.$t('account.team_destroy_success'), 'success');

          this.questionToDelete = 0;
          var id = this.questions.findIndex(x => x.id == question.id);
          this.questions.splice(id, 1);
        })
        .catch(error => {
          this.form.errors = _.flatten(_.toArray(error.response.data));
        });
    },
  }
};

</script>
