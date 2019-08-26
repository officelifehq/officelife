(window["webpackJsonp"] = window["webpackJsonp"] || []).push([[7],{

/***/ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/Pages/Employee/AssignEmployeeHierarchy.vue?vue&type=script&lang=js&":
/*!**************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib??ref--4-0!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/Pages/Employee/AssignEmployeeHierarchy.vue?vue&type=script&lang=js& ***!
  \**************************************************************************************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var vue_click_outside__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! vue-click-outside */ "./node_modules/vue-click-outside/index.js");
/* harmony import */ var vue_click_outside__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(vue_click_outside__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var vue_loaders_dist_vue_loaders_css__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! vue-loaders/dist/vue-loaders.css */ "./node_modules/vue-loaders/dist/vue-loaders.css");
/* harmony import */ var vue_loaders_dist_vue_loaders_css__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(vue_loaders_dist_vue_loaders_css__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var vue_loaders_src_loaders_ball_pulse__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! vue-loaders/src/loaders/ball-pulse */ "./node_modules/vue-loaders/src/loaders/ball-pulse.vue");
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
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
    BallPulseLoader: vue_loaders_src_loaders_ball_pulse__WEBPACK_IMPORTED_MODULE_2__["default"]
  },
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
    managers: {
      type: Array,
      "default": null
    },
    directReports: {
      type: Array,
      "default": null
    }
  },
  data: function data() {
    return {
      modal: 'hide',
      processingSearch: false,
      searchManagers: [],
      searchDirectReports: [],
      form: {
        searchTerm: null,
        errors: []
      },
      managerModalId: 0,
      directReportModalId: 0,
      deleteEmployeeConfirmation: false
    };
  },
  mounted: function mounted() {
    // prevent click outside event with popupItem.
    this.popupItem = this.$el;
  },
  methods: {
    toggleModals: function toggleModals() {
      if (this.modal == 'hide') {
        this.modal = 'menu';
      } else {
        this.modal = 'hide';
      }

      this.searchManagers = [];
      this.searchDirectReports = [];
      this.form.searchTerm = null;
    },
    displayManagerModal: function displayManagerModal() {
      var _this = this;

      this.modal = 'manager';
      this.$nextTick(function () {
        _this.$refs.search.focus();
      });
    },
    displayDirectReportModal: function displayDirectReportModal() {
      var _this2 = this;

      this.modal = 'directReport';
      this.$nextTick(function () {
        _this2.$refs.search.focus();
      });
    },
    hideManagerModal: function hideManagerModal() {
      this.managerModalId = 0;
    },
    hideDirectReportModal: function hideDirectReportModal() {
      this.directReportModalId = 0;
    },
    search: _.debounce(function () {
      var _this3 = this;

      if (this.form.searchTerm != '') {
        this.processingSearch = true;
        axios.post('/' + this.$page.auth.company.id + '/employees/' + this.employee.id + '/search/hierarchy', this.form).then(function (response) {
          if (_this3.modal == 'manager') {
            _this3.searchManagers = response.data.data;
          }

          if (_this3.modal == 'directReport') {
            _this3.searchDirectReports = response.data.data;
          }

          _this3.processingSearch = false;
        })["catch"](function (error) {
          _this3.form.errors = _.flatten(_.toArray(error.response.data));
          _this3.processingSearch = false;
        });
      }
    }, 500),
    assignManager: function assignManager(manager) {
      var _this4 = this;

      axios.post('/' + this.$page.auth.company.id + '/employees/' + this.employee.id + '/assignManager', manager).then(function (response) {
        _this4.$snotify.success(_this4.$t('employee.hierarchy_modal_add_manager_success'), {
          timeout: 2000,
          showProgressBar: true,
          closeOnClick: true,
          pauseOnHover: true
        });

        _this4.managers.push(response.data.data);

        _this4.modal = 'hide';
      })["catch"](function (error) {
        _this4.form.errors = _.flatten(_.toArray(error.response.data));
      });
    },
    assignDirectReport: function assignDirectReport(directReport) {
      var _this5 = this;

      axios.post('/' + this.$page.auth.company.id + '/employees/' + this.employee.id + '/assignDirectReport', directReport).then(function (response) {
        _this5.$snotify.success(_this5.$t('employee.hierarchy_modal_add_direct_report_success'), {
          timeout: 2000,
          showProgressBar: true,
          closeOnClick: true,
          pauseOnHover: true
        });

        _this5.directReports.push(response.data.data);

        _this5.modal = 'hide';
      })["catch"](function (error) {
        _this5.form.errors = _.flatten(_.toArray(error.response.data));
      });
    },
    unassignManager: function unassignManager(manager) {
      var _this6 = this;

      axios.post('/' + this.$page.auth.company.id + '/employees/' + this.employee.id + '/unassignManager', manager).then(function (response) {
        _this6.$snotify.success(_this6.$t('employee.hierarchy_modal_remove_manager_success'), {
          timeout: 2000,
          showProgressBar: true,
          closeOnClick: true,
          pauseOnHover: true
        });

        _this6.managers.splice(_this6.managers.indexOf(response.data.data), 1);

        _this6.deleteEmployeeConfirmation = false;
        _this6.managerModalId = 0;
      })["catch"](function (error) {
        _this6.form.errors = _.flatten(_.toArray(error.response.data));
      });
    },
    unassignDirectReport: function unassignDirectReport(directReport) {
      var _this7 = this;

      axios.post('/' + this.$page.auth.company.id + '/employees/' + this.employee.id + '/unassignDirectReport', directReport).then(function (response) {
        _this7.$snotify.success(_this7.$t('employee.hierarchy_modal_remove_direct_report_success'), {
          timeout: 2000,
          showProgressBar: true,
          closeOnClick: true,
          pauseOnHover: true
        });

        _this7.directReports.splice(_this7.directReports.indexOf(response.data.data), 1);

        _this7.deleteEmployeeConfirmation = false;
        _this7.directReportModalId = 0;
      })["catch"](function (error) {
        _this7.form.errors = _.flatten(_.toArray(error.response.data));
      });
    }
  }
});

