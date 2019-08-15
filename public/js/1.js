(window["webpackJsonp"] = window["webpackJsonp"] || []).push([[1],{

/***/ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/Shared/Layout.vue?vue&type=script&lang=js&":
/*!*************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib??ref--4-0!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/Shared/Layout.vue?vue&type=script&lang=js& ***!
  \*************************************************************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var vue_click_outside__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! vue-click-outside */ "./node_modules/vue-click-outside/index.js");
/* harmony import */ var vue_click_outside__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(vue_click_outside__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _Shared_UserMenu__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @/Shared/UserMenu */ "./resources/js/Shared/UserMenu.vue");
/* harmony import */ var _Shared_LoadingButton__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @/Shared/LoadingButton */ "./resources/js/Shared/LoadingButton.vue");
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//



/* harmony default export */ __webpack_exports__["default"] = ({
  components: {
    UserMenu: _Shared_UserMenu__WEBPACK_IMPORTED_MODULE_1__["default"],
    LoadingButton: _Shared_LoadingButton__WEBPACK_IMPORTED_MODULE_2__["default"]
  },
  directives: {
    ClickOutside: vue_click_outside__WEBPACK_IMPORTED_MODULE_0___default.a
  },
  props: {
    title: {
      type: String,
      "default": ''
    },
    noMenu: {
      type: Boolean,
      "default": false
    },
    employee: {
      type: Object,
      "default": null
    },
    notifications: {
      type: Array,
      "default": null
    }
  },
  data: function data() {
    return {
      loadingState: '',
      modalFind: false,
      showModalNotifications: false,
      dataReturnedFromSearch: false,
      form: {
        searchTerm: null,
        errors: []
      },
      employees: [],
      teams: []
    };
  },
  watch: {
    title: function title(_title) {
      this.updatePageTitle(_title);
    }
  },
  mounted: function mounted() {
    this.updatePageTitle(this.title); // prevent click outside event with popupItem.

    this.popupItem = this.$el;
  },
  methods: {
    updatePageTitle: function updatePageTitle(title) {
      document.title = title ? "".concat(title, " | Example app") : 'Example app';
    },
    showFindModal: function showFindModal() {
      var _this = this;

      this.dataReturnedFromSearch = false;
      this.form.searchTerm = null;
      this.employees = [];
      this.teams = [];
      this.modalFind = !this.modalFind;
      this.$nextTick(function () {
        _this.$refs.search.focus();
      });
    },
    showNotifications: function showNotifications() {
      var _this2 = this;

      this.showModalNotifications = !this.showModalNotifications;
      axios.post('/notifications/read')["catch"](function (error) {
        _this2.loadingState = null;
        _this2.form.errors = _.flatten(_.toArray(error.response.data));
      });
    },
    hideNotifications: function hideNotifications() {
      this.showModalNotifications = false;
    },
    submit: function submit() {
      var _this3 = this;

      axios.post('/search/employees', this.form).then(function (response) {
        _this3.dataReturnedFromSearch = true;
        _this3.employees = response.data.data;
      })["catch"](function (error) {
        _this3.loadingState = null;
        _this3.form.errors = _.flatten(_.toArray(error.response.data));
      });
      axios.post('/search/teams', this.form).then(function (response) {
        _this3.dataReturnedFromSearch = true;
        _this3.teams = response.data.data;
      })["catch"](function (error) {
        _this3.loadingState = null;
        _this3.form.errors = _.flatten(_.toArray(error.response.data));
      });
    }
  }
});

/***/ }),

/***/ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/Shared/LoadingButton.vue?vue&type=script&lang=js&":
/*!********************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib??ref--4-0!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/Shared/LoadingButton.vue?vue&type=script&lang=js& ***!
  \********************************************************************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var vue_loaders_dist_vue_loaders_css__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! vue-loaders/dist/vue-loaders.css */ "./node_modules/vue-loaders/dist/vue-loaders.css");
/* harmony import */ var vue_loaders_dist_vue_loaders_css__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(vue_loaders_dist_vue_loaders_css__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var vue_loaders_src_loaders_ball_pulse__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! vue-loaders/src/loaders/ball-pulse */ "./node_modules/vue-loaders/src/loaders/ball-pulse.vue");
//
//
//
//
//
//
//
//
//


/* harmony default export */ __webpack_exports__["default"] = ({
  components: {
    BallPulseLoader: vue_loaders_src_loaders_ball_pulse__WEBPACK_IMPORTED_MODULE_1__["default"]
  },
  props: {
    text: {
      type: String,
      "default": ''
    },
    state: {
      type: String,
      "default": ''
    },
    classes: {
      type: String,
      "default": ''
    },
    cypressSelector: {
      type: String,
      "default": ''
    }
  }
});

/***/ }),

/***/ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/Shared/UserMenu.vue?vue&type=script&lang=js&":
/*!***************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib??ref--4-0!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/Shared/UserMenu.vue?vue&type=script&lang=js& ***!
  \***************************************************************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
/* harmony default export */ __webpack_exports__["default"] = ({
  props: {
    notifications: {
      type: Array,
      "default": null
    }
  },
  data: function data() {
    return {
      menu: false
    };
  },
  created: function created() {
    window.addEventListener('click', this.close);
  },
  beforeDestroy: function beforeDestroy() {
    window.removeEventListener('click', this.close);
  },
  methods: {
    close: function close(e) {
      if (!this.$el.contains(e.target)) {
        this.menu = false;
      }
    }
  }
});

/***/ }),

