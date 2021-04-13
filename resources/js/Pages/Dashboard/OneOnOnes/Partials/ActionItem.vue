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
    <h3 class="f4 fw5 mb2">
      <span class="mr1">
        🤜
      </span> {{ $t('dashboard.one_on_ones_action_item_title') }}
    </h3>
    <p class="f6 gray mt1 pl4">{{ $t('dashboard.one_on_ones_action_item_desc') }}</p>
    <ul class="list pl4 mb4">
      <li v-for="actionItem in localActionItems" :key="actionItem.id" :class="entry.happened ? 'list-item-offset' : ''" class="list-item relative">
        <checkbox
          :id="'ai-' + actionItem.id"
          v-model="actionItem.checked"
          :item-id="actionItem.id"
          :datacy="'action-item-' + actionItem.id"
          :label="actionItem.description"
          :extra-class-upper-div="'mb0 relative'"
          :class="'mb0 mr1'"
          :maxlength="255"
          :required="true"
          :editable="!entry.happened"
          @update:model-value="toggle(actionItem.id)"
          @update="showEditActionItem(actionItem.id, actionItem.description)"
          @destroy="destroy(actionItem.id)"
        />

        <!-- edit action item -->
        <div v-if="actionItemToEdit == actionItem.id" class="bg-gray add-item-section edit-item ph2 mt1 mb3 pv1 br1 relative">
          <form @submit.prevent="update(actionItem.id)">
            <text-area
              :ref="'actionItem' + actionItem.id"
              v-model="form.description"
              :label="$t('dashboard.one_on_ones_action_talking_point_desc')"
              :datacy="'edit-action-item-description-textarea-' + actionItem.id"
              :required="true"
              :rows="2"
              @esc-key-pressed="actionItemToEdit = 0"
            />
            <!-- actions -->
            <div>
              <loading-button :class="'btn add w-auto-ns w-100 mb2 pv2 ph3'" data-cy="edit-action-item-cta" :state="loadingState" :text="$t('app.update')" />
              <a class="btn dib tc w-auto-ns w-100 mb2 pv2 ph3" @click.prevent="actionItemToEdit = 0">
                {{ $t('app.cancel') }}
              </a>
            </div>
          </form>
        </div>
      </li>

      <!-- cta to add a new action item -->
      <li v-if="!addActionItemMode && !entry.happened" class="bg-gray add-item-section ph2 mt1 pv1 br1">
        <span class="bb b--dotted bt-0 bl-0 br-0 pointer f6" data-cy="add-new-action-item" @click="displayAddActionItem()">{{ $t('dashboard.one_on_ones_note_cta') }}</span>
      </li>

      <!-- add a new action item -->
      <li v-if="addActionItemMode" class="bg-gray add-item-section ph2 mt1 pv1 br1">
        <form @submit.prevent="store()">
          <text-area
            ref="newActionItem"
            v-model="form.description"
            :label="$t('dashboard.one_on_ones_action_item_textarea_desc')"
            :datacy="'action-item-description-textarea'"
            :required="true"
            :rows="2"
            @esc-key-pressed="addActionItemMode = false"
          />
          <!-- actions -->
          <div>
            <loading-button :class="'btn add w-auto-ns w-100 mb2 pv2 ph3'" :state="loadingState" :text="$t('app.add')" data-cy="add-action-item-cta" />
            <a class="btn dib tc w-auto-ns w-100 mb2 pv2 ph3" @click.prevent="addActionItemMode = false">
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
import Checkbox from '@/Shared/EditableCheckbox';
import LoadingButton from '@/Shared/LoadingButton';

export default {
  components: {
    TextArea,
    Checkbox,
    LoadingButton,
  },

  props: {
    entry: {
      type: Object,
      default: null,
    },
  },

  data() {
    return {
      loadingState: '',
      addActionItemMode: false,
      localActionItems: null,
      actionItemToEdit: 0,
      form: {
        manager_id: null,
        employee_id: null,
        description: null,
        content: null,
        errors: [],
      },
    };
  },

  watch: {
    entry: {
      handler(value) {
        this.localActionItems = value.action_items;
      },
      deep: true
    }
  },

  created() {
    this.localActionItems = this.entry.action_items;
  },

  methods: {
    showEditActionItem(id, description) {
      this.actionItemToEdit = id;
      this.form.description = description;

      this.$nextTick(() => {
        this.$refs[`actionItem${id}`].focus();
      });
    },

    displayAddActionItem() {
      this.addActionItemMode = true;
      this.form.description = null;

      this.$nextTick(() => {
        this.$refs.newActionItem.focus();
      });
    },

    store() {
      this.loadingState = 'loading';
      this.form.employee_id = this.entry.employee.id;
      this.form.manager_id = this.entry.manager.id;

      axios.post('/' + this.$page.props.auth.company.id + '/dashboard/oneonones/' + this.entry.id + '/actionItems', this.form)
        .then(response => {
          this.localActionItems.push(response.data.data);
          this.addActionItemMode = false;
          this.form.description = null;
          this.loadingState = null;
        })
        .catch(error => {
          this.loadingState = null;
          this.form.errors = error.response.data;
        });
    },

    update(itemId) {
      this.loadingState = 'loading';

      axios.post('/' + this.$page.props.auth.company.id + '/dashboard/oneonones/' + this.entry.id + '/actionItems/' + itemId, this.form)
        .then(response => {
          this.actionItemToEdit = 0;
          this.loadingState = null;
          this.form.description = null;

          this.localActionItems[this.localActionItems.findIndex(x => x.id === itemId)] = response.data.data;
        })
        .catch(error => {
          this.loadingState = null;
          this.form.errors = error.response.data;
        });
    },

    toggle(id) {
      axios.post('/' + this.$page.props.auth.company.id + '/dashboard/oneonones/' + this.entry.id + '/actionItems/' + id + '/toggle')
        .then(response => {
        })
        .catch(error => {
          this.form.errors = error.response.data;
        });
    },

    destroy(id) {
      axios.delete('/' + this.$page.props.auth.company.id + '/dashboard/oneonones/' + this.entry.id + '/actionItems/' + id)
        .then(response => {
          this.flash(this.$t('dashboard.one_on_ones_note_deletion_success'), 'success');
          id = this.localActionItems.findIndex(x => x.id === id);
          this.localActionItems.splice(id, 1);
        })
        .catch(error => {
          this.form.errors = error.response.data;
        });
    },
  },
};

</script>