/***/ }),

/***/ "./node_modules/css-loader/index.js?!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/Pages/Employee/AssignEmployeeHierarchy.vue?vue&type=style&index=0&id=1c31e9a0&scoped=true&lang=css&":
/*!*********************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/css-loader??ref--6-1!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src??ref--6-2!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/Pages/Employee/AssignEmployeeHierarchy.vue?vue&type=style&index=0&id=1c31e9a0&scoped=true&lang=css& ***!
  \*********************************************************************************************************************************************************************************************************************************************************************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

exports = module.exports = __webpack_require__(/*! ../../../../node_modules/css-loader/lib/css-base.js */ "./node_modules/css-loader/lib/css-base.js")(false);
// imports


// module
exports.push([module.i, "\n.list-employees > ul[data-v-1c31e9a0] {\n  padding-left: 43px;\n}\n.list-employees li[data-v-1c31e9a0]:last-child {\n  margin-bottom: 0;\n}\n.avatar[data-v-1c31e9a0] {\n  top: 1px;\n  left: -44px;\n  width: 35px;\n}\n.list-employees-action[data-v-1c31e9a0] {\n  top: 15px;\n}\n.list-employees-modal[data-v-1c31e9a0] {\n  right: -6px;\n  top: 27px;\n}\n.icon-delete[data-v-1c31e9a0] {\n  top: 2px;\n}\n.ball-pulse[data-v-1c31e9a0] {\n  right: 8px;\n  top: 10px;\n  position: absolute;\n}\n", ""]);

// exports


/***/ }),

