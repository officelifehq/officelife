(window["webpackJsonp"] = window["webpackJsonp"] || []).push([[10],{

/***/ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/Pages/Employee/AssignEmployeePosition.vue?vue&type=script&lang=js&":
/*!*************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib??ref--4-0!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/Pages/Employee/AssignEmployeePosition.vue?vue&type=script&lang=js& ***!
  \*************************************************************************************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var vue_click_outside__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! vue-click-outside */ "./node_modules/vue-click-outside/index.js");
/* harmony import */ var vue_click_outside__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(vue_click_outside__WEBPACK_IMPORTED_MODULE_0__);
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
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
  directives: {
    ClickOutside: vue_click_outside__WEBPACK_IMPORTED_MODULE_0___default.a
  },
  props: {
    employee: {
      type: Object,
      "default": null
    },
    notifications: {
      type: Array,
      "default": null
    },
    positions: {
      type: Array,
      "default": null
    }
  },
  data: function data() {
    return {
      modal: false,
      search: '',
      title: '',
      updatedEmployee: Object
    };
  },
  computed: {
    filteredList: function filteredList() {
      var _this = this;

      // filter the list when searching
      // also, sort the list by title
      var list;
      list = this.positions.filter(function (position) {
        return position.title.toLowerCase().includes(_this.search.toLowerCase());
      });

      function compare(a, b) {
        if (a.title < b.title) return -1;
        if (a.title > b.title) return 1;
        return 0;
      }

      return list.sort(compare);
    }
  },
  mounted: function mounted() {
    if (this.employee.position != null) {
      this.title = this.employee.position.title;
    }

    this.updatedEmployee = this.$page.auth.employee; // prevent click outside event with popupItem.

    this.popupItem = this.$el;
  },
  methods: {
    toggleModal: function toggleModal() {
      this.modal = false;
    },
    assign: function assign(position) {
      var _this2 = this;

      axios.post('/' + this.$page.auth.company.id + '/employees/' + this.employee.id + '/position', position).then(function (response) {
        _this2.$snotify.success(_this2.$t('employee.position_modal_assign_success'), {
          timeout: 2000,
          showProgressBar: true,
          closeOnClick: true,
          pauseOnHover: true
        });

        _this2.title = response.data.data.position.title;
        _this2.updatedEmployee = response.data.data;
        _this2.modal = false;
      })["catch"](function (error) {
        _this2.form.errors = _.flatten(_.toArray(error.response.data));
      });
    },
    reset: function reset() {
      var _this3 = this;

      axios["delete"]('/' + this.$page.auth.company.id + '/employees/' + this.employee.id + '/position/' + this.updatedEmployee.position.id).then(function (response) {
        _this3.$snotify.success(_this3.$t('employee.position_modal_unassign_success'), {
          timeout: 2000,
          showProgressBar: true,
          closeOnClick: true,
          pauseOnHover: true
        });

        _this3.title = '';
        _this3.modal = false;
        _this3.updatedEmployee = response.data.data;
      })["catch"](function (error) {
        _this3.form.errors = _.flatten(_.toArray(error.response.data));
      });
    }
  }
});

/***/ }),

/***/ "./node_modules/css-loader/index.js?!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/Pages/Employee/AssignEmployeePosition.vue?vue&type=style&index=0&id=b31fdce4&scoped=true&lang=css&":
/*!********************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/css-loader??ref--6-1!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src??ref--6-2!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/Pages/Employee/AssignEmployeePosition.vue?vue&type=style&index=0&id=b31fdce4&scoped=true&lang=css& ***!
  \********************************************************************************************************************************************************************************************************************************************************************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

exports = module.exports = __webpack_require__(/*! ../../../../node_modules/css-loader/lib/css-base.js */ "./node_modules/css-loader/lib/css-base.js")(false);
// imports


// module
exports.push([module.i, "\n.positions-list[data-v-b31fdce4] {\n  max-height: 150px;\n}\n.popupmenu[data-v-b31fdce4] {\n  right: 2px;\n  top: 26px;\n  width: 280px;\n}\n.c-delete[data-v-b31fdce4]:hover {\n  border-bottom-width: 0;\n}\n", ""]);

