<style lang="scss" scoped>
.participant-list {
  li:first-child:hover {
    border-top-left-radius: 10px;
    border-top-right-radius: 10px;
  }

  li:last-child {
    border-bottom: none;

    &:hover {
      border-bottom-left-radius: 10px;
      border-bottom-right-radius: 10px;
    }
  }
}

.participant-icon {
  width: 16px;
}

.ball-pulse {
  right: 8px;
  top: 10px;
  position: absolute;
}

.badge {
  padding: 1px 6px;
  border-radius: 4px;
  background-color: #e3e8ee;

  &.feedback {
    background-color: #cbf4c9;
  }
}
</style>

<template>
  <div class="ph3 pv4 bb bb-gray">
    <h3 class="mt0 fw5 f5">
      <span class="mr1">
        🤓
      </span> {{ $t('dashboard.job_opening_stage_participants_title') }}
    </h3>
    <p class="gray f6 mt0 lh-copy">{{ $t('dashboard.job_opening_stage_participants_help') }}</p>

    <!-- list of participants -->
    <ul class="pl0 ma0 ba bb-gray participant-list br3">
      <li v-for="participant in localParticipants" :key="participant.id" class="ph2 pv3 list bb bb-gray bb-gray-hover flex justify-between">
        <small-name-and-avatar
          v-if="participant.id"
          :name="participant.name"
          :avatar="participant.avatar"
          :url="participant.url"
          :top="'0px'"
          :margin-between-name-avatar="'29px'"
        />

        <!-- case the employee doesn't exist anymore -->
        <span v-if="!participant.id">
          {{ participant.name }}
        </span>
        <div>
          <span v-if="participant.participated" class="badge f7 feedback">{{ $t('dashboard.job_opening_stage_participants_feedback') }}</span>
          <span v-else class="badge f7">{{ $t('dashboard.job_opening_stage_participants_no_feedback') }}</span>

          <!-- remove button -->
          <ul class="list pl2 di f7 mv0">
            <li v-if="idToDelete == participant.id" class="di">
              {{ $t('app.sure') }}
              <a class="c-delete mr1 pointer" @click.prevent="remove(participant)">
                {{ $t('app.yes') }}
              </a>
              <a class="pointer" @click.prevent="idToDelete = 0">
                {{ $t('app.no') }}
              </a>
            </li>
            <li v-else class="di">
              <a class="bb b--dotted bt-0 bl-0 br-0 pointer c-delete" @click.prevent="idToDelete = participant.id">
                {{ $t('app.remove') }}
              </a>
            </li>
          </ul>
        </div>
      </li>

      <!-- add a new participant cta -->
      <li v-if="!modal" class="ph2 pv3 list bb bb-gray bb-gray-hover tc">
        <a class="bb b--dotted bt-0 bl-0 br-0 pointer f6" @click="showSearch()">{{ $t('dashboard.job_opening_stage_participants_cta') }}</a>
      </li>

      <!-- modal to add -->
      <li v-if="modal" class="list ph2 pv3">
        <div>
          <form @submit.prevent="search">
            <div class="relative">
              <div class="relative">
                <input id="search" ref="search" v-model="form.searchTerm" type="text" name="search"
                       :placeholder="$t('employee.hierarchy_search_placeholder')" class="br2 f5 w-100 ba b--black-40 pa2 outline-0" required data-cy="search-manager"
                       @keyup="search" @keydown.esc="hideSearch()"
                />
                <ball-pulse-loader v-if="processingSearch" color="#5c7575" size="7px" />
              </div>
            </div>
          </form>

          <!-- search results -->
          <ul v-show="potentialParticipants.length > 0" class="list pl0 ba bb-gray bb-gray-hover">
            <li v-for="employee in potentialParticipants" :key="employee.id" class="relative pa2 bb bb-gray">
              {{ employee.name }}
              <a href="" class="fr f6" @click.prevent="add(employee)">{{ $t('app.add') }}</a>
            </li>
          </ul>

          <!-- no results found -->
          <ul v-show="potentialParticipants.length == 0 && form.searchTerm" class="list pl0 ba bb-gray bb-gray-hover">
            <li class="relative pa2 bb bb-gray">
              {{ $t('team.members_no_results') }}
            </li>
          </ul>
        </div>
      </li>
    </ul>
  </div>
</template>

<script>
import SmallNameAndAvatar from '@/Shared/SmallNameAndAvatar';
import BallPulseLoader from 'vue-loaders/dist/loaders/ball-pulse';

export default {
  components: {
    SmallNameAndAvatar,
    'ball-pulse-loader': BallPulseLoader.component,
  },

  props: {
    jobOpeningId: {
      type: Number,
      default: null,
    },
    candidateId: {
      type: Number,
      default: null,
    },
    stageId: {
      type: Number,
      default: null,
    },
    participants: {
      type: Object,
      default: null,
    },
  },

  data() {
    return {
      modal: false,
      idToDelete: 0,
      form: {
        employeeId: 0,
        searchTerm: '',
        errors: [],
      },
      potentialParticipants: [],
      localParticipants: [],
      loadingStateReject: '',
      processingSearch: false,
    };
  },

  mounted() {
    this.localParticipants = this.participants;

    if (localStorage.success) {
      this.flash(localStorage.success, 'success');
      localStorage.removeItem('success');
    }
  },

  methods: {
    showSearch() {
      this.modal = true;

      this.$nextTick(() => {
        this.$refs.search.focus();
      });
    },

    hideSearch() {
      this.modal = false;
      this.form.searchTerm = '';
    },

    search: _.debounce(
      function() {

        if (this.form.searchTerm != '') {
          this.processingSearch = true;

          axios.post(`${this.$page.props.auth.company.id}/recruiting/job-openings/${this.jobOpeningId}/candidates/${this.candidateId}/stages/${this.stageId}/searchParticipants`, this.form)
            .then(response => {
              this.potentialParticipants = response.data.data;
              this.processingSearch = false;
            })
            .catch(error => {
              this.form.errors = error.response.data;
              this.processingSearch = false;
            });
        }
      }, 500),

    add(employee) {
      this.form.employeeId = employee.id;

      axios.post(`${this.$page.props.auth.company.id}/recruiting/job-openings/${this.jobOpeningId}/candidates/${this.candidateId}/stages/${this.stageId}/assignParticipant`, this.form)
        .then(response => {
          this.flash(this.$t('dashboard.job_opening_stage_participants_success'), 'success');

          this.localParticipants.push(response.data.data);
          this.potentialParticipants = [];
          this.form.searchTerm = null;
          this.form.employeeId = 0;
          this.modal = false;
        })
        .catch(error => {
          this.form.errors = error.response.data;
        });
    },

    remove(employee) {
      axios.delete(`${this.$page.props.auth.company.id}/recruiting/job-openings/${this.jobOpeningId}/candidates/${this.candidateId}/stages/${this.stageId}/participants/${employee.participant_id}`)
        .then(response => {
          this.flash(this.$t('dashboard.job_opening_stage_participants_remove_success'), 'success');

          var id = this.localParticipants.findIndex(x => x.id == employee.id);
          this.localParticipants.splice(id, 1);

          this.idToDelete = 0;
        })
        .catch(error => {
          this.form.errors = error.response.data;
        });
    },
  }
};

</script>
