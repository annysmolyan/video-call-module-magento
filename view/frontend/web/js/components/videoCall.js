define([
        'jquery',
        'uiComponent',
        'ko',
        'BelSmol_VideoCall/js/components/timer',
        'webrtcAdapter',
        'mage/mage'
    ], function ($, Component, ko, timer) {
        'use strict';

        var PeerConnection = window.RTCPeerConnection || window.mozRTCPeerConnection || window.webkitRTCPeerConnection;
        var SessionDescription = window.mozRTCSessionDescription || window.RTCSessionDescription;
        var IceCandidate = window.mozRTCIceCandidate || window.RTCIceCandidate;
        navigator.getUserMedia = navigator.getUserMedia || navigator.mozGetUserMedia || navigator.webkitGetUserMedia;


    return Component.extend({

            defaults: {
                template: 'BelSmol_VideoCall/videoCall'
            },

            _SIGNALING_SERVER_ERROR: "WebSocket server error. Please, try again later",
            _SIGNALING_SERVER_EMPTY_ROOM_ERROR: "Incorrect video session. Please, try again later",
            _SIGNALING_SERVER_MAX_CONNECTION_ERROR: "Maximum connection reached",
            _SIGNALING_SERVER_SUBSCRIBE_REJECTED_ERROR: "Connection rejected",
            _WRONG_ROOM_ERROR: "Wrong video call session",

            _MSG_JOIN_CUSTOMER: "Waiting for customer to join",
            _MSG_JOIN_MANAGER: "Wait, please. The first available manager will join soon",

            _ACTION_SUBSCRIBE: "subscribe",
            _ACTION_CANDIDATE: "candidate",
            _ACTION_ANSWER: "answer",
            _ACTION_OFFER: "offer",
            _ACTION_HANG_UP: "hangUp",
            _ACTION_SHARING: "sharing",
            _ACTION_STOP_SHARING: "stopSharing",

            _ROOM_PARAM_NAME: "room",
            _TOKEN_PARAM_NAME: "token",

            _localVideoSelector: "#localVideo",
            _remoteVideoSelector: "#remoteVideo",
            _sharingBtnSelector: "#sharescreen",

            _room: null,
            _signalingServer: null,
            _peerConnection: null,
            _peerAudio: $("<audio />", {id: "perrAudio"})[0],

            _errorMsg: ko.observable(''),
            _joinMsg: ko.observable(''),
            _showCallBtn: ko.observable(false),

            senders: [],


            displayMediaOptions: { // options for sharing screen
                video: {
                    cursor: "always"
                },
                audio: true,
            },

            // Put variables in global scope to make them available to the browser console.
            constraints: window.constraints = {
                audio: true,
                video: true
            },

            iceConfiguration: {
                iceServers: []
            },

            /**
             * Init component
             */
            initialize: function () {
                this._super();
                this._setRoom();
                this._initJoinMessage();
                this._setIceConfiguration();
                this._initSignalingServerConnection();

                //if browser tab with call was closed
                //send request to server
                $(window).on('unload', function() {
                    this._saveCallHistory();
                }.bind(this));
            },

            /**
             * Set room id from url
             * @private
             */
            _setRoom: function () {
                this._room = this._getUrlParamValue(this._ROOM_PARAM_NAME);
            },

            /**
             * Initial join message initialization
             * @private
             */
            _initJoinMessage: function () {
                if (this._getUrlParamValue(this._TOKEN_PARAM_NAME)) {
                    this._joinMsg(this._MSG_JOIN_CUSTOMER);
                } else {
                    this._joinMsg(this._MSG_JOIN_MANAGER);
                }
            },

            /**
             * Read query param from url and return param value
             * @param param
             * @returns {string}
             * @private
             */
            _getUrlParamValue: function (param) {
                let queryString = window.location.search;
                let urlParams = new URLSearchParams(queryString);
                return urlParams.get(param);
            },

            /**
             * Init ice server configuration
             * Object must be similar to:
             *
             * iceConfiguration: {
             *  iceServers: [
             *      {
             *          urls: 'turn:myserver.com',
             *          credential: 'my_credential',
             *          username: 'my_user'
             *      },
             *      {
             *          urls: 'another url without credentials'
             *      }
             *  ]
             * },
             * @private
             */
            _setIceConfiguration: function () {
                this.iceConfiguration.iceServers = this.iceServers;
            },

            /**
             * Connect to the socket server
             * if room exists
             * @private
             */
            _initSignalingServerConnection: function () {
                if (this._room) {
                    this._establishSignalingServerConnection();
                } else {
                    this._errorMsg(this._SIGNALING_SERVER_EMPTY_ROOM_ERROR);
                }
            },

            /**
             * Establish connection with a signaling server
             * @private
             */
            _establishSignalingServerConnection: function () {
                this._signalingServer = new WebSocket(this.signalingServer);

                this._signalingServer.onerror = function() {
                    this._errorMsg(this._SIGNALING_SERVER_ERROR);
                }.bind(this);

                this._signalingServer.onopen = function() { //subscribe to a room on open connection
                    this._sendSignalingMsg(this._ACTION_SUBSCRIBE)
                }.bind(this);

                this._signalingServer.onmessage = async function(event) {
                    let data = JSON.parse(event.data);

                    if (data.room != this._room){
                        this._errorMsg(this._WRONG_ROOM_ERROR);
                        return;
                    }

                    switch (data.message) {
                        case 'maxConnectionReached':
                            this._errorMsg(this._SIGNALING_SERVER_MAX_CONNECTION_ERROR);
                            break;
                        case 'subscribeRejected':
                            this._errorMsg(this._SIGNALING_SERVER_SUBSCRIBE_REJECTED_ERROR);
                            break;
                        case 'subscribed':
                            await this._initPeerConnection();
                            await this._initMedia();
                            break;
                        case 'offer':
                            await this._peerConnection.setRemoteDescription(new SessionDescription(data.data[0]));
                            console.log('Got remote offer and set remote description');
                            await this._createAnswer();
                            break;
                        case 'answer':
                            await this._peerConnection.setRemoteDescription(new SessionDescription(data.data[0]));
                            console.log('Got answer and set remote description');
                            break;
                        case "candidate":
                            await this._addIceCandidate(data.data[0]);
                            break;
                        case "hangUp":
                            this._terminateConnection();
                            this._redirectAfterEndCall();
                            break;
                        case "sharing":
                            this._hideSharingBtn();
                            console.log('Got remote sharing stream');
                            break;
                        case "stopSharing":
                            this._showSharingBtn();
                            console.log('Stop remote sharing stream');
                            break;
                    }
                }.bind(this);
            },

            /**
             * Send message to signaling server
             * @param action
             * @param data
             * @private
             */
            _sendSignalingMsg: function (action, data = null) {
                this._signalingServer.send(JSON.stringify({
                    action: action,
                    room: this._room,
                    data: data
                }));
            },

            /**
             * Init connection between peers
             * @returns {Promise<void>}
             * @private
             */
            _initPeerConnection: async function () {
                this._peerConnection = await new PeerConnection(this.iceConfiguration);
                this._peerConnection.onaddstream = this._gotRemoteStream.bind(this);
                this._peerConnection.onicecandidate = this._sendIceCandidate.bind(this);
                this._peerConnection.onconnectionstatechange = function(event){
                    if (this._peerConnection.connectionState === 'connecting') {
                        this._joinMsg("Establish connection");
                        console.log(this._peerConnection.connectionState);
                    }

                    if (this._peerConnection.connectionState === 'connected') {
                        this._joinMsg("");
                        this._showCallBtn(true);
                        timer.start();
                        console.log(this._peerConnection.connectionState);
                    }
                }.bind(this);
                console.log('Created local peer connection object localPC');
            },

            /**
             * Init media data
             * @private
             */
            _initMedia: function () {
                navigator.getUserMedia(this.constraints, this._gotStream.bind(this), function(error) {
                        this._errorMsg(error.name + ": " + error.message);
                    }.bind(this)
                );
            },

            /**
             * Show stream and create offer
             * @param stream
             * @private
             */
            _gotStream: function (stream) {
                window.localStream = stream;
                stream.getTracks().forEach(function(track) {
                    this.senders.push(this._peerConnection.addTrack(track, stream))
                }.bind(this));

                document.querySelector(this._localVideoSelector).srcObject = stream;
                console.log('Got local PC stream, set to PeerConnection');
                this._createOffer();
            },

            /**
            * @param event
            * @private
            */
            _gotRemoteStream: function (event) {
                document.querySelector(this._remoteVideoSelector).srcObject = event.stream;

                //create audio element and play sound from peer
                this._peerAudio.srcObject = event.stream;
                this._peerAudio.play();

                console.log('Got remote PC stream');
            },

            /**
             * Create offer for peer
             * @returns {Promise<void>}
             * @private
             */
            _createOffer: async function () {
                let offer = await this._peerConnection.createOffer();
                await this._peerConnection.setLocalDescription(offer);
                this._sendSignalingMsg(this._ACTION_OFFER, offer);
                console.log('Created offer and set local peer description');
            },

            /**
             * Create answer for peer
             * @returns {Promise<void>}
             * @private
             */
            _createAnswer: async function() {
                const answer = await this._peerConnection.createAnswer();
                await this._peerConnection.setLocalDescription(answer);
                this._sendSignalingMsg(this._ACTION_ANSWER, answer);
                console.log('Created answer and set local peer description');
            },

            /**
             * Add ICE candidate to peer connection
             * @param data
             * @returns {Promise<void>}
             * @private
             */
            _addIceCandidate: async function (data) {
                let candidate = new IceCandidate({sdpMLineIndex: data.label, candidate: data.candidate});
                await this._peerConnection.addIceCandidate(candidate);
                console.log('Added ICE candidate');
            },

            /**
             * Send ICE candidate
             * @param event
             * @private
             */
            _sendIceCandidate: function (event) {
                if(event.candidate){
                    this._sendSignalingMsg(
                        this._ACTION_CANDIDATE,
                        {
                            label: event.candidate.sdpMLineIndex,
                            id: event.candidate.sdpMid,
                            candidate: event.candidate.candidate
                        }
                    );
                }
            },

            /**
             * Mute video
             * @private
             */
            _muteVideo: function () {
                $(event.target).parent().toggleClass( "on" );
                window.localStream.getVideoTracks().forEach(track => track.enabled = !track.enabled);
            },

            /**
             * Mute audio
             * @private
             */
            _muteAudio: function () {
                $(event.target).parent().toggleClass( "on" );
                window.localStream.getAudioTracks().forEach(track => track.enabled = !track.enabled);
            },

            /**
             * Share screen audio
             * @private
             */
            _shareScreen: async function () {
                let displayMediaStream = await navigator.mediaDevices.getDisplayMedia(this.displayMediaOptions);
                document.querySelector(this._localVideoSelector).srcObject = displayMediaStream;
                this._sendSignalingMsg(this._ACTION_SHARING, displayMediaStream);
                this._hideSharingBtn();

                displayMediaStream.getVideoTracks()[0].onended = function () {
                    this._showSharingBtn();
                    document.querySelector(this._localVideoSelector).srcObject = window.localStream;
                    this.senders.find(sender => sender.track.kind === 'video').replaceTrack(
                        window.localStream.getTracks().find(track => track.kind === 'video')
                    );
                    this._sendSignalingMsg(this._ACTION_STOP_SHARING);
                }.bind(this);

                this.senders.find(sender => sender.track.kind === 'video').replaceTrack(displayMediaStream.getTracks()[0]);
            },

            /**
             * @private
             */
            _hideSharingBtn: function () {
                $(this._sharingBtnSelector).hide();
            },

            /**
             * @private
             */
            _showSharingBtn: function () {
                $(this._sharingBtnSelector).show();
            },

            /**
             * Hang up
             * @private
             */
            _hangUp: function () {
                timer.stop();
                this._sendSignalingMsg(this._ACTION_HANG_UP);
                this._signalingServer.close();
                this._terminateConnection();
                this._saveCallHistory();
                this._redirectAfterEndCall();
            },

            /**
             * @private
             */
            _saveCallHistory: function () {
                $.ajax({
                    type: "POST",
                    url: '/videocall/video/saveHistory',
                    data: {
                        "room": this._room,
                        "duration": timer.getOverallTime()
                    }
                })
            },

            /**
             * Terminate call connection.
             * Destroy p2p connection
             * Remove stream
             * @private
             */
            _terminateConnection: function () {
                this._peerConnection.close();
                this._peerConnection = null;
                document.querySelector(this._localVideoSelector).srcObject = null;

                if (window.localStream && window.localStream.getTracks().length) {
                    window.localStream.getTracks().forEach(track => track.stop())
                }
            },

            /**
             * When call ended determine redirection path
             * and redirect
             */
            _redirectAfterEndCall: function () {
                let isManager = this._getUrlParamValue(this._TOKEN_PARAM_NAME);

                if (isManager) {
                    $.cookieStorage.set('mage-messages', [{type: "success", text: "The call was ended"}]);
                    window.location.href = "/videocall/manager/dashboard/";
                } else {
                    window.location.href = "/videocall/customer/endedCall";
                }
            }
        }
    );}
);
