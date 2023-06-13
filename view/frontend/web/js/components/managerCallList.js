define([
        'jquery',
        'uiComponent',
        'ko',
        'mage/translate',
        'mage/mage'
    ], function ($, Component, ko, $t) {
        'use strict';

        return Component.extend({

                defaults: {
                    template: 'BelSmol_VideoCall/managerCallList'
                },

                _timerInterval: null,
                _initialTimeInSec: 120,
                _callList: ko.observable([]),

                /**
                 * Init component
                 */
                initialize: function () {
                    this._super();
                    this._loadCallList();
                },

                /**
                 * Load call list
                 * @private
                 */
                _loadCallList: function () {
                    try {
                        $.ajax({
                            type: "POST",
                            url: '/videocall/manager_ajax/calllist',
                            success: this._initCallList.bind(this),
                            showLoader: true,
                            error: function (response) {
                                console.log("error");
                            }.bind(this),
                        })
                    } catch (Error) {
                        this._onFailInitCall.bind(this);
                    }
                },

                /**
                 * Load call list from backend
                 * @param response
                 * @private
                 */
                _initCallList: function (response) {
                    this._callList(response.callList);
                    clearInterval(this._timerInterval);
                    this.updateCallListOnTimer();
                },

                /**
                 * Update call list on timer
                 * @param duration
                 */
                updateCallListOnTimer: function (duration = this._initialTimeInSec) {
                    let timer = duration,
                        display = $('div#timer'),
                        minutes,
                        seconds;

                    this._timerInterval = setInterval(function () {
                        minutes = parseInt(timer / 60, 10)
                        seconds = parseInt(timer % 60, 10);

                        minutes = minutes < 10 ? "0" + minutes : minutes;
                        seconds = seconds < 10 ? "0" + seconds : seconds;

                        display.text($t("Next update in") + ' ' + minutes + ":" + seconds);

                        if (--timer < 1) {
                            this._loadCallList();
                        }
                    }.bind(this), 1000);
                }
            }
        );}
);
