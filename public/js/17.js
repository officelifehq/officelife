(window["webpackJsonp"] = window["webpackJsonp"] || []).push([[17],{

/***/ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/Pages/Auth/Invitation/AcceptInvitationUnlogged.vue?vue&type=script&lang=js&":
/*!**********************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib??ref--4-0!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/Pages/Auth/Invitation/AcceptInvitationUnlogged.vue?vue&type=script&lang=js& ***!
  \**********************************************************************************************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
function _typeof(obj) { if (typeof Symbol === "function" && typeof Symbol.iterator === "symbol") { _typeof = function _typeof(obj) { return typeof obj; }; } else { _typeof = function _typeof(obj) { return obj && typeof Symbol === "function" && obj.constructor === Symbol && obj !== Symbol.prototype ? "symbol" : typeof obj; }; } return _typeof(obj); }

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
  props: {
    company: {
      type: Object,
      "default": null
    },
    employee: {
      type: Object,
      "default": null
    },
    invitationLink: {
      type: String,
      "default": ''
    }
  },
  data: function data() {
    return {
      displayCreateAccount: false,
      displaySignin: false,
      form: {
        email: null,
        password: null,
        errors: []
      },
      loadingState: '',
      errorTemplate: Error
    };
  },
  mounted: function mounted() {
    this.form.email = this.employee.email;
  },
  methods: {
    submit: function submit() {
      var _this = this;

      this.loadingState = 'loading';
      axios.post('/invite/employee/' + this.invitationLink + '/join', this.form).then(function (response) {
        Turbolinks.visit('/home');
      })["catch"](function (error) {
        _this.loadingState = null;

        if (_typeof(error.response.data) === 'object') {
          _this.form.errors = _.flatten(_.toArray(error.response.data));
        } else {
          _this.form.errors = [_this.$t('app.error_try_again')];
        }
      });
    }
  }
});

/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/Pages/Auth/Invitation/AcceptInvitationUnlogged.vue?vue&type=template&id=b4cae598&":
/*!**************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/Pages/Auth/Invitation/AcceptInvitationUnlogged.vue?vue&type=template&id=b4cae598& ***!
  \**************************************************************************************************************************************************************************************************************************************/
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
  return _c("div", { staticClass: "ph2 ph0-ns" }, [
    _c("div", { staticClass: "cf mt3 mw6 center tc" }, [
      _c("h2", { staticClass: "lh-title" }, [
        _vm._v(
          "\n      " +
            _vm._s(
              _vm.$t("auth.invitation_unlogged_title", {
                name: _vm.company.name
              })
            ) +
            "\n    "
        )
      ]),
      _vm._v(" "),
      _c(
        "p",
        {
          directives: [
            {
              name: "show",
              rawName: "v-show",
              value: !_vm.displayCreateAccount && !_vm.displaySignin,
              expression: "!displayCreateAccount && !displaySignin"
            }
          ]
        },
        [
          _vm._v(
            "\n      " +
              _vm._s(_vm.$t("auth.invitation_unlogged_desc")) +
              "\n    "
          )
        ]
      ),
      _vm._v(" "),
      _c(
        "p",
        {
          directives: [
            {
              name: "show",
              rawName: "v-show",
              value: _vm.displayCreateAccount,
              expression: "displayCreateAccount"
            }
          ]
        },
        [
          _c(
            "a",
            {
              staticClass: "pointer",
              on: {
                click: function($event) {
                  _vm.displaySignin = true
                  _vm.displayCreateAccount = false
                }
              }
            },
            [
              _vm._v(
                "← " +
                  _vm._s(
                    _vm.$t("auth.invitation_unlogged_create_account_instead")
                  )
              )
            ]
          )
        ]
      ),
      _vm._v(" "),
      _c(
        "p",
        {
          directives: [
            {
              name: "show",
              rawName: "v-show",
              value: _vm.displaySignin,
              expression: "displaySignin"
            }
          ]
        },
        [
          _c(
            "a",
            {
              staticClass: "pointer",
              on: {
                click: function($event) {
                  _vm.displayCreateAccount = true
                  _vm.displaySignin = false
                }
              }
            },
            [
              _vm._v(
                "← " + _vm._s(_vm.$t("auth.invitation_unlogged_login_instead"))
              )
            ]
          )
        ]
      )
    ]),
    _vm._v(" "),
    _c(
      "div",
      {
        directives: [
          {
            name: "show",
            rawName: "v-show",
            value: !_vm.displayCreateAccount && !_vm.displaySignin,
            expression: "!displayCreateAccount && !displaySignin"
          }
        ],
        staticClass: "cf mt3 mw6 center br3 mb3 bg-white box pa3 pointer",
        on: {
          click: function($event) {
            _vm.displayCreateAccount = true
          }
        }
      },
      [
        _c("p", { staticClass: "fw5" }, [
          _vm._v(
            "\n      " +
              _vm._s(_vm.$t("auth.invitation_unlogged_choice_account_title")) +
              "\n    "
          )
        ]),
        _vm._v(" "),
        _c("p", [
          _vm._v(_vm._s(_vm.$t("auth.invitation_unlogged_choice_account_desc")))
        ])
      ]
    ),
    _vm._v(" "),
    _c(
      "div",
      {
        directives: [
          {
            name: "show",
            rawName: "v-show",
            value: !_vm.displayCreateAccount && !_vm.displaySignin,
            expression: "!displayCreateAccount && !displaySignin"
          }
        ],
        staticClass: "cf mt3 mw6 center br3 mb3 bg-white box pa3 pointer",
        on: {
          click: function($event) {
            _vm.displaySignin = true
          }
        }
      },
      [
        _c("p", { staticClass: "fw5" }, [
          _vm._v(
            "\n      " +
              _vm._s(_vm.$t("auth.invitation_unlogged_choice_login_title")) +
              "\n    "
          )
        ]),
        _vm._v(" "),
        _c("p", [
          _vm._v(_vm._s(_vm.$t("auth.invitation_unlogged_choice_login_desc")))
        ])
      ]
    ),
    _vm._v(" "),
    _c(
      "div",
      {
        directives: [
          {
            name: "show",
            rawName: "v-show",
            value: _vm.displayCreateAccount,
            expression: "displayCreateAccount"
          }
        ],
        staticClass: "cf mw6 center br3 mb3 bg-white box"
      },
      [
        _c(
          "div",
          { staticClass: "pa3" },
          [
            _c("h2", { staticClass: "tc f4" }, [
              _vm._v(
                "\n        " +
                  _vm._s(_vm.$t("auth.invitation_unlogged_choice_account")) +
                  "\n      "
              )
            ]),
            _vm._v(" "),
            _c("errors", { attrs: { errors: _vm.form.errors } }),
            _vm._v(" "),
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
                _c("div", {}, [
                  _c(
                    "label",
                    {
                      staticClass: "db fw4 lh-copy f6",
                      attrs: { for: "email" }
                    },
                    [_vm._v(_vm._s(_vm.$t("auth.register_email")))]
                  ),
                  _vm._v(" "),
                  _c("input", {
                    directives: [
                      {
                        name: "model",
                        rawName: "v-model",
                        value: _vm.form.email,
                        expression: "form.email"
                      }
                    ],
                    staticClass: "br2 f5 w-100 ba b--black-40 pa2 outline-0",
                    attrs: { type: "email", name: "email", required: "" },
                    domProps: { value: _vm.form.email },
                    on: {
                      input: function($event) {
                        if ($event.target.composing) {
                          return
                        }
                        _vm.$set(_vm.form, "email", $event.target.value)
                      }
                    }
                  }),
                  _vm._v(" "),
                  _c("p", { staticClass: "f7 mb4 lh-title" }, [
                    _vm._v(
                      "\n            " +
                        _vm._s(_vm.$t("auth.register_email_help")) +
                        "\n          "
                    )
                  ])
                ]),
                _vm._v(" "),
                _c("div", { staticClass: "mb4" }, [
                  _c(
                    "label",
                    {
                      staticClass: "db fw4 lh-copy f6",
                      attrs: { for: "password" }
                    },
                    [_vm._v(_vm._s(_vm.$t("auth.register_password")))]
                  ),
                  _vm._v(" "),
                  _c("input", {
                    directives: [
                      {
                        name: "model",
                        rawName: "v-model",
                        value: _vm.form.password,
                        expression: "form.password"
                      }
                    ],
                    staticClass: "br2 f5 w-100 ba b--black-40 pa2 outline-0",
                    attrs: { type: "password", name: "password", required: "" },
                    domProps: { value: _vm.form.password },
                    on: {
                      input: function($event) {
                        if ($event.target.composing) {
                          return
                        }
                        _vm.$set(_vm.form, "password", $event.target.value)
                      }
                    }
                  })
                ]),
                _vm._v(" "),
                _c("div", {}, [
                  _c("div", { staticClass: "flex-ns justify-between" }, [
                    _c(
                      "div",
                      [
                        _c("loading-button", {
                          attrs: {
                            classes: "btn add w-auto-ns w-100 mb2 pv2 ph3",
                            state: _vm.loadingState,
                            text: _vm.$t("auth.register_cta")
                          }
                        })
                      ],
                      1
                    )
                  ])
                ])
              ]
            )
          ],
          1
        )
      ]
    ),
    _vm._v(" "),
    _c(
      "div",
      {
        directives: [
          {
            name: "show",
            rawName: "v-show",
            value: _vm.displaySignin,
            expression: "displaySignin"
          }
        ],
        staticClass: "cf mw6 center br3 mb3 bg-white box"
      },
      [
        _c(
          "div",
          { staticClass: "pa3" },
          [
            _c("h2", { staticClass: "tc f4" }, [
              _vm._v(
                "\n        " +
                  _vm._s(_vm.$t("auth.invitation_unlogged_choice_login")) +
                  "\n      "
              )
            ]),
            _vm._v(" "),
            _c("errors", { attrs: { errors: _vm.form.errors } }),
            _vm._v(" "),
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
                _c("div", {}, [
                  _c(
                    "label",
                    {
                      staticClass: "db fw4 lh-copy f6",
                      attrs: { for: "email" }
                    },
                    [_vm._v(_vm._s(_vm.$t("auth.register_email")))]
                  ),
                  _vm._v(" "),
                  _c("input", {
                    directives: [
                      {
                        name: "model",
                        rawName: "v-model",
                        value: _vm.form.email,
                        expression: "form.email"
                      }
                    ],
                    staticClass: "br2 f5 w-100 ba b--black-40 pa2 outline-0",
                    attrs: { type: "email", name: "email", required: "" },
                    domProps: { value: _vm.form.email },
                    on: {
                      input: function($event) {
                        if ($event.target.composing) {
                          return
                        }
                        _vm.$set(_vm.form, "email", $event.target.value)
                      }
                    }
                  })
                ]),
                _vm._v(" "),
                _c("div", { staticClass: "mb4" }, [
                  _c(
                    "label",
                    {
                      staticClass: "db fw4 lh-copy f6",
                      attrs: { for: "password" }
                    },
                    [_vm._v(_vm._s(_vm.$t("auth.register_password")))]
                  ),
                  _vm._v(" "),
                  _c("input", {
                    directives: [
                      {
                        name: "model",
                        rawName: "v-model",
                        value: _vm.form.password,
                        expression: "form.password"
                      }
                    ],
                    staticClass: "br2 f5 w-100 ba b--black-40 pa2 outline-0",
                    attrs: { type: "password", name: "password", required: "" },
                    domProps: { value: _vm.form.password },
                    on: {
                      input: function($event) {
                        if ($event.target.composing) {
                          return
                        }
                        _vm.$set(_vm.form, "password", $event.target.value)
                      }
                    }
                  })
                ]),
                _vm._v(" "),
                _c("div", {}, [
                  _c("div", { staticClass: "flex-ns justify-between" }, [
                    _c(
                      "div",
                      [
                        _c("loading-button", {
                          attrs: {
                            classes: "btn add w-auto-ns w-100 mb2 pv2 ph3",
                            state: _vm.loadingState,
                            text: _vm.$t("auth.login_cta")
                          }
                        })
                      ],
                      1
                    )
                  ])
                ])
              ]
            )
          ],
          1
        )
      ]
    )
  ])
}
var staticRenderFns = []
render._withStripped = true



