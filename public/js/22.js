(window["webpackJsonp"] = window["webpackJsonp"] || []).push([[22],{

/***/ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/Pages/Dashboard/MyTeamEmptyState.vue?vue&type=script&lang=js&":
/*!********************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib??ref--4-0!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/Pages/Dashboard/MyTeamEmptyState.vue?vue&type=script&lang=js& ***!
  \********************************************************************************************************************************************************************************/
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

/* harmony default export */ __webpack_exports__["default"] = ({
  components: {
    Layout: _Shared_Layout__WEBPACK_IMPORTED_MODULE_0__["default"]
  },
  props: {
    company: {
      type: Object,
      "default": null
    },
    employee: {
      type: Object,
      "default": null
    },
    notifications: {
      type: Array,
      "default": null
    },
    message: {
      type: String,
      "default": null
    }
  }
});

/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/Pages/Dashboard/MyTeamEmptyState.vue?vue&type=template&id=28c478f1&scoped=true&":
/*!************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/Pages/Dashboard/MyTeamEmptyState.vue?vue&type=template&id=28c478f1&scoped=true& ***!
  \************************************************************************************************************************************************************************************************************************************/
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
    {
      attrs: {
        title: "Home",
        employee: _vm.employee,
        notifications: _vm.notifications
      }
    },
    [
      _c("div", { staticClass: "ph2 ph0-ns" }, [
        _c("div", { staticClass: "cf mt4 mw7 center" }, [
          _c("h2", { staticClass: "tc fw5" }, [
            _vm._v("\n        " + _vm._s(_vm.company.name) + "\n      ")
          ])
        ]),
        _vm._v(" "),
        _c("div", { staticClass: "cf mw7 center br3 mb5 tc" }, [
          _c(
            "div",
            { staticClass: "cf dib btn-group" },
            [
              _c(
                "inertia-link",
                {
                  staticClass: "f6 fl ph3 pv2 dib pointer",
                  class: {
                    selected: _vm.$page.auth.user.default_dashboard_view == "me"
                  },
                  attrs: { href: "/" + _vm.company.id + "/dashboard/me" }
                },
                [_vm._v("\n          Me\n        ")]
              ),
              _vm._v(" "),
              _c(
                "inertia-link",
                {
                  staticClass: "f6 fl ph3 pv2 dib pointer",
                  class: {
                    selected:
                      _vm.$page.auth.user.default_dashboard_view == "team"
                  },
                  attrs: {
                    href: "/" + _vm.company.id + "/dashboard/team",
                    "data-cy": "dashboard-team-tab"
                  }
                },
                [_vm._v("\n          My team\n        ")]
              ),
              _vm._v(" "),
              _c(
                "inertia-link",
                {
                  staticClass: "f6 fl ph3 pv2 dib pointer",
                  class: {
                    selected:
                      _vm.$page.auth.user.default_dashboard_view == "company"
                  },
                  attrs: {
                    href: "/" + _vm.company.id + "/dashboard/company",
                    "data-cy": "dashboard-company-tab"
                  }
                },
                [_vm._v("\n          My company\n        ")]
              ),
              _vm._v(" "),
              _c(
                "inertia-link",
                {
                  staticClass: "f6 fl ph3 pv2 dib pointer",
                  class: {
                    selected: _vm.$page.auth.user.default_dashboard_view == "hr"
                  },
                  attrs: {
                    href: "/" + _vm.company.id + "/dashboard/hr",
                    "data-cy": "dashboard-hr-tab"
                  }
                },
                [_vm._v("\n          HR area\n        ")]
              )
            ],
            1
          )
        ]),
        _vm._v(" "),
        _c("div", { staticClass: "cf mw7 center br3 mb3 bg-white box" }, [
          _c("div", { staticClass: "pa3 tc" }, [
            _vm._v("\n        " + _vm._s(_vm.message) + "\n      ")
          ])
        ])
      ])
    ]
  )
}
var staticRenderFns = []
render._withStripped = true



/***/ }),

/***/ "./resources/js/Pages/Dashboard/MyTeamEmptyState.vue":
/*!***********************************************************!*\
  !*** ./resources/js/Pages/Dashboard/MyTeamEmptyState.vue ***!
  \***********************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _MyTeamEmptyState_vue_vue_type_template_id_28c478f1_scoped_true___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./MyTeamEmptyState.vue?vue&type=template&id=28c478f1&scoped=true& */ "./resources/js/Pages/Dashboard/MyTeamEmptyState.vue?vue&type=template&id=28c478f1&scoped=true&");
/* harmony import */ var _MyTeamEmptyState_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./MyTeamEmptyState.vue?vue&type=script&lang=js& */ "./resources/js/Pages/Dashboard/MyTeamEmptyState.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport *//* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");





/* normalize component */

var component = Object(_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__["default"])(
  _MyTeamEmptyState_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _MyTeamEmptyState_vue_vue_type_template_id_28c478f1_scoped_true___WEBPACK_IMPORTED_MODULE_0__["render"],
  _MyTeamEmptyState_vue_vue_type_template_id_28c478f1_scoped_true___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"],
  false,
  null,
  "28c478f1",
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/Pages/Dashboard/MyTeamEmptyState.vue"
/* harmony default export */ __webpack_exports__["default"] = (component.exports);

/***/ }),

/***/ "./resources/js/Pages/Dashboard/MyTeamEmptyState.vue?vue&type=script&lang=js&":
/*!************************************************************************************!*\
  !*** ./resources/js/Pages/Dashboard/MyTeamEmptyState.vue?vue&type=script&lang=js& ***!
  \************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_MyTeamEmptyState_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../node_modules/babel-loader/lib??ref--4-0!../../../../node_modules/vue-loader/lib??vue-loader-options!./MyTeamEmptyState.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/Pages/Dashboard/MyTeamEmptyState.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport */ /* harmony default export */ __webpack_exports__["default"] = (_node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_MyTeamEmptyState_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/js/Pages/Dashboard/MyTeamEmptyState.vue?vue&type=template&id=28c478f1&scoped=true&":
/*!******************************************************************************************************!*\
  !*** ./resources/js/Pages/Dashboard/MyTeamEmptyState.vue?vue&type=template&id=28c478f1&scoped=true& ***!
  \******************************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_MyTeamEmptyState_vue_vue_type_template_id_28c478f1_scoped_true___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../node_modules/vue-loader/lib??vue-loader-options!./MyTeamEmptyState.vue?vue&type=template&id=28c478f1&scoped=true& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/Pages/Dashboard/MyTeamEmptyState.vue?vue&type=template&id=28c478f1&scoped=true&");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "render", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_MyTeamEmptyState_vue_vue_type_template_id_28c478f1_scoped_true___WEBPACK_IMPORTED_MODULE_0__["render"]; });

/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_MyTeamEmptyState_vue_vue_type_template_id_28c478f1_scoped_true___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"]; });



/***/ })

}]);