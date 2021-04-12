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

@media (max-width: 480px) {
  .list-item {
    left: 0;
  }

  .edit-item {
    left: 0;
  }
}

.list-item-offset {
  left: 0px;
}

.title-section {
  background-color: #E9EDF2;
}
</style>

<template>
  <div>
    <h3 class="f4 fw5 mb2">
      <span class="mr1">
        🗣
      </span> {{ $t('dashboard.one_on_ones_talking_point_title') }}
    </h3>
    <p class="f6 gray mt1 pl4">{{ $t('dashboard.one_on_ones_talking_point_desc') }}</p>
    <ul class="list pl4 mb4">
      <li v-for="talkingPoint in localTalkingPoints" :key="talkingPoint.id" :class="entry.happened ? 'list-item-offset' : ''" class="list-item relative">
        <checkbox
          :id="'tp-' + talkingPoint.id"
          v-model="talkingPoint.checked"
          :item-id="talkingPoint.id"
          :datacy="'talking-point-' + talkingPoint.id"
          :label="talkingPoint.description"
          :extra-class-upper-div="'mb0 relative'"
          :class="'mb0 mr1'"
          :maxlength="255"
          :required="true"
          :editable="!entry.happened"
          @update:model-value="toggle(talkingPoint.id)"
          @update="showEditTalkingPoint(talkingPoint.id, talkingPoint.description)"
          @destroy="destroy(talkingPoint.id)"
        />

        <!-- edit talking point -->
        <div v-if="talkingPointToEdit == talkingPoint.id" class="bg-gray add-item-section edit-item ph2 mt1 mb3 pv1 br1 relative">
          <form @submit.prevent="update(talkingPoint.id)">
            <text-area
              :ref="'talkingPoint' + talkingPoint.id"
              v-model="form.description"
              :label="$t('dashboard.one_on_ones_action_talking_point_desc')"
              :datacy="'edit-talking-point-description-textarea-' + talkingPoint.id"
              :required="true"
              :rows="2"
              @esc-key-pressed="talkingPointToEdit = 0"
            />
            <!-- actions -->
            <div>
              <loading-button :class="'btn add w-auto-ns w-100 mb2 pv2 ph3'" data-cy="edit-talking-point-cta" :state="loadingState" :text="$t('app.update')" />
              <a class="btn dib tc w-auto-ns w-100 mb2 pv2 ph3" @click.prevent="talkingPointToEdit = 0">
                {{ $t('app.cancel') }}
              </a>
            </div>
          </form>
        </div>
      </li>
      <!-- cta to add a new talking point -->
      <li v-if="!addTalkingPointMode && !entry.happened" class="bg-gray add-item-section ph2 mt1 pv1 br1">
        <span data-cy="add-new-talking-point" class="bb b--dotted bt-0 bl-0 br-0 pointer f6" @click="displayAddTalkingPoint()">{{ $t('dashboard.one_on_ones_note_cta') }}</span>
      </li>
      <!-- add a new talking point -->
      <li v-if="addTalkingPointMode" class="bg-gray add-item-section ph2 mt1 pv1 br1">
        <form @submit.prevent="store()">
          <text-area
            ref="newTalkingPoint"
            v-model="form.description"
            :label="$t('dashboard.one_on_ones_action_talking_point_desc')"
            :datacy="'talking-point-description-textarea'"
            :required="true"
            :rows="2"
            @esc-key-pressed="addTalkingPointMode = false"
          />
          <!-- actions -->
          <div>
            <loading-button :class="'btn add w-auto-ns w-100 mb2 pv2 ph3'" :state="loadingState" :text="$t('app.add')" data-cy="add-talking-point-cta" />
            <a class="btn dib tc w-auto-ns w-100 mb2 pv2 ph3" @click.prevent="addTalkingPointMode = false">
              {{ $t('app.cancel') }}
            </a>
          </div>
        </form>
      </li>
    </ul>
  </div>
</template>

<script>
import TextArea from '@/Shared/TextArea';
import Checkbox from '@/Shared/EditableCheckbox';
import LoadingButton from '@/Shared/LoadingButton';

export default {
  components: {
    TextArea,
    Checkbox,
    LoadingButton,
  },

  props: {
    entry: {
      type: Object,
      default: null,
    },
  },

  data() {
    return {
      loadingState: '',
      addTalkingPointMode: false,
      localTalkingPoints: null,
      talkingPointToEdit: 0,
      form: {
        manager_id: null,
        employee_id: null,
        description: null,
        content: null,
        errors: [],
      },
    };
  },

  watch: {
    entry: {
      handler(value) {
        this.localTalkingPoints = value.talking_points;
      },
      deep: true
    }
  },

  created() {
    this.localTalkingPoints = this.entry.talking_points;
  },

  methods: {
    displayAddTalkingPoint() {
      this.addTalkingPointMode = true;
      this.form.description = null;

      this.$nextTick(() => {
        this.$refs.newTalkingPoint.focus();
      });
    },

    showEditTalkingPoint(id, description) {
      this.talkingPointToEdit = id;
      this.form.description = description;

      this.$nextTick(() => {
        this.$refs[`talkingPoint${id}`].focus();
      });
    },

    store() {
      this.loadingState = 'loading';
      this.form.employee_id = this.entry.employee.id;
      this.form.manager_id = this.entry.manager.id;

      axios.post('/' + this.$page.props.auth.company.id + '/dashboard/oneonones/' + this.entry.id + '/talkingPoints', this.form)
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

    update(itemId) {
      this.loadingState = 'loading';

      axios.post('/' + this.$page.props.auth.company.id + '/dashboard/oneonones/' + this.entry.id + '/talkingPoints/' + itemId, this.form)
        .then(response => {
          this.talkingPointToEdit = 0;
          this.loadingState = null;
          this.form.description = null;

          this.localTalkingPoints[this.localTalkingPoints.findIndex(x => x.id === itemId)] = response.data.data;
        })
        .catch(error => {
          this.loadingState = null;
          this.form.errors = error.response.data;
        });
    },

    toggle(id) {
      axios.post('/' + this.$page.props.auth.company.id + '/dashboard/oneonones/' + this.entry.id + '/talkingPoints/' + id + '/toggle')
        .then(response => {
        })
        .catch(error => {
          this.form.errors = error.response.data;
        });
    },

    destroy(id) {
      axios.delete('/' + this.$page.props.auth.company.id + '/dashboard/oneonones/' + this.entry.id + '/talkingPoints/' + id)
        .then(response => {
          this.flash(this.$t('dashboard.one_on_ones_note_deletion_success'), 'success');
          id = this.localTalkingPoints.findIndex(x => x.id === id);
          this.localTalkingPoints.splice(id, 1);
        })
        .catch(error => {
          this.form.errors = error.response.data;
        });
    },
  },
};

</script>
