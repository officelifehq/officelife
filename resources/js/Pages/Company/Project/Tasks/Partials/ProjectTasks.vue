<style lang="scss" scoped>
.avatar {
  left: 1px;
  top: 5px;
  width: 35px;
}

.add-item-section {
  margin-left: 24px;
  top: 5px;
  background-color: #f5f5f5;
}

@media (max-width: 480px) {
  .list-item {
    left: 0;
  }

  .edit-item {
    left: 0;
  }
}

.list-item-offset {
  left: 0px;
}

.title-section {
  background-color: #E9EDF2;
}
</style>

<template>
  <div>
    <ul class="list pl0 mv0">
      <li v-for="task in localTasks" :key="task.id" class="list-item relative">
        <project-task-checkbox
          :id="'ai-' + task.id"
          v-model="task.completed"
          :item-id="task.id"
          :datacy="'task-' + task.id"
          :label="task.title"
          :extra-class-upper-div="'mb0 relative'"
          :assignee="task.assignee"
          :class="'mb0 mr1'"
          :maxlength="255"
          :required="true"
          :url="task.url"
          :duration="task.duration"
          :comments="task.comment_count"
          @update:model-value="toggle(task.id)"
          @edit="showEditTask(task)"
          @destroy="destroy(task.id)"
        />

        <!-- edit task -->
        <div v-if="taskToEdit == task.id" class="bg-gray add-item-section edit-item ph2 mt1 mb3 pv1 br1 relative">
          <form @submit.prevent="update(task)">
            <text-area
              :ref="'task' + task.id"
              v-model="form.title"
              :label="$t('project.task_add_title')"
              :datacy="'edit-task-title-textarea-' + task.id"
              :required="true"
              :rows="2"
              @esc-key-pressed="taskToEdit = 0"
            />

            <div class="w-50">
              <label class="db mb-2">{{ $t('project.task_edit_assignee') }}</label>
              <a-select
                v-model:value="form.assignee_id"
                :placeholder="$t('app.choose_value')"
                style="width: 200px; margin-bottom: 10px;"
                :options="members"
                show-search
                option-filter-prop="label"
              />
            </div>

            <!-- actions -->
            <div>
              <loading-button :class="'btn add w-auto-ns w-100 mb2 pv2 ph3 mr2'" :data-cy="'edit-task-cta-' + task.id" :state="loadingState" :text="$t('app.update')" />
              <a class="btn dib tc w-auto-ns w-100 mb2 pv2 ph3" @click.prevent="taskToEdit = 0">
                {{ $t('app.cancel') }}
              </a>
            </div>
          </form>
        </div>
      </li>

      <!-- call to action to add a new item -->
      <li v-if="!addTaskMode" class="add-item-section bg-gray ph2 mt1 pv1 br1">
        <span class="bb b--dotted bt-0 bl-0 br-0 pointer f6" :data-cy="'task-list-' + form.task_list_id + '-add-new-task'" @click="displayAddTask()">{{ $t('project.task_add_cta') }}</span>
      </li>

      <!-- form to create a new item -->
      <li v-if="addTaskMode" class="bg-gray add-item-section ph2 mt1 pv1 br1">
        <form @submit.prevent="store()">
          <text-area
            ref="newTaskItem"
            v-model="form.title"
            :label="$t('project.task_add_title')"
            :datacy="'task-list-' + form.task_list_id + '-task-title-textarea'"
            :required="true"
            :rows="2"
            @esc-key-pressed="addTaskMode = false"
          />

          <div class="w-50">
            <label class="db mb-2">{{ $t('project.task_edit_assignee') }}</label>
            <a-select
              v-model:value="form.assignee_id"
              :placeholder="$t('app.choose_value')"
              style="width: 200px; margin-bottom: 10px;"
              :options="members"
              show-search
              option-filter-prop="label"
            />
          </div>

          <!-- actions -->
          <div>
            <loading-button :class="'btn add w-auto-ns w-100 mb2 mr2 pv2 ph3'" :state="loadingState" :text="$t('app.add')" :data-cy="'task-list-' + form.task_list_id + '-add-task-cta'" />
            <a class="btn dib tc w-auto-ns w-100 mb2 pv2 ph3" @click.prevent="addTaskMode = false">
              {{ $t('app.cancel') }}
            </a>
          </div>
        </form>
      </li>
    </ul>
  </div>