/***/ "./node_modules/css-loader/index.js?!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/Shared/Layout.vue?vue&type=style&index=0&id=6bf30086&scoped=true&lang=css&":
/*!********************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/css-loader??ref--6-1!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src??ref--6-2!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/Shared/Layout.vue?vue&type=style&index=0&id=6bf30086&scoped=true&lang=css& ***!
  \********************************************************************************************************************************************************************************************************************************************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

exports = module.exports = __webpack_require__(/*! ../../../node_modules/css-loader/lib/css-base.js */ "./node_modules/css-loader/lib/css-base.js")(false);
// imports


// module
exports.push([module.i, "\n.find-box[data-v-6bf30086] {\n  border: 1px solid rgba(27,31,35,.15);\n  box-shadow: 0 3px 12px rgba(27,31,35,.15);\n  top: 63px;\n  width: 500px;\n  left: 0;\n  right: 0;\n  margin: 0 auto;\n}\n.notifications-box[data-v-6bf30086] {\n  border: 1px solid rgba(27,31,35,.15);\n  box-shadow: 0 3px 12px rgba(27,31,35,.15);\n  top: 63px;\n  width: 500px;\n  left: 0;\n  right: 0;\n  margin: 0 auto;\n}\n.bg-modal-find[data-v-6bf30086] {\n  position: fixed;\n  z-index: 100;\n  top: 0;\n  bottom: 0;\n  left: 0;\n  right: 0;\n  background-color: rgba(0, 0, 0, 0.3);\n  display: flex;\n  justify-content: center;\n  align-items: center;\n}\n", ""]);

// exports


/***/ }),

/***/ "./node_modules/css-loader/index.js?!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/Shared/UserMenu.vue?vue&type=style&index=0&id=901727b4&scoped=true&lang=css&":
/*!**********************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/css-loader??ref--6-1!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src??ref--6-2!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/Shared/UserMenu.vue?vue&type=style&index=0&id=901727b4&scoped=true&lang=css& ***!
  \**********************************************************************************************************************************************************************************************************************************************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

exports = module.exports = __webpack_require__(/*! ../../../node_modules/css-loader/lib/css-base.js */ "./node_modules/css-loader/lib/css-base.js")(false);
// imports


// module
exports.push([module.i, "\n.absolute[data-v-901727b4] {\n  border: 1px solid rgba(27,31,35,.15);\n  box-shadow: 0 3px 12px rgba(27,31,35,.15);\n  top: 36px;\n}\n.absolute[data-v-901727b4]:after,\n.absolute[data-v-901727b4]:before {\n  content: \"\";\n  display: inline-block;\n  position: absolute;\n}\n.absolute[data-v-901727b4]:after {\n  border: 7px solid transparent;\n  border-bottom-color: #fff;\n  left: auto;\n  right: 10px;\n  top: -14px;\n}\n.absolute[data-v-901727b4]:before {\n  border: 8px solid transparent;\n  border-bottom-color: rgba(27,31,35,.15);\n  left: auto;\n  right: 9px;\n  top: -16px;\n}\n", ""]);

// exports


/***/ }),

/***/ "./node_modules/style-loader/index.js!./node_modules/css-loader/index.js?!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/Shared/Layout.vue?vue&type=style&index=0&id=6bf30086&scoped=true&lang=css&":
/*!************************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/style-loader!./node_modules/css-loader??ref--6-1!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src??ref--6-2!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/Shared/Layout.vue?vue&type=style&index=0&id=6bf30086&scoped=true&lang=css& ***!
  \************************************************************************************************************************************************************************************************************************************************************************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {


var content = __webpack_require__(/*! !../../../node_modules/css-loader??ref--6-1!../../../node_modules/vue-loader/lib/loaders/stylePostLoader.js!../../../node_modules/postcss-loader/src??ref--6-2!../../../node_modules/vue-loader/lib??vue-loader-options!./Layout.vue?vue&type=style&index=0&id=6bf30086&scoped=true&lang=css& */ "./node_modules/css-loader/index.js?!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/Shared/Layout.vue?vue&type=style&index=0&id=6bf30086&scoped=true&lang=css&");

if(typeof content === 'string') content = [[module.i, content, '']];

var transform;
var insertInto;



var options = {"hmr":true}

options.transform = transform
options.insertInto = undefined;

var update = __webpack_require__(/*! ../../../node_modules/style-loader/lib/addStyles.js */ "./node_modules/style-loader/lib/addStyles.js")(content, options);

if(content.locals) module.exports = content.locals;

if(false) {}

/***/ }),

/***/ "./node_modules/style-loader/index.js!./node_modules/css-loader/index.js?!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/Shared/UserMenu.vue?vue&type=style&index=0&id=901727b4&scoped=true&lang=css&":
/*!**************************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/style-loader!./node_modules/css-loader??ref--6-1!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src??ref--6-2!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/Shared/UserMenu.vue?vue&type=style&index=0&id=901727b4&scoped=true&lang=css& ***!
  \**************************************************************************************************************************************************************************************************************************************************************************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {


var content = __webpack_require__(/*! !../../../node_modules/css-loader??ref--6-1!../../../node_modules/vue-loader/lib/loaders/stylePostLoader.js!../../../node_modules/postcss-loader/src??ref--6-2!../../../node_modules/vue-loader/lib??vue-loader-options!./UserMenu.vue?vue&type=style&index=0&id=901727b4&scoped=true&lang=css& */ "./node_modules/css-loader/index.js?!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/Shared/UserMenu.vue?vue&type=style&index=0&id=901727b4&scoped=true&lang=css&");

if(typeof content === 'string') content = [[module.i, content, '']];

var transform;
var insertInto;



var options = {"hmr":true}

options.transform = transform
options.insertInto = undefined;

var update = __webpack_require__(/*! ../../../node_modules/style-loader/lib/addStyles.js */ "./node_modules/style-loader/lib/addStyles.js")(content, options);

