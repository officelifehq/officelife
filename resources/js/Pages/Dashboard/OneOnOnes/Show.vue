<style lang="scss" scoped>
.avatar {
  left: 1px;
  top: 5px;
  width: 35px;
}

.add-item-section {
  margin-left: 24px;
  top: 5px;
  background-color: #f5f5f5;
}

.list-item {
  left: -91px;
}
</style>

<template>
  <layout title="Home" :notifications="notifications">
    <div class="ph2 ph0-ns">
      <!-- BREADCRUMB -->
      <div class="mt4-l mt1 mw6 br3 bg-white box center breadcrumb relative z-0 f6 pb2">
        <ul class="list ph0 tc-l tl">
          <li class="di">
            <inertia-link :href="'/' + $page.auth.company.id + '/dashboard'">{{ $t('app.breadcrumb_dashboard') }}</inertia-link>
          </li>
          <li class="di">
            One on one
          </li>
        </ul>
      </div>

      <!-- BODY -->
      <div class="cf mw7 center br3 mb3 bg-white box relative pa3">
        <!-- title -->
        <h2 class="tc fw5 mt0">
          1 on 1 between
        </h2>
        <ul class="tc list pl0 mb4">
          <li class="di tl">
            <small-name-and-avatar
              v-if="entry.employee.id"
              :name="entry.employee.name"
              :avatar="entry.employee.avatar"
              :size="'22px'"
              :top="'1px'"
              :margin-between-name-avatar="'29px'"
            />
          </li>
          <li class="di f6 mh3">
            and
          </li>
          <li class="di tl">
            <small-name-and-avatar
              v-if="entry.manager.id"
              :name="entry.manager.name"
              :avatar="entry.manager.avatar"
              :size="'22px'"
              :top="'1px'"
              :margin-between-name-avatar="'29px'"
            />
          </li>
        </ul>

        <p>Only you, the manager and HR or administrators can read this entry.</p>

        <!-- navigation -->
        <div class="flex justify-between mb4">
          <div class="">
            <span class="db">
              Previous entry
            </span>
            <span>Aug 23, 2020</span>
          </div>
          <div class="f4">
            {{ entry.happened_at }}
          </div>
          <div class="">
            <span class="db">
              Next entry
            </span>
            <span>Aug 23, 2020</span>
          </div>
        </div>

        <!-- talking points -->
        <h3 class="f5 fw5 mb2">
          🤝 {{ $t('dashboard.one_on_ones_talking_point_title') }}
        </h3>
        <p class="f6 gray mt1">{{ $t('dashboard.one_on_ones_talking_point_desc') }}</p>
        <ul class="list pl0 mb4">
          <li v-for="talkingPoint in localTalkingPoints" :key="talkingPoint.id" class="list-item relative">
            <checkbox
              :id="'1'"
              v-model="form.description"
              :item-id="talkingPoint.id"
              :datacy="'task-complete-cta'"
              :label="talkingPoint.description"
              :extra-class-upper-div="'mb0 relative'"
              :classes="'mb0'"
              :required="true"
              :checked="talkingPoint.checked"
              :editable="true"
              @change="toggleChecked(talkingPoint.id)"
              @destroy="destroy(talkingPoint.id)"
            />
          </li>
          <li v-if="!addTalkingPointMode" class="bg-gray add-item-section ph2 mt1 pv1 br1">
            <span class="bb b--dotted bt-0 bl-0 br-0 pointer f6" @click="displayAddTalkingPoint()">Add an item</span>
          </li>
          <li v-if="addTalkingPointMode" class="bg-gray add-item-section ph2 mt1 pv1 br1">
            <form @submit.prevent="store()">
              <text-area
                ref="newTalkingPoint"
                v-model="form.description"
                :label="'Describe what should be discussed'"
                :datacy="'description-textarea'"
                :required="true"
                :rows="2"
                @esc-key-pressed="addTalkingPointMode = false"
              />
              <!-- actions -->
              <div>
                <loading-button :classes="'btn add w-auto-ns w-100 mb2 pv2 ph3'" :state="loadingState" :text="$t('app.add')" />
                <a class="btn dib tc w-auto-ns w-100 mb2 pv2 ph3" @click.prevent="addTalkingPointMode = false">
                  {{ $t('app.cancel') }}
                </a>
              </div>
            </form>
          </li>
        </ul>

        <!-- action items -->
        <h3 class="f5 fw5 mb2">
          🤜 {{ $t('dashboard.one_on_ones_action_item_title') }}
        </h3>
        <p class="f6 gray mt1">{{ $t('dashboard.one_on_ones_action_item_desc') }}</p>
        <ul class="list pl0 mb2">
          <li v-for="actionItem in localActionItems" :key="actionItem.id" class="list-item relative">
            <checkbox
              :id="'1'"
              v-model="form.description"
              :item-id="actionItem.id"
              :datacy="'task-complete-cta'"
              :label="actionItem.description"
              :extra-class-upper-div="'mb0 relative'"
              :classes="'mb0'"
              :required="true"
              :checked="actionItem.checked"
              :editable="true"
              @change="toggleChecked(actionItem.id)"
              @destroy="destroy(actionItem.id)"
            />
          </li>
          <li v-if="!addActionItemMode" class="bg-gray add-item-section ph2 mt1 pv1 br1">
            <span class="bb b--dotted bt-0 bl-0 br-0 pointer f6" @click="displayAddActionItem()">Add an item</span>
          </li>
          <li v-if="addActionItemMode" class="bg-gray add-item-section ph2 mt1 pv1 br1">
            <form @submit.prevent="storeActionItem()">
              <text-area
                ref="newActionItem"
                v-model="form.description"
                :label="'Describe what should be discussed'"
                :datacy="'description-textarea'"
                :required="true"
                :rows="2"
                @esc-key-pressed="addActionItemMode = false"
              />
              <!-- actions -->
              <div>
                <loading-button :classes="'btn add w-auto-ns w-100 mb2 pv2 ph3'" :state="loadingState" :text="$t('app.add')" />
                <a class="btn dib tc w-auto-ns w-100 mb2 pv2 ph3" @click.prevent="addActionItemMode = false">
                  {{ $t('app.cancel') }}
                </a>
              </div>
            </form>
          </li>
        </ul>
      </div>
    </div>
  </layout>
