<style lang="scss" scoped>
.grid {
  display: grid;
}

.column-gap-10 {
  column-gap: 10px;
}

.board-menu {
  svg {
    width: 16px;
    top: 3px;
  }
}

.action-menu {
  svg {
    width: 16px;
    top: 3px;
  }
}

.cycle {
  grid-template-columns: 200px 3fr;
}

@media (max-width: 480px) {
  .cycle {
    grid-template-columns: 1fr;
  }
}
</style>

<template>
  <layout :notifications="notifications">
    <div class="ph2 ph5-ns">
      <breadcrumb :has-more="true"
                  :previous-url="'/' + $page.props.auth.company.id + '/company/projects/' + project.id + '/boards'"
                  :previous="$t('app.breadcrumb_project_board_list')"
                  :custom-class="'mt0-l'"
                  :center-box="false"
                  :custom-margin-top="'mt1'"
                  :custom-margin-bottom="'mb3'"
      >
        {{ data.board.name }}
      </breadcrumb>

      <!-- BODY -->
      <div class="grid column-gap-10 grid-cols-2 br3 mb5 relative z-1 cycle">
        <!-- left column -->
        <div>
          <ul class="list pl0 ma0 action-menu mb4">
            <li class="mb3 relative f6">
              <svg xmlns="http://www.w3.org/2000/svg" class="relative mr1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
              <span class="pointer b--dotted bb bt-0 br-0 bl-0">New issue</span>
            </li>
            <li class="mb3 relative f6">
              <svg xmlns="http://www.w3.org/2000/svg" class="relative mr1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
              <span class="pointer b--dotted bb bt-0 br-0 bl-0">New cycle</span>
            </li>
          </ul>

          <ul class="list pl0 ma0 board-menu">
            <li class="mb1 f7 ttu">Links</li>
            <li class="mb3 relative f6">
              <svg xmlns="http://www.w3.org/2000/svg" class="relative mr2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z" />
              </svg>
              <inertia-link>Summary</inertia-link>
            </li>
            <li class="mb3 relative f6">
              <svg xmlns="http://www.w3.org/2000/svg" class="relative mr2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
              </svg>
              <inertia-link>Backlog</inertia-link>
            </li>
            <li class="mb3 relative f6">
              <svg xmlns="http://www.w3.org/2000/svg" class="relative mr2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
              </svg>
              <inertia-link>Active cycle</inertia-link>
            </li>
            <li class="mb3 relative f6">
              <svg xmlns="http://www.w3.org/2000/svg" class="relative mr2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
              <inertia-link>Past cycles</inertia-link>
            </li>
            <li class="mb3 relative f6">
              <svg xmlns="http://www.w3.org/2000/svg" class="relative mr2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.5" />
              </svg>
              <inertia-link>Refinement</inertia-link>
            </li>
            <li class="mb3 relative f6">
              <svg xmlns="http://www.w3.org/2000/svg" class="relative mr2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
              <inertia-link>My issues</inertia-link>
            </li>
          </ul>
        </div>

        <!-- right column -->
        <div>
          <!-- cycle -->
          <div v-for="sprint in localSprints" :key="sprint.id">
            <sprint :board="data.board" :sprint="sprint" :issue-types="issueTypes" />
          </div>
        </div>
      </div>
    </div>
  </layout>
</template>

<script>
import Layout from '@/Shared/Layout';
import Breadcrumb from '@/Shared/Layout/Breadcrumb';
import Sprint from '@/Pages/Company/Project/Boards/Partials/Sprint';

export default {
  components: {
    Layout,
    Breadcrumb,
    Sprint,
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
    issueTypes: {
      type: Object,
      default: null,
    },
    data: {
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
      localSprints: [],
      showModal: false,
      form: {
        name: null,
        errors: [],
      },
    };
  },

  mounted() {
    this.localSprints =  this.data.sprints;

    if (localStorage.success) {
      this.flash(localStorage.success, 'success');
      localStorage.removeItem('success');
    }
  },

  methods: {
    displayAddModal() {
      this.showAddModal = true;
      this.form.name = '';
      this.form.errors = null;

      this.$nextTick(() => {
        this.$refs.newBoard.focus();
      });
    },

    hideAddModal() {
      this.showAddModal = false;
    },

    submit() {
      this.loadingState = 'loading';

      axios.post(this.data.url.store, this.form)
        .then(response => {
          this.flash(this.$t('project.board_cta_success'), 'success');

          this.loadingState = null;
          this.hideAddModal();
          this.localBoards.push(response.data.data);
        })
        .catch(error => {
          this.loadingState = null;
          this.form.errors = error.response.data;
        });
    },
  }
};

</script>
