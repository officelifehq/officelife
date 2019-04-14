<style scoped>
.list li:last-child {
  border-bottom: 0;
}
</style>

<template>
  <layout title="Home" :user="user">
    <div class="ph2 ph0-ns">
      <!-- BREADCRUMB -->
      <div class="mt4-l mt1 mw6 br3 bg-white box center breadcrumb relative z-0 f6 pb2">
        <ul class="list ph0 tc-l tl">
          <li class="di">
            <a :href="'/' + company.id + '/dashboard'">{{ company.name }}</a>
          </li>
          <li class="di">
            <a :href="'/' + company.id + '/account'">{{ $t('app.breadcrumb_account_home') }}</a>
          </li>
          <li class="di">
            {{ $t('app.breadcrumb_account_manage_positions') }}
          </li>
        </ul>
      </div>

      <!-- BODY -->
      <div class="mw7 center br3 mb5 bg-white box restricted relative z-1">
        <div class="pa3 mt5">
          <h2 class="tc normal mb4">
            {{ $t('account.positions_title', { company: company.name}) }}
          </h2>

          <p class="relative">
            <span class="dib mb3 di-l" :class="positions.length == 0 ? 'white' : ''">{{ $tc('account.positions_number_positions', positions.length, { company: company.name, count: positions.length}) }}</span>
            <a class="btn primary absolute-l relative dib-l db right-0" @click.prevent="modal = true">{{ $t('account.positions_cta') }}</a>
          </p>

          <!-- MODAL TO ADD A POSITION -->
          <form v-show="modal" class="mb3 pa3 ba br2 bb-gray bg-gray" @submit.prevent="submit">
            <label for="title">{{ $t('account.position_new_title') }}</label>
            <div class="cf">
              <input id="title" v-model="form.title" type="text"
                     name="title"
                     :placeholder="'Marketing coordinator'"
                     class="br2 f5 ba b--black-40 pa2 outline-0 fl w-100 w-70-ns mb3 mb0-ns"
                     required
                     @keydown.esc="modal = false"
              />
              <div class="fl w-30-ns w-100 tr">
                <a class="btn dib-l db mb2 mb0-ns" @click.prevent="modal = false">{{ $t('app.cancel') }}</a>
                <loading-button :classes="'btn add w-auto-ns w-100 mb2 pv2 ph3'" :state="loadingState" :text="$t('app.add')" />
              </div>
            </div>
          </form>

          <!-- LIST OF EXISTING POSITIONS -->
          <ul v-show="positions.length != 0" class="list pl0 mv0 center ba br2 bb-gray">
            <li v-for="position in positions" :key="position.id" class="pv3 ph2 bb bb-gray bb-gray-hover">
              {{ position.title }}

              <!-- RENAME POSITION FORM -->
              <div v-show="idToUpdate == position.id" class="cf mt3">
                <form @submit.prevent="update(position.id)">
                  <input id="title" v-model="form.title" type="text"
                         name="title"
                         :placeholder="'Marketing coordinator'"
                         class="br2 f5 ba b--black-40 pa2 outline-0 fl w-100 w-70-ns mb3 mb0-ns"
                         required
                         @keydown.esc="idToUpdate = 0"
                  />
                  <div class="fl w-30-ns w-100 tr">
                    <a class="btn dib-l db mb2 mb0-ns" @click.prevent="idToUpdate = 0">{{ $t('app.cancel') }}</a>
                    <loading-button :classes="'btn add w-auto-ns w-100 mb2 pv2 ph3'" :state="loadingState" :text="$t('app.update')" />
                  </div>
                </form>
              </div>

              <!-- LIST OF ACTIONS FOR EACH POSITION -->
              <ul v-show="idToUpdate != position.id" class="list pa0 ma0 di-ns db fr-ns mt2 mt0-ns">
                <!-- RENAME A POSITION -->
                <li class="di mr2">
                  <a class="pointer" @click.prevent="idToUpdate = position.id ; form.title = position.title">{{ $t('app.rename') }}</a>
                </li>

                <!-- DELETE A POSITION -->
                <li v-if="idToDelete == position.id" class="di">
                  {{ $t('app.sure') }}
                  <a class="c-delete mr1 pointer" @click.prevent="destroy(position.id)">{{ $t('app.yes') }}</a>
                  <a class="pointer" @click.prevent="idToDelete = 0">{{ $t('app.no') }}</a>
                </li>
                <li v-else class="di">
                  <a class="pointer" @click.prevent="idToDelete = position.id">{{ $t('app.delete') }}</a>
                </li>
              </ul>
            </li>
          </ul>

          <!-- BLANK STATE -->
          <div v-show="positions.length == 0" class="pa3 mt5">
            <p class="tc measure center mb4 lh-copy">
              {{ $t('account.positions_blank') }}
            </p>
            <img class="db center mb4" srcset="/img/company/account/blank-position-1x.png,
                                          /img/company/account/blank-position-2x.png 2x"
            />
          </div>
        </div>
      </div>
    </div>
  </layout>
</template>

<script>

export default {
  props: {
    company: {
      type: Object,
      default: null,
    },
    user: {
      type: Object,
      default: null,
    },
    positions: {
      type: Array,
      default: null,
    },
  },

  data() {
    return {
      modal: false,
      deleteModal: false,
      updateModal: false,
      loadingState: '',
      updateModalId: 0,
      idToUpdate: 0,
      idToDelete: 0,
      form: {
        title: null,
        errors: [],
      },
    }
  },

  methods: {
    submit() {
      this.loadingState = 'loading'

      axios.post('/' + this.company.id + '/account/positions', this.form)
        .then(response => {
          this.$snotify.success(this.$t('account.position_success_new'), {
            timeout: 5000,
            showProgressBar: true,
            closeOnClick: true,
            pauseOnHover: true,
          })

          this.loadingState = null
          this.form.title = null
          this.modal = false
          this.positions.push(response.data.data)
        })
        .catch(error => {
          this.loadingState = null
          this.form.errors = _.flatten(_.toArray(error.response.data))
        })
    },

    update(id) {
      axios.put('/' + this.company.id + '/account/positions/' + id, this.form)
        .then(response => {
          this.$snotify.success(this.$t('account.position_success_update'), {
            timeout: 5000,
            showProgressBar: true,
            closeOnClick: true,
            pauseOnHover: true,
          })

          this.idToUpdate = 0
          this.form.title = null

          id = this.positions.findIndex(x => x.id === id)
          this.$set(this.positions, id, response.data.data)
        })
        .catch(error => {
          this.form.errors = _.flatten(_.toArray(error.response.data))
        })
    },

    destroy(id) {
      axios.delete('/' + this.company.id + '/account/positions/' + id)
        .then(response => {
          this.$snotify.success(this.$t('account.position_success_destroy'), {
            timeout: 5000,
            showProgressBar: true,
            closeOnClick: true,
            pauseOnHover: true,
          })

          this.idToDelete = 0
          id = this.positions.findIndex(x => x.id === id)
          this.positions.splice(id, 1)
        })
        .catch(error => {
          this.form.errors = _.flatten(_.toArray(error.response.data))
        })
    },
  }
}

</script>
