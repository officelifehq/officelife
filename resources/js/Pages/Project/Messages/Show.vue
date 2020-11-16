<style lang="scss" scoped>
.avatar {
  left: 1px;
  top: 5px;
  width: 35px;
}

.comment-avatar {
  width: 50px;
}

.team-member {
  padding-left: 44px;

  .avatar {
    top: 2px;
  }
}

.icon-date {
  width: 15px;
  top: 2px;
}

.icon-role {
  width: 15px;
  top: 2px;
}
</style>

<template>
  <layout title="Home" :notifications="notifications">
    <div class="ph2 ph5-ns">
      <!-- BREADCRUMB -->
      <div class="mt4-l mt1 mb4 mw6 br3 center breadcrumb relative z-0 f6 pb2">
        <ul class="list ph0 tc-l tl">
          <li class="di">
            <inertia-link :href="$route('dashboard.index', $page.props.auth.company.id)">{{ $t('app.breadcrumb_dashboard') }}</inertia-link>
          </li>
          <li class="di">
            <inertia-link :href="'/' + $page.props.auth.company.id + '/projects'">{{ $t('app.breadcrumb_project_list') }}</inertia-link>
          </li>
          <li class="di">
            {{ $t('app.breadcrumb_project_detail') }}
          </li>
        </ul>
      </div>

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
          <div class="bg-white box mb4">
            <div class="pa3 pb3" data-cy="project-content">
              <div class="parsed-content" v-html="message.parsed_content"></div>
            </div>
          </div>
        </div>

        <!-- RIGHT COLUMN -->
        <div class="fl w-30-l w-100 pl4-l">
          <!-- written by -->
          <h3 v-if="message.author" class="ttc f7 gray mt0 mb2 fw4">
            {{ $t('project.message_show_written_by') }}
          </h3>

          <div v-if="message.author" class="flex items-center mb4">
            <div class="mr3">
              <img :src="message.author.avatar" alt="avatar" height="64" width="64" class="br-100" />
            </div>

            <div>
              <inertia-link :href="message.author.url" class="mb2 dib">{{ message.author.name }}</inertia-link>

              <span v-if="message.author.role" class="db f7 mb2 relative">
                <!-- icon role -->
                <svg class="relative icon-role gray" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                  <path fill-rule="evenodd" d="M10 2a1 1 0 00-1 1v1a1 1 0 002 0V3a1 1 0 00-1-1zM4 4h3a3 3 0 006 0h3a2 2 0 012 2v9a2 2 0 01-2 2H4a2 2 0 01-2-2V6a2 2 0 012-2zm2.5 7a1.5 1.5 0 100-3 1.5 1.5 0 000 3zm2.45 4a2.5 2.5 0 10-4.9 0h4.9zM12 9a1 1 0 100 2h3a1 1 0 100-2h-3zm-1 4a1 1 0 011-1h2a1 1 0 110 2h-2a1 1 0 01-1-1z" clip-rule="evenodd" />
                </svg>
                <span class="mr2">
                  {{ message.author.role }}
                </span>

                <!-- icon date -->
                <span>
                  <svg class="relative icon-date gray" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd" />
                  </svg>
                </span>
                <span class="gray">
                  {{ $t('project.members_index_role', { date: message.author.added_at }) }}
                </span>
              </span>
              <span v-if="message.author.position && message.author.role" class="db f7 gray">
                {{ $t('project.members_index_position_with_role', { role: message.author.position.title }) }}
              </span>
              <span v-if="message.author.position && !message.author.role" class="db f7 gray">
                {{ $t('project.members_index_position', { role: message.author.position.title }) }}
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
            <li v-if="!removalConfirmation"><a href="#" data-cy="project-delete" class="f6 gray" @click.prevent="removalConfirmation = true">{{ $t('project.message_show_destroy') }}</a></li>
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
import ProjectMenu from '@/Pages/Project/Partials/ProjectMenu';

export default {
  components: {
    Layout,
    ProjectMenu,
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
      removalConfirmation: false,
      form: {
        comment: null,
        errors: [],
      },
    };
  },

  mounted() {
    if (localStorage.success) {
      flash(localStorage.success, 'success');
      localStorage.removeItem('success');
    }
  },

  methods: {
    destroy() {
      axios.delete(`/${this.$page.props.auth.company.id}/projects/${this.project.id}/messages/${this.message.id}`)
        .then(response => {
          localStorage.success = this.$t('project.message_destroy_success');
          this.$inertia.visit(`/${this.$page.props.auth.company.id}/projects/${this.project.id}/messages/`);
        })
        .catch(error => {
          this.form.errors = error.response.data;
        });
    },
  }
};

</script>
