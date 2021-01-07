<style lang="scss" scoped>
.avatar {
  width: 160px;
  height: 160px;
  left: 20px;
  border: 1px solid #e1e4e8 !important;
  padding: 10px;
  background-color: #fff;
  border-radius: 7px;
  rotate: -2deg;
}

.names {
  padding-left: 220px;
}

@media (max-width: 480px) {
  .names {
    padding-left: 0;
  }

  .avatar {
    width: 80px;
    height: 80px;
    padding: 4px;
  }
}

.black-white {
   filter: grayscale(100%);
}
</style>

<template>
  <div>
    <!-- BREADCRUMB -->
    <div class="mt4-l mt1 mw7 br3 bg-white box center breadcrumb relative z-0 f6 pb2">
      <ul class="list ph0 tc-l tl">
        <li class="di">
          <inertia-link :href="'/' + $page.props.auth.company.id + '/dashboard'">{{ $t('app.breadcrumb_dashboard') }}</inertia-link>
        </li>
        <li class="di">
          <inertia-link :href="'/' + $page.props.auth.company.id + '/employees'">{{ $t('app.breadcrumb_employee_list') }}</inertia-link>
        </li>
        <li class="di">
          {{ employee.name }}
        </li>
      </ul>
    </div>

    <!-- profile -->
    <div class="mw9 center br3 mb5 bg-white box relative z-1">
      <div class="pa3 relative">
        <!-- EDIT BUTTON -->
        <img v-if="permissions.can_edit_profile" loading="lazy" src="/img/menu_button.svg" class="box-edit-button absolute br-100 pa2 bg-white pointer" data-cy="edit-profile-button"
             alt="edit button"
             @click="profileMenu = true"
        />

        <!-- EDIT MENU -->
        <div v-if="profileMenu" v-click-outside="toggleProfileMenu" class="popupmenu absolute br2 bg-white z-max tl pv2 ph3 bounceIn faster">
          <ul class="list ma0 pa0">
            <li v-if="permissions.can_see_audit_log" class="pv2">
              <inertia-link :href="employee.url.audit_log" class="pointer" data-cy="view-log-button">
                {{ $t('employee.menu_changelog') }}
              </inertia-link>
            </li>
            <li v-show="permissions.can_edit_profile" class="pv2">
              <inertia-link :href="employee.url.edit" class="pointer" data-cy="show-edit-view">
                {{ $t('app.edit') }}
              </inertia-link>
            </li>
            <li v-show="permissions.can_delete_profile" class="pv2">
              <inertia-link :href="employee.url.delete" class="pointer c-delete" data-cy="show-delete-view">
                {{ $t('app.delete') }}
              </inertia-link>
            </li>
          </ul>
        </div>

        <!-- AVATAR -->
        <img :class="{'black-white':(employee.locked)}" loading="lazy" :src="employee.avatar" class="avatar absolute-ns db center" width="80"
             height="80"
             alt="avatar"
        />
      </div>
    </div>
  </div>
</template>

<script>
import vClickOutside from 'v-click-outside';

export default {
  directives: {
    clickOutside: vClickOutside.directive
  },

  props: {
    employee: {
      type: Object,
      default: null,
    },
    permissions: {
      type: Object,
      default: null,
    },
    employeeTeams: {
      type: Array,
      default: null,
    },
    positions: {
      type: Array,
      default: null,
    },
    teams: {
      type: Array,
      default: null,
    },
    pronouns: {
      type: Array,
      default: null,
    },
  },

  data() {
    return {
      profileMenu: false,
    };
  },

  methods: {
    toggleProfileMenu() {
      this.profileMenu = false;
    },
  }
};

</script>