if(content.locals) module.exports = content.locals;

if(false) {}

/***/ }),

/***/ "./node_modules/vue-click-outside/index.js":
/*!*************************************************!*\
  !*** ./node_modules/vue-click-outside/index.js ***!
  \*************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

function validate(binding) {
  if (typeof binding.value !== 'function') {
    console.warn('[Vue-click-outside:] provided expression', binding.expression, 'is not a function.')
    return false
  }

  return true
}

function isPopup(popupItem, elements) {
  if (!popupItem || !elements)
    return false

  for (var i = 0, len = elements.length; i < len; i++) {
    try {
      if (popupItem.contains(elements[i])) {
        return true
      }
      if (elements[i].contains(popupItem)) {
        return false
      }
    } catch(e) {
      return false
    }
  }

  return false
}

function isServer(vNode) {
  return typeof vNode.componentInstance !== 'undefined' && vNode.componentInstance.$isServer
}

exports = module.exports = {
  bind: function (el, binding, vNode) {
    if (!validate(binding)) return

    // Define Handler and cache it on the element
    function handler(e) {
      if (!vNode.context) return

      // some components may have related popup item, on which we shall prevent the click outside event handler.
      var elements = e.path || (e.composedPath && e.composedPath())
      elements && elements.length > 0 && elements.unshift(e.target)
      
      if (el.contains(e.target) || isPopup(vNode.context.popupItem, elements)) return

      el.__vueClickOutside__.callback(e)
    }

    // add Event Listeners
    el.__vueClickOutside__ = {
      handler: handler,
      callback: binding.value
    }
    !isServer(vNode) && document.addEventListener('click', handler)
  },

  update: function (el, binding) {
    if (validate(binding)) el.__vueClickOutside__.callback = binding.value
  },
  
  unbind: function (el, binding, vNode) {
    // Remove Event Listeners
    !isServer(vNode) && document.removeEventListener('click', el.__vueClickOutside__.handler)
    delete el.__vueClickOutside__
  }
}


/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/Shared/Layout.vue?vue&type=template&id=6bf30086&scoped=true&":
/*!*****************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/Shared/Layout.vue?vue&type=template&id=6bf30086&scoped=true& ***!
  \*****************************************************************************************************************************************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "render", function() { return render; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return staticRenderFns; });
var render = function() {
  var _vm = this
  var _h = _vm.$createElement
  var _c = _vm._self._c || _h
  return _c(
    "div",
    [
      _c("vue-snotify"),
      _vm._v(" "),
      _c("header", { staticClass: "bg-white dn db-m db-l mb3 relative" }, [
        _c("div", { staticClass: "ph3 pt1 w-100" }, [
          _c("div", { staticClass: "cf" }, [
            _vm._m(0),
            _vm._v(" "),
            _c("div", { staticClass: "fl w-60 tc" }, [
              _c("div", {
                directives: [
                  {
                    name: "show",
                    rawName: "v-show",
                    value: _vm.noMenu,
                    expression: "noMenu"
                  }
                ],
                staticClass: "dib w-100"
              }),
              _vm._v(" "),
              _c(
                "ul",
                {
                  directives: [
                    {
                      name: "show",
                      rawName: "v-show",
                      value: !_vm.noMenu,
                      expression: "!noMenu"
                    }
                  ],
                  staticClass: "mv2"
                },
                [
                  _c(
                    "li",
                    { staticClass: "di header-menu-item pa2 pointer mr2" },
                    [
                      _c("inertia-link", { attrs: { href: "/home" } }, [
                        _c("span", { staticClass: "fw5" }, [
                          _c("img", {
                            staticClass: "relative",
                            attrs: { src: "/img/header/icon-home.svg" }
                          }),
                          _vm._v(
                            "\n                  " +
                              _vm._s(_vm.$t("app.header_home")) +
                              "\n                "
                          )
                        ])
                      ])
                    ],
                    1
                  ),
                  _vm._v(" "),
                  _c(
                    "li",
                    {
                      staticClass: "di header-menu-item pa2 pointer mr2",
                      attrs: { "data-cy": "header-find-link" },
                      on: { click: _vm.showFindModal }
                    },
                    [
                      _c("span", { staticClass: "fw5" }, [
                        _c("img", {
                          staticClass: "relative",
                          attrs: { src: "/img/header/icon-find.svg" }
                        }),
                        _vm._v(
                          "\n                " +
                            _vm._s(_vm.$t("app.header_find")) +
                            "\n              "
                        )
                      ])
                    ]
                  ),
                  _vm._v(" "),
                  _c(
                    "li",
                    {
                      staticClass: "di header-menu-item pa2 pointer",
                      attrs: { "data-cy": "header-notifications-link" },
                      on: { click: _vm.showNotifications }
                    },
                    [
                      _c("span", { staticClass: "fw5" }, [
                        _c("img", {
                          staticClass: "relative",
                          attrs: { src: "/img/header/icon-notification.svg" }
                        }),
                        _vm._v(
                          "\n                " +
                            _vm._s(_vm.$t("app.header_notifications")) +
                            "\n              "
                        )
                      ])
                    ]
                  ),
                  _vm._v(" "),
                  _c(
                    "li",
                    {
                      staticClass: "di header-menu-item pa2 pointer",
                      attrs: { "data-cy": "header-notifications-link" }
                    },
                    [
                      _c(
                        "inertia-link",
                        {
                          attrs: {
                            href: "/" + _vm.employee.company.id + "/account"
                          }
                        },
                        [
                          _c("span", { staticClass: "fw5" }, [
                            _c("img", {
                              staticClass: "relative",
                              attrs: {
                                src: "/img/header/icon-notification.svg"
                              }
                            }),
                            _vm._v(
                              "\n                  Adminland\n                "
                            )
                          ])
                        ]
                      )
                    ],
                    1
                  )
                ]
              )
            ]),
            _vm._v(" "),
            _c(
              "div",
              { staticClass: "fl w-20 pa2 tr relative header-menu-settings" },
              [_c("user-menu")],
              1
            )
          ])
        ]),
        _vm._v(" "),
        _c(
          "div",
          {
            directives: [
              {
                name: "show",
                rawName: "v-show",
                value: _vm.modalFind,
                expression: "modalFind"
              }
            ],
            staticClass: "absolute z-max find-box"
          },
          [
            _c(
              "div",
              { staticClass: "br2 bg-white tl pv3 ph3 bounceIn faster" },
              [
                _c(
                  "form",
                  {
                    on: {
                      submit: function($event) {
                        $event.preventDefault()
                        return _vm.submit($event)
                      }
                    }
                  },
                  [
                    _c(
                      "div",
                      { staticClass: "relative" },
                      [
                        _c("input", {
                          directives: [
                            {
                              name: "model",
                              rawName: "v-model",
                              value: _vm.form.searchTerm,
                              expression: "form.searchTerm"
                            }
                          ],
                          ref: "search",
                          staticClass:
                            "br2 f5 w-100 ba b--black-40 pa2 outline-0",
                          attrs: {
                            id: "search",
                            type: "text",
                            name: "search",
                            placeholder: _vm.$t(
                              "app.header_search_placeholder"
                            ),
                            required: ""
                          },
                          domProps: { value: _vm.form.searchTerm },
                          on: {
                            keydown: function($event) {
                              if (
                                !$event.type.indexOf("key") &&
                                _vm._k($event.keyCode, "esc", 27, $event.key, [
                                  "Esc",
                                  "Escape"
                                ])
                              ) {
                                return null
                              }
                              _vm.modalFind = false
                            },
                            input: function($event) {
                              if ($event.target.composing) {
                                return
                              }
                              _vm.$set(
                                _vm.form,
                                "searchTerm",
                                $event.target.value
                              )
                            }
                          }
                        }),
                        _vm._v(" "),
                        _c("loading-button", {
                          attrs: {
                            classes:
                              "btn add w-auto-ns w-100 mb2 pv2 ph3 absolute top-0 right-0",
                            state: _vm.loadingState,
                            text: _vm.$t("app.search"),
                            "cypress-selector": "header-find-submit"
                          }
                        })
                      ],
                      1
                    )
                  ]
                ),
                _vm._v(" "),
                _c(
                  "ul",
                  {
                    directives: [
                      {
                        name: "show",
                        rawName: "v-show",
                        value: _vm.dataReturnedFromSearch,
                        expression: "dataReturnedFromSearch"
                      }
                    ],
                    staticClass: "pl0 list ma0 mt3",
                    attrs: { "data-cy": "results" }
                  },
                  [
                    _c("li", { staticClass: "b mb3" }, [
                      _c("span", { staticClass: "f6 mb2 dib" }, [
                        _vm._v(_vm._s(_vm.$t("app.header_search_employees")))
                      ]),
                      _vm._v(" "),
                      _vm.employees.length > 0
                        ? _c(
                            "ul",
                            { staticClass: "list ma0 pl0" },
                            _vm._l(_vm.employees, function(localEmployee) {
                              return _c("li", { key: localEmployee.id }, [
                                _c(
                                  "a",
                                  {
                                    attrs: {
                                      href:
                                        "/" +
                                        localEmployee.company.id +
                                        "/employees/" +
                                        localEmployee.id
                                    }
                                  },
                                  [_vm._v(_vm._s(localEmployee.name))]
                                )
                              ])
                            }),
                            0
                          )
                        : _c("div", { staticClass: "silver" }, [
                            _vm._v(
                              "\n              " +
                                _vm._s(
                                  _vm.$t("app.header_search_no_employee_found")
                                ) +
                                "\n            "
                            )
                          ])
                    ]),
                    _vm._v(" "),
                    _c("li", { staticClass: "fw5" }, [
                      _c("span", { staticClass: "f6 mb2 dib" }, [
                        _vm._v(_vm._s(_vm.$t("app.header_search_teams")))
                      ]),
                      _vm._v(" "),
                      _vm.teams.length > 0
                        ? _c(
                            "ul",
                            { staticClass: "list ma0 pl0" },
                            _vm._l(_vm.teams, function(team) {
                              return _c("li", { key: team.id }, [
                                _c(
                                  "a",
                                  {
                                    attrs: {
                                      href:
                                        "/" +
                                        team.company.id +
                                        "/teams/" +
                                        team.id
                                    }
                                  },
                                  [_vm._v(_vm._s(team.name))]
                                )
                              ])
                            }),
                            0
                          )
                        : _c("div", { staticClass: "silver" }, [
                            _vm._v(
                              "\n              " +
                                _vm._s(
                                  _vm.$t("app.header_search_no_team_found")
                                ) +
                                "\n            "
                            )
                          ])
                    ])
                  ]
                )
              ]
            )
          ]
        ),
        _vm._v(" "),
        _vm.showModalNotifications
          ? _c(
              "div",
              {
                directives: [
                  {
                    name: "click-outside",
                    rawName: "v-click-outside",
                    value: _vm.hideNotifications,
                    expression: "hideNotifications"
                  }
                ],
                staticClass: "absolute z-max notifications-box"
              },
              [
                _c(
                  "div",
                  { staticClass: "br2 bg-white tl pv3 ph3 bounceIn faster" },
                  [
                    _c(
                      "div",
                      {
                        directives: [
                          {
                            name: "show",
                            rawName: "v-show",
                            value: _vm.notifications.length == 0,
                            expression: "notifications.length == 0"
                          }
                        ]
                      },
                      [
                        _c("img", {
                          staticClass: "db center mb2",
                          attrs: {
                            srcset:
                              "/img/header/notification_blank.png" +
                              ", " +
                              "/img/header/notitication_blank@2x.png" +
                              " 2x"
                          }
                        }),
                        _vm._v(" "),
                        _c("p", { staticClass: "tc" }, [
                          _vm._v("\n            All is clear!\n          ")
                        ])
                      ]
                    ),
                    _vm._v(" "),
                    _c(
                      "ul",
                      {
                        directives: [
                          {
                            name: "show",
                            rawName: "v-show",
                            value: _vm.notifications.length > 0,
                            expression: "notifications.length > 0"
                          }
                        ]
                      },
                      _vm._l(_vm.notifications, function(notification) {
                        return _c("li", { key: notification.id }, [
                          _vm._v(
                            "\n            " +
                              _vm._s(notification.action) +
                              "\n          "
                          )
                        ])
                      }),
                      0
                    )
                  ]
                )
              ]
            )
          : _vm._e()
      ]),
      _vm._v(" "),
      _vm._m(1),
      _vm._v(" "),
      _c("div", { class: [_vm.modalFind ? "bg-modal-find" : ""] }),
      _vm._v(" "),
      _vm._t("default")
    ],
    2
  )
}
var staticRenderFns = [
  function() {
    var _vm = this
    var _h = _vm.$createElement
    var _c = _vm._self._c || _h
    return _c("div", { staticClass: "fl w-20 pa2" }, [
      _c("a", { staticClass: "relative header-logo", attrs: { href: "" } }, [
        _c("img", { attrs: { src: "/img/logo.svg", height: "30" } })
      ])
    ])
  },
  function() {
    var _vm = this
    var _h = _vm.$createElement
    var _c = _vm._self._c || _h
    return _c("header", { staticClass: "bg-white mobile dn-ns mb3" }, [
      _c("div", { staticClass: "ph2 pv2 w-100 relative" }, [
        _c("div", { staticClass: "pv2 relative menu-toggle" }, [
          _c(
            "label",
            { staticClass: "dib b relative", attrs: { for: "menu-toggle" } },
            [_vm._v("Menu")]
          ),
          _vm._v(" "),
          _c("input", { attrs: { id: "menu-toggle", type: "checkbox" } }),
          _vm._v(" "),
          _c(
            "ul",
            { staticClass: "list pa0 mt4 mb0", attrs: { id: "mobile-menu" } },
            [
              _c("li", { staticClass: "pv2 bt b--light-gray" }, [
                _c(
                  "a",
                  {
                    staticClass: "no-color b no-underline",
                    attrs: { href: "" }
                  },
                  [_vm._v("\n              Home\n            ")]
                )
              ]),
              _vm._v(" "),
              _c("li", { staticClass: "pv2 bt b--light-gray" }, [
                _c(
                  "a",
                  {
                    staticClass: "no-color b no-underline",
                    attrs: { href: "" }
                  },
                  [_vm._v("\n              app.main_nav_people\n            ")]
                )
              ]),
              _vm._v(" "),
              _c("li", { staticClass: "pv2 bt b--light-gray" }, [
                _c(
                  "a",
                  {
                    staticClass: "no-color b no-underline",
                    attrs: { href: "" }
                  },
                  [_vm._v("\n              app.main_nav_journal\n            ")]
                )
              ]),
              _vm._v(" "),
              _c("li", { staticClass: "pv2 bt b--light-gray" }, [
                _c(
                  "a",
                  {
                    staticClass: "no-color b no-underline",
                    attrs: { href: "" }
                  },
                  [_vm._v("\n              app.main_nav_find\n            ")]
                )
              ]),
              _vm._v(" "),
              _c("li", { staticClass: "pv2 bt b--light-gray" }, [
                _c(
                  "a",
                  {
                    staticClass: "no-color b no-underline",
                    attrs: { href: "" }
                  },
                  [
                    _vm._v(
                      "\n              app.main_nav_changelog\n            "
                    )
                  ]
                )
              ]),
              _vm._v(" "),
              _c("li", { staticClass: "pv2 bt b--light-gray" }, [
                _c(
                  "a",
                  {
                    staticClass: "no-color b no-underline",
                    attrs: { href: "" }
                  },
                  [
                    _vm._v(
                      "\n              app.main_nav_settings\n            "
                    )
                  ]
                )
              ]),
              _vm._v(" "),
              _c("li", { staticClass: "pv2 bt b--light-gray" }, [
                _c(
                  "a",
                  {
                    staticClass: "no-color b no-underline",
                    attrs: { href: "" }
                  },
                  [_vm._v("\n              app.main_nav_signout\n            ")]
                )
              ])
            ]
          )
        ]),
        _vm._v(" "),
        _c("div", { staticClass: "absolute pa2 header-logo" }, [
          _c("a", { attrs: { href: "" } }, [
            _c("img", {
              attrs: { src: "/img/logo.svg", width: "30", height: "27" }
            })
          ])
        ])
      ])
    ])
  }
]
render._withStripped = true



/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/Shared/LoadingButton.vue?vue&type=template&id=5f30d6e2&":
/*!************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/Shared/LoadingButton.vue?vue&type=template&id=5f30d6e2& ***!
  \************************************************************************************************************************************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "render", function() { return render; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return staticRenderFns; });
var render = function() {
  var _vm = this
  var _h = _vm.$createElement
  var _c = _vm._self._c || _h
  return _c("div", { staticClass: "di" }, [
    _c(
      "button",
      {
        class: _vm.classes,
        attrs: { name: "save", type: "submit", "data-cy": _vm.cypressSelector }
      },
      [
        _vm.state == "loading"
          ? _c("ball-pulse-loader", {
              attrs: { color: "#218b8a", size: "7px" }
            })
          : _vm._e(),
        _vm._v(" "),
        _vm.state != "loading"
          ? _c("span", [_vm._v(_vm._s(_vm.text))])
          : _vm._e()
      ],
      1
    )
  ])
}
var staticRenderFns = []
render._withStripped = true



/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/Shared/UserMenu.vue?vue&type=template&id=901727b4&scoped=true&":
/*!*******************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/Shared/UserMenu.vue?vue&type=template&id=901727b4&scoped=true& ***!
  \*******************************************************************************************************************************************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "render", function() { return render; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return staticRenderFns; });
var render = function() {
  var _vm = this
  var _h = _vm.$createElement
  var _c = _vm._self._c || _h
  return _c("div", [
    _c(
      "a",
      {
        staticClass: "no-color no-underline relative pointer",
        attrs: { "data-cy": "header-menu" },
        on: {
          click: function($event) {
            $event.preventDefault()
            _vm.menu = !_vm.menu
          }
        }
      },
      [
        _vm._v("\n    " + _vm._s(_vm.$page.auth.user.email) + " "),
        _c("span", { staticClass: "dropdown-caret" })
      ]
    ),
    _vm._v(" "),
    _vm.menu == true
      ? _c(
          "div",
          {
            staticClass:
              "absolute br2 bg-white z-max tl pv2 ph3 bounceIn faster"
          },
          [
            _c("ul", { staticClass: "list ma0 pa0" }, [
              _c(
                "li",
                { staticClass: "pv2" },
                [
                  _c(
                    "inertia-link",
                    {
                      staticClass: "no-color no-underline",
                      attrs: { href: "/home" }
                    },
                    [
                      _vm._v(
                        "\n          " +
                          _vm._s(_vm.$t("app.header_switch_company")) +
                          "\n        "
                      )
                    ]
                  )
                ],
                1
              ),
              _vm._v(" "),
              _c(
                "li",
                { staticClass: "pv2" },
                [
                  _c(
                    "inertia-link",
                    {
                      staticClass: "no-color no-underline",
                      attrs: { href: "/logout", "data-cy": "logout-button" }
                    },
                    [
                      _vm._v(
                        "\n          " +
                          _vm._s(_vm.$t("app.header_logout")) +
                          "\n        "
                      )
                    ]
                  )
                ],
                1
              )
            ])
          ]
        )
      : _vm._e()
  ])
}
var staticRenderFns = []
render._withStripped = true



/***/ }),

