<style lang="scss" scoped>
.title {
  min-width: 240px;
}

.admin:not(:last-child) {
  margin-bottom: 0;
}
</style>

<template>
  <div class="bb bb-gray">
    <h3 class="ph3 fw5">
      Logo

      <help :url="$page.props.help_links.account_general_company_name" :datacy="'help-icon-general'" :top="'2px'" />
    </h3>

    <ul class="list ph3">
      <li class="mb3 flex-ns items-start">
        <span class="dib-ns db mb0-ns mb2 title">{{ $t('account.general_administrators') }}</span>
        <img src="" alt="" />
      </li>
    </ul>
  </div>
</template>

<script>
import Help from '@/Shared/Help';

export default {
  components: {
    Help,
  },

  props: {
    information: {
      type: Object,
      default: null,
    },
  },
};
</script>
