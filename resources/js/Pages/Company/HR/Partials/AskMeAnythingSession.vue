<style lang="scss" scoped>

</style>

<template>
  <div class="mb4">
    <span class="db fw5 mb2 relative">
      <span class="mr1">
        🎤
      </span> {{ $t('company.hr_ama_widget_title') }}
    </span>

    <div class="br3 bg-white box z-1 relative">
      <div class="pa3 flex justify-between items-center">
        <div>
          <h2 class="fw4 mt0 mb2">
            Sept 33, 2200
          </h2>
          <p class="f6 mt1 mb0">blabla</p>
        </div>

        <div class="tc">
          <inertia-link class="btn db mb2">{{ $t('app.view') }}</inertia-link>
          <p class="mv0 gray f7">{{ $t('company.hr_ama_widget_questions') }}</p>
        </div>
      </div>

      <div class="ph3 pv2 tc f6 bt bb-gray">
        <a href="https://officelife.test/1/employees">{{ $t('company.hr_ama_widget_view_all') }}</a>
      </div>
    </div>
  </div>
</template>

<script>

export default {
  props: {
    genders: {
      type: Object,
      default: null,
    },
  },
};
</script>
