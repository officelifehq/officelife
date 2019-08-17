(window["webpackJsonp"] = window["webpackJsonp"] || []).push([[20],{

/***/ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/Pages/Adminland/Flow/Actions.vue?vue&type=script&lang=js&":
/*!****************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib??ref--4-0!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/Pages/Adminland/Flow/Actions.vue?vue&type=script&lang=js& ***!
  \****************************************************************************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _Pages_Adminland_Flow_ActionNotification__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @/Pages/Adminland/Flow/ActionNotification */ "./resources/js/Pages/Adminland/Flow/ActionNotification.vue");
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
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
    ActionNotification: _Pages_Adminland_Flow_ActionNotification__WEBPACK_IMPORTED_MODULE_0__["default"]
  },
  model: {
    prop: 'value',
    event: 'change'
  },
  props: {
    value: {
      type: Array,
      "default": null
    }
  },
  data: function data() {
    return {
      complete: false,
      localActions: [],
      uniqueIds: 0,
      showActionMenu: false
    };
  },
  computed: {
    orderedActions: function orderedActions() {
      return _.orderBy(this.localActions, 'id');
    }
  },
  mounted: function mounted() {
    this.localActions = this.value;
  },
  methods: {
    addAction: function addAction(type) {
      this.uniqueIds = this.uniqueIds + 1;
      this.localActions.push({
        id: this.uniqueIds,
        type: type
      });
      this.showActionMenu = false;
      this.$emit('change', this.localActions);
      this.$emit('completed', this.complete);
    },
    updateAction: function updateAction(event, action) {
      Vue.set(this.localActions, this.localActions.indexOf(action), event); // check whether the actions are "complete" to prevent submitting a wrong
      // json to the backend

      var isCompleteYet = true;

      for (var index = 0; index < this.localActions.length; index++) {
        var _action = this.localActions[index];

        if (_action.complete == false || !_action.complete) {
          isCompleteYet = false;
        }
      }

      this.complete = isCompleteYet;
      this.$emit('completed', this.complete);
    },
    destroyAction: function destroyAction(action) {
      this.localActions.splice(this.localActions.findIndex(function (i) {
        return i.id === action.id;
      }), 1);
    }
  }
});

/***/ }),

/***/ "./node_modules/css-loader/index.js!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src/index.js?!./node_modules/sass-loader/lib/loader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/Pages/Adminland/Flow/Actions.vue?vue&type=style&index=0&id=d440b88c&lang=scss&scoped=true&":
/*!*****************************************************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/css-loader!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src??ref--7-2!./node_modules/sass-loader/lib/loader.js??ref--7-3!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/Pages/Adminland/Flow/Actions.vue?vue&type=style&index=0&id=d440b88c&lang=scss&scoped=true& ***!
  \*****************************************************************************************************************************************************************************************************************************************************************************************************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

exports = module.exports = __webpack_require__(/*! ../../../../../node_modules/css-loader/lib/css-base.js */ "./node_modules/css-loader/lib/css-base.js")(false);
// imports


// module
exports.push([module.i, ".blank-state-actions[data-v-d440b88c] {\n  background-color: #f3f6f9;\n}\n.blank-state-actions img[data-v-d440b88c] {\n  top: 3px;\n}", ""]);

// exports


/***/ }),