// exports


/***/ }),

/***/ "./node_modules/style-loader/index.js!./node_modules/css-loader/index.js?!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/Pages/Employee/AssignEmployeePosition.vue?vue&type=style&index=0&id=b31fdce4&scoped=true&lang=css&":
/*!************************************************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/style-loader!./node_modules/css-loader??ref--6-1!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src??ref--6-2!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/Pages/Employee/AssignEmployeePosition.vue?vue&type=style&index=0&id=b31fdce4&scoped=true&lang=css& ***!
  \************************************************************************************************************************************************************************************************************************************************************************************************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {


var content = __webpack_require__(/*! !../../../../node_modules/css-loader??ref--6-1!../../../../node_modules/vue-loader/lib/loaders/stylePostLoader.js!../../../../node_modules/postcss-loader/src??ref--6-2!../../../../node_modules/vue-loader/lib??vue-loader-options!./AssignEmployeePosition.vue?vue&type=style&index=0&id=b31fdce4&scoped=true&lang=css& */ "./node_modules/css-loader/index.js?!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/Pages/Employee/AssignEmployeePosition.vue?vue&type=style&index=0&id=b31fdce4&scoped=true&lang=css&");

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

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/Pages/Employee/AssignEmployeePosition.vue?vue&type=template&id=b31fdce4&scoped=true&":
/*!*****************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/Pages/Employee/AssignEmployeePosition.vue?vue&type=template&id=b31fdce4&scoped=true& ***!
  \*****************************************************************************************************************************************************************************************************************************************/
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
  return _c("div", { staticClass: "di relative" }, [
    _vm.$page.auth.user.permission_level <= 200
      ? _c(
          "span",
          {
            staticClass: "bb b--dotted bt-0 bl-0 br-0 pointer",
            attrs: { "data-cy": "open-position-modal" },
            on: {
              click: function($event) {
                $event.preventDefault()
                _vm.modal = true
              }
            }
          },
          [_vm._v(_vm._s(_vm.title))]
        )
      : _c("span", { attrs: { "data-cy": "position-title" } }, [
          _vm._v(_vm._s(_vm.title))
        ]),
    _vm._v(" "),
    _vm.$page.auth.user.permission_level <= 200
      ? _c(
          "a",
          {
            directives: [
              {
                name: "show",
                rawName: "v-show",
                value: _vm.title == "",
                expression: "title == ''"
              }
            ],
            staticClass: "pointer",
            attrs: { "data-cy": "open-position-modal-blank" },
            on: {
              click: function($event) {
                $event.preventDefault()
                _vm.modal = true
              }
            }
          },
          [_vm._v(_vm._s(_vm.$t("employee.position_modal_title")))]
        )
      : _c(
          "span",
          {
            directives: [
              {
                name: "show",
                rawName: "v-show",
                value: _vm.title == "",
                expression: "title == ''"
              }
            ]
          },
          [_vm._v(_vm._s(_vm.$t("employee.position_blank")))]
        ),
    _vm._v(" "),
    _vm.modal
      ? _c(
          "div",
          {
            directives: [
              {
                name: "click-outside",
                rawName: "v-click-outside",
                value: _vm.toggleModal,
                expression: "toggleModal"
              }
            ],
            staticClass:
              "popupmenu absolute br2 bg-white z-max tl bounceIn faster"
          },
          [
            _c("p", { staticClass: "pa2 ma0 bb bb-gray" }, [
              _vm._v(
                "\n      " +
                  _vm._s(_vm.$t("employee.position_modal_title")) +
                  "\n    "
              )
            ]),
            _vm._v(" "),
            _c(
              "form",
              {
                on: {
                  submit: function($event) {
                    $event.preventDefault()
                    return _vm.search($event)
                  }
                }
              },
              [
                _c("div", { staticClass: "relative pv2 ph2 bb bb-gray" }, [
                  _c("input", {
                    directives: [
                      {
                        name: "model",
                        rawName: "v-model",
                        value: _vm.search,
                        expression: "search"
                      }
                    ],
                    staticClass: "br2 f5 w-100 ba b--black-40 pa2 outline-0",
                    attrs: {
                      id: "search",
                      type: "text",
                      name: "search",
                      placeholder: _vm.$t("employee.position_modal_filter")
                    },
                    domProps: { value: _vm.search },
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
                        return _vm.toggleModal($event)
                      },
                      input: function($event) {
                        if ($event.target.composing) {
                          return
                        }
                        _vm.search = $event.target.value
                      }
                    }
                  })
                ])
              ]
            ),
            _vm._v(" "),
            _c(
              "ul",
              {
                staticClass:
                  "pl0 list ma0 overflow-auto relative positions-list"
              },
              _vm._l(_vm.filteredList, function(position) {
                return _c(
                  "li",
                  {
                    key: position.id,
                    staticClass: "pv2 ph3 bb bb-gray-hover bb-gray pointer",
                    attrs: { "data-cy": "list-position-" + position.id },
                    on: {
                      click: function($event) {
                        return _vm.assign(position)
                      }
                    }
                  },
                  [_vm._v("\n        " + _vm._s(position.title) + "\n      ")]
                )
              }),
              0
            ),
            _vm._v(" "),
            _vm.title != ""
              ? _c(
                  "a",
                  {
                    staticClass:
                      "pointer pv2 ph3 db no-underline c-delete bb-0",
                    attrs: { "data-cy": "position-reset-button" },
                    on: { click: _vm.reset }
                  },
                  [_vm._v(_vm._s(_vm.$t("employee.position_modal_reset")))]
                )
              : _vm._e()
          ]
        )
      : _vm._e()
  ])
}
var staticRenderFns = []
render._withStripped = true



