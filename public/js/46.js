(window["webpackJsonp"] = window["webpackJsonp"] || []).push([[46],{

/***/ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/Pages/Team/Index.vue?vue&type=script&lang=js&":
/*!****************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib??ref--4-0!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/Pages/Team/Index.vue?vue&type=script&lang=js& ***!
  \****************************************************************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _Shared_Layout__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @/Shared/Layout */ "./resources/js/Shared/Layout.vue");
/* harmony import */ var vue_click_outside__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! vue-click-outside */ "./node_modules/vue-click-outside/index.js");
/* harmony import */ var vue_click_outside__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(vue_click_outside__WEBPACK_IMPORTED_MODULE_1__);
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
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
  directives: {
    ClickOutside: vue_click_outside__WEBPACK_IMPORTED_MODULE_1___default.a
  },
  props: {
    notifications: {
      type: Array,
      "default": null
    },
    employees: {
      type: Array,
      "default": null
    },
    mostRecentEmployee: {
      type: String,
      "default": null
    },
    employeeCount: {
      type: Number,
      "default": null
    },
    team: {
      type: Object,
      "default": null
    }
  },
  data: function data() {
    return {};
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
  },
  methods: {}
});

/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/Pages/Team/Index.vue?vue&type=template&id=16900ce9&scoped=true&":
/*!********************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/Pages/Team/Index.vue?vue&type=template&id=16900ce9&scoped=true& ***!
  \********************************************************************************************************************************************************************************************************************/
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
      _c("div", { staticClass: "ph2 ph5-ns" }, [
        _c(
          "div",
          {
            staticClass:
              "mt4-l mt1 mw7 br3 bg-white box center breadcrumb relative z-0 f6 pb2"
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
                    [_vm._v(_vm._s(_vm.$page.auth.company.name))]
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
                        href: "/" + _vm.$page.auth.company.id + "/teams"
                      }
                    },
                    [_vm._v(_vm._s(_vm.$t("app.breadcrumb_team_list")))]
                  )
                ],
                1
              ),
              _vm._v(" "),
              _c("li", { staticClass: "di" }, [
                _vm._v("\n          " + _vm._s(_vm.team.name) + "\n        ")
              ])
            ])
          ]
        ),
        _vm._v(" "),
        _c(
          "div",
          { staticClass: "mw8 center br3 mb4 bg-white box relative z-1" },
          [
            _c("div", { staticClass: "pa3 relative" }, [
              _c("h2", { staticClass: "normal ma0 mb2" }, [
                _vm._v("\n          " + _vm._s(_vm.team.name) + "\n        ")
              ]),
              _vm._v(" "),
              _c("ul", { staticClass: "ma0 pa0 f6" }, [
                _c("li", { staticClass: "di mr2" }, [
                  _c("img", {
                    staticClass: "pr1",
                    attrs: { src: "/img/leader.svg" }
                  }),
                  _vm._v("\n            Lead by John Rambo\n          ")
                ]),
                _vm._v(" "),
                _c("li", { staticClass: "di" }, [
                  _c("img", {
                    staticClass: "pr1",
                    attrs: { src: "/img/location.svg" }
                  }),
                  _vm._v("\n            Office #5, south east\n          ")
                ])
              ])
            ])
          ]
        ),
        _vm._v(" "),
        _c("div", { staticClass: "cf mw6 center mb4" }, [
          _c("div", { staticClass: "bg-white box pa3 mb4" }, [
            _c("p", { staticClass: "lh-copy ma0 mb2" }, [
              _vm._v(
                "\n          This team has " +
                  _vm._s(_vm.employeeCount) +
                  " members, the most recent being "
              ),
              _c("a", { attrs: { href: "" } }, [_vm._v("sdfsd")]),
              _vm._v(".\n        ")
            ]),
            _vm._v(" "),
            _c("p", { staticClass: "ma0" }, [
              _c("a", { attrs: { href: "" } }, [_vm._v("View team members")])
            ])
          ]),
          _vm._v(" "),
          _c("div", { staticClass: "bg-white box pa3 mb4" }, [
            _c("p", { staticClass: "ma0 mb2" }, [
              _vm._v(
                "\n          Want to find out how someone in this team can help you?\n        "
              )
            ]),
            _vm._v(" "),
            _c("p", { staticClass: "ma0" }, [
              _c("a", { attrs: { href: "" } }, [
                _vm._v("Read about the different ways they can help you")
              ])
            ])
          ]),
          _vm._v(" "),
          _c("div", { staticClass: "bg-white box pa3 mb4" }, [
            _c("p", { staticClass: "f6 ma0 mb1" }, [
              _vm._v("\n          2 days ago\n        ")
            ]),
            _vm._v(" "),
            _c("p", { staticClass: "lh-copy ma0 mb2" }, [
              _vm._v(
                "\n          Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec a diam lectus. Sed sit amet ipsum mauris. Maecenas congue ligula ac quam viverra nec consectetur ante hendrerit. Donec et mollis dolor. Praesent et diam eget libero egestas mattis sit amet vitae augue. Nam tincidunt congue enim, ut porta lorem lacinia consectetur. Donec ut libero sed arcu vehicula ultricies a non tortor.\n        "
              )
            ]),
            _vm._v(" "),
            _c("p", { staticClass: "ma0" }, [
              _c("a", { attrs: { href: "" } }, [_vm._v("Read all the news")])
            ])
          ]),
          _vm._v(" "),
          _c("div", { staticClass: "bg-white box pa3" }, [
            _c("p", { staticClass: "ma0 mb2" }, [
              _vm._v("\n          New to the team?\n        ")
            ]),
            _vm._v(" "),
            _c("p", { staticClass: "ma0" }, [
              _c("a", { attrs: { href: "" } }, [_vm._v("Start here")])
            ])
          ])
        ])
      ])
    ]
  )
}
var staticRenderFns = []
render._withStripped = true



