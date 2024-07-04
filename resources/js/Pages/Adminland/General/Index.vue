<style lang="scss" scoped>
</style>

<template>
  <layout :notifications="notifications">
    <div class="ph2 ph0-ns">
      <breadcrumb :with-box="true"
                  :root-url="'/' + $page.props.auth.company.id + '/dashboard'"
                  :root="$t('app.breadcrumb_dashboard')"
                  :previous-url="'/' + $page.props.auth.company.id + '/account'"
                  :previous="$t('app.breadcrumb_account_home')"
                  :has-more="false"
      >
        {{ $t('app.breadcrumb_account_manage_general_settings') }}
      </breadcrumb>

      <!-- BODY -->
      <div class="mw7 center br3 mb5 bg-white box restricted relative z-1">
        <div class="mt5">
          <h2 class="pa3 tc normal mb4">
            {{ $t('account.general_title') }}
          </h2>

          <!-- stats -->
          <stat
            :information="information"
          />

          <!-- company name -->
          <name
            :information="information"
          />

          <!-- company founded -->
          <founded-date
            :information="information"
          />

          <!-- company location -->
          <location
            :information="information"
          />

          <!-- Currency -->
          <currency
            :information="information"
            :currencies="currencies"
          />

          <!-- Logo -->
          <logo
            :information="information"
          />
        </div>
      </div>
    </div>
  </layout>
</template>

<script>
import Layout from '@/Shared/Layout.vue';
import Breadcrumb from '@/Shared/Layout/Breadcrumb.vue';
import Name from '@/Pages/Adminland/General/Partials/Name.vue';
import Currency from '@/Pages/Adminland/General/Partials/Currency.vue';
import Stat from '@/Pages/Adminland/General/Partials/Stat.vue';
import Location from '@/Pages/Adminland/General/Partials/Location.vue';
import Logo from '@/Pages/Adminland/General/Partials/Logo.vue';
import FoundedDate from '@/Pages/Adminland/General/Partials/FoundedDate.vue';

export default {
  components: {
    Layout,
    Breadcrumb,
    Name,
    Location,
    Currency,
    Stat,
    Logo,
    FoundedDate,
  },

  props: {
    notifications: {
      type: Array,
      default: null,
    },
    information: {
      type: Object,
      default: null,
    },
    currencies: {
      type: Array,
      default: null,
    },
  },

  mounted() {
    if (localStorage.success) {
      this.flash(localStorage.success, 'success');

      localStorage.removeItem('success');
    }
  },
};

</script>
