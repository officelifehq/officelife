<style lang="scss" scoped>
.entry-item:first-child {
  border-top-width: 1px;
  border-top-style: solid;
  border-top-left-radius: 3px;
  border-top-right-radius: 3px;
}

.entry-item:last-child {
  border-bottom-left-radius: 3px;
  border-bottom-right-radius: 3px;
}

.avatar {
  left: 1px;
  top: 5px;
  width: 35px;
}

.team-member {
  padding-left: 44px;

  .avatar {
    top: 2px;
  }
}

.add-item-section {
  margin-left: 24px;
  top: 5px;
  background-color: #f5f5f5;
}
</style>

<template>
  <div>
    <div class="mb5" data-cy="rate-your-manager-survey">
      <div class="cf mw7 center mb2 fw5 relative">
        <span class="mr1">
          🏔
        </span> {{ $t('dashboard.one_on_ones_title') }}

        <help :url="$page.help_links.manager_rate_manager" />
      </div>

      <div class="cf mw7 center br3 mb3 bg-white box relative">
        <img loading="lazy" src="/img/streamline-icon-work-desk-sofa-1-3@140x140.png" width="90" alt="meeting" class="judge absolute top-1 left-1" />

        <ul class="pl6 pb3 pt3 pr3 ma0">
          <li v-for="manager in oneOnOnes" :key="manager.id" class="flex justify-between items-center br bl bb bb-gray bb-gray-hover pa3 entry-item">
            <!-- identity -->
            <div>
              <span class="pl3 db relative team-member">
                <img loading="lazy" :src="manager.avatar" alt="avatar" class="br-100 absolute avatar" />
                <inertia-link :href="manager.url" class="mb2">{{ manager.name }}</inertia-link>
                <span class="title db f7 mt1">
                  {{ manager.position }}
                </span>
              </span>
            </div>

            <!-- call to action -->
            <div class="tr">
              <inertia-link href="#" class="btn dib-l db">{{ $t('dashboard.one_on_ones_cta') }}</inertia-link>
            </div>
          </li>
        </ul>
      </div>

      <div class="cf mw7 center br3 mb3 bg-white box relative pa3">
        <!-- title -->
        <h2 class="tc fw5 mt0">
          1 on 1 between
        </h2>
        <ul class="tc list pl0 mb4">
          <li class="di tl">
            <span class="pl3 dib relative team-member">
              <img loading="lazy" src="https://api.adorable.io/avatars/200/1ba09752-922e-45eb-b1b1-0500fc2a1396.png" alt="avatar" class="br-100 absolute avatar" />
              <a href="https://officelife.test/1/employees/10" class="mb2">
                Toby Flenderson
              </a>
              <span class="title db f7 mt1">
                H.R Rep
              </span>
            </span>
          </li>
          <li class="di f6 mh3">
            and
          </li>
          <li class="di tl">
            <span class="pl3 dib relative team-member">
              <img loading="lazy" src="https://api.adorable.io/avatars/200/1ba09752-922e-45eb-b1b1-0500fc2a1396.png" alt="avatar" class="br-100 absolute avatar" />
              <a href="https://officelife.test/1/employees/10" class="mb2">
                Toby Flenderson
              </a>
              <span class="title db f7 mt1">
                H.R Rep
              </span>
            </span>
          </li>
        </ul>

        <!-- navigation -->
        <div class="flex justify-between mb4">
          <div class="">
            <span class="db">
              Previous entry
            </span>
            <span>Aug 23, 2020</span>
          </div>
          <div class="f4">
            Aug 23, 2020
          </div>
          <div class="">
            <span class="db">
              Next entry
            </span>
            <span>Aug 23, 2020</span>
          </div>
        </div>

        <!-- content -->
        <h3 class="fw5 mb2">
          Talking points
        </h3>
        <p class="f6 gray mt1">Things that need to be adressed during the meeting</p>
        <ul class="list pl0 mb2">
          <li>
            <checkbox
              :id="'1'"
              v-model="form.content"
              :datacy="'task-complete-cta'"
              :label="'sdafs'"
              :extra-class-upper-div="'mb0 relative'"
              :classes="'mb0'"
              :required="true"
              :editable="true"
              @change="updateStatus($event)"
            />
          </li>
          <li>
            <checkbox
              :id="'1'"
              v-model="form.content"
              :datacy="'task-complete-cta'"
              :label="'sdafs'"
              :extra-class-upper-div="'mb0 relative'"
              :classes="'mb0'"
              :required="true"
              :editable="true"
              @change="updateStatus($event)"
            />
          </li>
          <li class="bg-gray add-item-section ph2 mt1 pv1 br1">
            <span class="bb b--dotted bt-0 bl-0 br-0 pointer f6">Add an item</span>
          </li>
        </ul>

        <h3 class="fw5 mb2">
          Action items
        </h3>
        <p class="f6 gray mt1">If you have decided something or need to follow up next time, enter the points here.</p>
        <ul class="list pl0 mb2">
          <li>
            <checkbox
              :id="'1'"
              v-model="form.content"
              :datacy="'task-complete-cta'"
              :label="'sdafs'"
              :extra-class-upper-div="'mb0 relative'"
              :classes="'mb0'"
              :required="true"
              :editable="true"
              @change="updateStatus($event)"
            />
          </li>
          <li>
            <checkbox
              :id="'1'"
              v-model="form.content"
              :datacy="'task-complete-cta'"
              :label="'sdafs'"
              :extra-class-upper-div="'mb0 relative'"
              :classes="'mb0'"
              :required="true"
              :editable="true"
              @change="updateStatus($event)"
            />
          </li>
        </ul>
      </div>
    </div>
  </div>
