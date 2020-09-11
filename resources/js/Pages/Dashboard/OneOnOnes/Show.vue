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

.edit-item {
  left: 88px;
}

.list-item {
  left: -86px;
}

.title-section {
  background-color: #E9EDF2;
}
</style>

<template>
  <layout title="Home" :notifications="notifications">
    <div class="ph2 ph0-ns">
      <!-- BREADCRUMB -->
      <div class="mt4-l mt1 mw6 br3 bg-white box center breadcrumb relative z-0 f6 pb2">
        <ul class="list ph0 tc-l tl">
          <li class="di">
            <inertia-link :href="'/' + $page.auth.company.id + '/dashboard/me'">{{ $t('app.breadcrumb_dashboard') }}</inertia-link>
          </li>
          <li class="di">
            {{ $t('app.breadcrumb_dashboard_one_on_one') }}
          </li>
        </ul>
      </div>

      <!-- BODY -->
      <div class="cf mw7 center br3 mb3 bg-white box relative">
        <!-- title -->
        <div class="pa3 title-section bb bb-gray mb3">
          <h2 class="tc fw5 mt0">
            1 on 1
          </h2>
          <ul class="tc list pl0">
            <li class="di tl">
              <small-name-and-avatar
                v-if="entry.employee.id"
                :name="entry.employee.name"
                :avatar="entry.employee.avatar"
                :size="'22px'"
                :top="'0px'"
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
                :top="'0px'"
                :margin-between-name-avatar="'29px'"
              />
            </li>
          </ul>
        </div>

        <!-- dates -->
        <div class="flex justify-between mb4 pa3">
          <div class="">
            <span class="db">
              Previous entry
            </span>
            <span>Aug 23, 2020</span>
          </div>
          <div class="f4 tc">
            <span class="f7 gray db mb1">
              Created on
            </span>
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
        <div class="pl3 pb3 pr3">
          <h3 class="f4 fw5 mb2">
            <span class="mr1">
              🗣
            </span> {{ $t('dashboard.one_on_ones_talking_point_title') }}
          </h3>
          <p class="f6 gray mt1 pl4">{{ $t('dashboard.one_on_ones_talking_point_desc') }}</p>
          <ul class="list pl4 mb4">
            <li v-for="talkingPoint in localTalkingPoints" :key="talkingPoint.id" class="list-item relative">
              <checkbox
                :id="'tp-' + talkingPoint.id"
                v-model="talkingPoint.checked"
                :item-id="talkingPoint.id"
                :datacy="'task-complete-cta'"
                :label="talkingPoint.description"
                :extra-class-upper-div="'mb0 relative'"
                :classes="'mb0'"
                :maxlength="255"
                :required="true"
                :editable="true"
                @change="toggleTalkingPoint(talkingPoint.id)"
                @update="showEditTalkingPoint(talkingPoint.id, talkingPoint.description)"
                @destroy="destroyTalkingPoint(talkingPoint.id)"
              />

              <!-- edit item -->
              <div v-if="talkingPointToEdit == talkingPoint.id" class="bg-gray add-item-section edit-item ph2 mt1 mb3 pv1 br1 relative">
                <form @submit.prevent="updateTalkingPoint(talkingPoint.id)">
                  <text-area
                    :ref="'talkingPoint' + talkingPoint.id"
                    v-model="form.description"
                    :label="$t('dashboard.one_on_ones_action_talking_point_desc')"
                    :datacy="'description-textarea'"
                    :required="true"
                    :rows="2"
                    @esc-key-pressed="talkingPointToEdit = 0"
                  />
                  <!-- actions -->
                  <div>
                    <loading-button :classes="'btn add w-auto-ns w-100 mb2 pv2 ph3'" :state="loadingState" :text="$t('app.update')" />
                    <a class="btn dib tc w-auto-ns w-100 mb2 pv2 ph3" @click.prevent="talkingPointToEdit = 0">
                      {{ $t('app.cancel') }}
                    </a>
                  </div>
                </form>
              </div>
            </li>
            <!-- cta to add a new item -->
            <li v-if="!addTalkingPointMode" class="bg-gray add-item-section ph2 mt1 pv1 br1">
              <span class="bb b--dotted bt-0 bl-0 br-0 pointer f6" @click="displayAddTalkingPoint()">{{ $t('dashboard.one_on_ones_note_cta') }}</span>
            </li>
            <!-- add a new item -->
            <li v-if="addTalkingPointMode" class="bg-gray add-item-section ph2 mt1 pv1 br1">
              <form @submit.prevent="store()">
                <text-area
                  ref="newTalkingPoint"
                  v-model="form.description"
                  :label="$t('dashboard.one_on_ones_action_talking_point_desc')"
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
          <h3 class="f4 fw5 mb2">
            <span class="mr1">
              🤜
            </span> {{ $t('dashboard.one_on_ones_action_item_title') }}
          </h3>
          <p class="f6 gray mt1 pl4">{{ $t('dashboard.one_on_ones_action_item_desc') }}</p>
          <ul class="list pl4 mb4">
            <li v-for="actionItem in localActionItems" :key="actionItem.id" class="list-item relative">
              <checkbox
                :id="'ai-' + actionItem.id"
                v-model="actionItem.checked"
                :item-id="actionItem.id"
                :datacy="'task-complete-cta'"
                :label="actionItem.description"
                :extra-class-upper-div="'mb0 relative'"
                :classes="'mb0'"
                :maxlength="255"
                :required="true"
                :editable="true"
                @change="toggleActionItem(actionItem.id)"
                @update="showEditActionItem(actionItem.id, actionItem.description)"
                @destroy="destroyActionItem(actionItem.id)"
              />

              <!-- edit item -->
              <div v-if="actionItemToEdit == actionItem.id" class="bg-gray add-item-section edit-item ph2 mt1 mb3 pv1 br1 relative">
                <form @submit.prevent="updateActionItem(actionItem.id)">
                  <text-area
                    :ref="'actionItem' + actionItem.id"
                    v-model="form.description"
                    :label="$t('dashboard.one_on_ones_action_talking_point_desc')"
                    :datacy="'description-textarea'"
                    :required="true"
                    :rows="2"
                    @esc-key-pressed="actionItemToEdit = 0"
                  />
                  <!-- actions -->
                  <div>
                    <loading-button :classes="'btn add w-auto-ns w-100 mb2 pv2 ph3'" :state="loadingState" :text="$t('app.update')" />
                    <a class="btn dib tc w-auto-ns w-100 mb2 pv2 ph3" @click.prevent="actionItemToEdit = 0">
                      {{ $t('app.cancel') }}
                    </a>
                  </div>
                </form>
              </div>
            </li>
            <!-- cta to add a new item -->
            <li v-if="!addActionItemMode" class="bg-gray add-item-section ph2 mt1 pv1 br1">
              <span class="bb b--dotted bt-0 bl-0 br-0 pointer f6" @click="displayAddActionItem()">{{ $t('dashboard.one_on_ones_note_cta') }}</span>
            </li>
            <!-- add a new item -->
            <li v-if="addActionItemMode" class="bg-gray add-item-section ph2 mt1 pv1 br1">
              <form @submit.prevent="storeActionItem()">
                <text-area
                  ref="newActionItem"
                  v-model="form.description"
                  :label="$t('dashboard.one_on_ones_action_item_textarea_desc')"
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

          <!-- notes -->
          <h3 class="f4 fw5 mb2">
            <span class="mr1">
              📝
            </span> {{ $t('dashboard.one_on_ones_note_title') }}
          </h3>
          <p class="f6 gray mt1 pl4">{{ $t('dashboard.one_on_ones_note_desc') }}</p>
          <ul class="pl4 mb2">
            <li v-for="note in localNotes" :key="note.id" class="relative mb2 ml3">
              {{ note.note }}
            </li>
            <!-- cta to add a new item -->
            <li v-if="!addNoteMode" class="list bg-gray add-item-section ph2 mt1 pv1 br1">
              <span class="bb b--dotted bt-0 bl-0 br-0 pointer f6" @click="displayAddNote()">{{ $t('dashboard.one_on_ones_note_cta') }}</span>
            </li>
            <!-- add a new item -->
            <li v-if="addNoteMode" class="bg-gray add-item-section ph2 mt1 pv1 br1">
              <form @submit.prevent="storeNote()">
                <text-area
                  ref="newNoteItem"
                  v-model="form.description"
                  :label="$t('dashboard.one_on_ones_note_textarea_desc')"
                  :datacy="'description-textarea'"
                  :required="true"
                  :rows="2"
                  @esc-key-pressed="addNoteMode = false"
                />
                <!-- actions -->
                <div>
                  <loading-button :classes="'btn add w-auto-ns w-100 mb2 pv2 ph3'" :state="loadingState" :text="$t('app.add')" />
                  <a class="btn dib tc w-auto-ns w-100 mb2 pv2 ph3" @click.prevent="addNoteMode = false">
                    {{ $t('app.cancel') }}
                  </a>
                </div>
              </form>
            </li>
          </ul>
        </div>

        <!-- title -->
        <div class="pa3 title-section bb bb-gray tc">
          <loading-button :classes="'btn w-auto-ns w-100 pv2 ph3'" :state="loadingState" :emoji="'✅'" :text="$t('dashboard.one_on_ones_mark_happened')" :cypress-selector="'expense-accept-button'"
                          @click="markAsHappened()"
          />

          <p class="mb1 f7 gray">{{ $t('dashboard.one_on_ones_mark_happened_note_1') }}</p>
          <p class="mv0 f7 gray">{{ $t('dashboard.one_on_ones_mark_happened_note_2') }}</p>
        </div>
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
      addNoteMode: false,
      localTalkingPoints: null,
      localActionItems: null,
      localNotes: null,
      talkingPointToEdit: 0,
      actionItemToEdit: 0,
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
    this.localNotes= this.entry.notes;
  },

  methods: {
    displayAddTalkingPoint() {
      this.addTalkingPointMode = true;
      this.form.description = null;

      this.$nextTick(() => {
        this.$refs['newTalkingPoint'].$refs['input'].focus();
      });
    },

    showEditTalkingPoint(id, description) {
      this.talkingPointToEdit = id;
      this.form.description = description;

      // this is really barbaric, but I need to do this to
      // first: target the TextInput with the right ref attribute
      // second: target within the component, the refs of the input text
      // this is because we try here to access $refs from a child component
      this.$nextTick(() => {
        this.$refs[`talkingPoint${id}`][0].$refs['input'].focus();
      });
    },

    showEditActionItem(id, description) {
      this.actionItemToEdit = id;
      this.form.description = description;

      // this is really barbaric, but I need to do this to
      // first: target the TextInput with the right ref attribute
      // second: target within the component, the refs of the input text
      // this is because we try here to access $refs from a child component
      this.$nextTick(() => {
        this.$refs[`actionItem${id}`][0].$refs['input'].focus();
      });
    },

    displayAddActionItem() {
      this.addActionItemMode = true;
      this.form.description = null;

      this.$nextTick(() => {
        this.$refs['newActionItem'].$refs['input'].focus();
      });
    },

    displayAddNote() {
      this.addNoteMode = true;
      this.form.description = null;

      this.$nextTick(() => {
        this.$refs['newNoteItem'].$refs['input'].focus();
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

    storeNote() {
      this.loadingState = 'loading';
      this.form.employee_id = this.entry.employee.id;
      this.form.manager_id = this.entry.manager.id;

      axios.post('/' + this.$page.auth.company.id + '/dashboard/oneonones/' + this.entry.id + '/notes', this.form)
        .then(response => {
          this.localNotes.push(response.data.data);
          this.addNoteMode = false;
          this.form.description = null;
          this.loadingState = null;
        })
        .catch(error => {
          this.loadingState = null;
          this.form.errors = error.response.data;
        });
    },

    updateTalkingPoint(itemId) {
      this.loadingState = 'loading';

      axios.post('/' + this.$page.auth.company.id + '/dashboard/oneonones/' + this.entry.id + '/talkingPoints/' + itemId, this.form)
        .then(response => {
          this.talkingPointToEdit = 0;
          this.loadingState = null;
          this.form.description = null;

          var id = this.localTalkingPoints.findIndex(x => x.id === itemId);
          this.$set(this.localTalkingPoints, id, response.data.data);
        })
        .catch(error => {
          this.loadingState = null;
          this.form.errors = _.flatten(_.toArray(error.response.data));
        });
    },

    updateActionItem(itemId) {
      this.loadingState = 'loading';

      axios.post('/' + this.$page.auth.company.id + '/dashboard/oneonones/' + this.entry.id + '/actionItems/' + itemId, this.form)
        .then(response => {
          this.actionItemToEdit = 0;
          this.loadingState = null;
          this.form.description = null;

          var id = this.localActionItems.findIndex(x => x.id === itemId);
          this.$set(this.localActionItems, id, response.data.data);
        })
        .catch(error => {
          this.loadingState = null;
          this.form.errors = _.flatten(_.toArray(error.response.data));
        });
    },

    toggleTalkingPoint(id) {
      axios.post('/' + this.$page.auth.company.id + '/dashboard/oneonones/' + this.entry.id + '/talkingPoints/' + id + '/toggle')
        .then(response => {
        })
        .catch(error => {
          this.form.errors = _.flatten(_.toArray(error.response.data));
        });
    },

    toggleActionItem(id) {
      axios.post('/' + this.$page.auth.company.id + '/dashboard/oneonones/' + this.entry.id + '/actionItems/' + id + '/toggle')
        .then(response => {
        })
        .catch(error => {
          this.form.errors = _.flatten(_.toArray(error.response.data));
        });
    },

    destroyTalkingPoint(id) {
      axios.delete('/' + this.$page.auth.company.id + '/dashboard/oneonones/' + this.entry.id + '/talkingPoints/' + id)
        .then(response => {
          flash(this.$t('dashboard.one_on_ones_note_deletion_success'), 'success');
          id = this.localTalkingPoints.findIndex(x => x.id === id);
          this.localTalkingPoints.splice(id, 1);
        })
        .catch(error => {
          this.form.errors = _.flatten(_.toArray(error.response.data));
        });
    },

    destroyActionItem(id) {
      axios.delete('/' + this.$page.auth.company.id + '/dashboard/oneonones/' + this.entry.id + '/actionItems/' + id)
        .then(response => {
          flash(this.$t('dashboard.one_on_ones_note_deletion_success'), 'success');
          id = this.localActionItems.findIndex(x => x.id === id);
          this.localActionItems.splice(id, 1);
        })
        .catch(error => {
          this.form.errors = _.flatten(_.toArray(error.response.data));
        });
    },

    markAsHappened() {
      this.loadingState = 'loading';

      axios.post('/' + this.$page.auth.company.id + '/dashboard/oneonones/' + this.entry.id + '/happened')
        .then(response => {
          this.loadingState = null;
          this.$inertia.visit(response.data.data.url);
        })
        .catch(error => {
          this.loadingState = null;
          this.form.errors = _.flatten(_.toArray(error.response.data));
        });
    }
  },
};

</script>
