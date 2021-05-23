<template>
  <div class="mb5">
    <div class="cf mw7 center mb2 fw5">
      <span class="mr1">
        🙃
      </span> {{ $t('dashboard.morale_title') }}

      <help :url="$page.props.help_links.employee_morale" :top="'2px'" />
    </div>

    <div class="cf mw7 center br3 mb3 bg-white box pa3">
      <!-- success message -->
      <div v-if="successMessage" class="tc">
        <p>🙌</p>
        <p>{{ $t('dashboard.morale_success_message') }}</p>
      </div>

      <!-- morale already logged -->
      <div v-if="localMorale.has_logged_morale_today && !successMessage" class="tc">
        <p>🙌</p>
        <p>{{ $t('dashboard.morale_already_logged') }}</p>
      </div>

      <div v-if="! localMorale.has_logged_morale_today && !successMessage">
        <errors :errors="form.errors" />
        <div class="flex-ns justify-center mt3 mb3">
          <span class="btn mr3-ns mb0-ns mb2 dib-l db" data-cy="log-morale-bad" @click.prevent="store(1)">
            <span class="mr1">
              😡
            </span> {{ $t('dashboard.morale_emotion_bad') }}
          </span>
          <span class="btn mr3-ns mb0-ns mb2 dib-l db" data-cy="log-morale-normal" @click.prevent="store(2)">
            <span class="mr1">
              😌
            </span> {{ $t('dashboard.morale_emotion_normal') }}
          </span>
          <span class="btn dib-l db mb0-ns" data-cy="log-morale-good" @click.prevent="store(3)">
            <span class="mr1">
              🥳
            </span> {{ $t('dashboard.morale_emotion_good') }}
          </span>
        </div>
      </div>

      <p v-if="!localMorale.has_logged_morale_today && !successMessage" class="f7 mb0 silver">
        {{ $t('dashboard.morale_rules') }}
      </p>
    </div>
  </div>
</template>

<script>
import Errors from '@/Shared/Errors';
import Help from '@/Shared/Help';

export default {
  components: {
    Errors,
    Help,
  },

  props: {
    morale: {
      type: Object,
      default: null,
    },
  },

  data() {
    return {
      showEditor: false,
      form: {
        emotion: null,
        errors: [],
      },
      localMorale: null,
      successMessage: false,
    };
  },

  created: function() {
    this.localMorale = this.morale;
  },

  methods: {
    store(emotion) {
      this.successMessage = true;
      this.form.emotion = emotion;

      axios.post(`${this.$page.props.auth.company.id}/dashboard/morale`, this.form)
        .then(response => {
          this.localMorale.has_logged_morale_today = true;
        })
        .catch(error => {
          this.successMessage = false;
          this.form.errors = error.response.data;
        });
    },
  }
};
</script>
