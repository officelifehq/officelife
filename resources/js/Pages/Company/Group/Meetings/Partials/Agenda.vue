<style lang="scss" scoped>
.bg-gray {
  background-color: #f5f5f5;
}

.decisions {
  background-color: #f6f8ff;
}

.dot {
  background-color: #06B6D4;
  width: 24px;
  color: #fff;
  height: 24px;
  padding: 3px 10px 5px 8px;
  top: 16px;
}
</style>

<template>
  <div>
    <div class="db fw5 mb2 flex justify-between items-center">
      <span>
        <span class="mr1">
          🎒
        </span> Agenda of the meeting

        <help :url="$page.props.help_links.project_tasks" :top="'3px'" />
      </span>
    </div>

    <ul class="list pl0 ma0 relative">
      <!-- list of agenda items -->
      <li v-for="agendaItem in localAgenda" :key="agendaItem.id" class="flex relative mb3">
        <div class="dot br-100 mr3 relative">
          {{ agendaItem.position }}
        </div>

        <!-- agenda item -->
        <div class="w-100">
          <div class="bg-white box pa3 w-100">
            <ul class="list pl0 mb0">
              <!-- summary -->
              <li v-if="agendaItemEditedId != agendaItem.id" class="di mr2 fw4 f4 pointer" @click="showEditSummary(agendaItem)">{{ agendaItem.summary }}</li>

              <!-- edit summary -->
              <li v-if="editSummaryMode && agendaItemEditedId == agendaItem.id">
                <form @submit.prevent="editSummary(agendaItem)">
                  <text-input :id="'summary'"
                              :ref="'summary' + agendaItem.id"
                              v-model="form.summary"
                              :name="'summary'"
                              :errors="$page.props.errors.summary"
                              :required="true"
                              @esc-key-pressed="hideEditAgendaItem()"
                  />
                </form>
              </li>

              <!-- add description -->
              <li v-if="!agendaItem.description" class="di"><a class="bb b--dotted mr2 bt-0 bl-0 br-0 pointer f7">Add more details</a></li>
            </ul>

            <!-- description -->
            <div v-if="agendaItem.description">
              <div class="db parsed-content mb3" v-html="agendaItem.description"></div>
              <span class="di"><a class="bb b--dotted mr2 bt-0 bl-0 br-0 pointer f7">Edit details</a></span>
            </div>
          </div>

          <!-- decisions -->
          <div class="decisions pa3 br3">
            <ul class="pa0 list mb3">
              <li class="mb3">
                <span class="db mb2 f7 fw6">👉 Decision #2</span>
                <span>We will focus on making the most money for sure</span>
              </li>
              <li>
                <span class="db mb2 f7 fw6">👉 Decision #2</span>
                <span>We will focus on making the most money for sure</span>
              </li>
            </ul>
            <span class="db f6">
              <a class="bb b--dotted bt-0 bl-0 br-0 pointer di">Add decision or follow-up</a>
            </span>
          </div>
        </div>
      </li>

      <!-- link to add a new agenda item -->
      <li v-if="! addAgendaItemMode" class="mt3 flex relative new-item relative">
        <a class="btn" @click.prevent="showAddAgendaItem()">Add new agenda item</a>
      </li>

      <!-- Modal - Add agenda item -->
      <li v-if="addAgendaItemMode" class="bg-white box pa3 bg-gray new-item relative">
        <form @submit.prevent="storeAgendaItem()">
          <!-- agenda item title + checkbox -->
          <div class="">
            <text-input :id="'summary'"
                        :ref="'summary'"
                        v-model="form.summary"
                        :name="'summary'"
                        :errors="$page.props.errors.summary"
                        :label="'What should we talk about?'"
                        :required="true"
                        @esc-key-pressed="hideAddAgendaItem()"
            />
          </div>

          <ul class="ma0 mb3 list pa0">
            <li v-if="!addDescriptionMode" class="di mr2" @click="showAddDescription()"><a class="bb b--dotted bt-0 bl-0 br-0 pointer di">Add more details</a></li>
            <li v-if="!addPresenterMode" class="di" @click="showAddPresenter()"><a class="bb b--dotted bt-0 bl-0 br-0 pointer di">Add a presenter</a></li>
          </ul>

          <!-- description -->
          <div v-if="addDescriptionMode" class="lh-copy">
            <text-area :ref="'description'"
                       v-model="form.description"
                       :label="$t('account.company_news_new_content')"
                       :required="false"
                       :rows="10"
                       @esc-key-pressed="hideAddDescription()"
            />
          </div>

          <!-- add presenter -->
          <div v-if="addPresenterMode" class="cf mb2">
            <select-box v-model="form.presented_by_id"
                        :options="members"
                        :errors="$page.props.errors.presented_by_id"
                        :label="'Who will present?'"
                        :placeholder="$t('app.choose_value')"
                        :value="form.presented_by_id"
            />
          </div>

          <!-- Actions -->
          <div class="flex justify-between">
            <div>
              <loading-button :class="'btn add w-auto-ns w-100 mb2 pv2 ph3 mr2'" :state="loadingState" :text="$t('app.update')" />
              <a class="btn dib tc w-auto-ns w-100 mb2 pv2 ph3" @click.prevent="hideAddAgendaItem()">{{ $t('app.cancel') }}</a>
            </div>
          </div>
        </form>
      </li>
    </ul>
  </div>
