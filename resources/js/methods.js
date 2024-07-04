import Emitter from 'tiny-emitter';
const emitter = new Emitter();

export default {
  /**
   * Flash a message.
   *
   * @param {string} message
   * @param {string} level
   */
  flash (message, level = 'success') {
    this.$emit('flash', { message, level });
  },

  $on: (...args) => emitter.on(...args),
  $once: (...args) => emitter.once(...args),
  $off: (...args) => emitter.off(...args),
  $emit: (...args) => emitter.emit(...args),
};
