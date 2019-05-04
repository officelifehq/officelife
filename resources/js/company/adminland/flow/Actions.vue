<style lang="scss" scoped>
.blank-state-actions {
  background-color: #f3f6f9;

  img {
    top: 3px;
  }
}

.actions-dots {
  top: 15px;
}
</style>

<template>
  <div>
    <div class="bb bb-gray pa3" v-show="orderedActions.length != 0">
      <p class="ma0 pa0 mb3">Do the following</p>
      <ul class="list ma0 pa0 tl">
        <li class="relative db bb-gray-hover pv2 ph1" v-for="action in orderedActions" :key="action.id">
          <action-notification :action="action" />
        </li>
      </ul>
    </div>

    <!-- add actions -->
    <div class="pa3">
      <a class="btn dib" @click.prevent="showActionMenu = true" v-show="!showActionMenu">Add action</a>

      <div class="tc" v-show="showActionMenu">
        <div class="tl pv2 ph2 mb3 blank-state-actions dib mr3 br2 pointer" @click="addAction('notification')">
          <img src="/img/company/account/action-notification.svg" class="relative mr1" height="18" width="20" />
          Notify an employee
        </div>
        <div class="tl pv2 ph2 mb3 blank-state-actions dib mr3 br2 pointer">
          <img src="/img/company/account/action-task.svg" class="relative mr1" height="20" width="20" />
          Add a task
        </div>
        <div class="tl pv2 ph2 mb3 blank-state-actions dib mr3 br2 pointer">
          <img src="/img/company/account/action-email.svg" class="relative mr1" height="20" width="20" />
          Send an email
        </div>
      </div>
    </div>
  </div>
</template>

<script>

export default {
  model: {
    prop: 'value',
    event: 'change'
  },

  props: {
    value: {
      type: Array,
      default: null,
    },
  },

  data() {
    return {
      localActions: [],
      numberOfActions: 0,
      showActionMenu: false,
    }
  },

  mounted() {
    this.localActions = this.value
  },

  computed: {
    orderedActions: function () {
      return _.orderBy(this.localActions, 'id')
    }
  },

  methods: {
    addAction(type) {
      this.numberOfActions = this.numberOfActions + 1
      this.localActions.push({
        id: this.numberOfActions,
        type: type,
      })

      this.showActionMenu = false
      this.$emit('change', this.localActions)
    },
  }
}

</script>
