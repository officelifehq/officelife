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

.edit-item {
  left: 88px;
}

.list-item {
  left: -86px;
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
    <ul class="list pl0 mt0  mb4">
      <li v-for="task in localTasks" :key="task.id" class="list-item relative">
        <checkbox
          :id="'ai-' + task.id"
          v-model="task.completed"
          :item-id="task.id"
          :datacy="'action-item-' + task.id"
          :label="task.title"
          :extra-class-upper-div="'mb0 relative'"
          :assignee="task.assignee"
          :classes="'mb0 mr1'"
          :maxlength="255"
          :required="true"
          @change="toggle(task.id)"
          @update="showEdittask(task.id, task.description)"
          @destroy="destroy(task.id)"
        />
      </li>

      <!-- add a new item -->
      <li v-if="!addTaskMode" class="add-item-section">
        <span class="bb b--dotted bt-0 bl-0 br-0 pointer f6" data-cy="add-new-action-item" @click="displayAddTask()">{{ $t('dashboard.one_on_ones_note_cta') }}</span>
      </li>

      <!-- create a new item -->
      <li v-if="addTaskMode" class="bg-gray add-item-section ph2 mt1 pv1 br1">
        <form @submit.prevent="store()">
          <text-area
            ref="newTaskItem"
            v-model="form.title"
            :label="$t('dashboard.one_on_ones_action_item_textarea_desc')"
            :datacy="'action-item-description-textarea'"
            :required="true"
            :rows="2"
            @esc-key-pressed="addTaskMode = false"
          />

          <div class="w-50">
            <select-box :id="'country_id'"
                        v-model="form.assignee_id"
                        :options="members"
                        :name="'country_id'"
                        :errors="$page.props.errors.country_id"
                        :label="'Who is responsible'"
                        :placeholder="$t('app.choose_value')"
                        :required="false"
                        :value="form.assignee_id"
                        :datacy="'country_selector'"
            />
          </div>

          <!-- actions -->
          <div>
            <loading-button :classes="'btn add w-auto-ns w-100 mb2 pv2 ph3'" :state="loadingState" :text="$t('app.add')" data-cy="add-action-item-cta" />
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
import Checkbox from '@/Shared/EditableCheckbox';
import TextArea from '@/Shared/TextArea';
import LoadingButton from '@/Shared/LoadingButton';
import SelectBox from '@/Shared/Select';

export default {
  components: {
    TextArea,
    Checkbox,
    LoadingButton,
    SelectBox,
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
      localTasks: null,
      loadingState: '',
      addTaskMode: false,
      form: {
        assignee_id: null,
        title: null,
        description: null,
        task_list_id: null,
        errors: [],
      },
    };
  },

  mounted() {
    this.localTasks= this.tasks;

    if (this.taskList) {
      this.form.task_list_id = this.taskList.id;
    }
  },

  methods: {
    displayAddTask() {
      this.addTaskMode = true;
      this.form.title = null;
      this.form.description = null;

      this.$nextTick(() => {
        this.$refs['newTaskItem'].$refs['input'].focus();
      });
    },

    store() {
      this.loadingState = 'loading';

      axios.post(`/${this.$page.props.auth.company.id}/projects/${this.project.id}/tasks`, this.form)
        .then(response => {
          this.localTasks.push(response.data.data);
          this.addTaskMode = false;
          this.form.title = null;
          this.form.description = null;
          this.loadingState = null;
        })
        .catch(error => {
          this.loadingState = null;
          this.form.errors = error.response.data;
        });
    },
  }
};

</script>
