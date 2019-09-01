(window["webpackJsonp"] = window["webpackJsonp"] || []).push([[48],{

/***/ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/Pages/Adminland/CompanyNews/Index.vue?vue&type=script&lang=js&":
/*!*********************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib??ref--4-0!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/Pages/Adminland/CompanyNews/Index.vue?vue&type=script&lang=js& ***!
  \*********************************************************************************************************************************************************************************/
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

/* harmony default export */ __webpack_exports__["default"] = ({
  components: {
    Layout: _Shared_Layout__WEBPACK_IMPORTED_MODULE_0__["default"]
  },
  props: {
    notifications: {
      type: Array,
      "default": null
    },
    companyNews: {
      type: Array,
      "default": null
    }
  },
  data: function data() {
    return {};
  },
  methods: {
    destroy: function destroy(id) {
      var _this = this;

      axios["delete"]('/' + this.$page.auth.company.id + '/account/positions/' + id).then(function (response) {
        _this.$snotify.success(_this.$t('account.position_success_destroy'), {
          timeout: 2000,
          showProgressBar: true,
          closeOnClick: true,
          pauseOnHover: true
        });

        _this.idToDelete = 0;
        id = _this.news.findIndex(function (x) {
          return x.id === id;
        });

        _this.news.splice(id, 1);
      })["catch"](function (error) {
        _this.form.errors = _.flatten(_.toArray(error.response.data));
      });
    }
  }
});

/***/ }),

/***/ "./node_modules/css-loader/index.js?!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/Pages/Adminland/CompanyNews/Index.vue?vue&type=style&index=0&id=40697985&scoped=true&lang=css&":
/*!****************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/css-loader??ref--6-1!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src??ref--6-2!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/Pages/Adminland/CompanyNews/Index.vue?vue&type=style&index=0&id=40697985&scoped=true&lang=css& ***!
  \****************************************************************************************************************************************************************************************************************************************************************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

exports = module.exports = __webpack_require__(/*! ../../../../../node_modules/css-loader/lib/css-base.js */ "./node_modules/css-loader/lib/css-base.js")(false);
// imports


// module
exports.push([module.i, "\n.list li[data-v-40697985]:last-child {\n  border-bottom: 0;\n}\n", ""]);

// exports


/***/ }),

/***/ "./node_modules/style-loader/index.js!./node_modules/css-loader/index.js?!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/Pages/Adminland/CompanyNews/Index.vue?vue&type=style&index=0&id=40697985&scoped=true&lang=css&":
/*!********************************************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/style-loader!./node_modules/css-loader??ref--6-1!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src??ref--6-2!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/Pages/Adminland/CompanyNews/Index.vue?vue&type=style&index=0&id=40697985&scoped=true&lang=css& ***!
  \********************************************************************************************************************************************************************************************************************************************************************************************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {


var content = __webpack_require__(/*! !../../../../../node_modules/css-loader??ref--6-1!../../../../../node_modules/vue-loader/lib/loaders/stylePostLoader.js!../../../../../node_modules/postcss-loader/src??ref--6-2!../../../../../node_modules/vue-loader/lib??vue-loader-options!./Index.vue?vue&type=style&index=0&id=40697985&scoped=true&lang=css& */ "./node_modules/css-loader/index.js?!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/Pages/Adminland/CompanyNews/Index.vue?vue&type=style&index=0&id=40697985&scoped=true&lang=css&");

if(typeof content === 'string') content = [[module.i, content, '']];

var transform;
var insertInto;



var options = {"hmr":true}

options.transform = transform
options.insertInto = undefined;

var update = __webpack_require__(/*! ../../../../../node_modules/style-loader/lib/addStyles.js */ "./node_modules/style-loader/lib/addStyles.js")(content, options);

if(content.locals) module.exports = content.locals;

if(false) {}

