<style lang="scss" scoped>
</style>

<template>
  <div class="bg-white box mb4">
    <!-- date -->
    <div class="pt3 pr3 pl3">
      <p class="silver f6 ma0 mb1">Date the meeting occurs</p>

      <p v-if="!editMode">{{ localMeeting.happened_at }} <a class="ml2 bb b--dotted bt-0 bl-0 br-0 pointer f6" @click.prevent="showEditMode()">{{ $t('app.edit') }}</a></p>
    </div>

    <!-- edit date -->
    <form v-if="editMode" class="pa3" @submit.prevent="set()">
      <div class="dt-ns dt--fixed di">
        <div class="dtc-ns pr2-ns pb0-ns w-100">
          <!-- year -->
          <text-input :id="'year'"
                      :ref="'year'"
                      v-model="form.year"
                      :name="'year'"
                      :errors="$page.props.errors.year"
                      :label="$t('employee.edit_information_year')"
                      :required="true"
                      :type="'number'"
                      :min="1900"
                      :max="localMeeting.happened_at_max_year"
                      :help="$t('employee.edit_information_year_help')"
                      @esc-key-pressed="editMode = false"
          />
        </div>
        <div class="dtc-ns pr2-ns pb0-ns w-100">
          <!-- month -->
          <text-input :id="'month'"
                      v-model="form.month"
                      :name="'month'"
                      :errors="$page.props.errors.month"
                      :label="$t('employee.edit_information_month')"
                      :required="true"
                      :type="'number'"
                      :min="1"
                      :max="12"
                      :help="$t('employee.edit_information_month_help')"
                      @esc-key-pressed="editMode = false"
          />
        </div>
        <div class="dtc-ns pr2-ns pb0-ns w-100">
          <!-- day -->
          <text-input :id="'day'"
                      v-model="form.day"
                      :name="'day'"
                      :errors="$page.props.errors.day"
                      :label="$t('employee.edit_information_day')"
                      :required="true"
                      :type="'number'"
                      :min="1"
                      :max="31"
                      :help="$t('employee.edit_information_day_help')"
                      @esc-key-pressed="editMode = false"
          />
        </div>
      </div>

      <!-- actions -->
      <div class="mb0">
        <div class="flex-ns justify-between">
          <a class="btn dib tc w-auto-ns w-100 mb2 pv2 ph3" @click.prevent="editMode = false">
            {{ $t('app.cancel') }}
          </a>
          <loading-button :class="'btn add w-auto-ns w-100 mb2 pv2 ph3'" :state="loadingState" :text="$t('app.save')" />
        </div>
      </div>
    </form>
  </div>
</template>

<script>
import TextInput from '@/Shared/TextInput';
import 'vue-loaders/dist/vue-loaders.css';
import LoadingButton from '@/Shared/LoadingButton';

export default {
  components: {
    TextInput,
    LoadingButton,
  },

  props: {
    groupId: {
      type: Number,
      default: null,
    },
    meeting: {
      type: Object,
      default: null,
    },
  },

  data() {
    return {
      editMode: false,
      form: {
        year: null,
        month: null,
        day: null,
        errors: [],
      },
      loadingState: '',
    };
  },

  created() {
    this.localMeeting = this.meeting.meeting;
    this.form.year = this.localMeeting.happened_at_year;
    this.form.month = this.localMeeting.happened_at_month;
    this.form.day = this.localMeeting.happened_at_day;
  },

  methods: {
    showEditMode() {
      this.editMode = true;

      this.$nextTick(() => {
        this.$refs.year.focus();
      });
    },

    set() {
      this.loadingState = 'loading';

      axios.post(`/${this.$page.props.auth.company.id}/company/groups/${this.groupId}/meetings/${this.localMeeting.id}/setDate`, this.form)
        .then(response => {
          this.flash(this.$t('group.meeting_show_date_updated'), 'success');

          this.localMeeting.happened_at = response.data.data;
          this.editMode = false;
          this.loadingState = null;
        })
        .catch(error => {
          this.form.errors = error.response.data;
          this.loadingState = null;
        });
    },
  }
};

</script>