</template>

<script>
import Checkbox from '@/Shared/EditableCheckbox';
import Help from '@/Shared/Help';

export default {
  components: {
    Checkbox,
    Help,
  },

  props: {
    employee: {
      type: Object,
      default: null,
    },
    oneOnOnes: {
      type: Array,
      default: null,
    },
  },

  data() {
    return {
      answerMode: false,
      commentMode: false,
      alreadyAnswered: false,
      showFinalSucessMessage: false,
      form: {
        content: null,
        rating: 0,
        reveal: false,
        comment: null,
        errors: [],
      },
      loadingState: '',
    };
  },

  created: function() {
  },

  methods: {
    submit(answer, rating) {
      this.loadingState = 'loading';
      this.form.rating = rating;

      axios.post('/' + this.$page.auth.company.id + '/dashboard/manager/rate/' + answer.id, this.form)
        .then(response => {
          this.loadingState = null;
          this.alreadyAnswered = true;
          this.answerMode = true;
          flash(this.$t('dashboard.rate_your_manager_submitted'), 'success');
        })
        .catch(error => {
          this.loadingState = null;
          this.form.errors = error.response.data.errors;
        });
    },

    submitComment(answer) {
      this.loadingState = 'loading';

      axios.post('/' + this.$page.auth.company.id + '/dashboard/manager/rate/' + answer.id + '/comment', this.form)
        .then(response => {
          this.loadingState = null;
          this.alreadyAnswered = true;
          this.answerMode = true;
          this.commentMode = false;
          this.showFinalSucessMessage = true;
          flash(this.$t('dashboard.rate_your_manager_submitted'), 'success');
        })
        .catch(error => {
          this.loadingState = null;
          this.form.errors = error.response.data.errors;
        });
    },

    showAnswerPanel(answer) {
      this.commentMode = true;

      this.$nextTick(() => {
        this.$refs[`editor-${answer.id}`][0].$refs['input'].focus();
      });
    },

    toggleReveal() {
      this.form.reveal = !this.form.reveal;
    }
  }
};
</script>