/***/ }),

/***/ "./resources/js/Pages/Team/Index.vue":
/*!*******************************************!*\
  !*** ./resources/js/Pages/Team/Index.vue ***!
  \*******************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _Index_vue_vue_type_template_id_16900ce9_scoped_true___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./Index.vue?vue&type=template&id=16900ce9&scoped=true& */ "./resources/js/Pages/Team/Index.vue?vue&type=template&id=16900ce9&scoped=true&");
/* harmony import */ var _Index_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./Index.vue?vue&type=script&lang=js& */ "./resources/js/Pages/Team/Index.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport *//* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");





/* normalize component */

var component = Object(_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__["default"])(
  _Index_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _Index_vue_vue_type_template_id_16900ce9_scoped_true___WEBPACK_IMPORTED_MODULE_0__["render"],
  _Index_vue_vue_type_template_id_16900ce9_scoped_true___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"],
  false,
  null,
  "16900ce9",
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/Pages/Team/Index.vue"
/* harmony default export */ __webpack_exports__["default"] = (component.exports);

/***/ }),

/***/ "./resources/js/Pages/Team/Index.vue?vue&type=script&lang=js&":
/*!********************************************************************!*\
  !*** ./resources/js/Pages/Team/Index.vue?vue&type=script&lang=js& ***!
  \********************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_Index_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../node_modules/babel-loader/lib??ref--4-0!../../../../node_modules/vue-loader/lib??vue-loader-options!./Index.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/Pages/Team/Index.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport */ /* harmony default export */ __webpack_exports__["default"] = (_node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_Index_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/js/Pages/Team/Index.vue?vue&type=template&id=16900ce9&scoped=true&":
/*!**************************************************************************************!*\
  !*** ./resources/js/Pages/Team/Index.vue?vue&type=template&id=16900ce9&scoped=true& ***!
  \**************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_Index_vue_vue_type_template_id_16900ce9_scoped_true___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../node_modules/vue-loader/lib??vue-loader-options!./Index.vue?vue&type=template&id=16900ce9&scoped=true& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/Pages/Team/Index.vue?vue&type=template&id=16900ce9&scoped=true&");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "render", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_Index_vue_vue_type_template_id_16900ce9_scoped_true___WEBPACK_IMPORTED_MODULE_0__["render"]; });

/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_Index_vue_vue_type_template_id_16900ce9_scoped_true___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"]; });



/***/ })

}]);