/***/ }),

/***/ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js":
/*!********************************************************************!*\
  !*** ./node_modules/vue-loader/lib/runtime/componentNormalizer.js ***!
  \********************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "default", function() { return normalizeComponent; });
/* globals __VUE_SSR_CONTEXT__ */

// IMPORTANT: Do NOT use ES2015 features in this file (except for modules).
// This module is a runtime utility for cleaner component module output and will
// be included in the final webpack user bundle.

function normalizeComponent (
  scriptExports,
  render,
  staticRenderFns,
  functionalTemplate,
  injectStyles,
  scopeId,
  moduleIdentifier, /* server only */
  shadowMode /* vue-cli only */
) {
  // Vue.extend constructor export interop
  var options = typeof scriptExports === 'function'
    ? scriptExports.options
    : scriptExports

  // render functions
  if (render) {
    options.render = render
    options.staticRenderFns = staticRenderFns
    options._compiled = true
  }

  // functional template
  if (functionalTemplate) {
    options.functional = true
  }

  // scopedId
  if (scopeId) {
    options._scopeId = 'data-v-' + scopeId
  }

  var hook
  if (moduleIdentifier) { // server build
    hook = function (context) {
      // 2.3 injection
      context =
        context || // cached call
        (this.$vnode && this.$vnode.ssrContext) || // stateful
        (this.parent && this.parent.$vnode && this.parent.$vnode.ssrContext) // functional
      // 2.2 with runInNewContext: true
      if (!context && typeof __VUE_SSR_CONTEXT__ !== 'undefined') {
        context = __VUE_SSR_CONTEXT__
      }
      // inject component styles
      if (injectStyles) {
        injectStyles.call(this, context)
      }
      // register component module identifier for async chunk inferrence
      if (context && context._registeredComponents) {
        context._registeredComponents.add(moduleIdentifier)
      }
    }
    // used by ssr in case component is cached and beforeCreate
    // never gets called
    options._ssrRegister = hook
  } else if (injectStyles) {
    hook = shadowMode
      ? function () { injectStyles.call(this, this.$root.$options.shadowRoot) }
      : injectStyles
  }

  if (hook) {
    if (options.functional) {
      // for template-only hot-reload because in that case the render fn doesn't
      // go through the normalizer
      options._injectStyles = hook
      // register for functioal component in vue file
      var originalRender = options.render
      options.render = function renderWithStyleInjection (h, context) {
        hook.call(context)
        return originalRender(h, context)
      }
    } else {
      // inject component registration as beforeCreate hook
      var existing = options.beforeCreate
      options.beforeCreate = existing
        ? [].concat(existing, hook)
        : [hook]
    }
  }

  return {
    exports: scriptExports,
    options: options
  }
}


