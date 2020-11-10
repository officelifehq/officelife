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
      <li v-for="task in tasks" :key="task.id" class="list-item relative">
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
            ref="newActionItem"
            v-model="form.description"
            :label="$t('dashboard.one_on_ones_action_item_textarea_desc')"
            :datacy="'action-item-description-textarea'"
            :required="true"
            :rows="2"
            @esc-key-pressed="addTaskMode = false"
          />
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

export default {
  components: {
    TextArea,
    Checkbox,
    LoadingButton,
  },

  props: {
    tasks: {
      type: Array,
      default: null,
    },
    taskList: {
      type: Object,
      default: null,
    },
  },

  data() {
    return {
      loadingState: '',
      addTaskMode: false,
      form: {
        manager_id: null,
        employee_id: null,
        title: null,
        description: null,
        content: null,
        errors: [],
      },
    };
  },

  mounted() {
  },

  methods: {
    displayAddTask() {
      this.addTaskMode = true;
      this.form.title = null;
      this.form.description = null;

      this.$nextTick(() => {
        this.$refs['newActionItem'].$refs['input'].focus();
      });
    },
  }
};

</script>