/***/ }),

/***/ "./resources/js/Pages/Employee/AssignEmployeePosition.vue":
/*!****************************************************************!*\
  !*** ./resources/js/Pages/Employee/AssignEmployeePosition.vue ***!
  \****************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _AssignEmployeePosition_vue_vue_type_template_id_b31fdce4_scoped_true___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./AssignEmployeePosition.vue?vue&type=template&id=b31fdce4&scoped=true& */ "./resources/js/Pages/Employee/AssignEmployeePosition.vue?vue&type=template&id=b31fdce4&scoped=true&");
/* harmony import */ var _AssignEmployeePosition_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./AssignEmployeePosition.vue?vue&type=script&lang=js& */ "./resources/js/Pages/Employee/AssignEmployeePosition.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport *//* harmony import */ var _AssignEmployeePosition_vue_vue_type_style_index_0_id_b31fdce4_scoped_true_lang_css___WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./AssignEmployeePosition.vue?vue&type=style&index=0&id=b31fdce4&scoped=true&lang=css& */ "./resources/js/Pages/Employee/AssignEmployeePosition.vue?vue&type=style&index=0&id=b31fdce4&scoped=true&lang=css&");
/* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");






/* normalize component */

var component = Object(_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_3__["default"])(
  _AssignEmployeePosition_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _AssignEmployeePosition_vue_vue_type_template_id_b31fdce4_scoped_true___WEBPACK_IMPORTED_MODULE_0__["render"],
  _AssignEmployeePosition_vue_vue_type_template_id_b31fdce4_scoped_true___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"],
  false,
  null,
  "b31fdce4",
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/Pages/Employee/AssignEmployeePosition.vue"
/* harmony default export */ __webpack_exports__["default"] = (component.exports);

/***/ }),