/***/ }),

/***/ "./resources/js/Pages/Auth/Invitation/AcceptInvitationUnlogged.vue":
/*!*************************************************************************!*\
  !*** ./resources/js/Pages/Auth/Invitation/AcceptInvitationUnlogged.vue ***!
  \*************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _AcceptInvitationUnlogged_vue_vue_type_template_id_b4cae598___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./AcceptInvitationUnlogged.vue?vue&type=template&id=b4cae598& */ "./resources/js/Pages/Auth/Invitation/AcceptInvitationUnlogged.vue?vue&type=template&id=b4cae598&");
/* harmony import */ var _AcceptInvitationUnlogged_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./AcceptInvitationUnlogged.vue?vue&type=script&lang=js& */ "./resources/js/Pages/Auth/Invitation/AcceptInvitationUnlogged.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport *//* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");





/* normalize component */

var component = Object(_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__["default"])(
  _AcceptInvitationUnlogged_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _AcceptInvitationUnlogged_vue_vue_type_template_id_b4cae598___WEBPACK_IMPORTED_MODULE_0__["render"],
  _AcceptInvitationUnlogged_vue_vue_type_template_id_b4cae598___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"],
  false,
  null,
  null,
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/Pages/Auth/Invitation/AcceptInvitationUnlogged.vue"
/* harmony default export */ __webpack_exports__["default"] = (component.exports);

/***/ }),

