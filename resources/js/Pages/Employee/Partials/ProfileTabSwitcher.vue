<template>
  <div class="cf mw7 center br3 mt3 mb5 tc">
    <div class="cf dib btn-group">
      <inertia-link :href="'/' + $page.props.auth.company.id + '/employees/' + employee.id" class="f6 fl ph3 pv2 dib pointer no-underline" :class="{'selected':(menu == 'presentation')}">
        Presentation
      </inertia-link>
      <inertia-link :href="'/' + $page.props.auth.company.id + '/employees/' + employee.id + '/work'" class="f6 fl ph3 pv2 dib pointer" :class="{'selected':(menu == 'work')}" data-cy="work-tab">
        Work
      </inertia-link>
      <inertia-link :href="'/' + $page.props.auth.company.id + '/employees/' + employee.id + '/performance'" class="f6 fl ph3 pv2 dib pointer" :class="{'selected':(menu == 'performance')}" data-cy="performance-tab">
        {{ $t('employee.menu_performance') }}
      </inertia-link>
      <inertia-link :href="'/' + $page.props.auth.company.id + '/employees/' + employee.id + '/administration'" class="f6 fl ph3 pv2 dib pointer" :class="{'selected':(menu == 'administration')}" data-cy="administration-tab">
        Administration
      </inertia-link>
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
    menu: {
      type: String,
      default: 'presentation',
    },
  },
};

</script>
