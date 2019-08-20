(window["webpackJsonp"] = window["webpackJsonp"] || []).push([[30],{

/***/ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/Pages/Adminland/Flow/Index.vue?vue&type=script&lang=js&":
/*!**************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib??ref--4-0!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/Pages/Adminland/Flow/Index.vue?vue&type=script&lang=js& ***!
  \**************************************************************************************************************************************************************************/
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
//
//
//
//

/* harmony default export */ __webpack_exports__["default"] = ({
  components: {
    Layout: _Shared_Layout__WEBPACK_IMPORTED_MODULE_0__["default"]
  },
  props: {
    flows: {
      type: Array,
      "default": null
    },
    notifications: {
      type: Array,
      "default": null
    }
  },
  mounted: function mounted() {
    if (localStorage.success) {
      this.$snotify.success(localStorage.success, {
        timeout: 2000,
        showProgressBar: true,
        closeOnClick: true,
        pauseOnHover: true
      });
      localStorage.clear();
    }
  }
});

/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/Pages/Adminland/Flow/Index.vue?vue&type=template&id=e79c1362&scoped=true&":
/*!******************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/Pages/Adminland/Flow/Index.vue?vue&type=template&id=e79c1362&scoped=true& ***!
  \******************************************************************************************************************************************************************************************************************************/
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
              _c(
                "li",
                { staticClass: "di" },
                [
                  _c(
                    "inertia-link",
                    {
                      attrs: {
                        href: "/" + _vm.$page.auth.company.id + "/account"
                      }
                    },
                    [
                      _vm._v(
                        "\n            " +
                          _vm._s(_vm.$t("app.breadcrumb_account_home")) +
                          "\n          "
                      )
                    ]
                  )
                ],
                1
              ),
              _vm._v(" "),
              _c("li", { staticClass: "di" }, [
                _vm._v(
                  "\n          " +
                    _vm._s(_vm.$t("app.breadcrumb_account_manage_flows")) +
                    "\n        "
                )
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
                _vm._v(
                  "\n          " +
                    _vm._s(
                      _vm.$t("account.flows_title", {
                        company: _vm.$page.auth.company.name
                      })
                    ) +
                    "\n        "
                )
              ]),
              _vm._v(" "),
              _c(
                "p",
                { staticClass: "relative" },
                [
                  _c("span", { staticClass: "dib mb3 di-l" }, [
                    _vm._v(
                      _vm._s(
                        _vm.$tc(
                          "account.flows_number_flows",
                          _vm.flows.length,
                          {
                            company: _vm.$page.auth.company.name,
                            count: _vm.flows.length
                          }
                        )
                      )
                    )
                  ]),
                  _vm._v(" "),
                  _c(
                    "inertia-link",
                    {
                      staticClass: "btn absolute-l relative dib-l db right-0",
                      attrs: {
                        href:
                          "/" +
                          _vm.$page.auth.company.id +
                          "/account/flows/create",
                        "data-cy": "add-employee-button"
                      }
                    },
                    [
                      _vm._v(
                        "\n            " +
                          _vm._s(_vm.$t("account.flows_cta")) +
                          "\n          "
                      )
                    ]
                  )
                ],
                1
              ),
              _vm._v(" "),
              _c(
                "ul",
                {
                  directives: [
                    {
                      name: "show",
                      rawName: "v-show",
                      value: _vm.flows.length != 0,
                      expression: "flows.length != 0"
                    }
                  ],
                  staticClass: "list pl0 mt0 center"
                },
                _vm._l(_vm.flows, function(flow) {
                  return _c(
                    "li",
                    {
                      key: flow.id,
                      staticClass:
                        "flex items-center lh-copy pa3-l pa1 ph0-l bb b--black-10"
                    },
                    [
                      _c("div", { staticClass: "flex-auto" }, [
                        _c("span", { staticClass: "db b" }, [
                          _vm._v(_vm._s(flow.name) + " "),
                          _c("span", { staticClass: "normal f6" }, [
                            _vm._v("(" + _vm._s(flow.steps.count) + " steps)")
                          ])
                        ]),
                        _vm._v(" "),
                        _c("ul", { staticClass: "f6 list pl0" }, [
                          _c(
                            "li",
                            { staticClass: "di pr2" },
                            [
                              _c(
                                "inertia-link",
                                {
                                  attrs: {
                                    href:
                                      "/" +
                                      _vm.$page.auth.company.id +
                                      "/account/flows/" +
                                      flow.id
                                  }
                                },
                                [
                                  _vm._v(
                                    "\n                    " +
                                      _vm._s(_vm.$t("app.view")) +
                                      "\n                  "
                                  )
                                ]
                              )
                            ],
                            1
                          ),
                          _vm._v(" "),
                          _c(
                            "li",
                            { staticClass: "di pr2" },
                            [
                              _c(
                                "inertia-link",
                                {
                                  attrs: {
                                    href:
                                      "/" +
                                      _vm.$page.auth.company.id +
                                      "/account/flows/" +
                                      flow.id +
                                      "/lock"
                                  }
                                },
                                [
                                  _vm._v(
                                    "\n                    " +
                                      _vm._s(_vm.$t("app.rename")) +
                                      "\n                  "
                                  )
                                ]
                              )
                            ],
                            1
                          ),
                          _vm._v(" "),
                          _c(
                            "li",
                            { staticClass: "di" },
                            [
                              _c(
                                "inertia-link",
                                {
                                  attrs: {
                                    href:
                                      "/" +
                                      _vm.$page.auth.company.id +
                                      "/account/flows/" +
                                      flow.id +
                                      "/destroy"
                                  }
                                },
                                [
                                  _vm._v(
                                    "\n                    " +
                                      _vm._s(_vm.$t("app.delete")) +
                                      "\n                  "
                                  )
                                ]
                              )
                            ],
                            1
                          )
                        ])
                      ])
                    ]
                  )
                }),
                0
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
                    value: _vm.flows.length == 0,
                    expression: "flows.length == 0"
                  }
                ],
                staticClass: "pa3"
              },
              [
                _c("p", { staticClass: "tc measure center mb4 lh-copy" }, [
                  _vm._v(
                    "\n          " +
                      _vm._s(_vm.$t("account.flows_blank")) +
                      "\n        "
                  )
                ]),
                _vm._v(" "),
                _c("img", {
                  staticClass: "db center mb4",
                  attrs: {
                    srcset:
                      "/img/company/account/blank-flow-1x.png" +
                      ", " +
                      "/img/company/account/blank-flow-2x.png" +
                      " 2x"
                  }
                })
              ]
            )
          ]
        )
      ])
    ]
  )
}
var staticRenderFns = []
render._withStripped = true



