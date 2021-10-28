<style lang="scss" scoped>
.grid {
  display: grid;
}
.column-gap-10 {
  column-gap: 10px;
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
                  :previous-url="route('projects.index', { company: $page.props.auth.company.id})"
                  :previous="project.name"
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
          <ul class="list pl0 ma0">
            <li class="mb3"><inertia-link>Summary</inertia-link></li>
            <li class="mb3"><inertia-link>Backlog</inertia-link></li>
            <li class="mb3"><inertia-link>Active cycles</inertia-link></li>
            <li class="mb3"><inertia-link>Past cycles</inertia-link></li>
            <li class="mb3"><inertia-link>Refinement</inertia-link></li>
            <li class="mb3"><inertia-link>My issues</inertia-link></li>
            <li class="mb3"><inertia-link>My views</inertia-link></li>
          </ul>
        </div>

        <!-- right column -->
        <div>
          <!-- cycle -->
          <div v-for="sprint in localSprints" :key="sprint.id">
            <sprint :board="data.board" :sprint="sprint" :issue-types="issueTypes" />
          </div>

          <!-- cycle -->
          <div class="flex justify-between items-center">
            <h3 class="normal mt0 mb2 f5">
              Backlog
            </h3>

            <div class="mb2">
              <span class="story-points">
                32
              </span>
            </div>
          </div>
          <div class="bg-white box issue-list">
            <p class="tc measure center mb4 lh-copy">
              {{ $t('account.project_management_blank') }}
            </p>

            <img loading="lazy" class="db center mb4" alt="team" src="/img/streamline-icon-extrinsic-drive-5@100x100.png" />
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
