<template>
  <div class="mb5">
    <div class="cf mw7 center mb2 fw5">
      🙃 {{ $t('dashboard.morale_title') }}
    </div>

    <div class="cf mw7 center br3 mb3 bg-white box">
      <div class="pa3">
        <!-- success message -->
        <div v-if="successMessage" class="tc">
          <p>🙌</p>
          <p>{{ $t('dashboard.morale_success_message') }}</p>
        </div>

        <!-- morale already logged -->
        <div v-if="updatedEmployee.has_logged_morale_today && !successMessage" class="tc">
          <p>🙌</p>
          <p>{{ $t('dashboard.morale_already_logged') }}</p>
        </div>

        <div v-if="! updatedEmployee.has_logged_morale_today && !successMessage">
          <errors :errors="form.errors" />
          <div class="flex-ns justify-center mt3 mb3">
            <span class="btn mr3-ns mb0-ns mb2 dib-l db" data-cy="log-morale-bad" @click.prevent="store(1)">
              😡 {{ $t('dashboard.morale_emotion_bad') }}
            </span>
            <span class="btn mr3-ns mb0-ns mb2 dib-l db" data-cy="log-morale-normal" @click.prevent="store(2)">
              😌 {{ $t('dashboard.morale_emotion_normal') }}
            </span>
            <span class="btn dib-l db mb0-ns" data-cy="log-morale-good" @click.prevent="store(3)">
              🥳 {{ $t('dashboard.morale_emotion_good') }}
            </span>
          </div>
        </div>

        <p v-if="!updatedEmployee.has_logged_morale_today && !successMessage" class="f7 mb0 silver">
          {{ $t('dashboard.morale_rules') }}
        </p>
      </div>
    </div>
  </div>
</template>

<script>
import Errors from '@/Shared/Errors';

export default {
  components: {
    Errors,
  },

  props: {
    employee: {
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
      updatedEmployee: null,
      successMessage: false,
    };
  },

  created: function() {
    this.updatedEmployee = this.employee;
  },

  methods: {
    store(emotion) {
      this.successMessage = true;
      this.form.emotion = emotion;

      axios.post('/' + this.$page.auth.company.id + '/dashboard/morale', this.form)
        .then(response => {
          this.updatedEmployee.has_logged_morale_today = true;
        })
        .catch(error => {
          this.successMessage = false;
          this.form.errors = error.response.data.errors;
        });
    },
  }
};
</script>
