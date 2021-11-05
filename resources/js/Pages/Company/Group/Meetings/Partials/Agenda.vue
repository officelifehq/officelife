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

.small-avatar {
  top: -12px;
  background-color: #fff;
  padding: 4px;
  right: 14px;

  a {
    border-bottom: 0;
  }
}

.new-item div {
  width: 24px;
}
</style>

<template>
  <div>
    <div class="db fw5 mb3 flex justify-between items-center">
      <span>
        <span class="mr1">
          🎒
        </span> {{ $t('group.meeting_show_title') }}

        <help :url="$page.props.help_links.project_tasks" :top="'3px'" />
      </span>
    </div>

    <ul class="list pl0 ma0 relative">
      <!-- list of agenda items -->
      <li v-for="agendaItem in localAgenda" :key="agendaItem.id" class="flex relative mb3">
        <div class="dot br-100 mr3 relative">
          {{ agendaItem.position }}
        </div>

        <!---------------------- -->
        <!-- AGENDA ITEM -->
        <!---------------------- -->
        <div class="w-100">
          <div class="bg-white box pa3 w-100 relative">
            <!-- presented by -->
            <div v-if="agendaItem.presenter && agendaItemEditedId != agendaItem.id" class="absolute small-avatar box">
              <small-name-and-avatar
                :name="agendaItem.presenter.name"
                :avatar="agendaItem.presenter.avatar"
                :class="'gray bb-0'"
                :size="'18px'"
                :top="'0px'"
                :margin-between-name-avatar="'25px'"
                :url="agendaItem.presenter.url"
              />
            </div>

            <ul class="list pl0 mb0">
              <!-- summary -->
              <li v-if="agendaItemEditedId != agendaItem.id" class="di mr2 fw4 f4 pointer" @click="showEditMode(agendaItem)">{{ agendaItem.summary }}</li>

              <!-- edit agenda item -->
              <li v-if="editAgendaItemMode && agendaItemEditedId == agendaItem.id">
                <form @submit.prevent="editAgendaItem(agendaItem)">
                  <!-- agenda item title + checkbox -->
                  <div class="">
                    <text-input :id="'summary'"
                                :ref="'summary' + agendaItem.id"
                                v-model="form.summary"
                                :name="'summary'"
                                :errors="$page.props.errors.summary"
                                :required="true"
                                @esc-key-pressed="hideEditMode()"
                    />
                  </div>

                  <ul class="ma0 mb3 list pa0">
                    <li v-if="!agendaItem.description" class="di mr2" @click="showEditDescriptionMode(agendaItem)"><a class="bb b--dotted bt-0 bl-0 br-0 pointer di">{{ $t('group.meeting_show_add_agenda_item_details') }}</a></li>
                    <li v-if="!agendaItem.presenter" class="di" @click="showEditPresenterMode(agendaItem)"><a class="bb b--dotted bt-0 bl-0 br-0 pointer di">{{ $t('group.meeting_show_add_agenda_item_presenter') }}</a></li>
                  </ul>

                  <!-- description -->
                  <div v-if="editDescriptionMode && agendaItemEditedId == agendaItem.id" class="lh-copy">
                    <text-area :ref="'description' + agendaItem.id"
                               v-model="form.description"
                               :label="$t('group.meeting_show_add_agenda_item_description')"
                               :required="false"
                               :rows="10"
                               @esc-key-pressed="hideEditMode()"
                    />
                  </div>

                  <!-- edit presenter -->
                  <div v-if="editPresenterMode && agendaItemEditedId == agendaItem.id" class="cf mb2">
                    <label class="db mb-2">{{ $t('group.meeting_show_add_agenda_item_presenter_title') }}</label>
                    <a-select
                      v-model:value="form.presented_by_id"
                      :placeholder="$t('app.choose_value')"
                      :options="potentialPresenters"
                      show-search
                      style="width: 200px"
                      option-filter-prop="label"
                    />
                  </div>

                  <!-- Actions -->
                  <div class="flex justify-between">
                    <div>
                      <loading-button :class="'btn add w-auto-ns w-100 mb2 pv2 ph3 mr2'" :state="loadingState" :text="$t('app.update')" />
                      <a class="btn dib tc w-auto-ns w-100 mb2 pv2 ph3" @click.prevent="hideEditMode()">{{ $t('app.cancel') }}</a>
                    </div>

                    <a :class="'btn destroy w-auto-ns w-100 mb2 pv2 ph3 mr2'" @click.prevent="destroyAgendaItem(agendaItem)">{{ $t('app.delete') }}</a>
                  </div>
                </form>
              </li>
            </ul>

            <!-- description -->
            <div v-if="agendaItem.description && agendaItemEditedId != agendaItem.id" class="mt2">
              <div class="db parsed-content mb2" v-html="agendaItem.description"></div>

              <span class="di"><a class="bb b--dotted mr2 bt-0 bl-0 br-0 pointer f7" @click="showEditMode(agendaItem)">{{ $t('group.meeting_show_edit_details') }}</a></span>
            </div>
          </div>

          <!---------------------- -->
          <!-- DECISIONS -->
          <!---------------------- -->
          <div class="decisions pa3 br3">
            <div v-if="agendaItem.decisions">
              <ul v-if="agendaItem.decisions.length > 0" class="pa0 list mb3">
                <li v-for="decision in agendaItem.decisions" :key="decision.id" class="mb3">
                  <!-- title -->
                  <span class="db mb2 f7 fw6 gray"><span class="mr1">👉</span> {{ $t('group.meeting_show_decision_title') }}</span>

                  <!-- description -->
                  <span class="parsed-content mr3" v-html="decision.description"></span>

                  <!-- remove decision -->
                  <span v-if="editDecisionMode && decisionForAgendaItemId == agendaItem.id" class="f6 gray bb b--dotted bt-0 bl-0 br-0 pointer di c-delete" @click="destroyDecision(agendaItem, decision)">{{ $t('app.remove') }}</span>
                </li>
              </ul>
            </div>

            <!-- add decision -->
            <div v-if="addDecisionMode && decisionForAgendaItemId == agendaItem.id" class="bg-white box pa3 bg-gray relative">
              <form @submit.prevent="storeDecision(agendaItem)">
                <div class="lh-copy">
                  <text-area :ref="'description'"
                             v-model="form.description"
                             :label="$t('group.meeting_show_add_decision_label')"
                             :required="false"
                             :rows="3"
                             @esc-key-pressed="hideAddDescription()"
                  />
                </div>

                <!-- Actions -->
                <div class="flex justify-between">
                  <div>
                    <loading-button :class="'btn add w-auto-ns w-100 mb2 pv2 ph3 mr2'" :state="loadingState" :text="$t('app.save')" />
                    <a class="btn dib tc w-auto-ns w-100 mb2 pv2 ph3" @click.prevent="hideAddDecision()">{{ $t('app.cancel') }}</a>
                  </div>
                </div>
              </form>
            </div>

            <!-- CTA for decisions -->
            <ul class="db f7 ma0 pl0 list">
              <!-- Add decision -->
              <li v-if="decisionForAgendaItemId != agendaItem.id" class="di mr3">
                <a class="bb b--dotted bt-0 bl-0 br-0 pointer di" @click="showAddDecision(agendaItem)">{{ $t('group.meeting_show_add_decision') }}</a>
              </li>

              <!-- Edit decisions -->
              <li v-if="decisionForAgendaItemId != agendaItem.id && agendaItem.decisions" class="di">
                <a v-if="agendaItem.decisions.length > 0" class="bb b--dotted bt-0 bl-0 br-0 pointer di" @click="showEditDecisionMode(agendaItem)">{{ $t('group.meeting_show_edit_decision_cta') }}</a>
              </li>

              <!-- Exit edit decision mode -->
              <li v-if="editDecisionMode && decisionForAgendaItemId == agendaItem.id" class="di">
                <a class="bb b--dotted bt-0 bl-0 br-0 pointer di" @click="hideEditDecisionMode">{{ $t('group.meeting_show_edit_decision_exit') }}</a>
              </li>
            </ul>
          </div>
        </div>
      </li>

      <!-- link to add a new agenda item -->
      <li v-if="! addAgendaItemMode && localAgenda.length != 0" class="mt3 flex relative new-item relative">
        <div class="mr3"></div>
        <a class="btn" @click.prevent="showAddAgendaItem()">{{ $t('group.meeting_show_add_agenda_item_cta') }}</a>
      </li>

      <!-- blank state -->
      <div v-if="localAgenda.length == 0" class="bg-white box pa3 tc mb2">
        <h3 class="fw4 f5">
          {{ $t('group.meeting_show_blank') }}
        </h3>
        <img loading="lazy" src="/img/streamline-icon-cyclist-1-4@140x140.png" width="140" height="140" alt="people hanging out"
             class="di-ns dn top-1 left-1"
        />
        <div class="db mt3 mb3">
          <a class="btn " @click.prevent="showAddAgendaItem()">{{ $t('group.meeting_show_add_agenda_item_cta') }}</a>
        </div>
      </div>

      <!-- Modal - Add agenda item -->
      <li v-if="addAgendaItemMode" class="bg-white box pa3 bg-gray relative">
        <form @submit.prevent="storeAgendaItem()">
          <!-- agenda item title + checkbox -->
          <div class="">
            <text-input :id="'summary'"
                        :ref="'summary'"
                        v-model="form.summary"
                        :name="'summary'"
                        :errors="$page.props.errors.summary"
                        :label="$t('group.meeting_show_add_title')"
                        :required="true"
                        @esc-key-pressed="hideAddAgendaItem()"
            />
          </div>

          <ul class="ma0 mb3 list pa0">
            <li v-if="!addDescriptionMode" class="di mr2" @click="showAddDescription()"><a class="bb b--dotted bt-0 bl-0 br-0 pointer di">{{ $t('group.meeting_show_add_agenda_item_details') }}</a></li>
            <li v-if="!addPresenterMode" class="di" @click="showAddPresenter()"><a class="bb b--dotted bt-0 bl-0 br-0 pointer di">{{ $t('group.meeting_show_add_agenda_item_presenter') }}</a></li>
          </ul>

          <!-- description -->
          <div v-if="addDescriptionMode" class="lh-copy">
            <text-area :ref="'description'"
                       v-model="form.description"
                       :label="$t('group.meeting_show_add_agenda_item_description')"
                       :required="false"
                       :rows="10"
                       @esc-key-pressed="hideAddDescription()"
            />
          </div>

          <!-- add presenter -->
          <div v-if="addPresenterMode" class="cf mb2">
            <label class="db mb-2">{{ $t('group.meeting_show_add_agenda_item_presenter_title') }}</label>
            <a-select
              v-model:value="form.presented_by_id"
              :placeholder="$t('app.choose_value')"
              :options="potentialPresenters"
              show-search
              style="width: 200px"
              option-filter-prop="label"
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
import LoadingButton from '@/Shared/LoadingButton';
import SmallNameAndAvatar from '@/Shared/SmallNameAndAvatar';