/***/ "./node_modules/style-loader/index.js!./node_modules/css-loader/index.js!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src/index.js?!./node_modules/sass-loader/lib/loader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/Pages/Adminland/Flow/Actions.vue?vue&type=style&index=0&id=d440b88c&lang=scss&scoped=true&":
/*!*********************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/style-loader!./node_modules/css-loader!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src??ref--7-2!./node_modules/sass-loader/lib/loader.js??ref--7-3!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/Pages/Adminland/Flow/Actions.vue?vue&type=style&index=0&id=d440b88c&lang=scss&scoped=true& ***!
  \*********************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {


var content = __webpack_require__(/*! !../../../../../node_modules/css-loader!../../../../../node_modules/vue-loader/lib/loaders/stylePostLoader.js!../../../../../node_modules/postcss-loader/src??ref--7-2!../../../../../node_modules/sass-loader/lib/loader.js??ref--7-3!../../../../../node_modules/vue-loader/lib??vue-loader-options!./Actions.vue?vue&type=style&index=0&id=d440b88c&lang=scss&scoped=true& */ "./node_modules/css-loader/index.js!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src/index.js?!./node_modules/sass-loader/lib/loader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/Pages/Adminland/Flow/Actions.vue?vue&type=style&index=0&id=d440b88c&lang=scss&scoped=true&");

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

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/Pages/Adminland/Flow/Actions.vue?vue&type=template&id=d440b88c&scoped=true&":
/*!********************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/Pages/Adminland/Flow/Actions.vue?vue&type=template&id=d440b88c&scoped=true& ***!
  \********************************************************************************************************************************************************************************************************************************/
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
      "div",
      {
        directives: [
          {
            name: "show",
            rawName: "v-show",
            value: _vm.orderedActions.length != 0,
            expression: "orderedActions.length != 0"
          }
        ],
        staticClass: "bb bb-gray pa3"
      },
      [
        _c("p", { staticClass: "ma0 pa0 mb1 f6" }, [
          _vm._v(
            "\n      " +
              _vm._s(_vm.$t("account.flow_new_action_following")) +
              "\n    "
          )
        ]),
        _vm._v(" "),
        _c(
          "ul",
          { staticClass: "list ma0 pa0 tl" },
          _vm._l(_vm.orderedActions, function(action) {
            return _c(
              "li",
              {
                key: action.id,
                staticClass: "relative db bb-gray-hover pv2 ph1"
              },
              [
                _c("action-notification", {
                  attrs: { action: action },
                  on: {
                    destroy: function($event) {
                      return _vm.destroyAction(action)
                    },
                    update: function($event) {
                      return _vm.updateAction($event, action)
                    }
                  }
                })
              ],
              1
            )
          }),
          0
        )
      ]
    ),
    _vm._v(" "),
    _c("div", { staticClass: "pa3" }, [
      _c(
        "a",
        {
          directives: [
            {
              name: "show",
              rawName: "v-show",
              value: !_vm.showActionMenu,
              expression: "!showActionMenu"
            }
          ],
          staticClass: "btn dib",
          on: {
            click: function($event) {
              $event.preventDefault()
              _vm.showActionMenu = true
            }
          }
        },
        [_vm._v("Add action")]
      ),
      _vm._v(" "),
      _c(
        "div",
        {
          directives: [
            {
              name: "show",
              rawName: "v-show",
              value: _vm.showActionMenu,
              expression: "showActionMenu"
            }
          ],
          staticClass: "tc"
        },
        [
          _c(
            "div",
            {
              staticClass:
                "tl pv2 ph2 mb3 blank-state-actions dib mr3 br2 pointer",
              on: {
                click: function($event) {
                  return _vm.addAction("notification")
                }
              }
            },
            [
              _c("img", {
                staticClass: "relative mr1",
                attrs: {
                  src: "/img/company/account/action-notification.svg",
                  height: "18",
                  width: "20"
                }
              }),
              _vm._v(
                "\n        " +
                  _vm._s(_vm.$t("account.flow_new_action_notification")) +
                  "\n      "
              )
            ]
          ),
          _vm._v(" "),
          _c(
            "div",
            {
              staticClass:
                "tl pv2 ph2 mb3 blank-state-actions dib mr3 br2 pointer"
            },
            [
              _c("img", {
                staticClass: "relative mr1",
                attrs: {
                  src: "/img/company/account/action-task.svg",
                  height: "20",
                  width: "20"
                }
              }),
              _vm._v(
                "\n        " +
                  _vm._s(_vm.$t("account.flow_new_action_task")) +
                  "\n      "
              )
            ]
          ),
          _vm._v(" "),
          _c(
            "div",
            {
              staticClass:
                "tl pv2 ph2 mb3 blank-state-actions dib mr3 br2 pointer"
            },
            [
              _c("img", {
                staticClass: "relative mr1",
                attrs: {
                  src: "/img/company/account/action-email.svg",
                  height: "20",
                  width: "20"
                }
              }),
              _vm._v(
                "\n        " +
                  _vm._s(_vm.$t("account.flow_new_action_email")) +
                  "\n      "
              )
            ]
          )
        ]
      )
    ])
  ])
}
var staticRenderFns = []
render._withStripped = true



/***/ }),

/***/ "./resources/js/Pages/Adminland/Flow/Actions.vue":
/*!*******************************************************!*\
  !*** ./resources/js/Pages/Adminland/Flow/Actions.vue ***!
  \*******************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _Actions_vue_vue_type_template_id_d440b88c_scoped_true___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./Actions.vue?vue&type=template&id=d440b88c&scoped=true& */ "./resources/js/Pages/Adminland/Flow/Actions.vue?vue&type=template&id=d440b88c&scoped=true&");
/* harmony import */ var _Actions_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./Actions.vue?vue&type=script&lang=js& */ "./resources/js/Pages/Adminland/Flow/Actions.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport *//* harmony import */ var _Actions_vue_vue_type_style_index_0_id_d440b88c_lang_scss_scoped_true___WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./Actions.vue?vue&type=style&index=0&id=d440b88c&lang=scss&scoped=true& */ "./resources/js/Pages/Adminland/Flow/Actions.vue?vue&type=style&index=0&id=d440b88c&lang=scss&scoped=true&");
/* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ../../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");






/* normalize component */

var component = Object(_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_3__["default"])(
  _Actions_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _Actions_vue_vue_type_template_id_d440b88c_scoped_true___WEBPACK_IMPORTED_MODULE_0__["render"],
  _Actions_vue_vue_type_template_id_d440b88c_scoped_true___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"],
  false,
  null,
  "d440b88c",
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/Pages/Adminland/Flow/Actions.vue"
/* harmony default export */ __webpack_exports__["default"] = (component.exports);

/***/ }),

