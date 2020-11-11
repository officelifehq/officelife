<style lang="scss" scoped>
.task-list:not(:last-child) {
  margin-bottom: 35px;
}

.add-list-section {
  top: 5px;
  background-color: #f5f5f5;
}
</style>

<template>
  <layout title="Home" :notifications="notifications">
    <div class="ph2 ph5-ns">
      <!-- BREADCRUMB -->
      <div class="mt4-l mt1 mb4 mw6 br3 center breadcrumb relative z-0 f6 pb2">
        <ul class="list ph0 tc-l tl">
          <li class="di">
            <inertia-link :href="'/' + $page.props.auth.company.id + '/dashboard'">{{ $t('app.breadcrumb_dashboard') }}</inertia-link>
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

      <div class="mw7 center br3 mb5 relative z-1">
        <p class="db fw5 mb2 flex justify-between items-center">
          <span>
            <span class="mr1">🪑</span> All the tasks in this project

            <help :url="$page.props.help_links.project_messages" :top="'3px'" />
          </span>
        </p>

        <!-- list of tasks without a list -->
        <div class="bg-white box pa3">
          <div class="mb4">
            <project-tasks :tasks="localTaskLists" :members="members" :project="project" />
          </div>

          <div v-for="taskList in tasks.task_lists" :key="taskList.id" class="task-list">
            <h3 class="f4 fw5 mb2">
              {{ taskList.title }}
            </h3>
            <p v-if="taskList.description" class="f6 gray mt1">{{ taskList.description }}</p>
            <project-tasks :tasks="taskList.tasks" :task-list="taskList" :members="members" :project="project" />
          </div>

          <div class="mt5">
            <a v-if="!addListMode" class="btn" href="#" @click.prevent="showAddListMode()">Create a new task list</a>

            <div v-if="addListMode" class="bg-gray add-list-section edit-item ph2 mt1 mb3 pv1 br1 relative">
              <form @submit.prevent="store()">
                <!-- Title -->
                <text-input :id="'title'"
                            :ref="'newTaskList'"
                            v-model="form.title"
                            :name="'title'"
                            :datacy="'news-title-input'"
                            :errors="$page.props.errors.title"
                            :label="'Name of the list'"
                            :required="true"
                            @esc-key-pressed="addListMode = false"
                />

                <!-- description -->
                <text-area
                  v-model="form.description"
                  :label="'Describe the list, if necessary'"
                  :datacy="'edit-action-item-title-textarea-'"
                  :required="false"
                  :rows="2"
                  @esc-key-pressed="addListMode = false"
                />

                <!-- actions -->
                <div>
                  <loading-button :classes="'btn add w-auto-ns w-100 mb2 pv2 ph3'" data-cy="edit-action-item-cta" :state="loadingState" :text="$t('app.update')" />
                  <a class="btn dib tc w-auto-ns w-100 mb2 pv2 ph3" @click.prevent="addListMode = false">
                    {{ $t('app.cancel') }}
                  </a>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </layout>
</template>

<script>
import Layout from '@/Shared/Layout';
import TextInput from '@/Shared/TextInput';
import ProjectMenu from '@/Pages/Project/Partials/ProjectMenu';
import ProjectTasks from '@/Pages/Project/Tasks/Partials/ProjectTasks';
import Help from '@/Shared/Help';
import TextArea from '@/Shared/TextArea';
import LoadingButton from '@/Shared/LoadingButton';

export default {
  components: {
    Layout,
    ProjectMenu,
    Help,
    ProjectTasks,
    TextArea,
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
    tasks: {
      type: Object,
      default: null,
    },
    members: {
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
      addListMode: false,
      taskToEdit: 0,
      localTaskLists: null,
      form: {
        title: null,
        description: null,
        errors: [],
      },
    };
  },

  mounted() {
    if (this.tasks.tasks_without_lists) {
      this.localTaskLists =  this.tasks.tasks_without_lists;
    }

    if (localStorage.success) {
      flash(localStorage.success, 'success');
      localStorage.removeItem('success');
    }
  },

  methods: {
    showAddListMode() {
      this.addListMode = true;
      this.form.title = null;
      this.form.description = null;

      this.$nextTick(() => {
        this.$refs['newTaskList'].$refs['input'].focus();
      });
    },

    store() {
      this.loadingState = 'loading';

      axios.post(`/${this.$page.props.auth.company.id}/projects/${this.project.id}/tasks/lists/store`, this.form)
        .then(response => {
          this.loadingState = null;
          this.addListMode = false;
          this.localTaskLists.push(response.data.data);
          this.form.title = null;
          this.form.description = null;
          flash(this.$t('project.task_create_success'), 'success');
        })
        .catch(error => {
          this.loadingState = null;
          this.form.errors = error.response.data;
        });
    },
  }
};

</script>
