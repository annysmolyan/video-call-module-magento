define([
    "jquery",
    'BelSmol_VideoCall/js/plugins/datatable/datatables.min'
], function ($, datatables) {
    'use strict';

    function Datatable_Loader(config) {
        this._tableId = config.tableId;
        this._initialize();
    }

    Datatable_Loader.prototype = {

        constructor: Datatable_Loader,

        /**
         * @var string
         */
        _tableId: '',

        /**
         * Initialize method. Prepare data for jquery data table
         *
         * @private
         */
        _initialize: function () {
            $('#' + this._tableId).DataTable({});
        },
    };

    return function (config) {
        return new Datatable_Loader(config);
    };
});