</template>

<script>
import TextInput from '@/Shared/TextInput';
import TextArea from '@/Shared/TextArea';
import SelectBox from '@/Shared/Select';
import LoadingButton from '@/Shared/LoadingButton';

export default {
  components: {
    TextInput,
    TextArea,
    SelectBox,
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
    agenda: {
      type: Array,
      default: null,
    },
  },

  data() {
    return {
      loadingState: null,
      localAgenda: null,
      addAgendaItemMode: false,
      addDescriptionMode: false,
      addPresenterMode: false,
      editSummaryMode: false,
      agendaItemEditedId: 0,
      form: {
        summary: null,
        description: null,
        presented_by_id: null,
        errors: [],
      },
    };
  },

  created() {
    this.localAgenda = this.agenda;
  },

  methods: {
    showAddAgendaItem() {
      this.addAgendaItemMode = true;

      this.$nextTick(() => {
        this.$refs.summary.focus();
      });
    },

    hideAddAgendaItem() {
      this.addAgendaItemMode = false;
      this.clearForm();
      this.hideAddDescription();
      this.hideAddPresenter();
    },

    showEditSummary(agendaItem) {
      this.form.summary = agendaItem.summary;
      this.form.description = agendaItem.description;
      this.form.presented_by_id = agendaItem.presented_by_id;
      this.editSummaryMode = true;
      this.agendaItemEditedId = agendaItem.id;

      this.$nextTick(() => {
        this.$refs[`summary${agendaItem.id}`].focus();
      });
    },

    hideEditAgendaItem() {
      this.clearForm();
      this.editSummaryMode = false;
      this.agendaItemEditedId = 0;
    },

    showAddDescription() {
      this.addDescriptionMode = true;

      this.$nextTick(() => {
        this.$refs.description.focus();
      });
    },

    hideAddDescription() {
      this.addDescriptionMode = false;
      this.form.description = null;
    },

    showAddPresenter() {
      this.addPresenterMode = true;
    },

    hideAddPresenter() {
      this.addPresenterMode = false;
      this.form.presented_by_id = null;
    },

    clearForm() {
      this.form.summary = null;
      this.form.description = null;
      this.form.presented_by_id = null;
    },

    storeAgendaItem() {
      this.loadingState = 'loading';

      axios.post(`/${this.$page.props.auth.company.id}/company/groups/${this.groupId}/meetings/${this.meeting.meeting.id}/addAgendaItem`, this.form)
        .then(response => {
          this.flash(this.$t('project.members_index_add_success'), 'success');
          this.loadingState = null;
          this.clearForm();
          this.localAgenda.push(response.data.data);
        })
        .catch(error => {
          this.loadingState = null;
          this.form.errors = error.response.data;
        });
    },

    editSummary(agendaItem) {
      axios.post(`/${this.$page.props.auth.company.id}/company/groups/${this.groupId}/meetings/${this.meeting.meeting.id}/updateSummary/${agendaItem.id}`, this.form)
        .then(response => {
          this.clearForm();
          this.editSummaryMode = false;
          this.agendaItemEditedId = 0;

          this.localAgenda[this.localAgenda.findIndex(x => x.id === agendaItem.id)] = response.data.data;
        })
        .catch(error => {
          this.loadingState = null;
          this.form.errors = error.response.data;
        });
    }
  }
};

</script>
