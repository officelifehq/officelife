<template>
  <div class="editor">
    <editor-content class="editor__content" :editor="editor" :data-cy="cypressSelector" />
  </div>
</template>

<script>

//import Icon from 'Components/Icon'
import { EditorContent } from 'tiptap';
import {
  Blockquote,
  CodeBlock,
  HardBreak,
  Heading,
  HorizontalRule,
  OrderedList,
  BulletList,
  ListItem,
  TodoItem,
  TodoList,
  Bold,
  Code,
  Italic,
  Link,
  Strike,
  Underline,
  History,
  Placeholder,
} from 'tiptap-extensions';

export default {
  components: {
    EditorContent,
    //Icon,
  },

  props: {
    cypressSelector: {
      type: String,
      default: '',
    },
  },

  emits: [
    'update'
  ],

  data() {
    return {
      editor: new Editor({
        extensions: [
          new Blockquote(),
          new BulletList(),
          new CodeBlock(),
          new HardBreak(),
          new Heading({ levels: [1, 2, 3] }),
          new HorizontalRule(),
          new ListItem(),
          new OrderedList(),
          new TodoItem(),
          new TodoList(),
          new Link(),
          new Bold(),
          new Code(),
          new Italic(),
          new Strike(),
          new Underline(),
          new History(),
          new Placeholder({
            emptyNodeClass: 'is-empty',
            emptyNodeText: 'Write something …',
            showOnlyWhenEditable: true,
          }),
        ],
        content: `
          <p></p>
        `,
        onUpdate: ({ getJSON, getHTML }) => {
          this.$emit('update', getHTML());
        },
      }),
      html: 'Update content to see changes',
    };
  },

  beforeUnmount() {
    this.editor.destroy();
  },
};
</script>
