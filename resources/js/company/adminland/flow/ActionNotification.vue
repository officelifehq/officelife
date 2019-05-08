<style lang="scss" scoped>
.actions-dots {
  top: 15px;
}

.employee-modal {
  top: 30px;
  left: -120px;
  right: 290px;
}

.confirmation-menu {
  top: 30px;
  left: -160px;
  right: initial;
  width: 310px;
}
</style>

<template>
  <div class="relative pr3 lh-copy">
    <span class="number">{{ action.id }}. </span>

    Notify <span class="bb b--dotted bt-0 bl-0 br-0 pointer" @click.prevent="displayModal = true">{{ who }}</span> with <span class="bb b--dotted bt-0 bl-0 br-0 pointer">a message</span>

    <!-- Modal to display the first step of "An employee" -->
    <div v-show="displayModal" v-click-outside="toggleModals" class="popupmenu employee-modal absolute br2 bg-white z-max tl pv2 ph3 bounceIn faster">
      <ul class="list ma0 pa0">
        <li class="pv2">
          <a class="pointer" @click.prevent="setTarget('actualEmployee')">The actual employee</a>
        </li>
        <li class="pv2">
          <a class="pointer" @click.prevent="displayEmployeeSearchBox">A specific employee</a>
        </li>
        <li class="pv2">
          <a class="pointer" @click.prevent="setTarget('managers')">The employee’s manager(s)</a>
        </li>
        <li class="pv2">
          <a class="pointer" @click.prevent="setTarget('directReports')">The employee’s direct report(s)</a>
        </li>
        <li class="pv2">
          <a class="pointer" @click.prevent="displayTeamSearchBox">All the employee team's members</a>
        </li>
        <li class="pv2">
          <a class="pointer" @click.prevent="displayConfirmationModal">Everyone in the company</a>
        </li>
      </ul>
    </div>

    <!-- Modal about confirming everyone in the company -->
    <div v-show="showEveryoneConfirmationModal" v-click-outside="toggleModals" class="popupmenu confirmation-menu absolute br2 bg-white z-max tl pv2 ph3 bounceIn faster">
      <p class="lh-copy">Are you sure? This will alert <span class="brush-blue">all the employees</span> of the company.</p>
      <ul class="list ma0 pa0 pb2">
        <li class="pv2 di relative mr2">
          <a class="pointer ml1" @click.prevent="setTarget('everyone')">Yes, I’m sure</a>
        </li>
        <li class="pv2 di">
          <a class="pointer" @click.prevent="showEveryoneConfirmationModal = false; displayModal = true">No, forget it</a>
        </li>
      </ul>
    </div>

    <img src="/img/common/triple-dots.svg" class="absolute right-0 pointer actions-dots" />
  </div>
</template>

<script>
import ClickOutside from 'vue-click-outside'

export default {

  directives: {
    ClickOutside
  },

  props: {
    action: {
      type: Object,
      default: null,
    }
  },

  data() {
    return {
      who: '',
      notification: {
        target: '',
      },
      displayModal: false,
      showEveryoneConfirmationModal: false,
    }
  },

  mounted() {
    // prevent click outside event with popupItem.
    this.popupItem = this.$el

    this.who = 'an employee'
  },

  methods: {
    displayConfirmationModal() {
      this.showEveryoneConfirmationModal = true
      this.displayModal = false
    },

    toggleModals() {
      this.showEveryoneConfirmationModal = false
      this.displayModal = false
    },

    setTarget(target) {
      this.notification.target = target
      this.toggleModals()

      switch(target) {
        case 'actualEmployee':
          this.who = 'the actual employee'
          break
        case 'everyone':
          this.who = 'everyone in the company'
          break
        case 'managers':
          this.who = 'the manager(s) of the employee'
          break
        case 'directReports':
          this.who = 'the direct report(s) of the employee'
          break
        default:
          this.who = 'an employee'
      }
    }
  }
}

</script>