/***/ "./resources/js/Pages/Auth/Invitation/AcceptInvitationUnlogged.vue?vue&type=script&lang=js&":
/*!**************************************************************************************************!*\
  !*** ./resources/js/Pages/Auth/Invitation/AcceptInvitationUnlogged.vue?vue&type=script&lang=js& ***!
  \**************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_AcceptInvitationUnlogged_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../node_modules/babel-loader/lib??ref--4-0!../../../../../node_modules/vue-loader/lib??vue-loader-options!./AcceptInvitationUnlogged.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/Pages/Auth/Invitation/AcceptInvitationUnlogged.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport */ /* harmony default export */ __webpack_exports__["default"] = (_node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_AcceptInvitationUnlogged_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/js/Pages/Auth/Invitation/AcceptInvitationUnlogged.vue?vue&type=template&id=b4cae598&":
/*!********************************************************************************************************!*\
  !*** ./resources/js/Pages/Auth/Invitation/AcceptInvitationUnlogged.vue?vue&type=template&id=b4cae598& ***!
  \********************************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_AcceptInvitationUnlogged_vue_vue_type_template_id_b4cae598___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../../node_modules/vue-loader/lib??vue-loader-options!./AcceptInvitationUnlogged.vue?vue&type=template&id=b4cae598& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/Pages/Auth/Invitation/AcceptInvitationUnlogged.vue?vue&type=template&id=b4cae598&");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "render", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_AcceptInvitationUnlogged_vue_vue_type_template_id_b4cae598___WEBPACK_IMPORTED_MODULE_0__["render"]; });

/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_AcceptInvitationUnlogged_vue_vue_type_template_id_b4cae598___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"]; });



/***/ })

}]);