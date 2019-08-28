(window["webpackJsonp"] = window["webpackJsonp"] || []).push([[18],{

/***/ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/Pages/Dashboard/MyCompany.vue?vue&type=script&lang=js&":
/*!*************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib??ref--4-0!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/Pages/Dashboard/MyCompany.vue?vue&type=script&lang=js& ***!
  \*************************************************************************************************************************************************************************/
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
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
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
    employee: {
      type: Object,
      "default": null
    },
    notifications: {
      type: Array,
      "default": null
    },
    ownerPermissionLevel: {
      type: Number,
      "default": 0
    }
  }
});

/***/ }),

/***/ "./node_modules/css-loader/index.js?!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/Pages/Dashboard/MyCompany.vue?vue&type=style&index=0&id=2554723d&scoped=true&lang=css&":
/*!********************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/css-loader??ref--6-1!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src??ref--6-2!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/Pages/Dashboard/MyCompany.vue?vue&type=style&index=0&id=2554723d&scoped=true&lang=css& ***!
  \********************************************************************************************************************************************************************************************************************************************************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

exports = module.exports = __webpack_require__(/*! ../../../../node_modules/css-loader/lib/css-base.js */ "./node_modules/css-loader/lib/css-base.js")(false);
// imports


// module
exports.push([module.i, "\n.dummy[data-v-2554723d] {\n  right: 40px;\n  bottom: 20px;\n}\n", ""]);

// exports


/***/ }),

/***/ "./node_modules/style-loader/index.js!./node_modules/css-loader/index.js?!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/Pages/Dashboard/MyCompany.vue?vue&type=style&index=0&id=2554723d&scoped=true&lang=css&":
/*!************************************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/style-loader!./node_modules/css-loader??ref--6-1!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src??ref--6-2!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/Pages/Dashboard/MyCompany.vue?vue&type=style&index=0&id=2554723d&scoped=true&lang=css& ***!
  \************************************************************************************************************************************************************************************************************************************************************************************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {


var content = __webpack_require__(/*! !../../../../node_modules/css-loader??ref--6-1!../../../../node_modules/vue-loader/lib/loaders/stylePostLoader.js!../../../../node_modules/postcss-loader/src??ref--6-2!../../../../node_modules/vue-loader/lib??vue-loader-options!./MyCompany.vue?vue&type=style&index=0&id=2554723d&scoped=true&lang=css& */ "./node_modules/css-loader/index.js?!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/Pages/Dashboard/MyCompany.vue?vue&type=style&index=0&id=2554723d&scoped=true&lang=css&");

if(typeof content === 'string') content = [[module.i, content, '']];

var transform;
var insertInto;



var options = {"hmr":true}

options.transform = transform
options.insertInto = undefined;

var update = __webpack_require__(/*! ../../../../node_modules/style-loader/lib/addStyles.js */ "./node_modules/style-loader/lib/addStyles.js")(content, options);

if(content.locals) module.exports = content.locals;

if(false) {}

