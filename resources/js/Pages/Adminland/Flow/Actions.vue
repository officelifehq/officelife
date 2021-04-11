<style lang="scss" scoped>
.blank-state-actions {
  background-color: #f3f6f9;

  img {
    top: 3px;
  }
}
</style>

<template>
  <div>
    <div v-show="orderedActions.length != 0" class="bb bb-gray pa3">
      <p class="ma0 pa0 mb1 f6">
        {{ $t('account.flow_new_action_following') }}
      </p>
      <ul class="list ma0 pa0 tl">
        <li v-for="action in orderedActions" :key="action.id" class="relative db bb-gray-hover pv2 ph1">
          <action-notification :action="action" @destroy="destroyAction(action)" @update="updateAction($event, action)" />
        </li>
      </ul>
    </div>

    <!-- add actions -->
    <div class="pa3">
      <a v-show="!showActionMenu" class="btn dib" @click.prevent="showActionMenu = true">
        Add action
      </a>

      <div v-show="showActionMenu" class="tc">
        <div class="tl pv2 ph2 mb3 blank-state-actions dib mr3 br2 pointer" @click="addAction('notification')">
          <img loading="lazy" src="/img/company/account/action-notification.svg" alt="add notification symbol" class="relative mr1" height="18"
               width="20"
          />
          {{ $t('account.flow_new_action_notification') }}
        </div>
        <div class="tl pv2 ph2 mb3 blank-state-actions dib mr3 br2 pointer">
          <img loading="lazy" src="/img/company/account/action-task.svg" alt="add task symbol" class="relative mr1" height="20"
               width="20"
          />
          {{ $t('account.flow_new_action_task') }}
        </div>
        <div class="tl pv2 ph2 mb3 blank-state-actions dib mr3 br2 pointer">
          <img loading="lazy" src="/img/company/account/action-email.svg" alt="add email symbol" class="relative mr1" height="20"
               width="20"
          />
          {{ $t('account.flow_new_action_email') }}
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import ActionNotification from '@/Pages/Adminland/Flow/ActionNotification';

export default {
  components: {
    ActionNotification,
  },

  model: {
    prop: 'modelValue',
    event: 'update:modelValue'
  },

  props: {
    modelValue: {
      type: Array,
      default: null,
    },
  },

  emits: [
    'update:modelValue', 'completed'
  ],

  data() {
    return {
      complete: false,
      localActions: [],
      uniqueIds: 0,
      showActionMenu: false,
    };
  },

  computed: {
    orderedActions: function () {
      return _.orderBy(this.localActions, 'id');
    }
  },

  mounted() {
    this.localActions = this.modelValue;
  },

  methods: {
    addAction(type) {
      this.uniqueIds = this.uniqueIds + 1;
      this.localActions.push({
        id: this.uniqueIds,
        type: type,
      });

      this.showActionMenu = false;
      this.$emit('update:modelValue', this.localActions);
      this.$emit('completed', this.complete);
    },

    updateAction(event, action) {
      this.localActions[this.localActions.indexOf(action)] = event;

      // check whether the actions are "complete" to prevent submitting a wrong
      // json to the backend
      var isCompleteYet = true;
      for (let localAction of this.localActions) {
        if (localAction.complete === false || !localAction.complete) {
          isCompleteYet = false;
          break;
        }
      }

      this.complete = isCompleteYet;
      this.$emit('completed', this.complete);
    },

    destroyAction(action) {
      this.localActions.splice(this.localActions.findIndex(i => i.id === action.id), 1);
    }
  }
};

</script>