/***/ "./node_modules/style-loader/index.js!./node_modules/css-loader/index.js?!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/Pages/Employee/AssignEmployeeHierarchy.vue?vue&type=style&index=0&id=1c31e9a0&scoped=true&lang=css&":
/*!*************************************************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/style-loader!./node_modules/css-loader??ref--6-1!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src??ref--6-2!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/Pages/Employee/AssignEmployeeHierarchy.vue?vue&type=style&index=0&id=1c31e9a0&scoped=true&lang=css& ***!
  \*************************************************************************************************************************************************************************************************************************************************************************************************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {


var content = __webpack_require__(/*! !../../../../node_modules/css-loader??ref--6-1!../../../../node_modules/vue-loader/lib/loaders/stylePostLoader.js!../../../../node_modules/postcss-loader/src??ref--6-2!../../../../node_modules/vue-loader/lib??vue-loader-options!./AssignEmployeeHierarchy.vue?vue&type=style&index=0&id=1c31e9a0&scoped=true&lang=css& */ "./node_modules/css-loader/index.js?!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/Pages/Employee/AssignEmployeeHierarchy.vue?vue&type=style&index=0&id=1c31e9a0&scoped=true&lang=css&");

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

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/Pages/Employee/AssignEmployeeHierarchy.vue?vue&type=template&id=1c31e9a0&scoped=true&":
/*!******************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/Pages/Employee/AssignEmployeeHierarchy.vue?vue&type=template&id=1c31e9a0&scoped=true& ***!
  \******************************************************************************************************************************************************************************************************************************************/
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
  return _c("div", { staticClass: "mb4 relative" }, [
    _c("span", { staticClass: "tc db fw5 mb2" }, [
      _vm._v(_vm._s(_vm.$t("employee.hierarchy_title")))
    ]),
    _vm._v(" "),
    _c("img", {
      directives: [
        {
          name: "show",
          rawName: "v-show",
          value: _vm.$page.auth.employee.permission_level <= 200,
          expression: "$page.auth.employee.permission_level <= 200"
        }
      ],
      staticClass: "box-plus-button absolute br-100 pa2 bg-white pointer",
      attrs: { src: "/img/plus_button.svg", "data-cy": "add-hierarchy-button" },
      on: {
        click: function($event) {
          $event.preventDefault()
          return _vm.toggleModals()
        }
      }
    }),
    _vm._v(" "),
    _vm.modal == "menu"
      ? _c(
          "div",
          {
            directives: [
              {
                name: "click-outside",
                rawName: "v-click-outside",
                value: _vm.toggleModals,
                expression: "toggleModals"
              }
            ],
            staticClass:
              "popupmenu absolute br2 bg-white z-max tl pv2 ph3 bounceIn faster"
          },
          [
            _c("ul", { staticClass: "list ma0 pa0" }, [
              _c("li", { staticClass: "pv2" }, [
                _c(
                  "a",
                  {
                    staticClass: "pointer",
                    attrs: { "data-cy": "add-manager-button" },
                    on: {
                      click: function($event) {
                        $event.preventDefault()
                        return _vm.displayManagerModal()
                      }
                    }
                  },
                  [
                    _vm._v(
                      _vm._s(_vm.$t("employee.hierarchy_modal_add_manager"))
                    )
                  ]
                )
              ]),
              _vm._v(" "),
              _c("li", { staticClass: "pv2" }, [
                _c(
                  "a",
                  {
                    staticClass: "pointer",
                    attrs: { "data-cy": "add-direct-report-button" },
                    on: {
                      click: function($event) {
                        $event.preventDefault()
                        return _vm.displayDirectReportModal()
                      }
                    }
                  },
                  [
                    _vm._v(
                      _vm._s(
                        _vm.$t("employee.hierarchy_modal_add_direct_report")
                      )
                    )
                  ]
                )
              ])
            ])
          ]
        )
      : _vm._e(),
    _vm._v(" "),
    _vm.modal == "manager"
      ? _c(
          "div",
          {
            directives: [
              {
                name: "click-outside",
                rawName: "v-click-outside",
                value: _vm.toggleModals,
                expression: "toggleModals"
              }
            ],
            staticClass:
              "popupmenu absolute br2 bg-white z-max tl pv2 ph3 bounceIn faster"
          },
          [
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
                _c("div", { staticClass: "mb3 relative" }, [
                  _c("p", [
                    _vm._v(
                      _vm._s(
                        _vm.$t("employee.hierarchy_modal_add_manager_search", {
                          name: _vm.employee.first_name
                        })
                      )
                    )
                  ]),
                  _vm._v(" "),
                  _c(
                    "div",
                    { staticClass: "relative" },
                    [
                      _c("input", {
                        directives: [
                          {
                            name: "model",
                            rawName: "v-model",
                            value: _vm.form.searchTerm,
                            expression: "form.searchTerm"
                          }
                        ],
                        ref: "search",
                        staticClass:
                          "br2 f5 w-100 ba b--black-40 pa2 outline-0",
                        attrs: {
                          id: "search",
                          type: "text",
                          name: "search",
                          placeholder: _vm.$t(
                            "employee.hierarchy_search_placeholder"
                          ),
                          required: "",
                          "data-cy": "search-manager"
                        },
                        domProps: { value: _vm.form.searchTerm },
                        on: {
                          keyup: _vm.search,
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
                            return _vm.toggleModals()
                          },
                          input: function($event) {
                            if ($event.target.composing) {
                              return
                            }
                            _vm.$set(
                              _vm.form,
                              "searchTerm",
                              $event.target.value
                            )
                          }
                        }
                      }),
                      _vm._v(" "),
                      _vm.processingSearch
                        ? _c("ball-pulse-loader", {
                            attrs: { color: "#5c7575", size: "7px" }
                          })
                        : _vm._e()
                    ],
                    1
                  )
                ])
              ]
            ),
            _vm._v(" "),
            _c("ul", { staticClass: "pl0 list ma0" }, [
              _c("li", { staticClass: "fw5 mb3" }, [
                _c("span", { staticClass: "f6 mb2 dib" }, [
                  _vm._v(_vm._s(_vm.$t("employee.hierarchy_search_results")))
                ]),
                _vm._v(" "),
                _vm.searchManagers.length > 0
                  ? _c(
                      "ul",
                      { staticClass: "list ma0 pl0" },
                      _vm._l(_vm.searchManagers, function(manager) {
                        return _c(
                          "li",
                          {
                            key: manager.id,
                            staticClass:
                              "bb relative pv2 ph1 bb-gray bb-gray-hover"
                          },
                          [
                            _vm._v(
                              "\n            " +
                                _vm._s(manager.name) +
                                "\n            "
                            ),
                            _c(
                              "a",
                              {
                                staticClass: "absolute right-1 pointer",
                                attrs: {
                                  "data-cy": "potential-manager-button"
                                },
                                on: {
                                  click: function($event) {
                                    $event.preventDefault()
                                    return _vm.assignManager(manager)
                                  }
                                }
                              },
                              [_vm._v(_vm._s(_vm.$t("app.choose")))]
                            )
                          ]
                        )
                      }),
                      0
                    )
                  : _c("div", { staticClass: "silver" }, [
                      _vm._v(
                        "\n          " +
                          _vm._s(_vm.$t("app.no_results")) +
                          "\n        "
                      )
                    ])
              ])
            ])
          ]
        )
      : _vm._e(),
    _vm._v(" "),
    _vm.modal == "directReport"
      ? _c(
          "div",
          {
            directives: [
              {
                name: "click-outside",
                rawName: "v-click-outside",
                value: _vm.toggleModals,
                expression: "toggleModals"
              }
            ],
            staticClass:
              "popupmenu absolute br2 bg-white z-max tl pv2 ph3 bounceIn faster"
          },
          [
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
                _c("div", { staticClass: "mb3 relative" }, [
                  _c("p", [
                    _vm._v(
                      _vm._s(
                        _vm.$t(
                          "employee.hierarchy_modal_add_direct_report_search",
                          { name: _vm.employee.first_name }
                        )
                      )
                    )
                  ]),
                  _vm._v(" "),
                  _c(
                    "div",
                    { staticClass: "relative" },
                    [
                      _c("input", {
                        directives: [
                          {
                            name: "model",
                            rawName: "v-model",
                            value: _vm.form.searchTerm,
                            expression: "form.searchTerm"
                          }
                        ],
                        ref: "search",
                        staticClass:
                          "br2 f5 w-100 ba b--black-40 pa2 outline-0",
                        attrs: {
                          id: "search",
                          type: "text",
                          name: "search",
                          placeholder: _vm.$t(
                            "employee.hierarchy_search_placeholder"
                          ),
                          required: "",
                          "data-cy": "search-direct-report"
                        },
                        domProps: { value: _vm.form.searchTerm },
                        on: {
                          keyup: _vm.search,
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
                            return _vm.toggleModals()
                          },
                          input: function($event) {
                            if ($event.target.composing) {
                              return
                            }
                            _vm.$set(
                              _vm.form,
                              "searchTerm",
                              $event.target.value
                            )
                          }
                        }
                      }),
                      _vm._v(" "),
                      _vm.processingSearch
                        ? _c("ball-pulse-loader", {
                            attrs: { color: "#5c7575", size: "7px" }
                          })
                        : _vm._e()
                    ],
                    1
                  )
                ])
              ]
            ),
            _vm._v(" "),
            _c("ul", { staticClass: "pl0 list ma0" }, [
              _c("li", { staticClass: "fw5 mb3" }, [
                _c("span", { staticClass: "f6 mb2 dib" }, [
                  _vm._v(_vm._s(_vm.$t("employee.hierarchy_search_results")))
                ]),
                _vm._v(" "),
                _vm.searchDirectReports.length > 0
                  ? _c(
                      "ul",
                      { staticClass: "list ma0 pl0" },
                      _vm._l(_vm.searchDirectReports, function(directReport) {
                        return _c(
                          "li",
                          {
                            key: directReport.id,
                            staticClass:
                              "bb relative pv2 ph1 bb-gray bb-gray-hover"
                          },
                          [
                            _vm._v(
                              "\n            " +
                                _vm._s(directReport.name) +
                                "\n            "
                            ),
                            _c(
                              "a",
                              {
                                staticClass: "absolute right-1 pointer",
                                attrs: {
                                  "data-cy": "potential-direct-report-button"
                                },
                                on: {
                                  click: function($event) {
                                    $event.preventDefault()
                                    return _vm.assignDirectReport(directReport)
                                  }
                                }
                              },
                              [_vm._v(_vm._s(_vm.$t("app.choose")))]
                            )
                          ]
                        )
                      }),
                      0
                    )
                  : _c("div", { staticClass: "silver" }, [
                      _vm._v(
                        "\n          " +
                          _vm._s(_vm.$t("app.no_results")) +
                          "\n        "
                      )
                    ])
              ])
            ])
          ]
        )
      : _vm._e(),
    _vm._v(" "),
    _c("div", { staticClass: "br3 bg-white box z-1 pa3 list-employees" }, [
      _vm.managers.length == 0 && _vm.directReports.length == 0
        ? _c("p", { staticClass: "lh-copy mb0 f6" }, [
            _vm._v(
              "\n      " + _vm._s(_vm.$t("employee.hierarchy_blank")) + "\n    "
            )
          ])
        : _vm._e(),
      _vm._v(" "),
      _c(
        "div",
        {
          directives: [
            {
              name: "show",
              rawName: "v-show",
              value: _vm.managers.length != 0,
              expression: "managers.length != 0"
            }
          ],
          attrs: { "data-cy": "list-managers" }
        },
        [
          _c("p", { staticClass: "mt0 mb2 f6" }, [
            _vm._v(
              "\n        " +
                _vm._s(
                  _vm.$tc(
                    "employee.hierarchy_list_manager_title",
                    _vm.managers.length
                  )
                ) +
                "\n      "
            )
          ]),
          _vm._v(" "),
          _c(
            "ul",
            { staticClass: "list mv0" },
            _vm._l(_vm.managers, function(manager) {
              return _c(
                "li",
                { key: manager.id, staticClass: "mb3 relative" },
                [
                  _c("img", {
                    staticClass: "br-100 absolute avatar",
                    attrs: { src: manager.avatar }
                  }),
                  _vm._v(" "),
                  _c(
                    "a",
                    {
                      staticClass: "mb2",
                      attrs: {
                        href:
                          "/" +
                          _vm.$page.auth.company.id +
                          "/employees/" +
                          manager.id
                      }
                    },
                    [_vm._v(_vm._s(manager.name))]
                  ),
                  _vm._v(" "),
                  manager.position !== null
                    ? _c("span", { staticClass: "title db f7 mt1" }, [
                        _vm._v(_vm._s(manager.position.title))
                      ])
                    : _c("span", { staticClass: "title db f7 mt1" }, [
                        _vm._v(_vm._s(_vm.$t("app.no_position_defined")))
                      ]),
                  _vm._v(" "),
                  _c("img", {
                    staticClass:
                      "absolute right-0 pointer list-employees-action",
                    attrs: {
                      src: "/img/common/triple-dots.svg",
                      "data-cy": "display-remove-manager-modal"
                    },
                    on: {
                      click: function($event) {
                        _vm.managerModalId = manager.id
                      }
                    }
                  }),
                  _vm._v(" "),
                  _vm.managerModalId == manager.id
                    ? _c(
                        "div",
                        {
                          directives: [
                            {
                              name: "show",
                              rawName: "v-show",
                              value:
                                _vm.$page.auth.employee.permission_level <= 200,
                              expression:
                                "$page.auth.employee.permission_level <= 200"
                            },
                            {
                              name: "click-outside",
                              rawName: "v-click-outside",
                              value: _vm.hideManagerModal,
                              expression: "hideManagerModal"
                            }
                          ],
                          staticClass:
                            "popupmenu absolute br2 bg-white z-max tl pv2 ph3 bounceIn list-employees-modal"
                        },
                        [
                          _c("ul", { staticClass: "list ma0 pa0" }, [
                            _c(
                              "li",
                              {
                                directives: [
                                  {
                                    name: "show",
                                    rawName: "v-show",
                                    value: !_vm.deleteEmployeeConfirmation,
                                    expression: "!deleteEmployeeConfirmation"
                                  }
                                ],
                                staticClass: "pv2 relative"
                              },
                              [
                                _c("icon-delete", {
                                  attrs: {
                                    classes: "icon-delete relative",
                                    width: 15,
                                    height: 15
                                  }
                                }),
                                _vm._v(" "),
                                _c(
                                  "a",
                                  {
                                    staticClass: "pointer ml1 c-delete",
                                    attrs: {
                                      "data-cy": "remove-manager-button"
                                    },
                                    on: {
                                      click: function($event) {
                                        $event.preventDefault()
                                        _vm.deleteEmployeeConfirmation = true
                                      }
                                    }
                                  },
                                  [
                                    _vm._v(
                                      _vm._s(
                                        _vm.$t(
                                          "employee.hierarchy_modal_remove_manager"
                                        )
                                      )
                                    )
                                  ]
                                )
                              ],
                              1
                            ),
                            _vm._v(" "),
                            _c(
                              "li",
                              {
                                directives: [
                                  {
                                    name: "show",
                                    rawName: "v-show",
                                    value: _vm.deleteEmployeeConfirmation,
                                    expression: "deleteEmployeeConfirmation"
                                  }
                                ],
                                staticClass: "pv2"
                              },
                              [
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
                                      "data-cy": "confirm-remove-manager"
                                    },
                                    on: {
                                      click: function($event) {
                                        $event.preventDefault()
                                        return _vm.unassignManager(manager)
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
                                    on: {
                                      click: function($event) {
                                        $event.preventDefault()
                                        _vm.deleteEmployeeConfirmation = false
                                      }
                                    }
                                  },
                                  [_vm._v(_vm._s(_vm.$t("app.no")))]
                                )
                              ]
                            )
                          ])
                        ]
                      )
                    : _vm._e()
                ]
              )
            }),
            0
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
              value: _vm.directReports.length != 0,
              expression: "directReports.length != 0"
            }
          ],
          class: _vm.managers.length != 0 ? "mt3" : "",
          attrs: { "data-cy": "list-direct-reports" }
        },
        [
          _c("p", { staticClass: "mt0 mb2 f6" }, [
            _vm._v(
              "\n        " +
                _vm._s(
                  _vm.$tc(
                    "employee.hierarchy_list_direct_report_title",
                    _vm.directReports.length
                  )
                ) +
                "\n      "
            )
          ]),
          _vm._v(" "),
          _c(
            "ul",
            { staticClass: "list mv0" },
            _vm._l(_vm.directReports, function(directReport) {
              return _c(
                "li",
                { key: directReport.id, staticClass: "mb3 relative" },
                [
                  _c("img", {
                    staticClass: "br-100 absolute avatar",
                    attrs: { src: directReport.avatar }
                  }),
                  _vm._v(" "),
                  _c(
                    "a",
                    {
                      staticClass: "mb2",
                      attrs: {
                        href:
                          "/" +
                          _vm.$page.auth.company.id +
                          "/employees/" +
                          directReport.id
                      }
                    },
                    [_vm._v(_vm._s(directReport.name))]
                  ),
                  _vm._v(" "),
                  directReport.position !== null
                    ? _c("span", { staticClass: "title db f7 mt1" }, [
                        _vm._v(_vm._s(directReport.position.title))
                      ])
                    : _c("span", { staticClass: "title db f7 mt1" }, [
                        _vm._v(_vm._s(_vm.$t("app.no_position_defined")))
                      ]),
                  _vm._v(" "),
                  _c("img", {
                    staticClass:
                      "absolute right-0 pointer list-employees-action",
                    attrs: {
                      src: "/img/common/triple-dots.svg",
                      "data-cy": "display-remove-directreport-modal"
                    },
                    on: {
                      click: function($event) {
                        _vm.directReportModalId = directReport.id
                      }
                    }
                  }),
                  _vm._v(" "),
                  _vm.directReportModalId == directReport.id
                    ? _c(
                        "div",
                        {
                          directives: [
                            {
                              name: "show",
                              rawName: "v-show",
                              value:
                                _vm.$page.auth.employee.permission_level <= 200,
                              expression:
                                "$page.auth.employee.permission_level <= 200"
                            },
                            {
                              name: "click-outside",
                              rawName: "v-click-outside",
                              value: _vm.hideDirectReportModal,
                              expression: "hideDirectReportModal"
                            }
                          ],
                          staticClass:
                            "popupmenu absolute br2 bg-white z-max tl pv2 ph3 bounceIn list-employees-modal"
                        },
                        [
                          _c("ul", { staticClass: "list ma0 pa0" }, [
                            _c(
                              "li",
                              {
                                directives: [
                                  {
                                    name: "show",
                                    rawName: "v-show",
                                    value: !_vm.deleteEmployeeConfirmation,
                                    expression: "!deleteEmployeeConfirmation"
                                  }
                                ],
                                staticClass: "pv2 relative"
                              },
                              [
                                _c("icon-delete", {
                                  attrs: {
                                    classes: "icon-delete relative",
                                    width: 15,
                                    height: 15
                                  }
                                }),
                                _vm._v(" "),
                                _c(
                                  "a",
                                  {
                                    staticClass: "pointer ml1 c-delete",
                                    attrs: {
                                      "data-cy": "remove-directreport-button"
                                    },
                                    on: {
                                      click: function($event) {
                                        $event.preventDefault()
                                        _vm.deleteEmployeeConfirmation = true
                                      }
                                    }
                                  },
                                  [
                                    _vm._v(
                                      _vm._s(
                                        _vm.$t(
                                          "employee.hierarchy_modal_remove_direct_report"
                                        )
                                      )
                                    )
                                  ]
                                )
                              ],
                              1
                            ),
                            _vm._v(" "),
                            _c(
                              "li",
                              {
                                directives: [
                                  {
                                    name: "show",
                                    rawName: "v-show",
                                    value: _vm.deleteEmployeeConfirmation,
                                    expression: "deleteEmployeeConfirmation"
                                  }
                                ],
                                staticClass: "pv2"
                              },
                              [
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
                                      "data-cy": "confirm-remove-directreport"
                                    },
                                    on: {
                                      click: function($event) {
                                        $event.preventDefault()
                                        return _vm.unassignDirectReport(
                                          directReport
                                        )
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
                                    on: {
                                      click: function($event) {
                                        $event.preventDefault()
                                        _vm.deleteEmployeeConfirmation = false
                                      }
                                    }
                                  },
                                  [_vm._v(_vm._s(_vm.$t("app.no")))]
                                )
                              ]
                            )
                          ])
                        ]
                      )
                    : _vm._e()
                ]
              )
            }),
            0
          )
        ]
      )
    ])
  ])
}
var staticRenderFns = []
render._withStripped = true



