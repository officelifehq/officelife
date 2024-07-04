<template>
  <div class="cf mw7 center br3 mt3 mb5 tc">
    <div class="cf dib btn-group">
      <Link :href="'/' + $page.props.auth.company.id + '/employees/' + employee.id" class="f6 fl ph3 pv2 dib pointer no-underline" :class="{'selected':(menu == 'presentation')}">
        {{ $t('employee.menu_all') }}
      </Link>
      <Link :href="'/' + $page.props.auth.company.id + '/employees/' + employee.id + '/work'" class="f6 fl ph3 pv2 dib pointer" :class="{'selected':(menu == 'work')}" data-cy="work-tab">
        {{ $t('employee.menu_work') }}
      </Link>
      <Link v-if="permissions.can_see_performance_tab" :href="'/' + $page.props.auth.company.id + '/employees/' + employee.id + '/performance'" class="f6 fl ph3 pv2 dib pointer" :class="{'selected':(menu == 'performance')}" data-cy="performance-tab">
        {{ $t('employee.menu_performance') }}
      </Link>
      <Link v-if="permissions.can_see_administration_tab" :href="'/' + $page.props.auth.company.id + '/employees/' + employee.id + '/administration'" class="f6 fl ph3 pv2 dib pointer" :class="{'selected':(menu == 'administration')}" data-cy="administration-tab">
        {{ $t('employee.menu_administration') }}
      </Link>
    </div>
  </div>
</template>

<script>

export default {
  props: {
    employee: {
      type: Object,
      default: null,
    },
    permissions: {
      type: Object,
      default: null,
    },
    menu: {
      type: String,
      default: 'presentation',
    },
  },
};

</script>
