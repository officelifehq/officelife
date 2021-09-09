<style lang="scss" scoped>
.avatar {
  left: 1px;
  top: 5px;
  width: 35px;
}

.box-bottom {
  border-bottom-left-radius: 11px;
  border-bottom-right-radius: 11px;
}
</style>

<template>
  <layout :notifications="notifications">
    <div class="ph2 ph5-ns">
      <breadcrumb :has-more="false"
                  :previous-url="route('projects.index', { company: $page.props.auth.company.id})"
                  :previous="$t('app.breadcrumb_project_list')"
      >
        {{ $t('app.breadcrumb_project_detail') }}
      </breadcrumb>

      <!-- BODY -->
      <div class="mw8 center br3 mb5 relative z-1">
        <!-- Menu -->
        <project-menu :project="project" :tab="tab" />
      </div>

      <!-- BODY -->
      <div class="mw8 center br3 mb5 relative cf">
        <h2 class="mt0 mb3 fw5" data-cy="project-title">
          {{ message.title }}
        </h2>

        <!-- LEFT COLUMN -->
        <div class="fl w-70-l w-100">
          <!-- message content -->
          <div class="bg-white box mb4">
            <div class="pa3 pb3" data-cy="project-content">
              <div class="parsed-content" v-html="message.parsed_content"></div>
            </div>
          </div>

          <!-- existing comments -->
          <div v-if="localComments.length > 0">
            <div v-for="comment in localComments" :key="comment.id" class="flex">
              <!-- avatar -->
              <div v-if="comment.author.id">
                <avatar :avatar="comment.author.avatar" :size="32" :class="'br-100 mr3'" />
              </div>

              <!-- avatar if the employee is no longer in the system -->
              <div v-else>
                <img loading="lazy" src="/img/streamline-icon-avatar-neutral@100x100.png" alt="anonymous avatar" class="br-100 mr3" height="32"
                     width="32"
                />
              </div>

              <!-- comment box -->
              <div class="box bg-white mb4 w-100">
                <!-- comment -->
                <div class="bb bb-gray ph3" v-html="comment.content"></div>

                <!-- comment info -->
                <div class="bg-gray ph3 pv2 f7 box-bottom">
                  <ul class="ma0 list pl0">
                    <li v-if="comment.author.id" class="di" v-html="$t('project.message_comment_written_by', { url: comment.author.url, name: comment.author.name, date: comment.written_at })"></li>
                    <li v-else class="di">{{ $t('project.message_comment_written_by_anonymous', { name: comment.author, date: comment.written_at }) }}</li>

                    <!-- edit -->
                    <li v-if="comment.can_edit" class="di ml2">
                      <a class="bb b--dotted bt-0 bl-0 br-0 pointer">{{ $t('app.edit') }}</a>
                    </li>

                    <!-- delete -->
                    <li v-if="comment.can_delete" class="di ml2">
                      <a class="bb b--dotted bt-0 bl-0 br-0 pointer c-delete">{{ $t('app.delete') }}</a>
                    </li>
                  </ul>
                </div>
              </div>
            </div>
          </div>

          <!-- post a comment box -->
          <div class="bg-white box mb4">
            <form class="pa3" @submit.prevent="storeComment()">
              <text-area
                v-model="form.comment"
                :label="$t('project.message_comment_label')"
                :required="true"
                :rows="4"
              />

              <!-- actions -->
              <div class="flex justify-between">
                <loading-button :class="'btn add w-auto-ns w-100 mb2 pv2 ph3'" :state="loadingState" :text="$t('app.post')" />
              </div>
            </form>
          </div>
        </div>

        <!-- RIGHT COLUMN -->
        <div class="fl w-30-l w-100 pl4-l">
          <!-- written by -->
          <h3 v-if="message.author" class="ttc f7 gray mt0 mb2 fw4">
            {{ $t('project.message_show_written_by') }}
          </h3>

          <div v-if="message.author" class="flex mb4">
            <div class="mr3">
              <avatar :avatar="message.author.avatar" :size="64" :class="'br-100'" />
            </div>

            <div>
              <inertia-link :href="message.author.url" class="mb2 dib">{{ message.author.name }}</inertia-link>

              <span v-if="message.author.role" class="db f7 mb2 relative">

                <ul class="list pa0 ma0">
                  <li class="mb2">
                    <!-- role -->
                    {{ message.author.role }}
                  </li>

                  <li>
                    <!-- in the project since -->
                    <span class="gray">
                      {{ $t('project.members_index_role', { date: message.author.added_at }) }}
                    </span>
                  </li>
                </ul>
              </span>
              <span v-if="message.author.position && message.author.role" class="db f7 gray">
                {{ $t('project.members_index_position_with_role', { role: message.author.position.title }) }}
              </span>
              <span v-if="message.author.position && !message.author.role" class="db f7 gray">
                {{ message.author.position.title }}
              </span>
            </div>
          </div>

          <!-- written on -->
          <h3 class="ttc f7 gray mt0 mb2 fw4">
            {{ $t('project.message_show_written_on') }}
          </h3>
          <p class="mt0 mb4">{{ message.written_at }} <span class="f6 gray">({{ message.written_at_human }})</span></p>

          <!-- actions -->
          <h3 class="ttc f7 gray mt0 mb2 fw4">
            {{ $t('project.message_show_actions') }}
          </h3>
          <ul class="list pl0 ma0">
            <!-- edit -->
            <li class="mb2"><inertia-link :href="message.url_edit" data-cy="project-edit" class="f6 gray">{{ $t('project.message_show_edit') }}</inertia-link></li>

            <!-- delete -->
            <li v-if="!removalConfirmation"><a href="#" data-cy="project-delete" class="f6 gray bb b--dotted bt-0 bl-0 br-0 pointer di c-delete" @click.prevent="removalConfirmation = true">{{ $t('project.message_show_destroy') }}</a></li>
            <li v-if="removalConfirmation" class="pv2 f6">
              {{ $t('app.sure') }}
              <a data-cy="confirm-project-deletion" class="c-delete mr1 pointer" @click.prevent="destroy()">
                {{ $t('app.yes') }}
              </a>
              <a data-cy="cancel-project-deletion" class="pointer" @click.prevent="removalConfirmation = false">
                {{ $t('app.no') }}
              </a>
            </li>
          </ul>
        </div>
      </div>
    </div>
  </layout>