/***/ }),

/***/ "./resources/js/Pages/Employee/AssignEmployeeHierarchy.vue":
/*!*****************************************************************!*\
  !*** ./resources/js/Pages/Employee/AssignEmployeeHierarchy.vue ***!
  \*****************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _AssignEmployeeHierarchy_vue_vue_type_template_id_1c31e9a0_scoped_true___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./AssignEmployeeHierarchy.vue?vue&type=template&id=1c31e9a0&scoped=true& */ "./resources/js/Pages/Employee/AssignEmployeeHierarchy.vue?vue&type=template&id=1c31e9a0&scoped=true&");
/* harmony import */ var _AssignEmployeeHierarchy_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./AssignEmployeeHierarchy.vue?vue&type=script&lang=js& */ "./resources/js/Pages/Employee/AssignEmployeeHierarchy.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport *//* harmony import */ var _AssignEmployeeHierarchy_vue_vue_type_style_index_0_id_1c31e9a0_scoped_true_lang_css___WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./AssignEmployeeHierarchy.vue?vue&type=style&index=0&id=1c31e9a0&scoped=true&lang=css& */ "./resources/js/Pages/Employee/AssignEmployeeHierarchy.vue?vue&type=style&index=0&id=1c31e9a0&scoped=true&lang=css&");
/* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");






/* normalize component */

