<style lang="scss" scoped>
.task-list:not(:last-child) {
  margin-bottom: 35px;
}

.add-list-section {
  top: 5px;
  background-color: #f5f5f5;
}

.edit-cta {
  top: -2px;
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
            <span class="mr1">🪑</span> {{ $t('project.task_title') }}

            <help :url="$page.props.help_links.project_tasks" :top="'3px'" />
          </span>
        </p>

        <div class="bg-white box pa3">
          <!-- blank state -->
          <div v-if="!checkTasksPresence" class="pa3 tc">
            <img loading="lazy" src="/img/streamline-icon-artist-drawing-3@140x140.png" width="140" height="140" alt="meeting"
                 class=""
            />
            <p class="lh-copy measure center mb4">{{ $t('project.task_blank_desc') }}</p>
          </div>

          <!-- list of tasks, if they exist -->
          <div>
            <!-- list of tasks without a list -->
            <div class="mb4">
              <h3 class="fw4 gray f4 mt0">
                {{ $t('project.task_default_task_list') }}
              </h3>
              <project-tasks :tasks="tasks.tasks_without_lists" :members="members" :project="project" />
            </div>

            <!-- list of tasks with task lists -->
            <div v-for="taskList in localTaskLists" :key="taskList.id" class="task-list">
              <h3 :data-cy="'task-list-' + taskList.id" class="f4 fw5 mb2 relative">
                {{ taskList.title }} <a :data-cy="'edit-task-list-' + taskList.id" class="bb b--dotted bt-0 bl-0 br-0 pointer ml1 f7 fw4 relative edit-cta" @click.prevent="showEditMode(taskList)">{{ $t('app.edit') }}</a>
              </h3>
              <p v-if="taskList.description" class="f6 gray mt1">{{ taskList.description }}</p>

              <!-- edit task list details -->
              <div v-if="editListMode && listToEdit == taskList" class="bg-gray add-list-section edit-item ph2 mt1 mb3 pv1 br1 relative">
                <form @submit.prevent="update(taskList)">
                  <!-- Title -->
                  <text-input :id="'title'"
                              :ref="'editTaskList' + taskList.id"
                              v-model="form.title"
                              :name="'title'"
                              :datacy="'task-list-title-input-' + taskList.id"
                              :errors="$page.props.errors.title"
                              :label="$t('project.task_create_title')"
                              :required="true"
                              @esc-key-pressed="editListMode = false"
                  />

                  <!-- description -->
                  <text-area
                    v-model="form.description"
                    :label="$t('project.task_create_description')"
                    :datacy="'edit-task-list-title-textarea-' + taskList.id"
                    :required="false"
                    :rows="2"
                    @esc-key-pressed="editListMode = false"
                  />

                  <!-- actions -->
                  <div class="flex justify-between">
                    <div>
                      <loading-button :class="'btn add w-auto-ns w-100 mb2 pv2 ph3 mr2'" :data-cy="'edit-task-list-cta-' + taskList.id" :state="loadingState" :text="$t('app.update')" />
                      <a class="btn dib tc w-auto-ns w-100 mb2 pv2 ph3" @click.prevent="editListMode = false">{{ $t('app.cancel') }}</a>
                    </div>

                    <!-- destroy list -->
                    <a :data-cy="'delete-task-list-cta-' + taskList.id" class="btn destroy dib tc w-auto-ns w-100 mb2 pv2 ph3" @click.prevent="destroy(taskList)">{{ $t('app.delete') }}</a>
                  </div>
                </form>
              </div>

              <!-- actual tasks -->
              <div class="pl3">
                <project-tasks :tasks="taskList.tasks"
                               :task-list="taskList"
                               :members="members"
                               :project="project"
                />
              </div>
            </div>
          </div>

          <!-- create a new task list -->
          <div class="db mt4 mb3">
            <a v-if="!addListMode" class="btn pointer" data-cy="new-task-list-cta" @click.prevent="showAddListMode()">{{ $t('project.task_list_cta') }}</a>

            <div v-if="addListMode" class="bg-gray add-list-section edit-item ph2 mt1 mb3 pv1 br1 relative">
              <form @submit.prevent="store()">
                <!-- Title -->
                <text-input :id="'title'"
                            :ref="'newTaskList'"
                            v-model="form.title"
                            :name="'title'"
                            :datacy="'task-list-title-input'"
                            :errors="$page.props.errors.title"
                            :label="$t('project.task_create_title')"
                            :required="true"
                            @esc-key-pressed="addListMode = false"
                />

                <!-- description -->
                <text-area
                  v-model="form.description"
                  :label="$t('project.task_create_description')"
                  :datacy="'task-list-description'"
                  :required="false"
                  :rows="2"
                  @esc-key-pressed="addListMode = false"
                />

                <!-- actions -->
                <div>
                  <loading-button :class="'btn add w-auto-ns w-100 mb2 pv2 ph3'" data-cy="store-task-list-cta" :state="loadingState" :text="$t('app.save')" />
                  <a class="btn dib tc w-auto-ns w-100 mb2 pv2 ph3" @click.prevent="addListMode = false">{{ $t('app.cancel') }}</a>
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
import Breadcrumb from '@/Shared/Layout/Breadcrumb';
import TextInput from '@/Shared/TextInput';
import ProjectMenu from '@/Pages/Company/Project/Partials/ProjectMenu';
import ProjectTasks from '@/Pages/Company/Project/Tasks/Partials/ProjectTasks';
import Help from '@/Shared/Help';
import TextArea from '@/Shared/TextArea';
import LoadingButton from '@/Shared/LoadingButton';

export default {
  components: {
    Layout,
    Breadcrumb,
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
      editListMode: false,
      listToEdit: 0,
      localTasks: null,
      localTaskLists: null,
      form: {
        title: null,
        description: null,
        errors: [],
      },
    };
  },

  computed: {
    checkTasksPresence() {
      let numberOfTasks = 0;

      if (this.localTasks) {
        numberOfTasks = this.localTasks.length;
      }

      if (this.localTaskLists) {
        numberOfTasks = numberOfTasks + this.localTaskLists.length;
      }

      return numberOfTasks > 0;
    }
  },

  watch: {
    tasks: {
      handler(value) {
        if (value.tasks_without_lists) {
          this.localTasks =  value.tasks_without_lists;
        }
        if (value.task_lists) {
          this.localTaskLists =  value.task_lists;
        }
      },
      deep: true
    }
  },

  mounted() {
    if (this.tasks.tasks_without_lists) {
      this.localTasks =  this.tasks.tasks_without_lists;
    }

    if (this.tasks.task_lists) {
      this.localTaskLists =  this.tasks.task_lists;
    }

    if (localStorage.success) {
      this.flash(localStorage.success, 'success');
      localStorage.removeItem('success');
    }
  },

  methods: {
    showAddListMode() {
      this.addListMode = true;
      this.form.title = null;
      this.form.description = null;

      this.$nextTick(() => {
        this.$refs.newTaskList.focus();
      });
    },

    showEditMode(taskList) {
      this.editListMode = true;
      this.listToEdit = taskList;
      this.form.title = taskList.title;
      this.form.description = taskList.description;

      this.$nextTick(() => {
        this.$refs[`editTaskList${taskList.id}`].focus();
      });
    },

    store() {
      this.loadingState = 'loading';

      axios.post(`/${this.$page.props.auth.company.id}/company/projects/${this.project.id}/tasks/lists/store`, this.form)
        .then(response => {
          this.loadingState = null;
          this.addListMode = false;
          this.localTaskLists.push(response.data.data);
          this.form.title = null;
          this.form.description = null;
          this.flash(this.$t('project.task_list_create_success'), 'success');
        })
        .catch(error => {
          this.loadingState = null;
          this.form.errors = error.response.data;
        });
    },

    update(taskList) {
      this.loadingState = 'loading';

      axios.put(`/${this.$page.props.auth.company.id}/company/projects/${this.project.id}/tasks/lists/${taskList.id}`, this.form)
        .then(response => {
          this.loadingState = null;
          this.editListMode = false;

          this.localTaskLists[this.localTaskLists.findIndex(x => x.id === taskList.id)] = response.data.data;

          this.form.title = null;
          this.form.description = null;
          this.flash(this.$t('project.task_list_update_success'), 'success');
        })
        .catch(error => {
          this.loadingState = null;
          this.form.errors = error.response.data;
        });
    },

    destroy(taskList) {
      this.loadingState = 'loading';

      axios.delete(`/${this.$page.props.auth.company.id}/company/projects/${this.project.id}/tasks/lists/${taskList.id}`, this.form)
        .then(response => {
          localStorage.success = this.$t('project.task_list_destroy_success');
          this.$inertia.visit(`/${this.$page.props.auth.company.id}/company/projects/${this.project.id}/tasks`);
        })
        .catch(error => {
          this.loadingState = null;
          this.form.errors = error.response.data;
        });
    },
  }
};

</script>