/***/ "./resources/js/Shared/Layout.vue":
/*!****************************************!*\
  !*** ./resources/js/Shared/Layout.vue ***!
  \****************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _Layout_vue_vue_type_template_id_6bf30086_scoped_true___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./Layout.vue?vue&type=template&id=6bf30086&scoped=true& */ "./resources/js/Shared/Layout.vue?vue&type=template&id=6bf30086&scoped=true&");
/* harmony import */ var _Layout_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./Layout.vue?vue&type=script&lang=js& */ "./resources/js/Shared/Layout.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport *//* harmony import */ var _Layout_vue_vue_type_style_index_0_id_6bf30086_scoped_true_lang_css___WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./Layout.vue?vue&type=style&index=0&id=6bf30086&scoped=true&lang=css& */ "./resources/js/Shared/Layout.vue?vue&type=style&index=0&id=6bf30086&scoped=true&lang=css&");
/* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");






/* normalize component */

var component = Object(_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_3__["default"])(
  _Layout_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _Layout_vue_vue_type_template_id_6bf30086_scoped_true___WEBPACK_IMPORTED_MODULE_0__["render"],
  _Layout_vue_vue_type_template_id_6bf30086_scoped_true___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"],
  false,
  null,
  "6bf30086",
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/Shared/Layout.vue"
/* harmony default export */ __webpack_exports__["default"] = (component.exports);

/***/ }),

