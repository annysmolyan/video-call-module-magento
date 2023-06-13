define([

], function ( ) {
    'use strict';

    var timer;

    timer = {

        _HOUR_CSS_ID: "hour",
        _MINS_CSS_ID: "mins",
        _SECS_CSS_ID: "secs",

        _hour: '',
        _mins: '',
        _secs: '',
        _overallSecs: 0,
        _timerInterval: null,

        /**
         * Start timer
         */
        start: function () {
            this._hour = document.getElementById(this._HOUR_CSS_ID);
            this._mins = document.getElementById(this._MINS_CSS_ID);
            this._secs = document.getElementById(this._SECS_CSS_ID);

            let S = '00', M = '00', H = '00';

            this._timerInterval = setInterval(function(){
                S = +S +1;

                if( S < 10 ) { //Add 0 if result < 10
                    S = '0' + S;
                }

                if( S == 60 ) {
                    S = '00';

                    M = +M + 1; //if secs = 60 then add a minute

                    if( M < 10 ) {
                        M = '0' + M;
                    }

                    if( M == 60 ) { //add 1hr if 60 min
                        M = '00';
                        H = +H + 1;
                        if( H < 10 ) { H = '0' + H; }
                    }
                }
                this._secs.innerText = S;
                this._mins.innerText = M;
                this._hour.innerText = H;
                this._overallSecs++;

            }.bind(this),1000);
        },

        /**
         * @private
         */
        stop: function () {
            clearInterval(this._timerInterval);
        },

        /**
         * Return common spent time in sec
         * @returns {number}
         */
        getOverallTime: function () {
            return this._overallSecs;
        }

    };

    return timer;
});
