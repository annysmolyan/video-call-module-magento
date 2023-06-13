define([
    'jquery',
    'uiComponent',
    'ko',
    'webrtcAdapter',
    'mage/mage',
    ], function ($, Component, ko) {
        'use strict';

        return Component.extend({

            defaults: {
                template: 'BelSmol_VideoCall/userMedia'
            },

            _ERROR_NON_SATISFIED_CONSTRAINT: "ConstraintNotSatisfiedError",
            _ERROR_PERMISSION_DENIED: "PermissionDeniedError",

            _ERROR_PERMISSION_DENIED_MSG: "Permissions have not been granted to use your camera and microphone, " +
                "you need to allow the page access to your devices in order for the demo to work.",
            _ERROR_JOIN_CALL_MSG: "An error occurred while connecting. Please refresh the page and try again. " +
                "If the error reappears, please contact us.",

            _videoSelector: 'video#localVideo',
            _errorMessageBlockSelector: '#videoErrorMsg',

            _isCheckButtonVisible: ko.observable(true),
            _isJoinBtnVisible: ko.observable(false),
            _errorMsg: ko.observable(''),

            // Put variables in global scope to make them available to the browser console.
            constraints: window.constraints = {
                audio: true,
                video: true
            },

            /**
             * Init component
             */
            initialize: function () {
                this._super();
            },

            /**
             * Check if check media button visible or not
             * @returns {*}
             */
            isCheckBtnVisible: function () {
                return this._isCheckButtonVisible;
            },

            /**
             * Check if join call button available or not
             * @returns {*}
             */
            isJoinBtnVisible: function () {
                return this._isJoinBtnVisible;
            },

            /**
             * Check user media data
             * @returns {Promise<void>}
             */
            checkMedia: async function () {
                try {
                    this._isCheckButtonVisible(false); // hide check btn
                    const stream = await navigator.mediaDevices.getUserMedia(constraints);
                    this.handleSuccess(stream);
                } catch (e) {
                    this.handleError(e);
                }
            },

            /**
             * Display video and turn on audio
             * @param stream
             */
            handleSuccess: function(stream) {
                const video = document.querySelector(this._videoSelector);
                window.stream = stream; // make variable available to browser console
                video.srcObject = stream;
                this._isJoinBtnVisible(true);
            },

            /**
             * Resolve error message and display it
             * @param error
             */
            handleError: function (error) {
                switch(error.name) {
                    case this._ERROR_NON_SATISFIED_CONSTRAINT:
                        let v = constraints.video;
                        this._errorMsg(`The resolution ${v.width.exact}x${v.height.exact} px is not supported by your device.`);
                        break;
                    case this._ERROR_PERMISSION_DENIED:
                        this._errorMsg(this._ERROR_PERMISSION_DENIED_MSG);
                        break;
                    default:
                        this._errorMsg(`getUserMedia error: ${error.name} . Can not proceed the call`);
                        console.error(error);
                        break;
                }
            },

            /**
             * Create
             */
            initCall: function () {
                try {
                    $.ajax({
                        type: "POST",
                        url: '/videocall/video/initroom',
                        data: {
                            "customer_id": this.customerId
                        },
                        success: function (response) {
                            window.location.href = "/videocall/video/call?room=" + response.room;
                        }.bind(this),
                        error: this._onFailInitCall.bind(this)
                    })
                } catch (Error) {
                    this._onFailInitCall.bind(this);
                }
            },

            /**
             * Will be called if can't join a call
             * @private
             */
            _onFailInitCall: function () {
                this._isJoinBtnVisible(false);
                this._errorMsg(this._ERROR_JOIN_CALL_MSG);
            }
        });
    }
);
