<style lang="scss" scoped>
.ball-pulse {
  right: 8px;
  top: 37px;
  position: absolute;
}
</style>

<template>
  <layout :notifications="notifications">
    <div class="ph2 ph0-ns">
      <breadcrumb :with-box="true" :has-more="false">
        {{ $t('app.breadcrumb_company_news') }}
      </breadcrumb>

      <!-- BODY -->
      <div class="mw7 center br3 mb5 bg-white box relative z-1">
        <div class="pa3 measure center">
          <h2 class="tc normal mb4 lh-copy">
            {{ $t('company.news_index_title') }}

            <help :url="$page.props.help_links.project_messages" :top="'1px'" />
          </h2>

          <div v-for="newsItem in news" :key="newsItem.id" class="mb5">
            <div class="f7 gray mr3">
              {{ newsItem.written_at }}
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
                :class="'f4 fw4'"
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
import Breadcrumb from '@/Shared/Layout/Breadcrumb';
import Help from '@/Shared/Help';
import SmallNameAndAvatar from '@/Shared/SmallNameAndAvatar';

export default {
  components: {
    Layout,
    Breadcrumb,
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
