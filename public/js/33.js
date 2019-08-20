(window["webpackJsonp"] = window["webpackJsonp"] || []).push([[33],{

/***/ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/Pages/Adminland/Flow/Show.vue?vue&type=script&lang=js&":
/*!*************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib??ref--4-0!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/Pages/Adminland/Flow/Show.vue?vue&type=script&lang=js& ***!
  \*************************************************************************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _Shared_Layout__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @/Shared/Layout */ "./resources/js/Shared/Layout.vue");
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
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
    Layout: _Shared_Layout__WEBPACK_IMPORTED_MODULE_0__["default"]
  },
  props: {
    flow: {
      type: Object,
      "default": null
    },
    notifications: {
      type: Array,
      "default": null
    }
  }
});

/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/Pages/Adminland/Flow/Show.vue?vue&type=template&id=5d2b6290&scoped=true&":
/*!*****************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/Pages/Adminland/Flow/Show.vue?vue&type=template&id=5d2b6290&scoped=true& ***!
  \*****************************************************************************************************************************************************************************************************************************/
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
    "layout",
    { attrs: { title: "Home", notifications: _vm.notifications } },
    [
      _c("div", { staticClass: "ph2 ph0-ns" }, [
        _c(
          "div",
          {
            staticClass:
              "mt4-l mt1 mw6 br3 bg-white box center breadcrumb relative z-0 f6 pb2"
          },
          [
            _c("ul", { staticClass: "list ph0 tc-l tl" }, [
              _c(
                "li",
                { staticClass: "di" },
                [
                  _c(
                    "inertia-link",
                    {
                      attrs: {
                        href: "/" + _vm.$page.auth.company.id + "/dashboard"
                      }
                    },
                    [
                      _vm._v(
                        "\n            " +
                          _vm._s(_vm.$page.auth.company.name) +
                          "\n          "
                      )
                    ]
                  )
                ],
                1
              ),
              _vm._v(" "),
              _c("li", { staticClass: "di" }, [
                _vm._v("\n          ...\n        ")
              ]),
              _vm._v(" "),
              _c(
                "li",
                { staticClass: "di" },
                [
                  _c(
                    "inertia-link",
                    {
                      attrs: {
                        href: "/" + _vm.$page.auth.company.id + "/account/flows"
                      }
                    },
                    [
                      _vm._v(
                        "\n            " +
                          _vm._s(
                            _vm.$t("app.breadcrumb_account_manage_flows")
                          ) +
                          "\n          "
                      )
                    ]
                  )
                ],
                1
              ),
              _vm._v(" "),
              _c("li", { staticClass: "di" }, [
                _vm._v("\n          View a flow\n        ")
              ])
            ])
          ]
        ),
        _vm._v(" "),
        _c(
          "div",
          {
            staticClass:
              "mw7 center br3 mb5 bg-white box restricted relative z-1"
          },
          [
            _c("div", { staticClass: "pa3 mt5" }, [
              _c("h2", { staticClass: "tc normal mb4" }, [
                _vm._v("\n          " + _vm._s(_vm.flow.name) + "\n        ")
              ]),
              _vm._v(" "),
              _c("p", { staticClass: "relative" }, [
                _vm._v(
                  "\n          This flow is about " +
                    _vm._s(_vm.$t("account.flow_new_type_" + _vm.flow.type)) +
                    " and has " +
                    _vm._s(_vm.flow.steps.count) +
                    " steps.\n        "
                )
              ]),
              _vm._v(" "),
              _c(
                "ul",
                _vm._l(_vm.flow.steps.data, function(step) {
                  return _c(
                    "li",
                    {
                      directives: [
                        {
                          name: "show",
                          rawName: "v-show",
                          value: step.modifier == "before",
                          expression: "step.modifier == 'before'"
                        }
                      ],
                      key: step.id
                    },
                    [
                      _vm._v(
                        "\n            " +
                          _vm._s(step.number) +
                          " " +
                          _vm._s(step.unit_of_time) +
                          " before " +
                          _vm._s(_vm.flow.type) +
                          "\n            "
                      ),
                      _c(
                        "ul",
                        _vm._l(step.actions.data, function(action) {
                          return _c("li", { key: action.id }, [
                            _vm._v(
                              "\n                " +
                                _vm._s(action.type) +
                                " for " +
                                _vm._s(action.recipient) +
                                "\n              "
                            )
                          ])
                        }),
                        0
                      )
                    ]
                  )
                }),
                0
              ),
              _vm._v(" "),
              _c(
                "ul",
                _vm._l(_vm.flow.steps.data, function(step) {
                  return _c(
                    "li",
                    {
                      directives: [
                        {
                          name: "show",
                          rawName: "v-show",
                          value: step.modifier == "same_day",
                          expression: "step.modifier == 'same_day'"
                        }
                      ],
                      key: step.id
                    },
                    [
                      _vm._v(
                        "\n            On " +
                          _vm._s(_vm.flow.type) +
                          "\n            "
                      ),
                      _c(
                        "ul",
                        _vm._l(step.actions.data, function(action) {
                          return _c("li", { key: action.id }, [
                            _vm._v(
                              "\n                " +
                                _vm._s(action.type) +
                                " for " +
                                _vm._s(action.recipient) +
                                "\n              "
                            )
                          ])
                        }),
                        0
                      )
                    ]
                  )
                }),
                0
              ),
              _vm._v(" "),
              _c(
                "ul",
                _vm._l(_vm.flow.steps.data, function(step) {
                  return _c(
                    "li",
                    {
                      directives: [
                        {
                          name: "show",
                          rawName: "v-show",
                          value: step.modifier == "after",
                          expression: "step.modifier == 'after'"
                        }
                      ],
                      key: step.id
                    },
                    [
                      _vm._v(
                        "\n            " +
                          _vm._s(step.number) +
                          " " +
                          _vm._s(step.unit_of_time) +
                          " after " +
                          _vm._s(_vm.flow.type) +
                          "\n            "
                      ),
                      _c(
                        "ul",
                        _vm._l(step.actions.data, function(action) {
                          return _c("li", { key: action.id }, [
                            _vm._v(
                              "\n                " +
                                _vm._s(action.type) +
                                " for " +
                                _vm._s(action.recipient) +
                                "\n              "
                            )
                          ])
                        }),
                        0
                      )
                    ]
                  )
                }),
                0
              )
            ])
          ]
        )
      ])
    ]
  )
}
var staticRenderFns = []
render._withStripped = true