/***/ "./resources/js/Shared/Layout.vue?vue&type=script&lang=js&":
/*!*****************************************************************!*\
  !*** ./resources/js/Shared/Layout.vue?vue&type=script&lang=js& ***!
  \*****************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_Layout_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../node_modules/babel-loader/lib??ref--4-0!../../../node_modules/vue-loader/lib??vue-loader-options!./Layout.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/Shared/Layout.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport */ /* harmony default export */ __webpack_exports__["default"] = (_node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_Layout_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/js/Shared/Layout.vue?vue&type=style&index=0&id=6bf30086&scoped=true&lang=css&":
/*!*************************************************************************************************!*\
  !*** ./resources/js/Shared/Layout.vue?vue&type=style&index=0&id=6bf30086&scoped=true&lang=css& ***!
  \*************************************************************************************************/
/*! no static exports found */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_style_loader_index_js_node_modules_css_loader_index_js_ref_6_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_6_2_node_modules_vue_loader_lib_index_js_vue_loader_options_Layout_vue_vue_type_style_index_0_id_6bf30086_scoped_true_lang_css___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../node_modules/style-loader!../../../node_modules/css-loader??ref--6-1!../../../node_modules/vue-loader/lib/loaders/stylePostLoader.js!../../../node_modules/postcss-loader/src??ref--6-2!../../../node_modules/vue-loader/lib??vue-loader-options!./Layout.vue?vue&type=style&index=0&id=6bf30086&scoped=true&lang=css& */ "./node_modules/style-loader/index.js!./node_modules/css-loader/index.js?!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/Shared/Layout.vue?vue&type=style&index=0&id=6bf30086&scoped=true&lang=css&");
/* harmony import */ var _node_modules_style_loader_index_js_node_modules_css_loader_index_js_ref_6_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_6_2_node_modules_vue_loader_lib_index_js_vue_loader_options_Layout_vue_vue_type_style_index_0_id_6bf30086_scoped_true_lang_css___WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_node_modules_style_loader_index_js_node_modules_css_loader_index_js_ref_6_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_6_2_node_modules_vue_loader_lib_index_js_vue_loader_options_Layout_vue_vue_type_style_index_0_id_6bf30086_scoped_true_lang_css___WEBPACK_IMPORTED_MODULE_0__);
/* harmony reexport (unknown) */ for(var __WEBPACK_IMPORT_KEY__ in _node_modules_style_loader_index_js_node_modules_css_loader_index_js_ref_6_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_6_2_node_modules_vue_loader_lib_index_js_vue_loader_options_Layout_vue_vue_type_style_index_0_id_6bf30086_scoped_true_lang_css___WEBPACK_IMPORTED_MODULE_0__) if(__WEBPACK_IMPORT_KEY__ !== 'default') (function(key) { __webpack_require__.d(__webpack_exports__, key, function() { return _node_modules_style_loader_index_js_node_modules_css_loader_index_js_ref_6_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_6_2_node_modules_vue_loader_lib_index_js_vue_loader_options_Layout_vue_vue_type_style_index_0_id_6bf30086_scoped_true_lang_css___WEBPACK_IMPORTED_MODULE_0__[key]; }) }(__WEBPACK_IMPORT_KEY__));
 /* harmony default export */ __webpack_exports__["default"] = (_node_modules_style_loader_index_js_node_modules_css_loader_index_js_ref_6_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_6_2_node_modules_vue_loader_lib_index_js_vue_loader_options_Layout_vue_vue_type_style_index_0_id_6bf30086_scoped_true_lang_css___WEBPACK_IMPORTED_MODULE_0___default.a); 

