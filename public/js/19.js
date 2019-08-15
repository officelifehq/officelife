(window["webpackJsonp"] = window["webpackJsonp"] || []).push([[19],{

/***/ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/Pages/Home/CreateCompany.vue?vue&type=script&lang=js&":
/*!************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib??ref--4-0!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/Pages/Home/CreateCompany.vue?vue&type=script&lang=js& ***!
  \************************************************************************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _Shared_TextInput__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @/Shared/TextInput */ "./resources/js/Shared/TextInput.vue");
/* harmony import */ var _Shared_Errors__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @/Shared/Errors */ "./resources/js/Shared/Errors.vue");
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



/* harmony default export */ __webpack_exports__["default"] = ({
  components: {
    TextInput: _Shared_TextInput__WEBPACK_IMPORTED_MODULE_0__["default"],
    Errors: _Shared_Errors__WEBPACK_IMPORTED_MODULE_1__["default"],
    LoadingButton: _Shared_LoadingButton__WEBPACK_IMPORTED_MODULE_2__["default"]
  },
  props: {
    company: {
      type: Object,
      "default": null
    },
    user: {
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
      form: {
        name: null,
        errors: []
      },
      loadingState: '',
      errorTemplate: Error
    };
  },
  methods: {
    submit: function submit() {
      var _this = this;

      this.loadingState = 'loading';
      this.$inertia.post(this.route('company.store'), this.form).then(function () {
        return _this.loadingState = null;
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

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/Pages/Home/CreateCompany.vue?vue&type=template&id=2ce40b7a&":
/*!****************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/Pages/Home/CreateCompany.vue?vue&type=template&id=2ce40b7a& ***!
  \****************************************************************************************************************************************************************************************************************/
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
    _c("div", { staticClass: "cf mt4 mw7 center br3 mb3 bg-white box" }, [
      _c("div", { staticClass: "fn fl-ns w-50-ns pa3" }, [
        _vm._v("\n      Create a company\n    ")
      ]),
      _vm._v(" "),
      _c(
        "div",
        { staticClass: "fn fl-ns w-50-ns pa3" },
        [
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
              _c("text-input", {
                attrs: {
                  errors: _vm.$page.errors.name,
                  label: _vm.$t("company.new_name")
                },
                model: {
                  value: _vm.form.name,
                  callback: function($$v) {
                    _vm.$set(_vm.form, "name", $$v)
                  },
                  expression: "form.name"
                }
              }),
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
                          text: "register",
                          "data-cy": "create-company-submit"
                        }
                      })
                    ],
                    1
                  )
                ])
              ])
            ],
            1
          )
        ],
        1
      )
    ])
  ])
}
var staticRenderFns = []
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

/***/ "./resources/js/Pages/Home/CreateCompany.vue":
/*!***************************************************!*\
  !*** ./resources/js/Pages/Home/CreateCompany.vue ***!
  \***************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _CreateCompany_vue_vue_type_template_id_2ce40b7a___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./CreateCompany.vue?vue&type=template&id=2ce40b7a& */ "./resources/js/Pages/Home/CreateCompany.vue?vue&type=template&id=2ce40b7a&");
/* harmony import */ var _CreateCompany_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./CreateCompany.vue?vue&type=script&lang=js& */ "./resources/js/Pages/Home/CreateCompany.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport *//* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");





/* normalize component */

var component = Object(_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__["default"])(
  _CreateCompany_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _CreateCompany_vue_vue_type_template_id_2ce40b7a___WEBPACK_IMPORTED_MODULE_0__["render"],
  _CreateCompany_vue_vue_type_template_id_2ce40b7a___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"],
  false,
  null,
  null,
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/Pages/Home/CreateCompany.vue"
/* harmony default export */ __webpack_exports__["default"] = (component.exports);

/***/ }),

/***/ "./resources/js/Pages/Home/CreateCompany.vue?vue&type=script&lang=js&":
/*!****************************************************************************!*\
  !*** ./resources/js/Pages/Home/CreateCompany.vue?vue&type=script&lang=js& ***!
  \****************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_CreateCompany_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../node_modules/babel-loader/lib??ref--4-0!../../../../node_modules/vue-loader/lib??vue-loader-options!./CreateCompany.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/Pages/Home/CreateCompany.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport */ /* harmony default export */ __webpack_exports__["default"] = (_node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_CreateCompany_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/js/Pages/Home/CreateCompany.vue?vue&type=template&id=2ce40b7a&":
/*!**********************************************************************************!*\
  !*** ./resources/js/Pages/Home/CreateCompany.vue?vue&type=template&id=2ce40b7a& ***!
  \**********************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_CreateCompany_vue_vue_type_template_id_2ce40b7a___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../node_modules/vue-loader/lib??vue-loader-options!./CreateCompany.vue?vue&type=template&id=2ce40b7a& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/Pages/Home/CreateCompany.vue?vue&type=template&id=2ce40b7a&");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "render", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_CreateCompany_vue_vue_type_template_id_2ce40b7a___WEBPACK_IMPORTED_MODULE_0__["render"]; });

/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_CreateCompany_vue_vue_type_template_id_2ce40b7a___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"]; });



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



/***/ })

}]);