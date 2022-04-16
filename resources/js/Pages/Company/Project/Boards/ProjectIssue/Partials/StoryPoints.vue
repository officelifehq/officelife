<style lang="scss" scoped>
.cog-icon {
  width: 16px;
}

.story-point {
  font-family: -apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,Oxygen,Ubuntu,'Fira Sans','Droid Sans','Helvetica Neue',sans-serif;
  color: #5e6c84;
  border-radius: 2em;
  padding: 3px 5px 2px 5px;
  font-size: 12px;
  font-weight: 600;
  line-height: 16px;
  background-color: #dfe1e6;
  height: 16px;
  max-height: 16px;
  min-width: 12px;
  padding-left: 7px;
  padding-right: 7px;

  &.pointer:hover {
    background-color: #92a6d7;
    color: #fff;
  }
}
</style>

<template>
  <div class="mb3 bb bb-gray pb3 relative">
    <div class="flex items-center justify-between mb1">
      <h3 class="ttc f7 gray ma0 fw4">
        {{ $t('project.issue_point_title') }}
      </h3>
      <svg xmlns="http://www.w3.org/2000/svg" class="cog-icon pointer" fill="none" viewBox="0 0 24 24" stroke="currentColor"
           @click.prevent="toggleEditMode()"
      >
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
      </svg>
    </div>

    <span v-if="!editMode && localPoints" class="story-point">
      {{ localPoints }}
    </span>
    <span v-if="!editMode && !localPoints" class="">
      {{ $t('project.issue_point_blank') }}
    </span>

    <!-- list of available points -->
    <div v-if="editMode">
      <p class="mt2 mb2 f6">{{ $t('project.issue_point_question') }}</p>
      <ul class="ma0 pl0 list">
        <li class="di mr2"><span class="story-point pointer" @click="submit(0)">0</span></li>
        <li class="di mr2"><span class="story-point pointer" @click="submit(1)">1</span></li>
        <li class="di mr2"><span class="story-point pointer" @click="submit(2)">2</span></li>
        <li class="di mr2"><span class="story-point pointer" @click="submit(3)">3</span></li>
        <li class="di mr2"><span class="story-point pointer" @click="submit(5)">5</span></li>
        <li class="di mr2"><span class="story-point pointer" @click="submit(8)">8</span></li>
        <li class="di mr2"><span class="story-point pointer" @click="submit(13)">13</span></li>
        <li class="di"><span class="story-point pointer" @click="submit(21)">21</span></li>
      </ul>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    data: {
      type: Object,
      default: null,
    },
  },

  data() {
    return {
      localPoints: null,
      editMode: false,
      form: {
        points: null,
      }
    };
  },

  created() {
    if (this.data.points) {
      this.localPoints = this.data.points;
    }
  },

  methods: {
    toggleEditMode: function() {
      this.editMode = !this.editMode;
    },

    submit(points) {
      this.form.points = points;

      axios.post(this.data.url.store, this.form)
        .then(response => {
          this.flash(this.$t('project.issue_point_set'), 'success');

          this.localPoints = points;
          this.toggleEditMode();
        })
        .catch(error => {
          this.form.errors = error.response.data;
        });
    },
  }
};

</script>