/***/ }),

/***/ "./resources/js/Shared/Layout.vue?vue&type=template&id=6bf30086&scoped=true&":
/*!***********************************************************************************!*\
  !*** ./resources/js/Shared/Layout.vue?vue&type=template&id=6bf30086&scoped=true& ***!
  \***********************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_Layout_vue_vue_type_template_id_6bf30086_scoped_true___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../node_modules/vue-loader/lib??vue-loader-options!./Layout.vue?vue&type=template&id=6bf30086&scoped=true& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/Shared/Layout.vue?vue&type=template&id=6bf30086&scoped=true&");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "render", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_Layout_vue_vue_type_template_id_6bf30086_scoped_true___WEBPACK_IMPORTED_MODULE_0__["render"]; });

/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_Layout_vue_vue_type_template_id_6bf30086_scoped_true___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"]; });



/***/ }),

/***/ "./resources/js/Shared/LoadingButton.vue":
/*!***********************************************!*\
  !*** ./resources/js/Shared/LoadingButton.vue ***!
  \***********************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _LoadingButton_vue_vue_type_template_id_5f30d6e2___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./LoadingButton.vue?vue&type=template&id=5f30d6e2& */ "./resources/js/Shared/LoadingButton.vue?vue&type=template&id=5f30d6e2&");
