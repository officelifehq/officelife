<template>
  <div class="cf mw7 center br3 mt3 mb5 tc">
    <div class="cf dib btn-group">
      <inertia-link :href="'/' + $page.props.auth.company.id + '/company'" class="f6 fl ph3 pv2 dib pointer no-underline" :class="{'selected':(tab == 'company')}">
        {{ $t('company.menu_employees') }}
      </inertia-link>
      <inertia-link :href="'/' + $page.props.auth.company.id + '/company/projects'" class="f6 fl ph3 pv2 dib pointer" :class="{'selected':(tab == 'projects')}">
        {{ $t('company.menu_projects') }}
      </inertia-link>
      <inertia-link :href="'/' + $page.props.auth.company.id + '/company/groups'" class="f6 fl ph3 pv2 dib pointer" :class="{'selected':(tab == 'groups')}">
        {{ $t('company.menu_groups') }}
      </inertia-link>
      <inertia-link :href="'/' + $page.props.auth.company.id + '/company/kb'" class="f6 fl ph3 pv2 dib pointer" :class="{'selected':(tab == 'kb')}">
        {{ $t('company.menu_kb') }}
      </inertia-link>
      <inertia-link :href="'/' + $page.props.auth.company.id + '/company/hr'" class="f6 fl ph3 pv2 dib pointer" :class="{'selected':(tab == 'hr')}">
        {{ $t('company.menu_hr') }}
      </inertia-link>
    </div>
  </div>
</template>

<script>

export default {
  props: {
    tab: {
      type: String,
      default: 'company',
    },
  },
};

</script>