</template>

<script>
import Layout from '@/Shared/Layout';
import Breadcrumb from '@/Shared/Layout/Breadcrumb';
import ProjectMenu from '@/Pages/Company/Project/Partials/ProjectMenu';
import Avatar from '@/Shared/Avatar';
import TextArea from '@/Shared/TextArea';
import LoadingButton from '@/Shared/LoadingButton';

export default {
  components: {
    Layout,
    Breadcrumb,
    ProjectMenu,
    Avatar,
    TextArea,
    LoadingButton,
  },

  props: {
    notifications: {
      type: Array,
      default: null,
    },
    project: {
      type: Object,
      default: null,
    },
    message: {
      type: Object,
      default: null,
    },
    tab: {
      type: String,
      default: 'summary',
    },
  },

  data() {
    return {
      localComments: null,
      removalConfirmation: false,
      loadingState: null,
      form: {
        comment: null,
        errors: [],
      },
    };
  },

  mounted() {
    if (localStorage.success) {
      this.flash(localStorage.success, 'success');
      localStorage.removeItem('success');
    }
  },

  created() {
    this.localComments = this.message.comments;
  },

  methods: {
    destroy() {
      axios.delete(`/${this.$page.props.auth.company.id}/company/projects/${this.project.id}/messages/${this.message.id}`)
        .then(response => {
          localStorage.success = this.$t('project.message_destroy_success');
          this.$inertia.visit(`/${this.$page.props.auth.company.id}/company/projects/${this.project.id}/messages/`);
        })
        .catch(error => {
          this.form.errors = error.response.data;
        });
    },

    storeComment() {
      this.loadingState = 'loading';

      axios.post(`/${this.$page.props.auth.company.id}/company/projects/${this.project.id}/messages/${this.message.id}/comments`, this.form)
        .then(response => {
          this.loadingState = null;
          this.flash(this.$t('project.message_comment_written_success'), 'success');
          this.localComments.push(response.data.data);
          this.form.comment = null;
        })
        .catch(error => {
          this.loadingState = null;
          this.form.errors = error.response.data;
        });
    }
  }
};

</script>