/* harmony import */ var _LoadingButton_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./LoadingButton.vue?vue&type=script&lang=js& */ "./resources/js/Shared/LoadingButton.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport *//* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");





/* normalize component */

var component = Object(_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__["default"])(
  _LoadingButton_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _LoadingButton_vue_vue_type_template_id_5f30d6e2___WEBPACK_IMPORTED_MODULE_0__["render"],
  _LoadingButton_vue_vue_type_template_id_5f30d6e2___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"],
  false,
  null,
  null,
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/Shared/LoadingButton.vue"
/* harmony default export */ __webpack_exports__["default"] = (component.exports);

/***/ }),

/***/ "./resources/js/Shared/LoadingButton.vue?vue&type=script&lang=js&":
/*!************************************************************************!*\
  !*** ./resources/js/Shared/LoadingButton.vue?vue&type=script&lang=js& ***!
  \************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_LoadingButton_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../node_modules/babel-loader/lib??ref--4-0!../../../node_modules/vue-loader/lib??vue-loader-options!./LoadingButton.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/Shared/LoadingButton.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport */ /* harmony default export */ __webpack_exports__["default"] = (_node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_LoadingButton_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/js/Shared/LoadingButton.vue?vue&type=template&id=5f30d6e2&":
/*!******************************************************************************!*\
  !*** ./resources/js/Shared/LoadingButton.vue?vue&type=template&id=5f30d6e2& ***!
  \******************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_LoadingButton_vue_vue_type_template_id_5f30d6e2___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../node_modules/vue-loader/lib??vue-loader-options!./LoadingButton.vue?vue&type=template&id=5f30d6e2& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/Shared/LoadingButton.vue?vue&type=template&id=5f30d6e2&");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "render", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_LoadingButton_vue_vue_type_template_id_5f30d6e2___WEBPACK_IMPORTED_MODULE_0__["render"]; });

/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_LoadingButton_vue_vue_type_template_id_5f30d6e2___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"]; });



/***/ }),

/***/ "./resources/js/Shared/UserMenu.vue":
/*!******************************************!*\
  !*** ./resources/js/Shared/UserMenu.vue ***!
  \******************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _UserMenu_vue_vue_type_template_id_901727b4_scoped_true___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./UserMenu.vue?vue&type=template&id=901727b4&scoped=true& */ "./resources/js/Shared/UserMenu.vue?vue&type=template&id=901727b4&scoped=true&");