</template>

<script>
import TextArea from '@/Shared/TextArea';
import Checkbox from '@/Shared/EditableCheckbox';
import Layout from '@/Shared/Layout';
import LoadingButton from '@/Shared/LoadingButton';
import SmallNameAndAvatar from '@/Shared/SmallNameAndAvatar';

export default {
  components: {
    TextArea,
    Checkbox,
    Layout,
    LoadingButton,
    SmallNameAndAvatar,
  },

  props: {
    notifications: {
      type: Array,
      default: null,
    },
    entry: {
      type: Object,
      default: null,
    },
  },

  data() {
    return {
      loadingState: '',
      addTalkingPointMode: false,
      addActionItemMode: false,
      localTalkingPoints: null,
      localActionItems: null,
      localNotes: null,
      form: {
        manager_id: null,
        employee_id: null,
        description: null,
        content: null,
        errors: [],
      },
    };
  },

  created() {
    this.localTalkingPoints= this.entry.talking_points;
    this.localActionItems= this.entry.action_items;
  },

  methods: {
    displayAddTalkingPoint() {
      this.addTalkingPointMode = true;

      this.$nextTick(() => {
        this.$refs['newTalkingPoint'].$refs['input'].focus();
      });
    },

    displayAddActionItem() {
      this.addActionItemMode = true;

      this.$nextTick(() => {
        this.$refs['newActionItem'].$refs['input'].focus();
      });
    },

    store() {
      this.loadingState = 'loading';
      this.form.employee_id = this.entry.employee.id;
      this.form.manager_id = this.entry.manager.id;

      axios.post('/' + this.$page.auth.company.id + '/dashboard/oneonones/' + this.entry.id + '/talkingPoints', this.form)
        .then(response => {
          this.localTalkingPoints.push(response.data.data);
          this.addTalkingPointMode = false;
          this.form.description = null;
          this.loadingState = null;
        })
        .catch(error => {
          this.loadingState = null;
          this.form.errors = error.response.data;
        });
    },

    storeActionItem() {
      this.loadingState = 'loading';
      this.form.employee_id = this.entry.employee.id;
      this.form.manager_id = this.entry.manager.id;

      axios.post('/' + this.$page.auth.company.id + '/dashboard/oneonones/' + this.entry.id + '/actionItems', this.form)
        .then(response => {
          this.localActionItems.push(response.data.data);
          this.addActionItemMode = false;
          this.form.description = null;
          this.loadingState = null;
        })
        .catch(error => {
          this.loadingState = null;
          this.form.errors = error.response.data;
        });
    },

    toggleChecked(id) {
      this.loadingState = 'loading';

      axios.post('/' + this.$page.auth.company.id + '/dashboard/oneonones/' + this.entry.id + '/talkingPoints/' + id)
        .then(response => {
          id = this.localTalkingPoints.findIndex(x => x.id === id);
          this.localTalkingPoints.splice(id, 1);
        })
        .catch(error => {
          this.form.errors = _.flatten(_.toArray(error.response.data));
        });
    },

    destroy(id) {
      this.loadingState = 'loading';

      axios.delete('/' + this.$page.auth.company.id + '/dashboard/oneonones/' + this.entry.id + '/talkingPoints/' + id)
        .then(response => {
          id = this.localTalkingPoints.findIndex(x => x.id === id);
          this.localTalkingPoints.splice(id, 1);
        })
        .catch(error => {
          this.form.errors = _.flatten(_.toArray(error.response.data));
        });
    },
  },
};

</script>