/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/Pages/Dashboard/MyCompany.vue?vue&type=template&id=2554723d&scoped=true&":
/*!*****************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/Pages/Dashboard/MyCompany.vue?vue&type=template&id=2554723d&scoped=true& ***!
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
        _c("div", { staticClass: "cf mt4 mw7 center" }, [
          _c("h2", { staticClass: "tc fw5" }, [
            _vm._v("\n        " + _vm._s(_vm.company.name) + "\n      ")
          ])
        ]),
        _vm._v(" "),
        _c("div", { staticClass: "cf mw7 center br3 mb3 tc" }, [
          _c("div", { staticClass: "cf dib btn-group" }, [
            _c(
              "a",
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
              "a",
              {
                staticClass: "f6 fl ph3 pv2 pointer dib",
                class: {
                  selected: _vm.$page.auth.user.default_dashboard_view == "team"
                },
                attrs: { href: "/" + _vm.company.id + "/dashboard/team" }
              },
              [_vm._v("\n          My team\n        ")]
            ),
            _vm._v(" "),
            _c(
              "a",
              {
                staticClass: "f6 fl ph3 pv2 dib",
                class: {
                  selected:
                    _vm.$page.auth.user.default_dashboard_view == "company"
                },
                attrs: { href: "/" + _vm.company.id + "/dashboard/company" }
              },
              [_vm._v("\n          My company\n        ")]
            ),
            _vm._v(" "),
            _c(
              "a",
              {
                staticClass: "f6 fl ph3 pv2 dib",
                class: {
                  selected: _vm.$page.auth.user.default_dashboard_view == "hr"
                },
                attrs: { href: "/" + _vm.company.id + "/dashboard/hr" }
              },
              [_vm._v("\n          HR area\n        ")]
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
                value:
                  _vm.employee.permission_level == _vm.ownerPermissionLevel,
                expression: "employee.permission_level == ownerPermissionLevel"
              }
            ],
            staticClass: "cf mw7 center br3 mb3 bg-white box"
          },
          [
            _c("div", { staticClass: "pa3 relative" }, [
              _c("p", { staticClass: "b" }, [
                _vm._v(
                  "\n          Would you like to fill your account with fake data?\n        "
                )
              ]),
              _vm._v(" "),
              _c("p", { staticClass: "measure" }, [
                _vm._v(
                  "\n          This will let you play with an account with a lot of data. You will be able to remove them at any time to start fresh.\n        "
                )
              ]),
              _vm._v(" "),
              _c("img", {
                staticClass: "dummy w-25 absolute",
                attrs: { src: "/img/company/account/fake-data.png" }
              }),
              _vm._v(" "),
              _c("ul", { staticClass: "list pa0 ma0" }, [
                _c(
                  "li",
                  { staticClass: "di pr2" },
                  [
                    _c("loading-button", {
                      attrs: {
                        classes: "btn add w-auto-ns w-100 mb2 pv2 ph3",
                        state: _vm.loadingState,
                        text: "generate"
                      }
                    })
                  ],
                  1
                ),
                _vm._v(" "),
                _c("li", { staticClass: "di" }, [
                  _c("a", { attrs: { href: "" } }, [
                    _vm._v("Dismiss this message")
                  ])
                ])
              ])
            ])
          ]
        ),
        _vm._v(" "),
        _c("div", { staticClass: "cf mw7 center br3 mb3 bg-white box" }, [
          _c("div", { staticClass: "pa3" }, [_c("editor")], 1)
        ]),
        _vm._v(" "),
        _c("div", { staticClass: "cf mw7 center br3 mb3 bg-white box" }, [
          _c("div", { staticClass: "pa3" }, [
            _c("ul", [
              _c("li", [
                _c(
                  "a",
                  { attrs: { href: "/" + _vm.company.id + "/account" } },
                  [_vm._v("Access Adminland")]
                )
              ]),
              _vm._v(" "),
              _c("li", [_vm._v("latest news")]),
              _vm._v(" "),
              _c("li", [_vm._v("hr: expense overview")]),
              _vm._v(" "),
              _c("li", [_vm._v("hr: view all teams")]),
              _vm._v(" "),
              _c("li", [_vm._v("view company morale")]),
              _vm._v(" "),
              _c("li", [_vm._v("view all employees")]),
              _vm._v(" "),
              _c("li", [_vm._v("menu de la semaine")]),
              _vm._v(" "),
              _c("li", [_vm._v("Mise en avant random d'un employé")])
            ])
          ])
        ]),
        _vm._v(" "),
        _c("div", { staticClass: "cf mt4 mw7 center br3 mb3 bg-white box" }, [
          _c("div", { staticClass: "pa3" }, [
            _c("h2", [_vm._v("Team")]),
            _vm._v(" "),
            _c("ul", [
              _c("li", [_vm._v("team agenda")]),
              _vm._v(" "),
              _c("li", [_vm._v("anniversaires")]),
              _vm._v(" "),
              _c("li", [_vm._v("latest news")]),
              _vm._v(" "),
              _c("li", [_vm._v("manager: view time off requests")]),
              _vm._v(" "),
              _c("li", [_vm._v("manager: view morale")]),
              _vm._v(" "),
              _c("li", [_vm._v("manager: expense approval")]),
              _vm._v(" "),
              _c("li", [_vm._v("manager: one on one")]),
              _vm._v(" "),
              _c("li", [_vm._v("revue 360 de son boss ou d'employées")])
            ])
          ])
        ]),
        _vm._v(" "),
        _c("div", { staticClass: "cf mt4 mw7 center br3 mb3 bg-white box" }, [
          _c("div", { staticClass: "pa3" }, [
            _c("h2", [_vm._v("Me")]),
            _vm._v(" "),
            _c("ul", [
              _c("li", [_vm._v("View holidays")]),
              _vm._v(" "),
              _c("li", [_vm._v("Book time off")]),
              _vm._v(" "),
              _c("li", [_vm._v("Log morale")]),
              _vm._v(" "),
              _c("li", [_vm._v("Reply to what you've done this week")]),
              _vm._v(" "),
              _c("li", [_vm._v("Log an expense")]),
              _vm._v(" "),
              _c("li", [_vm._v("View one on ones")]),
              _vm._v(" "),
              _c("li", [_vm._v("View all my tasks")])
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

/***/ "./resources/js/Pages/Dashboard/MyCompany.vue":
/*!****************************************************!*\
  !*** ./resources/js/Pages/Dashboard/MyCompany.vue ***!
  \****************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _MyCompany_vue_vue_type_template_id_2554723d_scoped_true___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./MyCompany.vue?vue&type=template&id=2554723d&scoped=true& */ "./resources/js/Pages/Dashboard/MyCompany.vue?vue&type=template&id=2554723d&scoped=true&");