export default {
  components: {
    TextInput,
    TextArea,
    LoadingButton,
    SmallNameAndAvatar,
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
      potentialPresenters: [],
      addAgendaItemMode: false,
      addDescriptionMode: false,
      addPresenterMode: false,
      addDecisionMode: false,
      editDescriptionMode: false,
      editPresenterMode: false,
      editAgendaItemMode: false,
      editDecisionMode: false,
      agendaItemEditedId: 0,
      decisionForAgendaItemId: 0,
      decisionEditedId: 0,
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
    // when adding a new agenda item
    showAddAgendaItem() {
      this.addAgendaItemMode = true;
      this.form.presented_by_id = null;

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
      this.loadPotentialPresenters();
    },

    hideAddPresenter() {
      this.addPresenterMode = false;
      this.form.presented_by_id = null;
    },

    showAddDecision(agendaItem) {
      this.addDecisionMode = true;
      this.decisionForAgendaItemId = agendaItem.id;
    },

    hideAddDecision() {
      this.addDecisionMode = false;
      this.decisionForAgendaItemId = 0;
    },

    // when editing an existing agenda item
    showEditMode(agendaItem) {
      this.agendaItemEditedId = agendaItem.id;
      this.form.summary = agendaItem.summary;
      this.form.description = agendaItem.description;
      this.form.presented_by_id = agendaItem.presenter.name;
      this.editAgendaItemMode = true;
      this.editDescriptionMode = false;
      this.editPresenterMode = false;

      if (agendaItem.description) {
        this.editDescriptionMode = true;
      }

      if (agendaItem.presenter) {
        this.editPresenterMode = true;
        this.loadPotentialPresenters();
      }

      this.$nextTick(() => {
        this.$refs[`summary${agendaItem.id}`].focus();
      });
    },

    hideEditMode() {
      this.clearForm();
      this.editAgendaItemMode = false;
      this.agendaItemEditedId = 0;
      this.editDescriptionMode = false;
      this.editPresenterMode = false;
    },

    showEditDescriptionMode(agendaItem) {
      this.editDescriptionMode = true;

      this.$nextTick(() => {
        this.$refs[`description${agendaItem.id}`].focus();
      });
    },

    showEditPresenterMode() {
      this.editPresenterMode = true;
      this.loadPotentialPresenters();
    },

    showEditDecisionMode(agendaItem) {
      this.editDecisionMode = true;
      this.decisionForAgendaItemId = agendaItem.id;
    },

    hideEditDecisionMode() {
      this.editDecisionMode = false;
      this.decisionForAgendaItemId = 0;
    },

    clearForm() {
      this.form.summary = null;
      this.form.description = null;
      this.form.presented_by_id = null;
    },

    loadPotentialPresenters() {
      axios.get(`/${this.$page.props.auth.company.id}/company/groups/${this.groupId}/meetings/${this.meeting.meeting.id}/presenters`)
        .then(response => {
          this.potentialPresenters = response.data.data;
        })
        .catch(error => {
          this.loadingState = null;
          this.form.errors = error.response.data;
        });
    },

    storeAgendaItem() {
      this.loadingState = 'loading';

      axios.post(`/${this.$page.props.auth.company.id}/company/groups/${this.groupId}/meetings/${this.meeting.meeting.id}/addAgendaItem`, this.form)
        .then(response => {
          this.flash(this.$t('project.members_index_add_success'), 'success');
          this.loadingState = null;
          this.hideAddAgendaItem();
          this.localAgenda.push(response.data.data);
        })
        .catch(error => {
          this.loadingState = null;
          this.form.errors = error.response.data;
        });
    },

    editAgendaItem(agendaItem) {
      axios.post(`/${this.$page.props.auth.company.id}/company/groups/${this.groupId}/meetings/${this.meeting.meeting.id}/updateAgendaItem/${agendaItem.id}`, this.form)
        .then(response => {
          this.editAgendaItemMode = false;
          this.agendaItemEditedId = 0;
          this.clearForm();

          this.localAgenda[this.localAgenda.findIndex(x => x.id === agendaItem.id)] = response.data.data;
        })
        .catch(error => {
          this.loadingState = null;
          this.form.errors = error.response.data;
        });
    },

    destroyAgendaItem(agendaItem) {
      axios.delete(`/${this.$page.props.auth.company.id}/company/groups/${this.groupId}/meetings/${this.meeting.meeting.id}/agendaItem/${agendaItem.id}`)
        .then(response => {
          this.editAgendaItemMode = false;
          this.agendaItemEditedId = 0;
          this.clearForm();

          var removedAgendaItemId = this.localAgenda.findIndex(x => x.id === agendaItem.id);
          this.localAgenda.splice(removedAgendaItemId, 1);

          // now, reset all the positions
          // there has to be a better way to do this :-D
          var numberOfEntries = this.localAgenda.length;
          var counter = 0;
          for (counter = 0; counter < numberOfEntries; counter++) {
            if (this.localAgenda[counter].position > agendaItem.position) {
              this.localAgenda[counter].position = this.localAgenda[counter].position - 1;
            }
          }
        })
        .catch(error => {
          this.loadingState = null;
          this.form.errors = error.response.data;
        });
    },

    storeDecision(agendaItem) {
      this.loadingState = 'loading';

      axios.post(`/${this.$page.props.auth.company.id}/company/groups/${this.groupId}/meetings/${this.meeting.meeting.id}/agendaItem/${agendaItem.id}/addDecision`, this.form)
        .then(response => {
          this.loadingState = null;
          this.hideAddDecision();
          this.form.description = null;

          this.localAgenda[this.localAgenda.findIndex(x => x.id === agendaItem.id)].decisions.push(response.data.data);
        })
        .catch(error => {
          this.loadingState = null;
          this.form.errors = error.response.data;
        });
    },

    destroyDecision(agendaItem, decision) {
      axios.delete(`/${this.$page.props.auth.company.id}/company/groups/${this.groupId}/meetings/${this.meeting.meeting.id}/agendaItem/${agendaItem.id}/decisions/${decision.id}`)
        .then(response => {
          var agendaItemId = this.localAgenda.findIndex(x => x.id === agendaItem.id);
          var decisionId = this.localAgenda[agendaItemId].decisions.findIndex(x => x.id === decision.id);
          this.localAgenda[agendaItemId].decisions.splice(decisionId, 1);

          if (this.localAgenda[agendaItemId].decisions.length == 0) {
            this.hideEditDecisionMode();
          }
        })
        .catch(error => {
          this.loadingState = null;
          this.form.errors = error.response.data;
        });
    },
  }
};

</script>
