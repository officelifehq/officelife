<style lang="scss" scoped>
.present {
  svg {
    color: #78b382;
  }

  .name {
    color: #4d4d4f;
  }

   img {
     filter: grayscale(0%);
   }
}

.absent {
  svg {
    color: #cecfce;
  }

  .name {
    color: #a4a4a8;
  }

  img {
     filter: grayscale(100%);
   }
}

.check-icon {
  width: 20px;
}
</style>

<template>
  <div class="bg-white box mb4 pa3">
    <p class="silver f6 ma0 mb1">{{ $t('group.meeting_show_participants') }}</p>
    <p class="f7 gray mb4">{{ $t('group.meeting_show_participants_help') }}</p>

    <ul class="ma0 pa0 list">
      <li v-for="participant in localParticipants" :key="participant.id" :class="participant.attended ? 'present' : 'absent'" class="relative flex items-center mb3 pointer" @click="toggle(participant)">
        <svg class="check-icon mr2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
          <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
        </svg>
        <avatar :avatar="participant.avatar" :size="23" :classes="'br-100 mr2'" />
        <span class="name f5">{{ participant.name }}</span>
      </li>

      <!-- add guest cta -->
      <li><a class="bb b--dotted bt-0 bl-0 br-0 pointer f6">+ Add guest</a></li>

      <!-- search form -->
      <form class="relative" @submit.prevent="search">
        <text-input :id="'name'"
                    v-model="form.searchTerm"
                    :name="'name'"
                    :datacy="'project-lead-search'"
                    :errors="$page.props.errors.name"
                    :label="$t('project.create_input_project_lead')"
                    :required="false"
                    @keyup="search"
                    @input="search"
                    @esc-key-pressed="showAssignProjectLead = false"
        />
        <ball-pulse-loader v-if="processingSearch" color="#5c7575" size="7px" />
      </form>
    </ul>
  </div>
</template>

<script>
import Avatar from '@/Shared/Avatar';

export default {
  components: {
    Avatar,
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
      localParticipants: null,
      form: {
        searchTerm: null,
        id: null,
        role: null,
        errors: [],
      },
    };
  },

  created() {
    this.localParticipants = this.meeting.participants;
  },

  mounted() {
    if (localStorage.success) {
      flash(localStorage.success, 'success');
      localStorage.removeItem('success');
    }
  },

  methods: {
    toggle(member) {
      this.form.id = member.id;

      axios.post(`/${this.$page.props.auth.company.id}/company/groups/${this.groupId}/meetings/${this.meeting.id}/toggle`, this.form)
        .then(response => {
          flash(this.$t('app.saved'), 'success');
          this.form.id = null;
          var id = this.localParticipants.findIndex(x => x.id === member.id);
          this.localParticipants[id].attended = ! this.localParticipants[id].attended;
        })
        .catch(error => {
          console.log(error);
        });
    },
  }
};

</script>
