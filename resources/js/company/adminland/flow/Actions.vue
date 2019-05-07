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
      <p class="ma0 pa0 mb3">
        Do the following
      </p>
      <ul class="list ma0 pa0 tl">
        <li v-for="action in orderedActions" :key="action.id" class="relative db bb-gray-hover pv2 ph1">
          <action-notification :action="action" />
        </li>
      </ul>
    </div>

    <!-- add actions -->
    <div class="pa3">
      <a v-show="!showActionMenu" class="btn dib" @click.prevent="showActionMenu = true">Add action</a>

      <div v-show="showActionMenu" class="tc">
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

  computed: {
    orderedActions: function () {
      return _.orderBy(this.localActions, 'id')
    }
  },

  mounted() {
    this.localActions = this.value
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
