<style lang="scss" scoped>
.title {
  min-width: 410px;
}

.admin:not(:last-child) {
  margin-bottom: 0;
}
</style>

<template>
  <div class="bb bb-gray">
    <h3 class="ph3 fw5">
      {{ $t('account.general_information') }}

      <help :url="$page.props.help_links.account_general_company_name" :datacy="'help-icon-general'" :top="'2px'" />
    </h3>

    <!-- information about the company -->
    <ul class="list ph3">
      <li class="mb3 flex-ns items-start">
        <span class="dib-ns db mb0-ns mb2 title">{{ $t('account.general_administrators') }}</span>
        <ul class="dib list pl0">
          <li v-for="admin in information.administrators" :key="admin.id" class="db pb2 admin">
            <small-name-and-avatar
              :name="admin.name"
              :avatar="admin.avatar"
              :class="'f4 fw4'"
              :top="'0px'"
              :margin-between-name-avatar="'29px'"
            />
          </li>
        </ul>
      </li>
      <li class="mb3">
        <span class="dib-ns db mb0-ns mb2 title">{{ $t('account.general_creation_date') }}</span>
        <span class="fw6">{{ information.creation_date }}</span>
      </li>
      <li class="mb3">
        <span class="dib-ns db mb0-ns mb2 title">{{ $t('account.general_creation_size') }}</span>
        <span class="fw6">{{ $t('account.general_creation_size_kb', { size: information.total_size }) }}</span>
      </li>
      <li class="mb4">
        <span class="dib-ns db mb0-ns mb2 title">{{ $t('account.general_invitation_code') }}</span>
        <span class="fw6">{{ information.invitation_code }}</span>
      </li>
    </ul>
  </div>
</template>

<script>
import Help from '@/Shared/Help';
import SmallNameAndAvatar from '@/Shared/SmallNameAndAvatar';

export default {
  components: {
    Help,
    SmallNameAndAvatar
  },

  props: {
    information: {
      type: Object,
      default: null,
    },
  },
};
</script>
