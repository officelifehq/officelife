<style lang="scss" scoped>
.ball-pulse {
  right: 8px;
  top: 37px;
  position: absolute;
}
</style>

<template>
  <layout title="Home" :notifications="notifications">
    <div class="ph2 ph0-ns">
      <!-- BREADCRUMB -->
      <div class="mt4-l mt1 mw6 br3 bg-white box center breadcrumb relative z-0 f6 pb2">
        <ul class="list ph0 tc-l tl">
          <li class="di">
            <inertia-link :href="'/' + $page.props.auth.company.id + '/company'">{{ $t('app.breadcrumb_company') }}</inertia-link>
          </li>
          <li class="di">
            {{ $t('app.breadcrumb_company_news') }}
          </li>
        </ul>
      </div>

      <!-- BODY -->
      <div class="mw7 center br3 mb5 bg-white box relative z-1">
        <div class="pa3 measure center">
          <h2 class="tc normal mb4 lh-copy">
            {{ $t('company.news_index_title') }}

            <help :url="$page.props.help_links.project_messages" :top="'1px'" />
          </h2>

          <div v-for="newsItem in news" :key="newsItem.id" class="mb5">
            <div class="f6">
              <span class="mr3">
                {{ newsItem.written_at }}
              </span>
            </div>

            <!-- title -->
            <h2 class="mt1 mb2 fw4">
              {{ newsItem.title }}
            </h2>

            <!-- author -->
            <div v-if="newsItem.author.url" class="mb3 gray">
              <small-name-and-avatar
                :name="newsItem.author.name"
                :avatar="newsItem.author.avatar"
                :url="newsItem.author.url"
                :classes="'f4 fw4'"
                :top="'0px'"
                :margin-between-name-avatar="'29px'"
              />
            </div>
            <div v-else class="mb3 gray f6">
              {{ newsItem.author }}
            </div>

            <!-- content -->
            <div class="parsed-content" v-html="newsItem.content"></div>
          </div>
        </div>
      </div>
    </div>
  </layout>
</template>

<script>
import Layout from '@/Shared/Layout';
import Help from '@/Shared/Help';
import SmallNameAndAvatar from '@/Shared/SmallNameAndAvatar';

export default {
  components: {
    Layout,
    Help,
    SmallNameAndAvatar,
  },

  props: {
    news: {
      type: Array,
      default: null,
    },
    notifications: {
      type: Array,
      default: null,
    },
  },
};

</script>