/***/ "./resources/js/Pages/Employee/AssignEmployeePosition.vue?vue&type=script&lang=js&":
/*!*****************************************************************************************!*\
  !*** ./resources/js/Pages/Employee/AssignEmployeePosition.vue?vue&type=script&lang=js& ***!
  \*****************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_AssignEmployeePosition_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../node_modules/babel-loader/lib??ref--4-0!../../../../node_modules/vue-loader/lib??vue-loader-options!./AssignEmployeePosition.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/Pages/Employee/AssignEmployeePosition.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport */ /* harmony default export */ __webpack_exports__["default"] = (_node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_AssignEmployeePosition_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/js/Pages/Employee/AssignEmployeePosition.vue?vue&type=style&index=0&id=b31fdce4&scoped=true&lang=css&":
/*!*************************************************************************************************************************!*\
  !*** ./resources/js/Pages/Employee/AssignEmployeePosition.vue?vue&type=style&index=0&id=b31fdce4&scoped=true&lang=css& ***!
  \*************************************************************************************************************************/
/*! no static exports found */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_style_loader_index_js_node_modules_css_loader_index_js_ref_6_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_6_2_node_modules_vue_loader_lib_index_js_vue_loader_options_AssignEmployeePosition_vue_vue_type_style_index_0_id_b31fdce4_scoped_true_lang_css___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../node_modules/style-loader!../../../../node_modules/css-loader??ref--6-1!../../../../node_modules/vue-loader/lib/loaders/stylePostLoader.js!../../../../node_modules/postcss-loader/src??ref--6-2!../../../../node_modules/vue-loader/lib??vue-loader-options!./AssignEmployeePosition.vue?vue&type=style&index=0&id=b31fdce4&scoped=true&lang=css& */ "./node_modules/style-loader/index.js!./node_modules/css-loader/index.js?!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/Pages/Employee/AssignEmployeePosition.vue?vue&type=style&index=0&id=b31fdce4&scoped=true&lang=css&");
/* harmony import */ var _node_modules_style_loader_index_js_node_modules_css_loader_index_js_ref_6_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_6_2_node_modules_vue_loader_lib_index_js_vue_loader_options_AssignEmployeePosition_vue_vue_type_style_index_0_id_b31fdce4_scoped_true_lang_css___WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_node_modules_style_loader_index_js_node_modules_css_loader_index_js_ref_6_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_6_2_node_modules_vue_loader_lib_index_js_vue_loader_options_AssignEmployeePosition_vue_vue_type_style_index_0_id_b31fdce4_scoped_true_lang_css___WEBPACK_IMPORTED_MODULE_0__);
/* harmony reexport (unknown) */ for(var __WEBPACK_IMPORT_KEY__ in _node_modules_style_loader_index_js_node_modules_css_loader_index_js_ref_6_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_6_2_node_modules_vue_loader_lib_index_js_vue_loader_options_AssignEmployeePosition_vue_vue_type_style_index_0_id_b31fdce4_scoped_true_lang_css___WEBPACK_IMPORTED_MODULE_0__) if(__WEBPACK_IMPORT_KEY__ !== 'default') (function(key) { __webpack_require__.d(__webpack_exports__, key, function() { return _node_modules_style_loader_index_js_node_modules_css_loader_index_js_ref_6_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_6_2_node_modules_vue_loader_lib_index_js_vue_loader_options_AssignEmployeePosition_vue_vue_type_style_index_0_id_b31fdce4_scoped_true_lang_css___WEBPACK_IMPORTED_MODULE_0__[key]; }) }(__WEBPACK_IMPORT_KEY__));
 /* harmony default export */ __webpack_exports__["default"] = (_node_modules_style_loader_index_js_node_modules_css_loader_index_js_ref_6_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_6_2_node_modules_vue_loader_lib_index_js_vue_loader_options_AssignEmployeePosition_vue_vue_type_style_index_0_id_b31fdce4_scoped_true_lang_css___WEBPACK_IMPORTED_MODULE_0___default.a); 

/***/ }),

/***/ "./resources/js/Pages/Employee/AssignEmployeePosition.vue?vue&type=template&id=b31fdce4&scoped=true&":
/*!***********************************************************************************************************!*\
  !*** ./resources/js/Pages/Employee/AssignEmployeePosition.vue?vue&type=template&id=b31fdce4&scoped=true& ***!
  \***********************************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_AssignEmployeePosition_vue_vue_type_template_id_b31fdce4_scoped_true___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../node_modules/vue-loader/lib??vue-loader-options!./AssignEmployeePosition.vue?vue&type=template&id=b31fdce4&scoped=true& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/Pages/Employee/AssignEmployeePosition.vue?vue&type=template&id=b31fdce4&scoped=true&");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "render", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_AssignEmployeePosition_vue_vue_type_template_id_b31fdce4_scoped_true___WEBPACK_IMPORTED_MODULE_0__["render"]; });

/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_AssignEmployeePosition_vue_vue_type_template_id_b31fdce4_scoped_true___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"]; });



/***/ })

}]);