/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/Pages/Adminland/CompanyNews/Index.vue?vue&type=template&id=40697985&scoped=true&":
/*!*************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/Pages/Adminland/CompanyNews/Index.vue?vue&type=template&id=40697985&scoped=true& ***!
  \*************************************************************************************************************************************************************************************************************************************/
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
                    _vm._s(
                      _vm.$t("app.breadcrumb_account_manage_company_news")
                    ) +
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
                      _vm.$t("account.company_news_title", {
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
                  _c(
                    "span",
                    {
                      staticClass: "dib mb3 di-l",
                      class: _vm.news.length == 0 ? "white" : ""
                    },
                    [
                      _vm._v(
                        _vm._s(
                          _vm.$tc(
                            "account.company_news_number_news",
                            _vm.news.length,
                            {
                              company: _vm.$page.auth.company.name,
                              count: _vm.news.length
                            }
                          )
                        )
                      )
                    ]
                  ),
                  _vm._v(" "),
                  _c(
                    "inertia-link",
                    {
                      staticClass: "btn absolute-l relative dib-l db right-0",
                      attrs: { "data-cy": "add-position-button" }
                    },
                    [_vm._v(_vm._s(_vm.$t("account.company_news_cta")))]
                  )
                ],
                1
              ),
              _vm._v(" "),
              _c("p", [
                _vm._v(
                  "The job position is what you would probably put on a resume to show what work you actually did."
                )
              ]),
              _vm._v(" "),
              _c(
                "ul",
                {
                  directives: [
                    {
                      name: "show",
                      rawName: "v-show",
                      value: _vm.news.length != 0,
                      expression: "news.length != 0"
                    }
                  ],
                  staticClass: "list pl0 mv0 center ba br2 bb-gray",
                  attrs: { "data-cy": "news-list" }
                },
                _vm._l(_vm.companyNews, function(news) {
                  return _c(
                    "li",
                    {
                      key: news.id,
                      staticClass: "pv3 ph2 bb bb-gray bb-gray-hover"
                    },
                    [
                      _vm._v(
                        "\n            " +
                          _vm._s(news.title) +
                          "\n\n            "
                      ),
                      _vm._v(" "),
                      _c(
                        "ul",
                        {
                          directives: [
                            {
                              name: "show",
                              rawName: "v-show",
                              value: _vm.idToUpdate != news.id,
                              expression: "idToUpdate != news.id"
                            }
                          ],
                          staticClass: "list pa0 ma0 di-ns db fr-ns mt2 mt0-ns"
                        },
                        [
                          _c("li", { staticClass: "di mr2" }, [
                            _c(
                              "a",
                              {
                                staticClass: "pointer",
                                attrs: {
                                  "data-cy": "list-rename-button-" + news.id
                                },
                                on: {
                                  click: function($event) {
                                    $event.preventDefault()
                                    _vm.displayUpdateModal(_vm.position)
                                    _vm.form.title = news.title
                                  }
                                }
                              },
                              [_vm._v(_vm._s(_vm.$t("app.rename")))]
                            )
                          ]),
                          _vm._v(" "),
                          _vm.idToDelete == news.id
                            ? _c("li", { staticClass: "di" }, [
                                _vm._v(
                                  "\n                " +
                                    _vm._s(_vm.$t("app.sure")) +
                                    "\n                "
                                ),
                                _c(
                                  "a",
                                  {
                                    staticClass: "c-delete mr1 pointer",
                                    attrs: {
                                      "data-cy":
                                        "list-delete-confirm-button-" + news.id
                                    },
                                    on: {
                                      click: function($event) {
                                        $event.preventDefault()
                                        return _vm.destroy(news.id)
                                      }
                                    }
                                  },
                                  [_vm._v(_vm._s(_vm.$t("app.yes")))]
                                ),
                                _vm._v(" "),
                                _c(
                                  "a",
                                  {
                                    staticClass: "pointer",
                                    attrs: {
                                      "data-cy":
                                        "list-delete-cancel-button-" + news.id
                                    },
                                    on: {
                                      click: function($event) {
                                        $event.preventDefault()
                                        _vm.idToDelete = 0
                                      }
                                    }
                                  },
                                  [_vm._v(_vm._s(_vm.$t("app.no")))]
                                )
                              ])
                            : _c("li", { staticClass: "di" }, [
                                _c(
                                  "a",
                                  {
                                    staticClass: "pointer",
                                    attrs: {
                                      "data-cy": "list-delete-button-" + news.id
                                    },
                                    on: {
                                      click: function($event) {
                                        $event.preventDefault()
                                        _vm.idToDelete = news.id
                                      }
                                    }
                                  },
                                  [_vm._v(_vm._s(_vm.$t("app.delete")))]
                                )
                              ])
                        ]
                      )
                    ]
                  )
                }),
                0
              ),
              _vm._v(" "),
              _c(
                "div",
                {
                  directives: [
                    {
                      name: "show",
                      rawName: "v-show",
                      value: _vm.news.length == 0,
                      expression: "news.length == 0"
                    }
                  ],
                  staticClass: "pa3 mt5"
                },
                [
                  _c("p", { staticClass: "tc measure center mb4 lh-copy" }, [
                    _vm._v(
                      "\n            " +
                        _vm._s(_vm.$t("account.positions_blank")) +
                        "\n          "
                    )
                  ]),
                  _vm._v(" "),
                  _c("img", {
                    staticClass: "db center mb4",
                    attrs: {
                      srcset:
                        "/img/company/account/blank-position-1x.png" +
                        ", " +
                        "/img/company/account/blank-position-2x.png" +
                        " 2x"
                    }
                  })
                ]
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

/***/ "./resources/js/Pages/Adminland/CompanyNews/Index.vue":
/*!************************************************************!*\
  !*** ./resources/js/Pages/Adminland/CompanyNews/Index.vue ***!
  \************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _Index_vue_vue_type_template_id_40697985_scoped_true___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./Index.vue?vue&type=template&id=40697985&scoped=true& */ "./resources/js/Pages/Adminland/CompanyNews/Index.vue?vue&type=template&id=40697985&scoped=true&");