/***/ }),

/***/ "./resources/js/Pages/Adminland/Flow/Show.vue":
/*!****************************************************!*\
  !*** ./resources/js/Pages/Adminland/Flow/Show.vue ***!
  \****************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _Show_vue_vue_type_template_id_5d2b6290_scoped_true___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./Show.vue?vue&type=template&id=5d2b6290&scoped=true& */ "./resources/js/Pages/Adminland/Flow/Show.vue?vue&type=template&id=5d2b6290&scoped=true&");
/* harmony import */ var _Show_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./Show.vue?vue&type=script&lang=js& */ "./resources/js/Pages/Adminland/Flow/Show.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport *//* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");





/* normalize component */

var component = Object(_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__["default"])(
  _Show_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _Show_vue_vue_type_template_id_5d2b6290_scoped_true___WEBPACK_IMPORTED_MODULE_0__["render"],
  _Show_vue_vue_type_template_id_5d2b6290_scoped_true___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"],
  false,
  null,
  "5d2b6290",
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/Pages/Adminland/Flow/Show.vue"
/* harmony default export */ __webpack_exports__["default"] = (component.exports);

/***/ }),

/***/ "./resources/js/Pages/Adminland/Flow/Show.vue?vue&type=script&lang=js&":
/*!*****************************************************************************!*\
  !*** ./resources/js/Pages/Adminland/Flow/Show.vue?vue&type=script&lang=js& ***!
  \*****************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_Show_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../node_modules/babel-loader/lib??ref--4-0!../../../../../node_modules/vue-loader/lib??vue-loader-options!./Show.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/Pages/Adminland/Flow/Show.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport */ /* harmony default export */ __webpack_exports__["default"] = (_node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_Show_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/js/Pages/Adminland/Flow/Show.vue?vue&type=template&id=5d2b6290&scoped=true&":
/*!***********************************************************************************************!*\
  !*** ./resources/js/Pages/Adminland/Flow/Show.vue?vue&type=template&id=5d2b6290&scoped=true& ***!
  \***********************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_Show_vue_vue_type_template_id_5d2b6290_scoped_true___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../../node_modules/vue-loader/lib??vue-loader-options!./Show.vue?vue&type=template&id=5d2b6290&scoped=true& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/Pages/Adminland/Flow/Show.vue?vue&type=template&id=5d2b6290&scoped=true&");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "render", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_Show_vue_vue_type_template_id_5d2b6290_scoped_true___WEBPACK_IMPORTED_MODULE_0__["render"]; });

/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_Show_vue_vue_type_template_id_5d2b6290_scoped_true___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"]; });



/***/ })

}]);