/* harmony import */ var _MyCompany_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./MyCompany.vue?vue&type=script&lang=js& */ "./resources/js/Pages/Dashboard/MyCompany.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport *//* harmony import */ var _MyCompany_vue_vue_type_style_index_0_id_2554723d_scoped_true_lang_css___WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./MyCompany.vue?vue&type=style&index=0&id=2554723d&scoped=true&lang=css& */ "./resources/js/Pages/Dashboard/MyCompany.vue?vue&type=style&index=0&id=2554723d&scoped=true&lang=css&");
/* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");






/* normalize component */

var component = Object(_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_3__["default"])(
  _MyCompany_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _MyCompany_vue_vue_type_template_id_2554723d_scoped_true___WEBPACK_IMPORTED_MODULE_0__["render"],
  _MyCompany_vue_vue_type_template_id_2554723d_scoped_true___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"],
  false,
  null,
  "2554723d",
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/Pages/Dashboard/MyCompany.vue"
/* harmony default export */ __webpack_exports__["default"] = (component.exports);

/***/ }),

/***/ "./resources/js/Pages/Dashboard/MyCompany.vue?vue&type=script&lang=js&":
/*!*****************************************************************************!*\
  !*** ./resources/js/Pages/Dashboard/MyCompany.vue?vue&type=script&lang=js& ***!
  \*****************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_MyCompany_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../node_modules/babel-loader/lib??ref--4-0!../../../../node_modules/vue-loader/lib??vue-loader-options!./MyCompany.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/Pages/Dashboard/MyCompany.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport */ /* harmony default export */ __webpack_exports__["default"] = (_node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_MyCompany_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/js/Pages/Dashboard/MyCompany.vue?vue&type=style&index=0&id=2554723d&scoped=true&lang=css&":
/*!*************************************************************************************************************!*\
  !*** ./resources/js/Pages/Dashboard/MyCompany.vue?vue&type=style&index=0&id=2554723d&scoped=true&lang=css& ***!
  \*************************************************************************************************************/
/*! no static exports found */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_style_loader_index_js_node_modules_css_loader_index_js_ref_6_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_6_2_node_modules_vue_loader_lib_index_js_vue_loader_options_MyCompany_vue_vue_type_style_index_0_id_2554723d_scoped_true_lang_css___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../node_modules/style-loader!../../../../node_modules/css-loader??ref--6-1!../../../../node_modules/vue-loader/lib/loaders/stylePostLoader.js!../../../../node_modules/postcss-loader/src??ref--6-2!../../../../node_modules/vue-loader/lib??vue-loader-options!./MyCompany.vue?vue&type=style&index=0&id=2554723d&scoped=true&lang=css& */ "./node_modules/style-loader/index.js!./node_modules/css-loader/index.js?!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/Pages/Dashboard/MyCompany.vue?vue&type=style&index=0&id=2554723d&scoped=true&lang=css&");
/* harmony import */ var _node_modules_style_loader_index_js_node_modules_css_loader_index_js_ref_6_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_6_2_node_modules_vue_loader_lib_index_js_vue_loader_options_MyCompany_vue_vue_type_style_index_0_id_2554723d_scoped_true_lang_css___WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_node_modules_style_loader_index_js_node_modules_css_loader_index_js_ref_6_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_6_2_node_modules_vue_loader_lib_index_js_vue_loader_options_MyCompany_vue_vue_type_style_index_0_id_2554723d_scoped_true_lang_css___WEBPACK_IMPORTED_MODULE_0__);
/* harmony reexport (unknown) */ for(var __WEBPACK_IMPORT_KEY__ in _node_modules_style_loader_index_js_node_modules_css_loader_index_js_ref_6_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_6_2_node_modules_vue_loader_lib_index_js_vue_loader_options_MyCompany_vue_vue_type_style_index_0_id_2554723d_scoped_true_lang_css___WEBPACK_IMPORTED_MODULE_0__) if(__WEBPACK_IMPORT_KEY__ !== 'default') (function(key) { __webpack_require__.d(__webpack_exports__, key, function() { return _node_modules_style_loader_index_js_node_modules_css_loader_index_js_ref_6_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_6_2_node_modules_vue_loader_lib_index_js_vue_loader_options_MyCompany_vue_vue_type_style_index_0_id_2554723d_scoped_true_lang_css___WEBPACK_IMPORTED_MODULE_0__[key]; }) }(__WEBPACK_IMPORT_KEY__));
 /* harmony default export */ __webpack_exports__["default"] = (_node_modules_style_loader_index_js_node_modules_css_loader_index_js_ref_6_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_6_2_node_modules_vue_loader_lib_index_js_vue_loader_options_MyCompany_vue_vue_type_style_index_0_id_2554723d_scoped_true_lang_css___WEBPACK_IMPORTED_MODULE_0___default.a); 

/***/ }),

/***/ "./resources/js/Pages/Dashboard/MyCompany.vue?vue&type=template&id=2554723d&scoped=true&":
/*!***********************************************************************************************!*\
  !*** ./resources/js/Pages/Dashboard/MyCompany.vue?vue&type=template&id=2554723d&scoped=true& ***!
  \***********************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_MyCompany_vue_vue_type_template_id_2554723d_scoped_true___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../node_modules/vue-loader/lib??vue-loader-options!./MyCompany.vue?vue&type=template&id=2554723d&scoped=true& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/Pages/Dashboard/MyCompany.vue?vue&type=template&id=2554723d&scoped=true&");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "render", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_MyCompany_vue_vue_type_template_id_2554723d_scoped_true___WEBPACK_IMPORTED_MODULE_0__["render"]; });

/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_MyCompany_vue_vue_type_template_id_2554723d_scoped_true___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"]; });



/***/ })

}]);