/* harmony import */ var _Index_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./Index.vue?vue&type=script&lang=js& */ "./resources/js/Pages/Adminland/CompanyNews/Index.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport *//* harmony import */ var _Index_vue_vue_type_style_index_0_id_40697985_scoped_true_lang_css___WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./Index.vue?vue&type=style&index=0&id=40697985&scoped=true&lang=css& */ "./resources/js/Pages/Adminland/CompanyNews/Index.vue?vue&type=style&index=0&id=40697985&scoped=true&lang=css&");
/* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ../../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");






/* normalize component */

var component = Object(_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_3__["default"])(
  _Index_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _Index_vue_vue_type_template_id_40697985_scoped_true___WEBPACK_IMPORTED_MODULE_0__["render"],
  _Index_vue_vue_type_template_id_40697985_scoped_true___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"],
  false,
  null,
  "40697985",
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/Pages/Adminland/CompanyNews/Index.vue"
/* harmony default export */ __webpack_exports__["default"] = (component.exports);

/***/ }),

/***/ "./resources/js/Pages/Adminland/CompanyNews/Index.vue?vue&type=script&lang=js&":
/*!*************************************************************************************!*\
  !*** ./resources/js/Pages/Adminland/CompanyNews/Index.vue?vue&type=script&lang=js& ***!
  \*************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_Index_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../node_modules/babel-loader/lib??ref--4-0!../../../../../node_modules/vue-loader/lib??vue-loader-options!./Index.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/Pages/Adminland/CompanyNews/Index.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport */ /* harmony default export */ __webpack_exports__["default"] = (_node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_Index_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/js/Pages/Adminland/CompanyNews/Index.vue?vue&type=style&index=0&id=40697985&scoped=true&lang=css&":
/*!*********************************************************************************************************************!*\
  !*** ./resources/js/Pages/Adminland/CompanyNews/Index.vue?vue&type=style&index=0&id=40697985&scoped=true&lang=css& ***!
  \*********************************************************************************************************************/
/*! no static exports found */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_style_loader_index_js_node_modules_css_loader_index_js_ref_6_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_6_2_node_modules_vue_loader_lib_index_js_vue_loader_options_Index_vue_vue_type_style_index_0_id_40697985_scoped_true_lang_css___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../node_modules/style-loader!../../../../../node_modules/css-loader??ref--6-1!../../../../../node_modules/vue-loader/lib/loaders/stylePostLoader.js!../../../../../node_modules/postcss-loader/src??ref--6-2!../../../../../node_modules/vue-loader/lib??vue-loader-options!./Index.vue?vue&type=style&index=0&id=40697985&scoped=true&lang=css& */ "./node_modules/style-loader/index.js!./node_modules/css-loader/index.js?!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/Pages/Adminland/CompanyNews/Index.vue?vue&type=style&index=0&id=40697985&scoped=true&lang=css&");
/* harmony import */ var _node_modules_style_loader_index_js_node_modules_css_loader_index_js_ref_6_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_6_2_node_modules_vue_loader_lib_index_js_vue_loader_options_Index_vue_vue_type_style_index_0_id_40697985_scoped_true_lang_css___WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_node_modules_style_loader_index_js_node_modules_css_loader_index_js_ref_6_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_6_2_node_modules_vue_loader_lib_index_js_vue_loader_options_Index_vue_vue_type_style_index_0_id_40697985_scoped_true_lang_css___WEBPACK_IMPORTED_MODULE_0__);
/* harmony reexport (unknown) */ for(var __WEBPACK_IMPORT_KEY__ in _node_modules_style_loader_index_js_node_modules_css_loader_index_js_ref_6_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_6_2_node_modules_vue_loader_lib_index_js_vue_loader_options_Index_vue_vue_type_style_index_0_id_40697985_scoped_true_lang_css___WEBPACK_IMPORTED_MODULE_0__) if(__WEBPACK_IMPORT_KEY__ !== 'default') (function(key) { __webpack_require__.d(__webpack_exports__, key, function() { return _node_modules_style_loader_index_js_node_modules_css_loader_index_js_ref_6_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_6_2_node_modules_vue_loader_lib_index_js_vue_loader_options_Index_vue_vue_type_style_index_0_id_40697985_scoped_true_lang_css___WEBPACK_IMPORTED_MODULE_0__[key]; }) }(__WEBPACK_IMPORT_KEY__));
 /* harmony default export */ __webpack_exports__["default"] = (_node_modules_style_loader_index_js_node_modules_css_loader_index_js_ref_6_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_6_2_node_modules_vue_loader_lib_index_js_vue_loader_options_Index_vue_vue_type_style_index_0_id_40697985_scoped_true_lang_css___WEBPACK_IMPORTED_MODULE_0___default.a); 

/***/ }),

/***/ "./resources/js/Pages/Adminland/CompanyNews/Index.vue?vue&type=template&id=40697985&scoped=true&":
/*!*******************************************************************************************************!*\
  !*** ./resources/js/Pages/Adminland/CompanyNews/Index.vue?vue&type=template&id=40697985&scoped=true& ***!
  \*******************************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_Index_vue_vue_type_template_id_40697985_scoped_true___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../../node_modules/vue-loader/lib??vue-loader-options!./Index.vue?vue&type=template&id=40697985&scoped=true& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/Pages/Adminland/CompanyNews/Index.vue?vue&type=template&id=40697985&scoped=true&");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "render", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_Index_vue_vue_type_template_id_40697985_scoped_true___WEBPACK_IMPORTED_MODULE_0__["render"]; });

/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_Index_vue_vue_type_template_id_40697985_scoped_true___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"]; });



/***/ })

}]);