<style lang="scss" scoped>
.cog-icon {
  width: 16px;
}

.icon-blank {
  width: 16px;
  top: 3px;
}

.members-list {
  max-height: 150px;
}

.popover {
  width: 300px;
}

.popupmenu {
  right: -11px;
  top: 33px;

  &:after {
    left: auto;
    right: 10px;
  }

  &:before {
    left: auto;
    right: 9px;
  }
}
</style>

<template>
  <div class="mb3 bb bb-gray pb3 relative">
    <div class="flex items-center justify-between mb2">
      <h3 class="ttc f7 gray ma0 fw4">
        {{ $t('project.issue_assignee_title') }}
      </h3>
      <svg xmlns="http://www.w3.org/2000/svg" class="cog-icon pointer" fill="none" viewBox="0 0 24 24" stroke="currentColor"
           @click.prevent="showPopover()"
      >
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
      </svg>
    </div>

    <!-- modal -->
    <div v-if="showPopup" v-click-outside="hidePopover" class="absolute popupmenu popover z-max">
      <p class="pa2 ma0 bb bb-gray">
        {{ $t('employee.position_modal_title') }}
      </p>
      <form @submit.prevent="search">
        <div class="relative pv2 ph2 bb bb-gray">
          <input id="search" v-model="search" type="text" name="search"
                 :placeholder="$t('employee.position_modal_filter')" class="br2 f5 w-100 ba b--black-40 pa2 outline-0"
                 @keydown.esc="hidePopover"
          />
        </div>
      </form>

      <!-- list of available members -->
      <ul class="pl0 list ma0 overflow-auto relative members-list">
        <li v-for="member in filteredList" :key="member.id">
          <div v-if="isAssigned(member.id)" class="pv2 ph3 bb bb-gray-hover bb-gray pointer relative" @click="destroy(member)">
            {{ member.name }}

            <img loading="lazy" src="/img/check.svg" class="pr1 absolute right-1" alt="check symbol" />
          </div>

          <div v-else class="pv2 ph3 bb bb-gray-hover bb-gray pointer relative" @click="assign(member)">
            {{ member.name }}
          </div>
        </li>
      </ul>
    </div>

    <!-- list of assignees -->
    <div v-if="localAssignees.length > 0">
      <div v-for="assignee in localAssignees" :key="assignee.id" class="flex items-center relative br2 mb1">
        <div class="mr2">
          <avatar :avatar="assignee.avatar" :size="25" :class="'br-100'" />
        </div>

        <div>
          <inertia-link :href="assignee.url.show" class="dib">{{ assignee.name }}</inertia-link>
        </div>
      </div>
    </div>

    <!-- no assignees -->
    <div v-else>
      <p class="ma0 f6 gray relative">
        <svg xmlns="http://www.w3.org/2000/svg" class="relative icon-blank" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>

        {{ $t('project.issue_assignee_blank') }}
      </p>
    </div>
  </div>
</template>

<script>
import Avatar from '@/Shared/Avatar';
import vClickOutside from 'click-outside-vue3';

export default {
  components: {
    Avatar,
  },

  directives: {
    clickOutside: vClickOutside.directive
  },

  props: {
    assignees: {
      type: Object,
      default: null,
    },
  },

  data() {
    return {
      members: null,
      localAssignees: null,
      search: '',
      showPopup: false,
    };
  },

  computed: {
    filteredList() {
      // filter the list when searching
      // also, sort the list by name
      if (this.members) {
        var list = this.members.filter(member => {
          return member.name.toLowerCase().includes(this.search.toLowerCase());
        });
      }

      return _.sortBy(list, ['name']);
    }
  },

  created() {
    if (this.assignees) {
      this.localAssignees = this.assignees.data;
    }
  },

  methods: {
    hidePopover: function() {
      this.showPopup = false;
    },

    showPopover: function() {
      this.load();
      this.showPopup = true;
    },

    load() {
      if (! this.members) {
        axios.get(this.assignees.url.index)
          .then(response => {
            this.members = response.data.data;
          })
          .catch(error => {
            this.form.errors = error.response.data;
          });
      }
    },

    isAssigned: function(id) {
      if (this.localAssignees) {
        for(let member of this.localAssignees){
          if (member.id === id) {
            return true;
          }
        }
      }
      return false;
    },

    assign(member) {
      axios.post(this.assignees.url.store, member)
        .then(response => {
          this.flash(this.$t('project.issue_assignee_success'), 'success');

          this.localAssignees.push(response.data.data);
          this.hidePopover();
        })
        .catch(error => {
          this.form.errors = error.response.data;
        });
    },

    destroy(member) {
      var url = 0;
      for(let assignee of this.localAssignees){
        if (assignee.id === member.id) {
          url = assignee.url.destroy;
        }
      }

      axios.delete(url)
        .then(response => {
          this.flash(this.$t('project.issue_assignee_destroy_success'), 'success');

          var id = this.localAssignees.findIndex(x => x.id == member.id);
          this.localAssignees.splice(id, 1);
          this.hidePopover();
        })
        .catch(error => {
          this.form.errors = error.response.data;
        });
    },
  }
};

</script>
