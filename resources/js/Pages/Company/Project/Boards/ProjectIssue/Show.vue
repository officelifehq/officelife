<style lang="scss" scoped>
.avatar {
  left: 1px;
  top: 5px;
  width: 35px;
}

.issue-dropdown {
  border: 1px solid transparent;
  padding: 4px 4px 2px;

  &:hover {
    background-color: #fff;
    border: 1px solid #dae1e7;

    svg {
      display: inline;
      top: 9px;
      right: 7px;
      width: 16px;
    }
  }
}

.issue-label {
  padding: 3px 6px;
  color: #4d4d4f;

  &:hover {
    border-bottom: 1px solid #dae1e7;
  }
}

.icon-type {
  top: 0px;
}

.icon-task {
  width: 15px;
  top: 3px;
}
</style>

<template>
  <layout :notifications="notifications">
    <div class="ph2 ph5-ns">
      <breadcrumb :has-more="true"
                  :previous-url="localIssue.project.url"
                  :previous="localIssue.project.name"
                  :custom-class="'mt0-l'"
                  :center-box="false"
                  :custom-margin-top="'mt1'"
                  :custom-margin-bottom="'mb3'"
      >
        test
      </breadcrumb>

      <!-- BODY -->
      <div class="mw8 center br3 mb5 relative cf">
        <div class="flex items-center mt0 mb3 fw5">
          <icon-issue-type :background-color="localIssue.type.icon_hex_color" />

          <h2 class="mh2 mv0 pa0">
            {{ localIssue.title }}
          </h2>

          <span class="fw3 f6">
            {{ localIssue.key }}
          </span>
        </div>

        <!-- LEFT COLUMN -->
        <div class="fl w-70-l w-100">
          <!-- issue content -->
          <div class="bg-white box mb2">
            <div class="pa3 pb3">
              <div v-if="localIssue.parsed_description" class="parsed-content" v-html="localIssue.parsed_description"></div>

              <!-- blank state -->
              <div v-else class="tc">
                <img loading="lazy" src="/img/streamline-icon-open-book-3@100x100.png" alt="" class="" height="100"
                     width="100"
                />
              </div>
            </div>
          </div>

          <!-- actions -->
          <ul class="list pl0 ma0 mb4">
            <li class="di mr2"><span class="f7 pointer b--dotted bb bt-0 br-0 bl-0">+ Add tasks</span></li>
            <li class="di mr2"><span class="f7 pointer b--dotted bb bt-0 br-0 bl-0">Reference another issue</span></li>
          </ul>

          <!-- tasks  -->
          <div class="mb4">
            <div class="flex justify-between mb2 f6">
              <p class="ma0 fw5 relative">
                <svg xmlns="http://www.w3.org/2000/svg" class="icon-task relative" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                </svg>
                Tasks
              </p>
              <span class="f7 pointer b--dotted bb bt-0 br-0 bl-0">
                + Add tasks
              </span>
            </div>
            <div class="ma0 pl0 ba bg-white box br3">
              <div class="bb bb-gray bb-gray-hover task-list-item">
                <div class="ph3 pv2">
                  <span class="relative icon-type mr1" style="width: 10px; height: 10px; background-color: rgb(86, 82, 179);"></span>

                  <inertia-link :href="''" class="f7 project-key mr2 code">slkfjals</inertia-link>

                  <!-- title -->
                  <span>sdlkjfls</span>
                </div>
              </div>
            </div>
          </div>

          <comments
            :comments="localIssue.comments"
            :post-url="''"
          />
        </div>

        <!-- RIGHT COLUMN -->
        <div class="fl w-30-l w-100 pl4-l">
          <!-- assigned to -->
          <assignee :assignees="localIssue.assignees" />

          <!-- story points -->
          <story-points :data="localIssue.story_points" />

          <!-- labels -->
          <div class="mb3 bb bb-gray pb3">
            <h3 class="ttc f7 gray mt0 mb1 fw4">
              Labels
            </h3>
            <div class="flex flex-wrap">
              <inertia-link class="inline-flex items-center relative issue-label mr2 mb2 f6 ba bb-gray br3 bg-white">
                sdfksldk
              </inertia-link>
              <inertia-link class="inline-flex items-center relative issue-label mr2 mb2 f6 ba bb-gray br3 bg-white">
                test utilisateur
              </inertia-link>
              <inertia-link class="inline-flex items-center relative issue-label mr2 mb2 f6 ba bb-gray br3 bg-white">
                this is a test because it's insane
              </inertia-link>
              <inertia-link class="inline-flex items-center relative issue-label mr2 mb2 f6 ba bb-gray br3 bg-white">
                sdfksldk
              </inertia-link>
            </div>
          </div>

          <!-- cycle -->
          <div class="mb3 bb bb-gray pb3">
            <h3 class="ttc f7 gray mt0 mb1 fw4">
              Cycle
            </h3>
            <div>
              Cycle 32
            </div>
          </div>

          <!-- created by -->
          <h3 v-if="localIssue.author" class="ttc f7 gray mt0 mb1 fw4">
            Created by
          </h3>
          <div v-if="localIssue.author" class="flex mb3">
            <div class="mr2">
              <avatar :avatar="localIssue.author.avatar" :size="25" :class="'br-100'" />
            </div>

            <div>
              <inertia-link :href="localIssue.author.url" class="mb2 dib">{{ localIssue.author.name }}</inertia-link>
            </div>
          </div>

          <!-- written on -->
          <h3 class="ttc f7 gray mt0 mb2 fw4">
            Created on {{ localIssue.created_at }}
          </h3>
          <p class="mt0 mb4">{{ localIssue.created_at }} <span class="f6 gray">({{ localIssue.created_at_human }})</span></p>

          <!-- actions -->
          <h3 class="ttc f7 gray mt0 mb2 fw4">
            {{ $t('project.message_show_actions') }}
          </h3>
          <ul class="list pl0 ma0">
            <!-- edit -->
            <li class="mb2"><inertia-link :href="localIssue.url_edit" class="f6 gray">{{ $t('project.message_show_edit') }}</inertia-link></li>

            <!-- delete -->
            <li v-if="!removalConfirmation"><a href="#" class="f6 gray bb b--dotted bt-0 bl-0 br-0 pointer di c-delete" @click.prevent="removalConfirmation = true">{{ $t('project.message_show_destroy') }}</a></li>
            <li v-if="removalConfirmation" class="pv2 f6">
              {{ $t('app.sure') }}
              <a class="c-delete mr1 pointer" @click.prevent="destroy()">
                {{ $t('app.yes') }}
              </a>
              <a class="pointer" @click.prevent="removalConfirmation = false">
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
import Avatar from '@/Shared/Avatar';
import IconIssueType from '@/Shared/IconIssueType';
import Comments from '@/Shared/Comments';
import Assignee from '@/Pages/Company/Project/Boards/ProjectIssue/Partials/Assignee';
import StoryPoints from '@/Pages/Company/Project/Boards/ProjectIssue/Partials/StoryPoints';

export default {
  components: {
    Layout,
    Breadcrumb,
    Avatar,
    IconIssueType,
    Comments,
    Assignee,
    StoryPoints,
  },

  props: {
    notifications: {
      type: Array,
      default: null,
    },
    data: {
      type: Object,
      default: null,
    },
  },

  data() {
    return {
      removalConfirmation: false,
      localIssue: null,
    };
  },

  created() {
    this.localIssue = this.data;

    if (localStorage.success) {
      this.flash(localStorage.success, 'success');
      localStorage.removeItem('success');
    }
  },

  methods: {
    destroy() {
      axios.delete(`/${this.$page.props.auth.company.id}/company/projects/${this.project.id}/messages/${this.localIssue.id}`)
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
