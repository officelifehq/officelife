<style lang="scss" scoped>
.avatar {
  left: 1px;
  top: 5px;
  width: 35px;
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

          <comments
            :comments="message.comments"
            :post-url="`/${$page.props.auth.company.id}/company/projects/${project.id}/messages/${message.id}/comments/`"
          />
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
import Comments from '@/Shared/Comments';
import Avatar from '@/Shared/Avatar';

export default {
  components: {
    Layout,
    Breadcrumb,
    ProjectMenu,
    Avatar,
    Comments,
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
    };
  },

  mounted() {
    if (localStorage.success) {
      this.flash(localStorage.success, 'success');
      localStorage.removeItem('success');
    }
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
  }
};

</script>
