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
  <div class="bg-white box mb4">
    <!-- participants -->
    <div class="pt3 pr3 pl3 bb-gray bb">
      <p class="silver f6 ma0 mb1">{{ $t('group.meeting_show_participants') }}</p>
      <p class="f7 gray mb3 lh-copy">{{ $t('group.meeting_show_participants_help') }}</p>

      <ul class="ma0 pa0 list">
        <li v-for="participant in localParticipants" :key="participant.id" :class="participant.attended ? 'present' : 'absent'" class="relative flex items-center mb3 pointer" @click="toggle(participant)">
          <svg class="check-icon mr2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
          </svg>
          <avatar :avatar="participant.avatar" :size="23" :class="'br-100 mr2'" />
          <span class="name f5 lh-copy">{{ participant.name }}</span>
        </li>
      </ul>
    </div>

    <!-- guests -->
    <div class="pa3">
      <p class="silver f6 ma0 mb1">{{ $t('group.meeting_show_guests') }}</p>
      <p class="f7 gray mb3 lh-copy">{{ $t('group.meeting_show_guests_help') }}</p>

      <ul class="ma0 pa0 list">
        <li v-for="guest in localGuests" :key="guest.id" :class="guest.attended ? 'present' : 'absent'" class="relative mb3 pointer flex items-center">
          <div class="flex items-center" @click="toggle(guest)">
            <svg v-if="!editMode" class="check-icon mr2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
              <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
            </svg>
            <avatar :avatar="guest.avatar" :size="23" :class="'br-100 mr2'" />
            <span class="name f5 lh-copy mr2">{{ guest.name }}</span>
          </div>

          <!-- delete guest -->
          <a v-if="editMode" class="bb b--dotted bt-0 bl-0 f7 br-0 pointer c-delete" @click.prevent="removeGuest(guest)">
            {{ $t('app.delete') }}
          </a>
        </li>

        <!-- add guest cta -->
        <li v-if="!addMode">
          <ul class="list pl0 ma0">
            <!-- Add guest link -->
            <li v-if="!editMode" class="di mr2"><a class="bb b--dotted bt-0 bl-0 br-0 pointer f6" @click="showAddMode()">+ {{ $t('group.meeting_show_participants_add_guest') }}</a></li>

            <!-- Edit guest link -->
            <li v-if="localGuests.length > 0 && !editMode" class="di mr2"><a class="bb b--dotted bt-0 bl-0 br-0 pointer f6" @click.prevent="showEditMode()">{{ $t('group.meeting_show_participants_edit_guests') }}</a></li>

            <!-- Exit edit guest mode -->
            <li v-if="editMode" class="di mr2" @click="editMode = false"><a class="bb b--dotted bt-0 bl-0 br-0 pointer f6">{{ $t('group.meeting_show_participants_exit_edit_guests') }}</a></li>
          </ul>
        </li>

        <!-- search -->
        <div v-if="addMode">
          <!-- search form -->
          <form class="relative" @submit.prevent="search">
            <text-input :id="'name'"
                        :ref="'search'"
                        v-model="form.searchTerm"
                        :name="'name'"
                        :errors="$page.props.errors.name"
                        :label="$t('group.meeting_show_participants_add_guest_input')"
                        :required="true"
                        @keyup="search"
                        @input="search"
                        @esc-key-pressed="addMode = false"
            />
            <ball-pulse-loader v-if="processingSearch" color="#5c7575" size="7px" />
          </form>

          <!-- search results -->
          <div class="pl0 list ma0">
            <ul v-if="potentialParticipants.length > 0" class="list ma0 pl0">
              <li v-for="participant in potentialParticipants" :key="participant.id" class="bb relative pv2 ph1 bb-gray bb-gray-hover">
                {{ participant.name }}

                <a class="absolute right-1 pointer" @click.prevent="assign(participant)">
                  {{ $t('app.choose') }}
                </a>
              </li>
            </ul>
          </div>
        </div>
      </ul>
    </div>
  </div>
</template>

<script>
import Avatar from '@/Shared/Avatar';
import TextInput from '@/Shared/TextInput';
import 'vue-loaders/dist/vue-loaders.css';
import BallPulseLoader from 'vue-loaders/dist/loaders/ball-pulse';

export default {
  components: {
    Avatar,
    TextInput,
    'ball-pulse-loader': BallPulseLoader.component,
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
      addMode: false,
      editMode: false,
      idToDelete: false,
      processingSearch: false,
      hasMadeASearch: false,
      potentialParticipants: [],
      localParticipants: null,
      localGuests: null,
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
    this.localGuests = this.meeting.guests;
  },

  mounted() {
    if (localStorage.success) {
      this.flash(localStorage.success, 'success');
      localStorage.removeItem('success');
    }
  },

  methods: {
    showAddMode() {
      this.addMode = true;

      this.$nextTick(() => {
        this.$refs['search'].$refs['input'].focus();
      });
    },

    showEditMode() {
      this.editMode = true;
    },

    toggle(member) {
      this.form.id = member.id;

      axios.post(`/${this.$page.props.auth.company.id}/company/groups/${this.groupId}/meetings/${this.meeting.meeting.id}/toggle`, this.form)
        .then(response => {
          if (! member.was_a_guest) {
            var id = this.localParticipants.findIndex(x => x.id === member.id);
            this.localParticipants[id].attended = ! this.localParticipants[id].attended;
          } else {
            var id = this.localGuests.findIndex(x => x.id === member.id);
            this.localGuests[id].attended = ! this.localGuests[id].attended;
          }

          this.flash(this.$t('app.saved'), 'success');
          this.form.id = null;
        })
        .catch(error => {
          console.log(error);
        });
    },

    search: _.debounce(
      function() {

        if (this.form.searchTerm != '') {
          this.hasMadeASearch = false;
          this.processingSearch = true;

          axios.post(`/${this.$page.props.auth.company.id}/company/groups/${this.groupId}/meetings/${this.meeting.meeting.id}/search`, this.form)
            .then(response => {
              this.potentialParticipants = response.data.data;
              this.processingSearch = false;
              this.hasMadeASearch = true;
            })
            .catch(error => {
              this.form.errors = error.response.data;
              this.processingSearch = false;
              this.hasMadeASearch = false;
            });
        }
      },
      500),

    assign(participant) {
      this.form.id = participant.id;

      axios.post(`/${this.$page.props.auth.company.id}/company/groups/${this.groupId}/meetings/${this.meeting.meeting.id}/add`, this.form)
        .then(response => {
          this.flash(this.$t('group.meeting_show_add_guest_success'), 'success');

          this.localGuests.push(response.data.data);
          this.addMode = false;
          this.potentialParticipants = [];
          this.form.searchTerm = '';
        })
        .catch(error => {
          this.form.errors = error.response.data;
        });
    },

    removeGuest(guest) {
      this.form.id = guest.id;

      axios.post(`/${this.$page.props.auth.company.id}/company/groups/${this.groupId}/meetings/${this.meeting.meeting.id}/remove`, this.form)
        .then(response => {
          this.flash(this.$t('group.meeting_show_remove_guest_success'), 'success');

          var removedGuestId = this.localGuests.findIndex(x => x.id === guest.id);
          this.localGuests.splice(removedGuestId, 1);
          this.form.id = null;

          // also, exit Edit mode if there are no guests anymore
          if (this.localGuests.length == 0) {
            this.editMode = false;
          }
        })
        .catch(error => {
          this.form.errors = error.response.data;
        });
    },
  }
};

</script>
