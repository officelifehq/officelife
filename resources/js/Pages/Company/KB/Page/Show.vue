<style lang="scss" scoped>
.avatar {
  left: 1px;
  top: 5px;
  width: 23px;
}

.team-member {
  padding-left: 34px;

  .avatar {
    top: 6px;
    left: 7px;
  }
}

.ball-pulse {
  right: 8px;
  top: 37px;
  position: absolute;
}

.plus-button {
  padding: 2px 7px 4px;
  margin-right: 4px;
  border-color: #60995c;
  color: #60995c;
}
</style>

<template>
  <layout :notifications="notifications">
    <div class="ph2 ph0-ns">
      <!-- BREADCRUMB -->
      <div class="mt4-l mt1 mw6 br3 bg-white box center breadcrumb relative z-0 f6 pb2">
        <ul class="list ph0 tc-l tl">
          <li class="di">
            <inertia-link :href="'/' + $page.props.auth.company.id + '/company'">{{ $t('app.breadcrumb_company') }}</inertia-link>
          </li>
          <li class="di">
            ...
          </li>
          <li class="di">
            <inertia-link :href="'/' + $page.props.auth.company.id + '/company/kb/' + page.wiki.id">{{ $t('app.breadcrumb_page_list') }}</inertia-link>
          </li>
          <li class="di">
            {{ $t('app.breadcrumb_page_detail') }}
          </li>
        </ul>
      </div>

      <!-- BODY -->
      <div class="mw7 center br3 mb5 bg-white box relative z-1">
        <div class="pa4 center">
          <h2 class="tc normal f2 mb4 mt0 lh-copy">
            {{ page.title }}
          </h2>

          <!-- written by information  -->
          <div class="relative flex justify-center mb4">
            <!-- original author -->
            <div class="flex items-center mr4">
              <avatar :avatar="page.original_author.avatar" :url="page.original_author.url" :size="25" :class="'br-100 avatar mr2'" />
              <div class="gray f7">
                <p class="mt0 mr0 ml0 mb1">Written by {{ page.original_author.name }}</p>
                <p class="ma0">on {{ page.original_author.created_at }}</p>
              </div>
            </div>

            <!-- most recent author -->
            <div class="flex items-center">
              <avatar :avatar="page.most_recent_author.avatar" :url="page.most_recent_author.url" :size="25" :class="'br-100 avatar mr2'" />
              <div class="gray f7">
                <p class="mt0 mr0 ml0 mb1">Last edited by {{ page.most_recent_author.name }}</p>
                <p class="ma0">on {{ page.most_recent_author.created_at }}</p>
              </div>
            </div>
          </div>

          <div class="parsed-content" v-html="page.content"></div>
        </div>
      </div>
    </div>
  </layout>
</template>

<script>
import Layout from '@/Shared/Layout';
import Avatar from '@/Shared/Avatar';

export default {
  components: {
    Layout,
    Avatar,
  },

  props: {
    page: {
      type: Array,
      default: null,
    },
    notifications: {
      type: Array,
      default: null,
    },
  },

  data() {
    return {
    };
  },

  methods: {
  }
};

</script>
