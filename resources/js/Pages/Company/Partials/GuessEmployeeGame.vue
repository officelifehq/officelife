<style lang="scss" scoped>
.choice-item {
  span {
    font-size: 12px;
    border: 1px solid transparent;
    border-radius: 2em;
    padding: 3px 10px;
    line-height: 22px;
    color: #0366d6;
    background-color: #f1f8ff;

    &:hover {
      background-color: #def;
    }
  }

  &:not(:last-child) {
    margin-bottom: 10px;
  }
}

.replay {
  border: 1px solid #0366d6;
  border-radius: 2em;
  padding: 0px 9px;
  line-height: 22px;
  color: #0366d6;
  background-color: #f1f8ff;

  &:hover {
    background-color: #def;
  }
}

.avatar {
  width: 80px;
  height: 80px;
  right: 20px;
  bottom: 20px;
  border: 1px solid #e1e4e8 !important;
  padding: 4px;
  background-color: #fff;
  border-radius: 7px;
}

.svg-replay {
  width: 16px;
  top: 3px;
}
</style>

<template>
  <div class="mb4">
    <span class="db fw5 mb2 relative">
      <span class="mr1">
        🌼
      </span> {{ $t('company.guess_employee_game_title') }}
    </span>

    <div class="br3 bg-white box z-1 pa3 relative">
      <!-- list of choices -->
      <ul v-if="!showResult" class="list pl0 ma0">
        <li v-for="choice in updatedGame.choices" :key="choice.uuid" class="choice-item pointer" @click="vote(choice)">
          <span>{{ choice.name }}</span>
        </li>
      </ul>

      <!-- game results -->
      <div v-if="showResult">
        <!-- right or wrong -->
        <p v-if="isRightChoice" class="f6 gray mt0"><span class="mr1">👍</span> {{ $t('company.guess_employee_game_success') }}</p>
        <p v-else class="f6 gray mt0"><span class="mr1">👎</span> {{ $t('company.guess_employee_game_failure') }}</p>

        <!-- name of the employee -->
        <p class="mb1"><inertia-link :href="result.url">{{ result.name }}</inertia-link></p>
        <p v-if="result.position" class="f7 gray mt0">{{ result.position }}</p>

        <!-- replay button -->
        <p class="relative replay pointer dib mv0 f7" @click="replay()">
          <svg class="svg-replay relative" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M4 2a1 1 0 011 1v2.101a7.002 7.002 0 0111.601 2.566 1 1 0 11-1.885.666A5.002 5.002 0 005.999 7H9a1 1 0 010 2H4a1 1 0 01-1-1V3a1 1 0 011-1zm.008 9.057a1 1 0 011.276.61A5.002 5.002 0 0014.001 13H11a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0v-2.101a7.002 7.002 0 01-11.601-2.566 1 1 0 01.61-1.276z" clip-rule="evenodd" />
          </svg>
          <span>{{ $t('company.guess_employee_game_play_again') }}</span>
        </p>
      </div>
      <avatar :avatar="updatedGame.avatar_to_find" :size="64" :class="'absolute bottom-0 right-1 avatar'" />
    </div>
  </div>
</template>

<script>
import Avatar from '@/Shared/Avatar';

export default {
  components: {
    Avatar,
  },

  props: {
    game: {
      type: Object,
      default: null,
    },
  },

  data() {
    return {
      showResult: false,
      isRightChoice: false,
      updatedGame: null,
      result: {
        name: null,
        position: null,
        url: null,
      },
      form: {
        gameId: null,
        choiceId: null,
      }
    };
  },

  created() {
    this.updatedGame = this.game;
  },

  methods: {
    vote(choice) {
      this.isRightChoice = choice.right_answer;
      var rightResult = this.updatedGame.choices.findIndex(x => x.right_answer === true);

      this.result.name = this.updatedGame.choices[rightResult].name;
      this.result.position = this.updatedGame.choices[rightResult].position;
      this.result.url = this.updatedGame.choices[rightResult].url;
      this.showResult = true;

      this.form.gameId = this.updatedGame.id;
      this.form.choiceId = choice.id;

      axios.post('/' + this.$page.props.auth.company.id + '/company/guessEmployee/vote', this.form)
        .then(response => {
        })
        .catch(error => {
        });
    },

    replay() {
      axios.get('/' + this.$page.props.auth.company.id + '/company/guessEmployee/replay')
        .then(response => {
          this.isRightChoice = false;
          this.result.name = null;
          this.result.position = null;
          this.result.url = null;

          this.showResult = false;
          this.updatedGame = response.data.game;
        });
    },
  }
};
</script>