/***/ "./resources/js/Pages/Adminland/Flow/Actions.vue?vue&type=script&lang=js&":
/*!********************************************************************************!*\
  !*** ./resources/js/Pages/Adminland/Flow/Actions.vue?vue&type=script&lang=js& ***!
  \********************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_Actions_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../node_modules/babel-loader/lib??ref--4-0!../../../../../node_modules/vue-loader/lib??vue-loader-options!./Actions.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/Pages/Adminland/Flow/Actions.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport */ /* harmony default export */ __webpack_exports__["default"] = (_node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_Actions_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/js/Pages/Adminland/Flow/Actions.vue?vue&type=style&index=0&id=d440b88c&lang=scss&scoped=true&":
/*!*****************************************************************************************************************!*\
  !*** ./resources/js/Pages/Adminland/Flow/Actions.vue?vue&type=style&index=0&id=d440b88c&lang=scss&scoped=true& ***!
  \*****************************************************************************************************************/
/*! no static exports found */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_style_loader_index_js_node_modules_css_loader_index_js_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_7_2_node_modules_sass_loader_lib_loader_js_ref_7_3_node_modules_vue_loader_lib_index_js_vue_loader_options_Actions_vue_vue_type_style_index_0_id_d440b88c_lang_scss_scoped_true___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../node_modules/style-loader!../../../../../node_modules/css-loader!../../../../../node_modules/vue-loader/lib/loaders/stylePostLoader.js!../../../../../node_modules/postcss-loader/src??ref--7-2!../../../../../node_modules/sass-loader/lib/loader.js??ref--7-3!../../../../../node_modules/vue-loader/lib??vue-loader-options!./Actions.vue?vue&type=style&index=0&id=d440b88c&lang=scss&scoped=true& */ "./node_modules/style-loader/index.js!./node_modules/css-loader/index.js!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src/index.js?!./node_modules/sass-loader/lib/loader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/Pages/Adminland/Flow/Actions.vue?vue&type=style&index=0&id=d440b88c&lang=scss&scoped=true&");
/* harmony import */ var _node_modules_style_loader_index_js_node_modules_css_loader_index_js_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_7_2_node_modules_sass_loader_lib_loader_js_ref_7_3_node_modules_vue_loader_lib_index_js_vue_loader_options_Actions_vue_vue_type_style_index_0_id_d440b88c_lang_scss_scoped_true___WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_node_modules_style_loader_index_js_node_modules_css_loader_index_js_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_7_2_node_modules_sass_loader_lib_loader_js_ref_7_3_node_modules_vue_loader_lib_index_js_vue_loader_options_Actions_vue_vue_type_style_index_0_id_d440b88c_lang_scss_scoped_true___WEBPACK_IMPORTED_MODULE_0__);
/* harmony reexport (unknown) */ for(var __WEBPACK_IMPORT_KEY__ in _node_modules_style_loader_index_js_node_modules_css_loader_index_js_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_7_2_node_modules_sass_loader_lib_loader_js_ref_7_3_node_modules_vue_loader_lib_index_js_vue_loader_options_Actions_vue_vue_type_style_index_0_id_d440b88c_lang_scss_scoped_true___WEBPACK_IMPORTED_MODULE_0__) if(__WEBPACK_IMPORT_KEY__ !== 'default') (function(key) { __webpack_require__.d(__webpack_exports__, key, function() { return _node_modules_style_loader_index_js_node_modules_css_loader_index_js_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_7_2_node_modules_sass_loader_lib_loader_js_ref_7_3_node_modules_vue_loader_lib_index_js_vue_loader_options_Actions_vue_vue_type_style_index_0_id_d440b88c_lang_scss_scoped_true___WEBPACK_IMPORTED_MODULE_0__[key]; }) }(__WEBPACK_IMPORT_KEY__));
 /* harmony default export */ __webpack_exports__["default"] = (_node_modules_style_loader_index_js_node_modules_css_loader_index_js_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_7_2_node_modules_sass_loader_lib_loader_js_ref_7_3_node_modules_vue_loader_lib_index_js_vue_loader_options_Actions_vue_vue_type_style_index_0_id_d440b88c_lang_scss_scoped_true___WEBPACK_IMPORTED_MODULE_0___default.a); 

/***/ }),

/***/ "./resources/js/Pages/Adminland/Flow/Actions.vue?vue&type=template&id=d440b88c&scoped=true&":
/*!**************************************************************************************************!*\
  !*** ./resources/js/Pages/Adminland/Flow/Actions.vue?vue&type=template&id=d440b88c&scoped=true& ***!
  \**************************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_Actions_vue_vue_type_template_id_d440b88c_scoped_true___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../../node_modules/vue-loader/lib??vue-loader-options!./Actions.vue?vue&type=template&id=d440b88c&scoped=true& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/Pages/Adminland/Flow/Actions.vue?vue&type=template&id=d440b88c&scoped=true&");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "render", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_Actions_vue_vue_type_template_id_d440b88c_scoped_true___WEBPACK_IMPORTED_MODULE_0__["render"]; });

/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_Actions_vue_vue_type_template_id_d440b88c_scoped_true___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"]; });



/***/ })

}]);