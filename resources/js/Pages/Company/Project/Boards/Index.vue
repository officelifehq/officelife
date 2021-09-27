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
            <span class="mr1">📦</span> {{ $t('project.board_title') }}
          </span>
          <a class="btn f5" @click.prevent="displayAddModal">{{ $t('project.board_cta') }}</a>
        </p>

        <!-- create a board -->
        <form v-show="showAddModal" class="mb3 pa3 ba br2 bb-gray bg-gray" @submit.prevent="submit">
          <errors :errors="form.errors" />

          <div class="cf">
            <div class="fl w-100 w-70-ns mb0-ns">
              <text-input
                :ref="'newBoard'"
                v-model="form.name"
                :errors="$page.props.errors.name"
                required
                :placeholder="$t('account.employee_statuses_placeholder')"
                :extra-class-upper-div="'mb0'"
                @esc-key-pressed="hideAddModal"
              />
            </div>
            <div class="fl w-30-ns w-100 tr">
              <a class="btn dib-l db mb2 mb0-ns mr2" @click.prevent="hideAddModal">
                {{ $t('app.cancel') }}
              </a>
              <loading-button :class="'btn add w-auto-ns w-100 mb2 mb0-ns pv2 ph3'" :state="loadingState" :text="$t('app.add')" />
            </div>
          </div>
        </form>

        <!-- list of boards -->
        <div v-if="localBoards.length > 0" class="bg-white box">
          <ul class="list pl0 mv0 list-no-line-bottom">
            <li v-for="board in localBoards" :key="board.id" class="bb bb-gray bb-gray-hover pa3 relative flex justify-between items-center">
              <div class="ma0 fw4 board">
                <p class="mt0 mb0">
                  <inertia-link :href="board.url" class="lh-copy">{{ board.name }}</inertia-link>
                </p>
              </div>
            </li>
          </ul>
        </div>

        <!-- blank state -->
        <div v-else data-cy="messages-blank-state" class="bg-white box pa3 tc">
          <img loading="lazy" src="/img/streamline-icon-storyboard@100x100.png" width="100" height="100" alt="meeting"
               class=""
          />
          <p class="lh-copy measure center">{{ $t('project.board_blank_state') }}</p>
        </div>
      </div>
    </div>
  </layout>
</template>

<script>
import Layout from '@/Shared/Layout';
import Breadcrumb from '@/Shared/Layout/Breadcrumb';
import ProjectMenu from '@/Pages/Company/Project/Partials/ProjectMenu';
import TextInput from '@/Shared/TextInput';
import LoadingButton from '@/Shared/LoadingButton';

export default {
  components: {
    Layout,
    Breadcrumb,
    ProjectMenu,
    LoadingButton,
    TextInput,
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
      localBoards: [],
      showAddModal: false,
      form: {
        name: null,
        errors: [],
      },
    };
  },

  mounted() {
    this.localBoards =  this.data.boards;

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
