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
      <breadcrumb :with-box="true"
                  :previous-url="'/' + $page.props.auth.company.id + '/company/kb/' + page.wiki.id"
                  :previous="$t('app.breadcrumb_page_list')"
      >
        {{ $t('app.breadcrumb_page_detail') }}
      </breadcrumb>

      <!-- BODY -->
      <div class="mw7 center br3 mb5 bg-white box relative z-1">
        <div class="pa4 center">
          <h2 class="tc normal f2 mb4 mt0 lh-copy">
            {{ page.title }}
          </h2>

          <!-- written by information  -->
          <div class="relative flex justify-center items-center mb4">
            <!-- original author -->
            <div class="flex items-center mr4">
              <avatar :avatar="page.original_author.avatar" :url="page.original_author.url" :size="25" :class="'br-100 avatar mr2'" />
              <div class="gray f7">
                <p class="mt0 mr0 ml0 mb1">{{ $t('kb.page_show_written_by', {name: page.original_author.name}) }}</p>
                <p class="ma0">{{ $t('kb.page_show_on', {date: page.original_author.created_at}) }}</p>
              </div>
            </div>

            <!-- most recent author -->
            <div class="flex items-center mr4">
              <avatar :avatar="page.most_recent_author.avatar" :url="page.most_recent_author.url" :size="25" :class="'br-100 avatar mr2'" />
              <div class="gray f7">
                <p class="mt0 mr0 ml0 mb1">{{ $t('kb.page_show_edited_by', {name: page.most_recent_author.name}) }}</p>
                <p class="ma0">{{ $t('kb.page_show_on', {date: page.most_recent_author.created_at}) }}</p>
              </div>
            </div>

            <div class="f7">
              <ul class="ma0 pl0 list">
                <li class="di"><inertia-link :href="page.url_edit" class="bb b--dotted bt-0 bl-0 br-0 pointer mr3">{{ $t('app.edit') }}</inertia-link></li>
                <li v-if="!showDeleteMode" class="di"><a class="bb b--dotted bt-0 bl-0 br-0 pointer c-delete" @click="showDeleteMode = true">{{ $t('app.delete') }}</a></li>
                <li v-else class="di">
                  {{ $t('app.sure') }}
                  <a class="c-delete mr1 pointer" @click.prevent="destroy()">
                    {{ $t('app.yes') }}
                  </a>
                  <a class="pointer" @click.prevent="showDeleteMode = false">
                    {{ $t('app.no') }}
                  </a>
                </li>
              </ul>
            </div>
          </div>

          <!-- parsed content of the page -->
          <div class="parsed-content mb3" v-html="page.content"></div>

          <!-- stats -->
          <div class="f7 gray">
            {{ page.pageviews_counter }} views
          </div>
        </div>
      </div>
    </div>
  </layout>
</template>

<script>
import Layout from '@/Shared/Layout';
import Breadcrumb from '@/Shared/Layout/Breadcrumb';
import Avatar from '@/Shared/Avatar';

export default {
  components: {
    Layout,
    Breadcrumb,
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
      showDeleteMode: false,
    };
  },

  mounted() {
    if (localStorage.success) {
      this.flash(localStorage.success, 'success');
      localStorage.removeItem('success');
    }
  },

  methods: {
    destroy() {
      axios.delete(`/${this.$page.props.auth.company.id}/company/kb/${this.page.wiki.id}/pages/${this.page.id}`)
        .then(response => {
          localStorage.success = this.$t('kb.page_destroyed_success');
          this.$inertia.visit(response.data.data.url);
        })
        .catch(error => {
          this.form.errors = error.response.data;
        });
    },
  }
};

</script>
