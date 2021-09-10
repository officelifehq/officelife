<style lang="scss" scoped>
.list-no-line-bottom {
  li:last-child {
    border-bottom: 0;
    border-bottom-left-radius: 3px;
    border-bottom-right-radius: 3px;
  }

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

.dot {
  height: 11px;
  width: 11px;
  top: 0px;
  background-color: #5a45ff;
}

.read {
  .message {
    color: #b3b3b3;
    font-weight: 400;
  }
}

.author {
  width: 200px;
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

      <div class="mw7 center br3 mb5 relative z-1">
        <p class="db fw5 mb2 flex justify-between items-center">
          <span>
            <span class="mr1">📨</span> {{ $t('project.message_title') }}

            <help :url="$page.props.help_links.project_messages" :top="'3px'" />
          </span>
          <inertia-link :href="'/' + $page.props.auth.company.id + '/company/projects/' + project.id + '/messages/create'" class="btn f5" data-cy="add-message">{{ $t('project.message_cta') }}</inertia-link>
        </p>

        <!-- list of messages -->
        <div v-if="messages.length > 0" class="bg-white box">
          <ul class="list pl0 mv0 list-no-line-bottom">
            <li v-for="message in messages" :key="message.id" :class="message.read_status ? 'read' : ''" class="bb bb-gray bb-gray-hover pa3 relative flex justify-between items-center">
              <div class="ma0 fw4 message">
                <p class="mt0 mb2" :class="!message.read_status ? 'fw5' : ''">
                  <span v-if="!message.read_status" class="dib relative mr2 br-100 dot"></span>
                  <inertia-link :href="message.url" class="lh-copy">{{ message.title }}</inertia-link>
                </p>
                <ul class="list pl0 mt0 mb0 f6 gray">
                  <li class="di mr2 f7 gray">{{ message.written_at }}</li>
                  <li class="di f7 gray">{{ $tc('project.message_index_comment', message.comment_count, {count: message.comment_count}) }}</li>
                </ul>
              </div>

              <div v-if="message.author" class="author tr">
                <small-name-and-avatar
                  :name="message.author.name"
                  :avatar="message.author.avatar"
                  :class="'f4 fw4'"
                  :top="'0px'"
                  :margin-between-name-avatar="'29px'"
                />
              </div>
            </li>
          </ul>
        </div>

        <!-- blank state -->
        <div v-else data-cy="messages-blank-state" class="bg-white box pa3 tc">
          <img loading="lazy" src="/img/streamline-icon-morning-news-1@140x140.png" width="140" height="140" alt="meeting"
               class=""
          />
          <p class="lh-copy measure center">{{ $t('project.message_blank') }}</p>
        </div>
      </div>
    </div>
  </layout>
</template>

<script>
import Layout from '@/Shared/Layout';
import Breadcrumb from '@/Shared/Layout/Breadcrumb';
import ProjectMenu from '@/Pages/Company/Project/Partials/ProjectMenu';
import SmallNameAndAvatar from '@/Shared/SmallNameAndAvatar';
import Help from '@/Shared/Help';

export default {
  components: {
    Layout,
    Breadcrumb,
    ProjectMenu,
    SmallNameAndAvatar,
    Help,
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
    messages: {
      type: Array,
      default: null,
    },
    tab: {
      type: String,
      default: 'summary',
    },
  },

  data() {
    return {
      loadingState: '',
      addMode: false,
      decisionToDelete: 0,
      deleteActionConfirmation: false,
      showDeciders: false,
      potentialMembers: [],
      processingSearch: false,
      form: {
        title: null,
        searchTerm: null,
        employees: [],
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

  methods: {
  }
};

</script>