</template>

<script>
import TextArea from '@/Shared/TextArea';
import LoadingButton from '@/Shared/LoadingButton';
import ProjectTaskCheckbox from '@/Pages/Company/Project/Tasks/Partials/ProjectTaskCheckbox';

export default {
  components: {
    TextArea,
    ProjectTaskCheckbox,
    LoadingButton,
  },

  props: {
    tasks: {
      type: Array,
      default: null,
    },
    project: {
      type: Object,
      default: null,
    },
    taskList: {
      type: Object,
      default: null,
    },
    members: {
      type: Array,
      default: null,
    },
  },

  data() {
    return {
      localTasks: [],
      loadingState: '',
      addTaskMode: false,
      taskToEdit: 0,
      form: {
        assignee_id: null,
        title: null,
        description: null,
        task_list_id: null,
        errors: [],
      },
    };
  },

  watch: {
    tasks: {
      handler(value) {
        this.localTasks = value;
      },
      deep: true
    }
  },

  mounted() {
    if (this.tasks) {
      this.localTasks = this.tasks;
    }

    if (this.taskList) {
      this.form.task_list_id = this.taskList.id;
    } else {
      this.form.task_list_id = 0;
    }
  },

  methods: {
    displayAddTask() {
      this.addTaskMode = true;
      this.form.title = null;
      this.form.description = null;

      this.$nextTick(() => {
        this.$refs.newTaskItem.focus();
      });
    },

    showEditTask(task) {
      this.taskToEdit = task.id;
      this.form.title = task.title;
      this.form.assignee_id = task.assignee ? task.assignee.id : null;

      this.$nextTick(() => {
        this.$refs[`task${task.id}`].focus();
      });
    },

    store() {
      this.loadingState = 'loading';

      if (this.form.task_list_id == 0) {
        this.form.task_list_id = null;
      }

      axios.post(`/${this.$page.props.auth.company.id}/company/projects/${this.project.id}/tasks`, this.form)
        .then(response => {
          this.addTaskMode = false;
          this.localTasks.push(response.data.data);
          this.form.title = null;
          this.form.description = null;
          this.form.assignee_id = null;
          this.loadingState = null;
          this.flash(this.$t('project.task_create_success'), 'success');
        })
        .catch(error => {
          this.loadingState = null;
          this.form.errors = error.response.data;
        });
    },

    toggle(id) {
      axios.put(`/${this.$page.props.auth.company.id}/company/projects/${this.project.id}/tasks/${id}/toggle`)
        .then(response => {
        })
        .catch(error => {
          this.form.errors = error.response.data;
        });
    },

    update(task) {
      this.loadingState = 'loading';

      if (this.form.task_list_id == 0) {
        this.form.task_list_id = null;
      }

      axios.put(`/${this.$page.props.auth.company.id}/company/projects/${this.project.id}/tasks/${task.id}`, this.form)
        .then(response => {
          this.taskToEdit = 0;
          this.loadingState = null;
          this.form.title = null;

          this.localTasks[this.localTasks.findIndex(x => x.id === task.id)] = response.data.data;
        })
        .catch(error => {
          this.loadingState = null;
          this.form.errors = error.response.data;
        });
    },

    destroy(id) {
      axios.delete(`/${this.$page.props.auth.company.id}/company/projects/${this.project.id}/tasks/${id}`)
        .then(response => {
          this.flash(this.$t('project.task_delete_success'), 'success');
          id = this.localTasks.findIndex(x => x.id === id);
          this.localTasks.splice(id, 1);
        })
        .catch(error => {
          this.form.errors = error.response.data;
        });
    },
  }
};

</script>
