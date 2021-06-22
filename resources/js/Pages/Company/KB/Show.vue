<style lang="scss" scoped>
.small-avatar {
  margin-left: -8px;
  box-shadow: 0 0 0 2px #fff;
}

.meeting-item:first-child:hover {
  border-top-left-radius: 10px;
  border-top-right-radius: 10px;
}

.meeting-item:last-child {
  border-bottom: 0;

  &:hover {
    border-bottom-left-radius: 10px;
    border-bottom-right-radius: 10px;
  }
}
</style>

<template>
  <layout title="Home" :notifications="notifications">
    <div class="ph2 ph5-ns">
      <!-- BREADCRUMB -->
      <div class="mt4-l mt1 mb4 mw6 br3 center breadcrumb relative z-0 f6 pb2">
        <ul class="list ph0 tc-l tl">
          <li class="di">
            <inertia-link :href="'/' + $page.props.auth.company.id + '/company'">{{ $t('app.breadcrumb_company') }}</inertia-link>
          </li>
          <li class="di">
            <inertia-link :href="'/' + $page.props.auth.company.id + '/company/kb'">{{ $t('app.breadcrumb_wiki_list') }}</inertia-link>
          </li>
          <li class="di">
            {{ $t('app.breadcrumb_wiki_detail') }}
          </li>
        </ul>
      </div>

      <!-- BODY -->
      <div class="mw7 center br3 mb5 relative z-1">
        <h2 class="tc normal mb2 lh-copy">
          {{ wiki.title }}
        </h2>

        <div class="cf center">
          <!-- LEFT COLUMN -->
          <div class="fl w-70-l w-100">
            <!-- list of pages -->
            <div v-if="wiki.pages.length > 0" class="mb2 fw5 relative mt4">
              <span class="mr1">
                🌮
              </span> {{ $t('group.summary_meetings') }}
            </div>
            <div v-if="wiki.pages.length > 0" class="br3 bg-white box z-1">
              <ul class="list pl0 ma0">
                <li v-for="page in wiki.pages" :key="page.id" class="pa3 bb bb-gray bb-gray-hover flex items-center justify-between meeting-item">
                  <div class="mb1 relative">
                    <inertia-link :href="page.url" class="employee-name db">
                      {{ page.title }}
                    </inertia-link>
                  </div>

                  <p>{{ page.content }}</p>
                </li>
              </ul>
            </div>
          </div>

          <!-- RIGHT COLUMN -->
          <div class="fl w-30-l w-100 pl4-l">
            <div class="mb2 fw5 relative">
              <span class="mr1">
                💹
              </span> {{ $t('group.summary_stat') }}
            </div>

            <ul class="list pl0">
              <li class="pl2"><inertia-link :href="wiki.urls.delete" class="f6 gray c-delete">{{ $t('group.summary_delete') }}</inertia-link></li>
            </ul>
          </div>
        </div>
      </div>
    </div>
  </layout>
</template>

<script>
import Layout from '@/Shared/Layout';

export default {
  components: {
    Layout,
  },

  props: {
    notifications: {
      type: Array,
      default: null,
    },
    wiki: {
      type: Object,
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