var component = Object(_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_3__["default"])(
  _AssignEmployeeHierarchy_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _AssignEmployeeHierarchy_vue_vue_type_template_id_1c31e9a0_scoped_true___WEBPACK_IMPORTED_MODULE_0__["render"],
  _AssignEmployeeHierarchy_vue_vue_type_template_id_1c31e9a0_scoped_true___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"],
  false,
  null,
  "1c31e9a0",
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/Pages/Employee/AssignEmployeeHierarchy.vue"
/* harmony default export */ __webpack_exports__["default"] = (component.exports);

/***/ }),

/***/ "./resources/js/Pages/Employee/AssignEmployeeHierarchy.vue?vue&type=script&lang=js&":
/*!******************************************************************************************!*\
  !*** ./resources/js/Pages/Employee/AssignEmployeeHierarchy.vue?vue&type=script&lang=js& ***!
  \******************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_AssignEmployeeHierarchy_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../node_modules/babel-loader/lib??ref--4-0!../../../../node_modules/vue-loader/lib??vue-loader-options!./AssignEmployeeHierarchy.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/Pages/Employee/AssignEmployeeHierarchy.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport */ /* harmony default export */ __webpack_exports__["default"] = (_node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_AssignEmployeeHierarchy_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/js/Pages/Employee/AssignEmployeeHierarchy.vue?vue&type=style&index=0&id=1c31e9a0&scoped=true&lang=css&":
/*!**************************************************************************************************************************!*\
  !*** ./resources/js/Pages/Employee/AssignEmployeeHierarchy.vue?vue&type=style&index=0&id=1c31e9a0&scoped=true&lang=css& ***!
  \**************************************************************************************************************************/
/*! no static exports found */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_style_loader_index_js_node_modules_css_loader_index_js_ref_6_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_6_2_node_modules_vue_loader_lib_index_js_vue_loader_options_AssignEmployeeHierarchy_vue_vue_type_style_index_0_id_1c31e9a0_scoped_true_lang_css___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../node_modules/style-loader!../../../../node_modules/css-loader??ref--6-1!../../../../node_modules/vue-loader/lib/loaders/stylePostLoader.js!../../../../node_modules/postcss-loader/src??ref--6-2!../../../../node_modules/vue-loader/lib??vue-loader-options!./AssignEmployeeHierarchy.vue?vue&type=style&index=0&id=1c31e9a0&scoped=true&lang=css& */ "./node_modules/style-loader/index.js!./node_modules/css-loader/index.js?!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/Pages/Employee/AssignEmployeeHierarchy.vue?vue&type=style&index=0&id=1c31e9a0&scoped=true&lang=css&");
/* harmony import */ var _node_modules_style_loader_index_js_node_modules_css_loader_index_js_ref_6_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_6_2_node_modules_vue_loader_lib_index_js_vue_loader_options_AssignEmployeeHierarchy_vue_vue_type_style_index_0_id_1c31e9a0_scoped_true_lang_css___WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_node_modules_style_loader_index_js_node_modules_css_loader_index_js_ref_6_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_6_2_node_modules_vue_loader_lib_index_js_vue_loader_options_AssignEmployeeHierarchy_vue_vue_type_style_index_0_id_1c31e9a0_scoped_true_lang_css___WEBPACK_IMPORTED_MODULE_0__);
/* harmony reexport (unknown) */ for(var __WEBPACK_IMPORT_KEY__ in _node_modules_style_loader_index_js_node_modules_css_loader_index_js_ref_6_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_6_2_node_modules_vue_loader_lib_index_js_vue_loader_options_AssignEmployeeHierarchy_vue_vue_type_style_index_0_id_1c31e9a0_scoped_true_lang_css___WEBPACK_IMPORTED_MODULE_0__) if(__WEBPACK_IMPORT_KEY__ !== 'default') (function(key) { __webpack_require__.d(__webpack_exports__, key, function() { return _node_modules_style_loader_index_js_node_modules_css_loader_index_js_ref_6_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_6_2_node_modules_vue_loader_lib_index_js_vue_loader_options_AssignEmployeeHierarchy_vue_vue_type_style_index_0_id_1c31e9a0_scoped_true_lang_css___WEBPACK_IMPORTED_MODULE_0__[key]; }) }(__WEBPACK_IMPORT_KEY__));
 /* harmony default export */ __webpack_exports__["default"] = (_node_modules_style_loader_index_js_node_modules_css_loader_index_js_ref_6_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_6_2_node_modules_vue_loader_lib_index_js_vue_loader_options_AssignEmployeeHierarchy_vue_vue_type_style_index_0_id_1c31e9a0_scoped_true_lang_css___WEBPACK_IMPORTED_MODULE_0___default.a); 

/***/ }),

/***/ "./resources/js/Pages/Employee/AssignEmployeeHierarchy.vue?vue&type=template&id=1c31e9a0&scoped=true&":
/*!************************************************************************************************************!*\
  !*** ./resources/js/Pages/Employee/AssignEmployeeHierarchy.vue?vue&type=template&id=1c31e9a0&scoped=true& ***!
  \************************************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_AssignEmployeeHierarchy_vue_vue_type_template_id_1c31e9a0_scoped_true___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../node_modules/vue-loader/lib??vue-loader-options!./AssignEmployeeHierarchy.vue?vue&type=template&id=1c31e9a0&scoped=true& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/Pages/Employee/AssignEmployeeHierarchy.vue?vue&type=template&id=1c31e9a0&scoped=true&");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "render", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_AssignEmployeeHierarchy_vue_vue_type_template_id_1c31e9a0_scoped_true___WEBPACK_IMPORTED_MODULE_0__["render"]; });

/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_AssignEmployeeHierarchy_vue_vue_type_template_id_1c31e9a0_scoped_true___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"]; });



/***/ })

}]);