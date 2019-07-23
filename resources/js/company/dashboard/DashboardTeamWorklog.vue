<style lang="scss" scoped>
.worklog-item {
  margin-right: 30px;

  &:last-child {
    margin-right: 0;
  }

  &.future {
    color: #9E9E9E;
  }

  &.current {
    font-weight: 500;
    border-bottom: 2px solid black;
  }

  .pill {
    &.future {
      display: none;
    }
  }
}

.worklog-entry {
  &:not(:last-child) {
    border-bottom-width: 1px;
    border-bottom-style: solid;
    margin-bottom: 20px;
  }
}
</style>

<template>
  <div v-show="teams.length != 0" class="cf mw7 center br3 mb3 bg-white box">
    <div class="pa3">
      <h2 class="mt0 fw5 f4">
        🔨 What your team has done this week
      </h2>
      <div class="flex justify-center pa0 tc mv4">
        <div v-for="worklogDate in worklogDates" :key="worklogDate.friendlyDate" class="dib worklog-item relative pointer" :class="worklogDate.status" @click.prevent="load(worklogDate.friendlyDate)">
          <span class="dot"></span>
          <span class="db mb2">
            {{ worklogDate.day }}
          </span>
          <span class="db f7 mb1">
            {{ worklogDate.name }}
          </span>
        </div>
      </div>
      <div v-show="updatedWorklogEntries.length != 0">
        {{ }}
      </div>
      <div v-for="worklogEntry in updatedWorklogEntries" :key="worklogEntry.id" class="mb2 worklog-entry bb-gray">
        <small-name-and-avatar
          :name="worklogEntry.first_name + ' ' + worklogEntry.last_name"
          :avatar="worklogEntry.avatar"
        />
        <div class="lh-copy" v-html="worklogEntry.content">
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    company: {
      type: Object,
      default: null,
    },
    teams: {
      type: Array,
      default: () => ({}),
    },
    worklogDates: {
      type: Array,
      default: () => ({}),
    },
    worklogEntries: {
      type: Array,
      default: () => ({}),
    },
    currentDate: {
      type: String,
      default: null,
    },
    currentTeam: {
      type: Number,
      default: null,
    },
  },

  data() {
    return {
      updatedWorklogEntries: null,
      updatedCurrentDate: null,
    };
  },

  created() {
    this.updatedWorklogEntries = this.worklogEntries;
  },

  methods: {
    load(date) {
      axios.get('/' + this.company.id + '/dashboard/team/' + this.currentTeam + '/' + date)
        .then(response => {
          this.updatedWorklogEntries= response.data.worklogEntries;
          this.updatedCurrentDate = response.data.currentDate;
        })
        .catch(error => {
          this.form.errors = _.flatten(_.toArray(error.response.data));
        });
    },
  }
};
</script>
