<style lang="scss" scoped>
.avatar {
  left: 1px;
  top: 5px;
  width: 35px;
}

.comment-avatar {
  width: 50px;
}

.team-member {
  padding-left: 44px;

  .avatar {
    top: 2px;
  }
}
</style>

<template>
  <layout title="Home" :notifications="notifications">
    <div class="ph2 ph5-ns">
      <!-- BREADCRUMB -->
      <div class="mt4-l mt1 mw6 br3 center breadcrumb relative z-0 f6 pb2">
        <ul class="list ph0 tc-l tl">
          <li class="di">
            <inertia-link :href="'/' + $page.props.auth.company.id + '/dashboard'">{{ $t('app.breadcrumb_dashboard') }}</inertia-link>
          </li>
          <li class="di">
            ...
          </li>
          <li class="di">
            <inertia-link :href="'/' + $page.props.auth.company.id + '/teams/ships'">Messages</inertia-link>
          </li>
          <li class="di">
            Message details
          </li>
        </ul>
      </div>

      <!-- BODY -->
      <div class="mw8 center br3 mb5 relative cf">
        <!-- LEFT COLUMN -->
        <div class="fl w-70-l w-100">
          <div class="bg-white box mb4 pa3">
            <h3 class="mt0 mb0 fw5">
              Message title
            </h3>
            <p class="mb2 f7 gray">Written on Jan 02, 2020</p>
            <p class="lh-copy bb bb-gray pb3"> Open enrollment for our Flex Spending Account begins next month, so if you want to enroll or re-enroll, you have to do so within that month.</p>

            <div class="bb bb-gray pb3">
              <p class="fw6">Comments</p>
              <div class="relative mb4">
                <img loading="lazy" src="https://api.adorable.io/avatars/200/d5f148f9-f029-4c71-9fd7-1c9d3f1ef0ac.png" alt="avatar" class="br-100 absolute comment-avatar" />
                <div class="pl5">
                  <ul class="f7 list pl0 gray">
                    <li class="di mr1"><a href="">Regis Freyd</a></li>
                    <li class="di mr1">Sun, 4 Oct at 11:35am</li>
                    <li class="di mr1"><a href="">Edit</a></li>
                    <li class="di"><a href="">Delete</a></li>
                  </ul>
                  <p class="lh-copy">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec a diam lectus. Sed sit amet ipsum mauris. Maecenas congue ligula ac quam viverra nec consectetur ante hendrerit. Donec et mollis dolor. Praesent et diam eget libero egestas mattis sit amet vitae augue. Nam tincidunt congue enim, ut porta lorem lacinia consectetur. Donec ut libero sed arcu vehicula ultricies a non tortor.</p>
                </div>
              </div>
              <div class="relative">
                <img loading="lazy" src="https://api.adorable.io/avatars/200/d5f148f9-f029-4c71-9fd7-1c9d3f1ef0ac.png" alt="avatar" class="br-100 absolute comment-avatar" />
                <div class="pl5">
                  <ul class="f7 list pl0 gray">
                    <li class="di mr1"><a href="">Regis Freyd</a></li>
                    <li class="di mr1">Sun, 4 Oct at 11:35am</li>
                    <li class="di mr1"><a href="">Edit</a></li>
                    <li class="di"><a href="">Delete</a></li>
                  </ul>
                  <p class="lh-copy">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec a diam lectus. Sed sit amet ipsum mauris. Maecenas congue ligula ac quam viverra nec consectetur ante hendrerit. Donec et mollis dolor. Praesent et diam eget libero egestas mattis sit amet vitae augue. Nam tincidunt congue enim, ut porta lorem lacinia consectetur. Donec ut libero sed arcu vehicula ultricies a non tortor.</p>
                </div>
              </div>
            </div>

            <!-- add a comment -->
            <p class="fw6">Comments</p>
            <form action="">
              <!-- Content -->
              <text-area v-model="form.comment"
                         :label="$t('account.company_news_new_content')"
                         :datacy="'news-content-textarea'"
                         :required="true"
                         :rows="10"
                         :help="$t('account.company_news_new_content_help')"
              />

              <!-- Actions -->
              <div class="mt2 mb0">
                <div class="flex-ns">
                  <loading-button :classes="'btn add w-auto-ns w-100 mb2 pv2 ph3 mr2'" :state="loadingState" :text="$t('app.publish')" :cypress-selector="'submit-add-news-button'" />
                  <div>
                    <inertia-link :href="'/' + $page.props.auth.company.id + '/account/news'" class="btn dib tc w-auto-ns w-100 mb2 pv2 ph3">
                      {{ $t('app.cancel') }}
                    </inertia-link>
                  </div>
                </div>
              </div>
            </form>
          </div>
        </div>

        <!-- RIGHT COLUMN -->
        <div class="fl w-30-l w-100 pl4-l">
          <div class="bg-white box mb4 pa3">
            <!-- written by -->
            <h3 class="ttc f7 gray mt0 mb2 fw4 ttu">
              Written by
            </h3>
            <div class="bb bb-gray pb3 mb3">
              <span class="pl3 db relative team-member">
                <img loading="lazy" src="https://api.adorable.io/avatars/200/1499e9ea-b9a2-4d60-8bf6-a9bdf4cacbbc.png" alt="avatar" class="br-100 absolute avatar" />
                <inertia-link class="mb2">Scott</inertia-link>
                <span class="title db f7 mt1">
                  Manager
                </span>
              </span>
            </div>

            <!-- written by -->
            <h3 class="ttc f7 gray mt0 mb2 fw4 ttu">
              Seen by
            </h3>
            <p class="mb0 mt1">34 team members (85%)</p>
          </div>
        </div>
      </div>
    </div>
  </layout>
</template>

<script>
import Layout from '@/Shared/Layout';
import TextArea from '@/Shared/TextArea';

export default {
  components: {
    Layout,
    TextArea,
  },

  props: {
    notifications: {
      type: Array,
      default: null,
    },
  },

  data() {
    return {
      form: {
        comment: null,
        errors: [],
      },
    };
  },

  methods: {
  }
};

</script>
