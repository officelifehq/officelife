<style lang="scss" scoped>
.small-avatar {
  margin-left: -8px;
  box-shadow: 0 0 0 2px #fff;
}
</style>

<template>
  <layout :notifications="notifications">
    <!-- header -->
    <header-component :statistics="statistics" />

    <div class="ph2 ph5-ns">
      <!-- central content -->
      <tab :tab="tab" />

      <!-- BODY -->
      <div class="mw8 center br3 mb5 relative z-1">
        <div v-if="groups.data.length > 0">
          <div class="mt4 mt5-l center section-btn relative mb5">
            <p>
              <span class="pr2">
                {{ $t('group.index_title') }}
              </span>
              <inertia-link :href="groups.url_create" class="btn absolute db-l dn">
                {{ $t('group.index_cta') }}
              </inertia-link>
            </p>
          </div>

          <!-- list of groups -->
          <ul class="mt2 list pl0">
            <li v-for="group in groups.data" :key="group.id" class="w-100 bg-white box pa3 mb3 mr3 flex justify-between items-center">
              <div>
                <h2 class="fw4 f4 mt0 mb2 lh-copy relative">
                  <inertia-link :href="group.url">{{ group.name }}</inertia-link>
                </h2>
                <p class="mv0 lh-copy f6 parsed-content">{{ group.mission }}</p>
              </div>

              <!-- members -->
              <span v-if="group.preview_members" class="ma0 mb0 f7 grey">
                <div class="flex items-center relative tr">
                  <avatar v-for="member in group.preview_members" :key="member.id" :avatar="member.avatar" :url="member.url" :size="25"
                          :class="'br-100 small-avatar'"
                  />
                  <div v-if="group.remaining_members_count > 0" class="pl2 f7 more-members relative gray">
                    + {{ group.remaining_members_count }}
                  </div>
                </div>
              </span>
            </li>
          </ul>
        </div>

        <!-- blank state -->
        <div v-else class="tc">
          <img loading="lazy" src="/img/streamline-icon-projector-pie-chart@140x140.png" alt="project symbol" height="140"
               width="140"
          />
          <p class="mb3">
            <span class="db mb4">{{ $t('group.index_blank_title') }}</span>
            <inertia-link :href="groups.url_create" class="btn dib">{{ $t('group.index_cta') }}</inertia-link>
          </p>
        </div>
      </div>
    </div>
  </layout>
</template>

<script>
import Layout from '@/Shared/Layout';
import Avatar from '@/Shared/Avatar';
import Tab from '@/Pages/Company/Partials/Tab';
import HeaderComponent from '@/Pages/Company/Partials/Header';

export default {
  components: {
    Layout,
    Avatar,
    Tab,
    HeaderComponent,
  },

  props: {
    tab: {
      type: String,
      default: null,
    },
    statistics: {
      type: Object,
      default: null,
    },
    groups: {
      type: Object,
      default: null,
    },
    notifications: {
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
