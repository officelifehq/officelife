<style lang="scss" scoped>
.box-bottom {
  border-bottom-left-radius: 11px;
  border-bottom-right-radius: 11px;
}
</style>

<template>
  <div>
    <!-- existing comments -->
    <div v-if="localComments">
      <div v-if="localComments.length > 0">
        <div v-for="comment in localComments" :key="comment.id" class="flex">
          <!-- avatar -->
          <div v-if="comment.author.id">
            <avatar :avatar="comment.author.avatar" :size="32" :class="'br-100 mr3'" />
          </div>

          <!-- avatar if the employee is no longer in the system -->
          <div v-else>
            <img loading="lazy" src="/img/streamline-icon-avatar-neutral@100x100.png" alt="anonymous avatar" class="br-100 mr3" height="32"
                 width="32"
            />
          </div>

          <!-- comment box -->
          <div v-if="idToEdit != comment.id" class="box bg-white mb4 w-100">
            <!-- comment -->
            <div class="bb bb-gray ph3" v-html="comment.content"></div>

            <!-- comment info -->
            <div class="bg-gray ph3 pv2 f7 box-bottom">
              <ul class="ma0 list pl0">
                <li v-if="comment.author.id" class="di">{{ $t('app.message_comment_written_by') }} <inertia-link :href="comment.author.url">{{ comment.author.name }}</inertia-link> <span class="gray">({{ comment.written_at }})</span></li>
                <li v-else class="di">{{ $t('app.message_comment_written_by') }} comment.author <span class="gray">({{ comment.written_at }})</span>()</li>

                <!-- edit -->
                <li v-if="comment.can_edit" class="di ml2">
                  <a class="bb b--dotted bt-0 bl-0 br-0 pointer" @click="showEdit(comment)">{{ $t('app.edit') }}</a>
                </li>

                <!-- delete -->
                <li v-if="idToDelete == comment.id" class="di ml2">
                  {{ $t('app.sure') }}
                  <a class="c-delete mr1 pointer" :data-cy="'delete-confirm-button'" @click.prevent="destroyComment(comment)">
                    {{ $t('app.yes') }}
                  </a>
                  <a class="pointer" :data-cy="'delete-cancel-button'" @click.prevent="idToDelete = 0">
                    {{ $t('app.no') }}
                  </a>
                </li>
                <li v-if="comment.can_delete && idToDelete != comment.id" class="di ml2">
                  <a class="bb b--dotted bt-0 bl-0 br-0 pointer c-delete" :data-cy="'delete-button'" @click.prevent="idToDelete = comment.id">
                    {{ $t('app.delete') }}
                  </a>
                </li>
              </ul>
            </div>
          </div>

          <!-- edit comment modal -->
          <div v-else class="box bg-white mb4 w-100">
            <form class="pa3" @submit.prevent="updateComment(comment)">
              <text-area
                :ref="'comment' + comment.id"
                v-model="form.commentEdit"
                :label="$t('app.message_comment_label')"
                :required="true"
                :rows="4"
                @esc-key-pressed="hideEdit()"
              />

              <!-- actions -->
              <div class="flex justify-between">
                <a class="btn dib tc w-auto-ns w-100 mb2 pv2 ph3" @click="idToEdit = 0">{{ $t('app.cancel') }}</a>
                <loading-button :class="'btn add w-auto-ns w-100 mb2 pv2 ph3'" :state="loadingStateEdit" :text="$t('app.post')" />
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>

    <!-- post a comment box -->
    <div class="bg-white box mb4">
      <form class="pa3" @submit.prevent="storeComment()">
        <text-area
          v-model="form.comment"
          :label="$t('app.message_comment_label')"
          :required="true"
          :rows="4"
        />

        <!-- actions -->
        <div class="flex justify-between">
          <loading-button :class="'btn add w-auto-ns w-100 mb2 pv2 ph3'" :state="loadingState" :text="$t('app.post')" />
        </div>
      </form>
    </div>
  </div>
</template>

<script>
import Avatar from '@/Shared/Avatar';
import TextArea from '@/Shared/TextArea';
import LoadingButton from '@/Shared/LoadingButton';

export default {
  components: {
    Avatar,
    TextArea,
    LoadingButton,
  },

  props: {
    comments: {
      type: Object,
      default: null,
    },
    postUrl: {
      type: String,
      default: null,
    },
  },

  data() {
    return {
      idToEdit: 0,
      idToDelete: 0,
      localComments: null,
      loadingState: null,
      loadingStateEdit: null,
      form: {
        comment: null,
        commentEdit: null,
        errors: [],
      },
    };
  },

  created() {
    this.localComments = this.comments;
  },

  methods: {
    showEdit(comment) {
      this.form.commentEdit = comment.content_raw;
      this.idToEdit = comment.id;

      this.$nextTick(() => {
        this.$refs[`comment${comment.id}`].focus();
      });
    },

    hideEdit() {
      this.idToEdit = 0;
    },

    storeComment() {
      this.loadingState = 'loading';

      axios.post(this.postUrl, this.form)
        .then(response => {
          this.loadingState = null;
          this.flash(this.$t('app.message_comment_written_success'), 'success');
          this.localComments.push(response.data.data);
          this.form.comment = null;
        })
        .catch(error => {
          this.loadingState = null;
          this.form.errors = error.response.data;
        });
    },

    updateComment(comment) {
      this.loadingStateEdit = 'loading';

      axios.put(this.postUrl + `${comment.id}`, this.form)
        .then(response => {
          this.flash(this.$t('app.message_comment_written_success'), 'success');
          this.localComments[this.localComments.findIndex(x => x.id === comment.id)] = response.data.data;
          this.idToEdit = 0;
          this.loadingStateEdit = null;
          this.form.comment = null;
        })
        .catch(error => {
          this.loadingStateEdit = null;
        });
    },

    destroyComment(comment) {
      axios.delete(this.postUrl + `${comment.id}`)
        .then(response => {
          this.localComments.splice(this.localComments.findIndex(i => i.id === comment.id), 1);
          this.flash(this.$t('app.message_comment_delete_success'), 'success');
        })
        .catch(error => {
          this.form.errors = error.response.data;
        });
    }
  }
};

</script>