/* harmony import */ var _UserMenu_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./UserMenu.vue?vue&type=script&lang=js& */ "./resources/js/Shared/UserMenu.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport *//* harmony import */ var _UserMenu_vue_vue_type_style_index_0_id_901727b4_scoped_true_lang_css___WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./UserMenu.vue?vue&type=style&index=0&id=901727b4&scoped=true&lang=css& */ "./resources/js/Shared/UserMenu.vue?vue&type=style&index=0&id=901727b4&scoped=true&lang=css&");
/* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");






/* normalize component */

var component = Object(_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_3__["default"])(
  _UserMenu_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _UserMenu_vue_vue_type_template_id_901727b4_scoped_true___WEBPACK_IMPORTED_MODULE_0__["render"],
  _UserMenu_vue_vue_type_template_id_901727b4_scoped_true___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"],
  false,
  null,
  "901727b4",
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/Shared/UserMenu.vue"
/* harmony default export */ __webpack_exports__["default"] = (component.exports);

/***/ }),

/***/ "./resources/js/Shared/UserMenu.vue?vue&type=script&lang=js&":
/*!*******************************************************************!*\
  !*** ./resources/js/Shared/UserMenu.vue?vue&type=script&lang=js& ***!
  \*******************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_UserMenu_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../node_modules/babel-loader/lib??ref--4-0!../../../node_modules/vue-loader/lib??vue-loader-options!./UserMenu.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/Shared/UserMenu.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport */ /* harmony default export */ __webpack_exports__["default"] = (_node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_UserMenu_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/js/Shared/UserMenu.vue?vue&type=style&index=0&id=901727b4&scoped=true&lang=css&":
/*!***************************************************************************************************!*\
  !*** ./resources/js/Shared/UserMenu.vue?vue&type=style&index=0&id=901727b4&scoped=true&lang=css& ***!
  \***************************************************************************************************/
/*! no static exports found */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_style_loader_index_js_node_modules_css_loader_index_js_ref_6_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_6_2_node_modules_vue_loader_lib_index_js_vue_loader_options_UserMenu_vue_vue_type_style_index_0_id_901727b4_scoped_true_lang_css___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../node_modules/style-loader!../../../node_modules/css-loader??ref--6-1!../../../node_modules/vue-loader/lib/loaders/stylePostLoader.js!../../../node_modules/postcss-loader/src??ref--6-2!../../../node_modules/vue-loader/lib??vue-loader-options!./UserMenu.vue?vue&type=style&index=0&id=901727b4&scoped=true&lang=css& */ "./node_modules/style-loader/index.js!./node_modules/css-loader/index.js?!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/Shared/UserMenu.vue?vue&type=style&index=0&id=901727b4&scoped=true&lang=css&");
/* harmony import */ var _node_modules_style_loader_index_js_node_modules_css_loader_index_js_ref_6_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_6_2_node_modules_vue_loader_lib_index_js_vue_loader_options_UserMenu_vue_vue_type_style_index_0_id_901727b4_scoped_true_lang_css___WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_node_modules_style_loader_index_js_node_modules_css_loader_index_js_ref_6_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_6_2_node_modules_vue_loader_lib_index_js_vue_loader_options_UserMenu_vue_vue_type_style_index_0_id_901727b4_scoped_true_lang_css___WEBPACK_IMPORTED_MODULE_0__);
/* harmony reexport (unknown) */ for(var __WEBPACK_IMPORT_KEY__ in _node_modules_style_loader_index_js_node_modules_css_loader_index_js_ref_6_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_6_2_node_modules_vue_loader_lib_index_js_vue_loader_options_UserMenu_vue_vue_type_style_index_0_id_901727b4_scoped_true_lang_css___WEBPACK_IMPORTED_MODULE_0__) if(__WEBPACK_IMPORT_KEY__ !== 'default') (function(key) { __webpack_require__.d(__webpack_exports__, key, function() { return _node_modules_style_loader_index_js_node_modules_css_loader_index_js_ref_6_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_6_2_node_modules_vue_loader_lib_index_js_vue_loader_options_UserMenu_vue_vue_type_style_index_0_id_901727b4_scoped_true_lang_css___WEBPACK_IMPORTED_MODULE_0__[key]; }) }(__WEBPACK_IMPORT_KEY__));
 /* harmony default export */ __webpack_exports__["default"] = (_node_modules_style_loader_index_js_node_modules_css_loader_index_js_ref_6_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_6_2_node_modules_vue_loader_lib_index_js_vue_loader_options_UserMenu_vue_vue_type_style_index_0_id_901727b4_scoped_true_lang_css___WEBPACK_IMPORTED_MODULE_0___default.a); 

/***/ }),

/***/ "./resources/js/Shared/UserMenu.vue?vue&type=template&id=901727b4&scoped=true&":
/*!*************************************************************************************!*\
  !*** ./resources/js/Shared/UserMenu.vue?vue&type=template&id=901727b4&scoped=true& ***!
  \*************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_UserMenu_vue_vue_type_template_id_901727b4_scoped_true___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../node_modules/vue-loader/lib??vue-loader-options!./UserMenu.vue?vue&type=template&id=901727b4&scoped=true& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/Shared/UserMenu.vue?vue&type=template&id=901727b4&scoped=true&");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "render", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_UserMenu_vue_vue_type_template_id_901727b4_scoped_true___WEBPACK_IMPORTED_MODULE_0__["render"]; });

/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_UserMenu_vue_vue_type_template_id_901727b4_scoped_true___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"]; });



/***/ })

}]);