/***/ }),

/***/ "./resources/js/Pages/Adminland/Flow/Index.vue":
/*!*****************************************************!*\
  !*** ./resources/js/Pages/Adminland/Flow/Index.vue ***!
  \*****************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _Index_vue_vue_type_template_id_e79c1362_scoped_true___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./Index.vue?vue&type=template&id=e79c1362&scoped=true& */ "./resources/js/Pages/Adminland/Flow/Index.vue?vue&type=template&id=e79c1362&scoped=true&");
/* harmony import */ var _Index_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./Index.vue?vue&type=script&lang=js& */ "./resources/js/Pages/Adminland/Flow/Index.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport *//* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");





/* normalize component */

var component = Object(_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__["default"])(
  _Index_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _Index_vue_vue_type_template_id_e79c1362_scoped_true___WEBPACK_IMPORTED_MODULE_0__["render"],
  _Index_vue_vue_type_template_id_e79c1362_scoped_true___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"],
  false,
  null,
  "e79c1362",
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/Pages/Adminland/Flow/Index.vue"
/* harmony default export */ __webpack_exports__["default"] = (component.exports);

/***/ }),

/***/ "./resources/js/Pages/Adminland/Flow/Index.vue?vue&type=script&lang=js&":
/*!******************************************************************************!*\
  !*** ./resources/js/Pages/Adminland/Flow/Index.vue?vue&type=script&lang=js& ***!
  \******************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_Index_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../node_modules/babel-loader/lib??ref--4-0!../../../../../node_modules/vue-loader/lib??vue-loader-options!./Index.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/Pages/Adminland/Flow/Index.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport */ /* harmony default export */ __webpack_exports__["default"] = (_node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_Index_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/js/Pages/Adminland/Flow/Index.vue?vue&type=template&id=e79c1362&scoped=true&":
/*!************************************************************************************************!*\
  !*** ./resources/js/Pages/Adminland/Flow/Index.vue?vue&type=template&id=e79c1362&scoped=true& ***!
  \************************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_Index_vue_vue_type_template_id_e79c1362_scoped_true___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../../node_modules/vue-loader/lib??vue-loader-options!./Index.vue?vue&type=template&id=e79c1362&scoped=true& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/Pages/Adminland/Flow/Index.vue?vue&type=template&id=e79c1362&scoped=true&");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "render", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_Index_vue_vue_type_template_id_e79c1362_scoped_true___WEBPACK_IMPORTED_MODULE_0__["render"]; });

/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_Index_vue_vue_type_template_id_e79c1362_scoped_true___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"]; });



/***/ })

}]);