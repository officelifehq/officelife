<style lang="scss" scoped>
.reference-number {
  padding: 2px 6px;
  border-radius: 6px;
  top: -4px;
  background-color: #ddf4ff;
  color: #0969da;
}

.status-active {
  background-color: #dcf7ee;

  .dot {
    background-color: #00b760;
  }
}

.status-inactive {
  background-color: #ffe9e3;

  .dot {
    background-color: #ff3400;
  }
}

.dot {
  height: 7px;
  top: 3px;
}
</style>

<template>
  <div class="mb4 bg-white box pa3">
    <div class="flex justify-between items-center">
      <h2 class="mr2 mt0 mb2 fw4">
        {{ localJobOpening.title }}

        <span v-if="localJobOpening.reference_number" class="reference-number f7 code fw4">
          {{ localJobOpening.reference_number }}
        </span>
      </h2>
      <div v-if="!localJobOpening.fulfilled">
        <p v-if="isActive" class="status-active f7 dib ph2 pv2 br3 ma0">
          <span class="br3 f7 fw3 ph2 pv1 dib relative mr1 dot"></span>
          {{ $t('dashboard.job_opening_show_active') }}
        </p>
        <p v-else class="status-inactive f7 dib ph2 pv2 br3 ma0">
          <span class="br3 f7 fw3 ph2 pv1 dib relative mr1 dot"></span>
          {{ $t('dashboard.job_opening_show_inactive') }}
        </p>
      </div>
    </div>

    <ul class="ma0 pl0 list f7 gray">
      <!-- show public version -->
      <li class="di mr3">
        <a :href="localJobOpening.url_public_view" target="_blank">{{ $t('dashboard.job_opening_show_view_public_version') }}</a>
      </li>

      <!-- active toggle -->
      <li v-if="isActive && !localJobOpening.fulfilled" class="di mr3">
        <a class="bb b--dotted bt-0 bl-0 br-0 pointer" @click.prevent="toggle()">
          {{ $t('dashboard.job_opening_show_toggle_inactive') }}
        </a>
      </li>
      <li v-if="!isActive && !localJobOpening.fulfilled" class="di mr3">
        <a class="bb b--dotted bt-0 bl-0 br-0 pointer" @click.prevent="toggle()">
          {{ $t('dashboard.job_opening_show_toggle_active') }}
        </a>
      </li>

      <!-- edit -->
      <li class="di mr3">
        <inertia-link :href="localJobOpening.url_edit">{{ $t('app.edit') }}</inertia-link>
      </li>

      <!-- delete -->
      <li v-if="deleteMode" class="di">
        {{ $t('app.sure') }}
        <a class="c-delete mr1 pointer" @click.prevent="destroy()">
          {{ $t('app.yes') }}
        </a>
        <a class="pointer" @click.prevent="deleteMode = false">
          {{ $t('app.no') }}
        </a>
      </li>
      <li v-if="!deleteMode" class="di">
        <a class="bb b--dotted bt-0 bl-0 br-0 pointer c-delete" @click.prevent="deleteMode = true">
          {{ $t('app.delete') }}
        </a>
      </li>
    </ul>
  </div>
</template>

<script>
export default {
  props: {
    jobOpening: {
      type: Object,
      default: null,
    },
  },

  data() {
    return {
      localJobOpening: null,
      isActive: false,
      deleteMode: false,
      form: {
        name: null,
        errors: [],
      },
    };
  },

  created() {
    this.localJobOpening = this.jobOpening;
    this.isActive = this.jobOpening.active;
  },

  methods: {
    toggle() {
      this.isActive = !this.isActive;

      axios.post('/' + this.$page.props.auth.company.id + '/recruiting/job-openings/' + this.jobOpening.id + '/toggle', this.form)
        .then(response => {
        })
        .catch(error => {
          this.form.errors = error.response.data;
        });
    },

    destroy() {
      axios.delete('/' + this.$page.props.auth.company.id + '/recruiting/job-openings/' + this.jobOpening.id)
        .then(response => {
          localStorage.success = this.$t('dashboard.job_opening_show_delete_success');
          this.$inertia.visit(response.data.data.url);
        })
        .catch(error => {
          this.form.errors = error.response.data;
        });
    },
  },
};

</script>
