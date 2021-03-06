<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<?php header("Content-Type: application/x-javascript"); ?>

<?php if (isset($jsscript) && $jsscript == TRUE) { ?>
<script>
///////////
        var Params_M_Luar = null;

        Ext.namespace('Luar', 'Luar.reader', 'Luar.proxy',
                'Luar.Data', 'Luar.Grid', 'Luar.Window', 'Luar.Form', 'Luar.Action', 'Luar.URL');
        
        Luar.dataStorePengelolaan = new Ext.create('Ext.data.Store', {
            model: MPengelolaan, autoLoad: false, noCache: false,
            proxy: new Ext.data.AjaxProxy({
                url: BASE_URL + 'pengelolaan/getSpecificPengelolaan', actionMethods: {read: 'POST'},
                reader: new Ext.data.JsonReader({
                    root: 'results', totalProperty: 'total', idProperty: 'id'})
            })
        });
        
        Luar.dataStorePemeliharaanPart = new Ext.create('Ext.data.Store', {
            model: MPemeliharaanPart, autoLoad: false, noCache: false,
            proxy: new Ext.data.AjaxProxy({
                url: BASE_URL + 'pemeliharaan_part/getSpecificPemeliharaanPart', actionMethods: {read: 'POST'},
                reader: new Ext.data.JsonReader({
                    root: 'results', totalProperty: 'total', idProperty: 'id'})
            })
        });
        
        Luar.dataStorePendayagunaan = new Ext.create('Ext.data.Store', {
            model: MPendayagunaan, autoLoad: false, noCache: false,
            proxy: new Ext.data.AjaxProxy({
                url: BASE_URL + 'pendayagunaan/getSpecificPendayagunaan', actionMethods: {read: 'POST'},
                reader: new Ext.data.JsonReader({
                    root: 'results', totalProperty: 'total', idProperty: 'id'})
            })
        });
        
        Luar.dataStoreMutasi = new Ext.create('Ext.data.Store', {
            model: MMutasi, autoLoad: false, noCache: false,
            proxy: new Ext.data.AjaxProxy({
                url: BASE_URL + 'mutasi/getSpecificMutasi', actionMethods: {read: 'POST'},
                reader: new Ext.data.JsonReader({
                    root: 'results', totalProperty: 'total', idProperty: 'id'})
            })
        });
        
        Luar.dataStorePemeliharaan = new Ext.create('Ext.data.Store', {
            model: MPemeliharaan, autoLoad: false, noCache: false,
            proxy: new Ext.data.AjaxProxy({
                url: BASE_URL + 'Pemeliharaan/getSpecificPemeliharaan', actionMethods: {read: 'POST'},
                reader: new Ext.data.JsonReader({
                    root: 'results', totalProperty: 'total', idProperty: 'id'})
            })
        });

        Luar.URL = {
            read: BASE_URL + 'asset_Luar/getAllData',
            createUpdate: BASE_URL + 'asset_Luar/modifyLuar',
            remove: BASE_URL + 'asset_Luar/deleteLuar',
            createUpdatePemeliharaan: BASE_URL + 'Pemeliharaan/modifyPemeliharaan',
            removePemeliharaan: BASE_URL + 'Pemeliharaan/deletePemeliharaan',
            createUpdatePendayagunaan: BASE_URL +'pendayagunaan/modifyPendayagunaan',
            removePendayagunaan: BASE_URL + 'pendayagunaan/deletePendayagunaan',
            createUpdatePemeliharaanPart: BASE_URL + 'pemeliharaan_part/modifyPemeliharaanPart',
            removePemeliharaanPart: BASE_URL + 'pemeliharaan_part/deletePemeliharaanPart',
            createUpdatePengelolaan: BASE_URL +'pengelolaan/modifyPengelolaan',
            removePengelolaan: BASE_URL + 'pengelolaan/deletePengelolaan'

        };

        Luar.reader = new Ext.create('Ext.data.JsonReader', {
            id: 'Reader_Luar', root: 'results', totalProperty: 'total', idProperty: 'id'
        });

        Luar.proxy = new Ext.create('Ext.data.AjaxProxy', {
            id: 'Proxy_Luar',
            url: Luar.URL.read, actionMethods: {read: 'POST'}, extraParams: {id_open: '1'},
            reader: Luar.reader,
            timeout:600000,
            afterRequest: function(request, success) {
                Params_M_Luar = request.operation.params;
                
                if(success == true)
                {
                    Params_M_TB = request.operation.params;
                    var responseObject = eval ("(" + request.operation.response.responseText + ")");
                    var total_asset_field = Ext.getCmp('total_grid_Luar');

                    if(responseObject.total_rph_aset !=null && responseObject.total_rph_aset != undefined)
                    {
                        total_asset_field.setValue(responseObject.total_rph_aset.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","));
                    }
                    //USED FOR MAP SEARCH
                    var paramsUnker = request.params.searchUnker;
                    if(paramsUnker != null && paramsUnker != undefined)
                    {
    //                    Luar.Data.clearFilter();
    //                    Luar.Data.filter([{property: 'kd_lokasi', value: paramsUnker, anyMatch:true}]);
                          var gridFilterObject = {type:'string',value:paramsUnker,field:'kd_lokasi'};
                        var gridFilter = JSON.stringify(gridFilterObject);
                        Luar.Data.changeParams({params:{"gridFilter":'['+gridFilter+']'}})
                    }
                }
            }
        });

        Luar.Data = new Ext.create('Ext.data.Store', {
            id: 'Data_Luar', storeId: 'DataLuar', model: 'MLuar', pageSize: 50, noCache: false, autoLoad: true,
            proxy: Luar.proxy, groupField: 'tipe'
        });
        
          Luar.Window.actionSidePanels = function() {
            var actions = {
                details: function() {
                    var _tab = Modal.assetEdit.getComponent('asset-window-tab');
                    var tabpanels = _tab.getComponent('luar-details');
                    if (tabpanels === undefined)
                    {
                        Luar.Action.edit('luar-details');
                    }
                },
                pengadaan: function() {
                    var _tab = Modal.assetEdit.getComponent('asset-window-tab');
                    var tabpanels = _tab.getComponent('luar-pengadaan');
                    if (tabpanels === undefined)
                    {
                        Luar.Action.detail_pengadaan();
                    }
                },
                pemeliharaan: function() {
                    var _tab = Modal.assetEdit.getComponent('asset-window-tab');
                    var tabpanels = _tab.getComponent('luar-pemeliharaan');
                    if (tabpanels === undefined)
                    {
                        Luar.Action.pemeliharaanList();
                    }
                },
                perencanaan: function() {
                    var _tab = Modal.assetEdit.getComponent('asset-window-tab');
                    var tabpanels = _tab.getComponent('luar-perencanaan');
                    if (tabpanels === undefined)
                    {
                        Luar.Action.detail_perencanaan();
                    }
                },
                penghapusan: function() {
                    var _tab = Modal.assetEdit.getComponent('asset-window-tab');
                    var tabpanels = _tab.getComponent('luar-penghapusan');
                    if (tabpanels === undefined)
                    {
                        Luar.Action.penghapusanDetail();
                    }
                },
               pemindahan: function() {
                    var _tab = Modal.assetEdit.getComponent('asset-window-tab');
                    var tabpanels = _tab.getComponent('luar-pemindahan');
                    if (tabpanels === undefined)
                    {
                        Luar.Action.pemindahanList();
                    }
                },
               pendayagunaan: function() {
                    var _tab = Modal.assetEdit.getComponent('asset-window-tab');
                    var tabpanels = _tab.getComponent('luar-pendayagunaan');
                    if (tabpanels === undefined)
                    {
                        Luar.Action.pendayagunaanList();
                    }
                },
                printPDF: function() {
                        Luar.Action.printpdf();
                },
                pengelolaan: function(){
                    var _tab = Modal.assetEdit.getComponent('asset-window-tab');
                    var tabpanels = _tab.getComponent('luar-pengelolaan');
                    if (tabpanels === undefined)
                    {
                        Luar.Action.pengelolaanList();
                    }
               },
            };

            return actions;
        };
        
        Luar.Form.createPengelolaan = function(data, dataForm, edit) {
            var setting = {
                url: Luar.URL.createUpdatePengelolaan,
                data: data,
                isEditing: edit,
                addBtn: {
                    isHidden: true,
                    text: '',
                    fn: function() {
                    }
                },
                selectionAsset: {
                    noAsetHidden: false
                }
            };

            var form = Form.pengelolaanInAsset(setting);

            if (dataForm !== null)
            {
                Ext.Object.each(dataForm,function(key,value,myself){
                    if(dataForm[key] == '0000-00-00')
                    {
                        dataForm[key] = '';
                    }
                });
                
                form.getForm().setValues(dataForm);
            }
            return form;
        };
        
        Luar.Action.pengelolaanEdit = function() {
            var selected = Ext.getCmp('luar_grid_pengelolaan').getSelectionModel().getSelection();
            if (selected.length === 1)
            {
                var dataForm = selected[0].data;
                var form = Luar.Form.createPengelolaan(Luar.dataStorePengelolaan, dataForm, true);
                if (Modal.assetSecondaryWindow.items.length === 0)
                {
                    Modal.assetSecondaryWindow.setTitle('Edit Pengelolaan');
                }
                Modal.assetSecondaryWindow.add(form);
                Modal.assetSecondaryWindow.show();
//                Tab.addToForm(form, 'tanah-edit-pemeliharaan', 'Edit Pemeliharaan');
//                Modal.assetEdit.show();
            }
        };

        Luar.Action.pengelolaanRemove = function() {
            var selected = Ext.getCmp('luar_grid_pengelolaan').getSelectionModel().getSelection();
            if (selected.length > 0)
            {
                var arrayDeleted = [];
                _.each(selected, function(obj) {
                    var data = {
                        id: obj.data.id
                    };
                    arrayDeleted.push(data);
                });
                Modal.deleteAlert(arrayDeleted, Luar.URL.removePengelolaan, Luar.dataStorePengelolaan);
            }
        };


        Luar.Action.pengelolaanAdd = function()
        {
            var selected = Luar.Grid.grid.getSelectionModel().getSelection();
            var data = selected[0].data;
            var dataForm = {
                kd_lokasi: data.kd_lokasi,
                kd_brg: data.kd_brg,
                no_aset: data.no_aset,
                nama:data.nama,
            };

            var form = Luar.Form.createPengelolaan(Luar.dataStorePengelolaan, dataForm, false);
            if (Modal.assetSecondaryWindow.items.length === 0)
            {
                Modal.assetSecondaryWindow.setTitle('Tambah Pengelolaan');
            }
            Modal.assetSecondaryWindow.add(form);
            Modal.assetSecondaryWindow.show();
//            Tab.addToForm(form, 'tanah-add-pendayagunaan', 'Add Pendayagunaan');
        };
        
        Luar.Action.pengelolaanList = function() {
            var selected = Luar.Grid.grid.getSelectionModel().getSelection();
            if (selected.length === 1)
            {
                var data = selected[0].data;
                
                Luar.dataStorePengelolaan.getProxy().extraParams.kd_lokasi = data.kd_lokasi;
                Luar.dataStorePengelolaan.getProxy().extraParams.kd_brg = data.kd_brg;
                Luar.dataStorePengelolaan.getProxy().extraParams.no_aset = data.no_aset;
                Luar.dataStorePengelolaan.load();
                
                var toolbarIDs = {
                    idGrid : "luar_grid_pengelolaan",
                    edit : Luar.Action.pengelolaanEdit,
                    add : Luar.Action.pengelolaanAdd,
                    remove : Luar.Action.pengelolaanRemove,
                };

                var setting = {
                    data: data,
                    dataStore: Luar.dataStorePengelolaan,
                    toolbar: toolbarIDs,
                };
                
                var _luarPendayagunaanGrid = Grid.pengelolaanGrid(setting);
                Tab.addToForm(_luarPendayagunaanGrid, 'luar-pengelolaan', 'Pengelolaan');
            }
        };

        Luar.Form.create = function(data, edit) {
            var form = Form.asset(Luar.URL.createUpdate, Luar.Data, edit);
            form.insert(0, Form.Component.unit(edit,form));
            form.insert(1, Form.Component.kode(edit));
            form.insert(2, Form.Component.klasifikasiAset(edit))
            form.insert(5, Form.Component.luar());
            form.insert(6, Form.Component.fileUpload());
            if (data !== null)
            {
                Ext.Object.each(data,function(key,value,myself){
                            if(data[key] == '0000-00-00')
                            {
                                data[key] = '';
                            }
                        });
                form.getForm().setValues(data);
            }
            else
            {
                var presetData = {};
                if(user_kd_lokasi != null)
                {
                    presetData.kd_lokasi = user_kd_lokasi;
                }
                if(user_kode_unor != null)
                {
                    presetData.kode_unor = user_kode_unor;
                }
                form.getForm().setValues(presetData);
            }

            return form;
        };

        Luar.Form.createPemeliharaan = function(data, dataForm, edit) {
            var setting = {
                url: Luar.URL.createUpdatePemeliharaan,
                data: data,
                isEditing: edit,
                isBangunan: false,
                addBtn: {
                    isHidden: true,
                    text: '',
                    fn: function() {
                    }
                },
                selectionAsset: {
                    noAsetHidden: false
                }
            };

          var setting_grid_pemeliharaan_part = {
                id:'grid_luar_pemeliharaan_part',
                toolbar:{
                    add: Luar.addPemeliharaanPart,
                    edit: Luar.editPemeliharaanPart,
                    remove: Luar.removePemeliharaanPart
                },
                dataStore:Luar.dataStorePemeliharaanPart
            };

            var form = Form.pemeliharaanInAsset(setting,setting_grid_pemeliharaan_part);

            if (dataForm !== null)
            {
                if(dataForm.unit_waktu != 0 && edit == true)
                {
                    dataForm.comboUnitWaktuOrUnitPenggunaan = 1;
                }
                else if(dataForm.unit_pengunaan != 0 && edit == true)
                {
                    dataForm.comboUnitWaktuOrUnitPenggunaan = 2;
                }
                
                Ext.Object.each(dataForm,function(key,value,myself){
                            if(dataForm[key] == '0000-00-00')
                            {
                                dataForm[key] = '';
                            }
                        });
                form.getForm().setValues(dataForm);
            }
            return form;
        };
        
        Luar.addPemeliharaanPart = function(){
            var id_pemeliharaan = Ext.getCmp('hidden_identifier_id_pemeliharaan').value;
            if(id_pemeliharaan != null && id_pemeliharaan != undefined)
            {
                if (Modal.assetSecondaryWindow.items.length === 0)
                {
                    Modal.assetSecondaryWindow.setTitle('Tambah Part');
                }
                    var form = Form.pemeliharaanPart(Luar.URL.createUpdatePemeliharaanPart, Luar.dataStorePemeliharaanPart, false);
                    form.insert(0, Form.Component.dataPemeliharaanPart(id_pemeliharaan));
                    form.insert(1, Form.Component.inventoryPerlengkapan(true));
                    Modal.assetSecondaryWindow.add(form);
                    Modal.assetSecondaryWindow.show();

            }
        };
        
        Luar.editPemeliharaanPart = function(){
            var selected = Ext.getCmp('grid_luar_pemeliharaan_part').getSelectionModel().getSelection();
            if (selected.length === 1)
            {
               
                var data = selected[0].data;
                
                if (Modal.assetSecondaryWindow.items.length === 0)
                {
                    Modal.assetSecondaryWindow.setTitle('Edit Part');
                }
                    var form = Form.pemeliharaanPart(Luar.URL.createUpdatePemeliharaanPart, Luar.dataStorePemeliharaanPart, false);
                    form.insert(0, Form.Component.dataPemeliharaanPart(data.id_pemeliharaan,true));
                    form.insert(1, Form.Component.inventoryPerlengkapan(true));
                    
                    if (data !== null)
                    {
                         Ext.Object.each(data,function(key,value,myself){
                            if(data[key] == '0000-00-00')
                            {
                                data[key] = '';
                            }
                        });
                         form.getForm().setValues(data);
                    }
                    Modal.assetSecondaryWindow.add(form);
                    Modal.assetSecondaryWindow.show();
                
            }
        };
        
        Luar.removePemeliharaanPart = function(){
            var selected = Ext.getCmp('grid_luar_pemeliharaan_part').getSelectionModel().getSelection();
            var arrayDeleted = [];
            _.each(selected, function(obj) {
                var data = {
                    id: obj.data.id,
                    id_penyimpanan: obj.data.id_penyimpanan,
                    qty_pemeliharaan:obj.data.qty_pemeliharaan,
                };
                arrayDeleted.push(data);
            });
            console.log(arrayDeleted);
            Modal.deleteAlert(arrayDeleted, Luar.URL.removePemeliharaanPart, Luar.dataStorePemeliharaanPart);
        };

      
        
        Luar.Form.createPendayagunaan = function(data, dataForm, edit) {
            var setting = {
                url: Luar.URL.createUpdatePendayagunaan,
                data: data,
                isEditing: edit,
                addBtn: {
                    isHidden: true,
                    text: '',
                    fn: function() {
                    }
                },
                selectionAsset: {
                    noAsetHidden: false
                }
            };

            var form = Form.pendayagunaanInAsset(setting);

            if (dataForm !== null)
            {
                Ext.Object.each(dataForm,function(key,value,myself){
                            if(dataForm[key] == '0000-00-00')
                            {
                                dataForm[key] = '';
                            }
                        });
                form.getForm().setValues(dataForm);
            }
            return form;
        };
        
        Luar.Action.pendayagunaanEdit = function() {
            var selected = Ext.getCmp('luar_grid_pendayagunaan').getSelectionModel().getSelection();
            if (selected.length === 1)
            {
                var dataForm = selected[0].data;
                var form = Luar.Form.createPendayagunaan(Luar.dataStorePendayagunaan, dataForm, true);
                if (Modal.assetSecondaryWindow.items.length === 0)
                {
                    Modal.assetSecondaryWindow.setTitle('Edit Pendayagunaan');
                }
                Modal.assetSecondaryWindow.add(form);
                Modal.assetSecondaryWindow.show();
//                Tab.addToForm(form, 'luar-edit-pemeliharaan', 'Edit Pemeliharaan');
//                Modal.assetEdit.show();
            }
        };

        Luar.Action.pendayagunaanRemove = function() {
            var selected = Ext.getCmp('luar_grid_pendayagunaan').getSelectionModel().getSelection();
            if (selected.length > 0)
            {
                var arrayDeleted = [];
                _.each(selected, function(obj) {
                    var data = {
                        id: obj.data.id
                    };
                    arrayDeleted.push(data);
                });
                console.log(arrayDeleted);
                Modal.deleteAlert(arrayDeleted, Luar.URL.removePendayagunaan, Luar.dataStorePendayagunaan);
            }
        };


        Luar.Action.pendayagunaanAdd = function()
        {
            var selected = Luar.Grid.grid.getSelectionModel().getSelection();
            var data = selected[0].data;
            var dataForm = {
                kd_lokasi: data.kd_lokasi,
                kd_brg: data.kd_brg,
                no_aset: data.no_aset
            };

            var form = Luar.Form.createPendayagunaan(Luar.dataStorePendayagunaan, dataForm, false);
            if (Modal.assetSecondaryWindow.items.length === 0)
            {
                Modal.assetSecondaryWindow.setTitle('Tambah Pendayagunaan');
            }
            Modal.assetSecondaryWindow.add(form);
            Modal.assetSecondaryWindow.show();
//            Tab.addToForm(form, 'luar-add-pendayagunaan', 'Add Pendayagunaan');
        };
        
        Luar.Action.pendayagunaanList = function() {
            var selected = Luar.Grid.grid.getSelectionModel().getSelection();
            if (selected.length === 1)
            {
                var data = selected[0].data;
                
                Luar.dataStorePendayagunaan.getProxy().extraParams.kd_lokasi = data.kd_lokasi;
                Luar.dataStorePendayagunaan.getProxy().extraParams.kd_brg = data.kd_brg;
                Luar.dataStorePendayagunaan.getProxy().extraParams.no_aset = data.no_aset;
                Luar.dataStorePendayagunaan.load();
                
                var toolbarIDs = {
                    idGrid : "luar_grid_pendayagunaan",
                    edit : Luar.Action.pendayagunaanEdit,
                    add : Luar.Action.pendayagunaanAdd,
                    remove : Luar.Action.pendayagunaanRemove,
                };

                var setting = {
                    data: data,
                    dataStore: Luar.dataStorePendayagunaan,
                    toolbar: toolbarIDs,
                };
                
                var _luarPendayagunaanGrid = Grid.pendayagunaanGrid(setting);
                Tab.addToForm(_luarPendayagunaanGrid, 'luar-pendayagunaan', 'Pendayagunaan');
            }
        };
        
        Luar.Action.pemindahanEdit = function () {
            var selected = Ext.getCmp('luar_grid_pemindahan').getSelectionModel().getSelection();
            if (selected.length === 1)
            {
                var data = selected[0].data;
                var setting = {
                    url: '',
                    data: data,
                    isEditing: false,
                    addBtn: {
                        isHidden: true,
                        text: '',
                        fn: function() {
                        }
                    },
                    selectionAsset: {
                        noAsetHidden: false
                    }
                };
                        
                var form = Form.penghapusanDanMutasiInAsset(setting);

                if (data !== null && data !== undefined)
                {
                    Ext.Object.each(data,function(key,value,myself){
                            if(data[key] == '0000-00-00')
                            {
                                data[key] = '';
                            }
                        });
                    form.getForm().setValues(data);
                }

                if (Modal.assetSecondaryWindow.items.length === 0)
                {
                    Modal.assetSecondaryWindow.setTitle('Detail Pemindahan');
                }

                Modal.assetSecondaryWindow.add(form);
                Modal.assetSecondaryWindow.show();
           }
        };
        
        Luar.Action.pemindahanList = function() {
            var selected = Luar.Grid.grid.getSelectionModel().getSelection();
            if (selected.length === 1)
            {
                var data = selected[0].data;
                
                Luar.dataStoreMutasi.getProxy().extraParams.kd_lokasi = data.kd_lokasi;
                Luar.dataStoreMutasi.getProxy().extraParams.kd_brg = data.kd_brg;
                Luar.dataStoreMutasi.getProxy().extraParams.no_aset = data.no_aset;
                Luar.dataStoreMutasi.load();
                
                var toolbarIDs = {
                    idGrid : "luar_grid_pemindahan",
                    edit : Luar.Action.pemindahanEdit,
                    add : false,
                    remove : false,
                };

                var setting = {
                    data: data,
                    dataStore: Luar.dataStoreMutasi,
                    toolbar: toolbarIDs,
                };
                
                var _luarMutasiGrid = Grid.mutasiGrid(setting);
                Tab.addToForm(_luarMutasiGrid, 'luar-pemindahan', 'Pemindahan');
            }
        };
        
         Luar.Action.penghapusanDetail = function() {
            var selected = Luar.Grid.grid.getSelectionModel().getSelection();
            if (selected.length === 1)
            {
                var data = selected[0].data;
                var params = {
                    kd_lokasi: data.kd_lokasi,
                    kd_brg: data.kd_brg,
                    no_aset: data.no_aset
                };
                Ext.getCmp('layout-body').body.mask("Loading...", "x-mask-loading");
                Ext.Ajax.request({
                    url: BASE_URL + 'penghapusan/getSpecificPenghapusan/',
                    params: params,
                    timeout:500000,
                    async:false,
                    success: function(resp)
                    {
                        var jsonData = params;
                        var response = Ext.decode(resp.responseText);

                        if (response.length > 0)
                        {
                            var jsonData = response[0];
                        }

                        console.log(jsonData);

                        var setting = {
                            url: '',
                            data: jsonData,
                            isEditing: false,
                            addBtn: {
                                isHidden: true,
                                text: '',
                                fn: function() {
                                }
                            },
                            selectionAsset: {
                                noAsetHidden: false
                            }
                        };
                        
                        var form = Form.penghapusanDanMutasiInAsset(setting);

                        if (jsonData !== null && jsonData !== undefined)
                        {
                            Ext.Object.each(jsonData,function(key,value,myself){
                            if(jsonData[key] == '0000-00-00')
                            {
                                jsonData[key] = '';
                            }
                        });
                            form.getForm().setValues(jsonData);
                        }
                        Tab.addToForm(form, 'luar-penghapusan', 'Penghapusan');
                        Modal.assetEdit.show();
                        
                    },
                    callback: function()
                    {
                        Ext.getCmp('layout-body').body.unmask();
                    },
                });
            }
        };

        Luar.Action.detail_perencanaan = function() {
            var selected = Luar.Grid.grid.getSelectionModel().getSelection();
            if (selected.length === 1)
            {
                var data = selected[0].data;
                Ext.Ajax.request({
                    url: BASE_URL + 'perencanaan/getByID/',
                    params: {
                        id_perencanaan: 1
                    },
                    success: function(resp)
                    {
                        var form = Form.pengadaan(BASE_URL + 'Perencanaan/modifyPerencanaan', resp.responseText);
                        Tab.addToForm(form, 'luar-perencanaan', 'Simak Perencanaan');
                        Modal.assetEdit.show();
                    }
                });
            }
        };


        Luar.Action.detail_pengadaan = function() {

            var selected = Luar.Grid.grid.getSelectionModel().getSelection();
            if (selected.length === 1)
            {
                var data = selected[0].data;
                var params = {
                    kd_lokasi: data.kd_lokasi,
                    kd_unor: data.kd_unor,
                    kd_brg: data.kd_brg,
                    no_aset: data.no_aset
                };

                Ext.Ajax.request({
                    url: BASE_URL + 'pengadaan/getByKode/',
                    params: params,
                    success: function(resp)
                    {
                        var jsonData = params;
                        var response = Ext.decode(resp.responseText);

                        if (response.length > 0)
                        {
                            var jsonData = response[0];
                        }

                        console.log(jsonData);

                        var setting = {
                            url: BASE_URL + 'Pengadaan/modifyPengadaan',
                            data: jsonData,
                            isEditing: false,
                            addBtn: {
                                isHidden: true,
                                text: '',
                                fn: function() {
                                }
                            },
                            selectionAsset: {
                                noAsetHidden: false
                            }
                        };
                        var form = Form.pengadaanInAsset(setting);

                        if (jsonData !== null && jsonData !== undefined)
                        {
                            Ext.Object.each(jsonData,function(key,value,myself){
                            if(jsonData[key] == '0000-00-00')
                            {
                                jsonData[key] = '';
                            }
                        });
                            form.getForm().setValues(jsonData);
                        }
                        Tab.addToForm(form, 'luar-pengadaan', 'Pengadaan');
                        Modal.assetEdit.show();
                    }
                });
            }
        };

        Luar.Action.pemeliharaanEdit = function() {
            var selected = Ext.getCmp('luar_grid_pemeliharaan').getSelectionModel().getSelection();
            if (selected.length === 1)
            {
                var dataForm = selected[0].data;
                var form = Luar.Form.createPemeliharaan(Luar.dataStorePemeliharaan, dataForm, true);
//                Tab.addToForm(form, 'luar-edit-pemeliharaan', 'Edit Pemeliharaan');
//                Modal.assetEdit.show();
                if (Modal.assetSecondaryWindow.items.length === 0)
                {
                    Modal.assetSecondaryWindow.setTitle('Edit Pemeliharaan');
                }
                Modal.assetSecondaryWindow.add(form);
                Modal.assetSecondaryWindow.show();
                Luar.dataStorePemeliharaanPart.changeParams({params:{id_pemeliharaan:dataForm.id}});
            }
        };

        Luar.Action.pemeliharaanRemove = function() {
            var selected = Ext.getCmp('luar_grid_pemeliharaan').getSelectionModel().getSelection();
            if (selected.length > 0)
            {
                var arrayDeleted = [];
                _.each(selected, function(obj) {
                    var data = {
                        id: obj.data.id
                    };
                    arrayDeleted.push(data);
                });
                console.log(arrayDeleted);
                Modal.deleteAlert(arrayDeleted, Luar.URL.removePemeliharaan, Luar.dataStorePemeliharaan);
            }
        };


        Luar.Action.pemeliharaanAdd = function()
        {
            var selected = Luar.Grid.grid.getSelectionModel().getSelection();
            var data = selected[0].data;
            var dataForm = {
                kd_lokasi: data.kd_lokasi,
                kd_brg: data.kd_brg,
                no_aset: data.no_aset
            };

            var form = Luar.Form.createPemeliharaan(Luar.dataStorePemeliharaan, dataForm, false);
//            Tab.addToForm(form, 'luar-add-pemeliharaan', 'Add Pemeliharaan');
            if (Modal.assetSecondaryWindow.items.length === 0)
            {
                Modal.assetSecondaryWindow.setTitle('Edit Pemeliharaan');
            }
            Modal.assetSecondaryWindow.add(form);
            Modal.assetSecondaryWindow.show();
        };

        Luar.Action.pemeliharaanList = function() {
            var selected = Luar.Grid.grid.getSelectionModel().getSelection();
            if (selected.length === 1)
            {
                var data = selected[0].data;
                
                Luar.dataStorePemeliharaan.getProxy().extraParams.kd_lokasi = data.kd_lokasi;
                Luar.dataStorePemeliharaan.getProxy().extraParams.kd_brg = data.kd_brg;
                Luar.dataStorePemeliharaan.getProxy().extraParams.no_aset = data.no_aset;
                Luar.dataStorePemeliharaan.load();
                
                var toolbarIDs = {
                    idGrid : "luar_grid_pemeliharaan",
                    add : Luar.Action.pemeliharaanAdd,
                    remove : Luar.Action.pemeliharaanRemove,
                    edit : Luar.Action.pemeliharaanEdit
                };

                var setting = {
                    data: data,
                    dataStore: Luar.dataStorePemeliharaan,
                    toolbar: toolbarIDs,
                    isBangunan: false
                };
                
                var _luarPemeliharaanGrid = Grid.pemeliharaanGrid(setting);
                Tab.addToForm(_luarPemeliharaanGrid, 'luar-pemeliharaan', 'Pemeliharaan');
            }
        };

        Luar.Action.add = function() {
            var _form = Luar.Form.create(null, false);
            Modal.assetCreate.setTitle('Create Luar');
            Modal.assetCreate.add(_form);
            Modal.assetCreate.show();
        };

        Luar.Action.edit = function() {
            var selected = Luar.Grid.grid.getSelectionModel().getSelection();
            if (selected.length === 1)
            {
                var data = selected[0].data;
                delete data.nama_unker;
                delete data.nama_unor;

                if (Modal.assetEdit.items.length === 0)
                {
                    Modal.assetEdit.setTitle('Edit Luar');
                    Modal.assetEdit.add(Region.createSidePanel(Luar.Window.actionSidePanels()));
                    Modal.assetEdit.add(Tab.create());
                }
                var _form = Luar.Form.create(data, true);
                Tab.addToForm(_form, 'luar-details', 'Simak Details');
                Modal.assetEdit.show();
            }
        };

        Luar.Action.remove = function() {
            var selected = Luar.Grid.grid.getSelectionModel().getSelection();
            if (selected.length > 0)
            {
                var arrayDeleted = [];
                _.each(selected, function(obj) {
                    var data = {
                        kd_lokasi: obj.data.kd_lokasi,
                        kd_brg: obj.data.kd_brg,
                        no_aset: obj.data.no_aset,
                        id: obj.data.id
                    };
                    arrayDeleted.push(data);
                });
                console.log(arrayDeleted);
                Modal.deleteAlert(arrayDeleted, Luar.URL.remove, Luar.Data);
            }
        };

        Luar.Action.print = function() {
            var selected = Luar.Grid.grid.getSelectionModel().getSelection();
            var selectedData = "";
            if (selected.length > 0)
            {
                for (var i = 0; i < selected.length; i++)
                {
                    selectedData += selected[i].data.kd_brg + "||" + selected[i].data.no_aset + "||" + selected[i].data.kd_lokasi + ",";
                }
            }
            var gridHeader = Luar.Grid.grid.getView().getHeaderCt().getVisibleGridColumns();
            var gridHeaderList = "";
            //index starts at 2 to exclude the No. column
            for (var i = 2; i < gridHeader.length; i++)
            {
                if (gridHeader[i].dataIndex === undefined || gridHeader[i].dataIndex === "") //filter the action columns in grid
                {
                    //do nothing
                }
                else
                {
                    gridHeaderList += gridHeader[i].text + "&&" + gridHeader[i].dataIndex + "^^";
                }
            }
            var serverSideModelName = "Asset_Luar_Model";
            var title = "Luar";
            var primaryKeys = "kd_lokasi,kd_brg,no_aset";

            var my_form = document.createElement('FORM');
            my_form.name = 'myForm';
            my_form.method = 'POST';
            my_form.action = BASE_URL + 'excel_management/exportToExcel/';

            var my_tb = document.createElement('INPUT');
            my_tb.type = 'HIDDEN';
            my_tb.name = 'serverSideModelName';
            my_tb.value = serverSideModelName;
            my_form.appendChild(my_tb);

            var my_tb = document.createElement('INPUT');
            my_tb.type = 'HIDDEN';
            my_tb.name = 'title';
            my_tb.value = title;
            my_form.appendChild(my_tb);
            document.body.appendChild(my_form);

            my_tb = document.createElement('INPUT');
            my_tb.type = 'HIDDEN';
            my_tb.name = 'primaryKeys';
            my_tb.value = primaryKeys;
            my_form.appendChild(my_tb);
            document.body.appendChild(my_form);

            my_tb = document.createElement('INPUT');
            my_tb.type = 'HIDDEN';
            my_tb.name = 'gridHeaderList';
            my_tb.value = gridHeaderList;
            my_form.appendChild(my_tb);
            document.body.appendChild(my_form);

            my_tb = document.createElement('INPUT');
            my_tb.type = 'HIDDEN';
            my_tb.name = 'selectedData';
            my_tb.value = selectedData;
            my_form.appendChild(my_tb);
            document.body.appendChild(my_form);

            my_form.submit();
        };

		Luar.Action.printpdf = function() {
            var selected = Luar.Grid.grid.getSelectionModel().getSelection();
            var selectedData = "";
            if (selected.length > 0)
            {
                for (var i = 0; i < selected.length; i++)
                {
                    selectedData += selected[i].data.kd_lokasi + "||" + selected[i].data.kd_brg + "||" + selected[i].data.no_aset;  
                }
            }
            var arrayPrintpdf = [];
            var data = selected[0].data;
            _.each(selected, function(obj) {
                var data = {
                    kd_lokasi: obj.data.kd_lokasi,
                    kd_brg: obj.data.kd_brg,
                    no_aset: obj.data.no_aset
                };
                arrayPrintpdf.push(data);
            });
            Modal.printDocPdf(Ext.encode(arrayPrintpdf), BASE_URL + 'asset_luar/cetak/' + selectedData, 'Cetak Pengelolaan Asset Luar');
            
        };
		
        var setting = {
            grid: {
                id: 'grid_Luar',
                title: 'DAFTAR ASSET LUAR',
                column: [
                    {header: 'No', xtype: 'rownumberer', width: 35, resizable: true, style: 'padding-top: .5px;'},
                    {header: 'Klasifikasi Aset', dataIndex: 'nama_klasifikasi_aset', width: 150, hidden: false, groupable: false, filter: {type: 'string'}},
                    {header: 'Kode Klasifikasi Aset Level 1', dataIndex: 'kd_lvl1', width: 150, hidden: true, groupable: false, filter: {type: 'string'}},
                    {header: 'Kode Klasifikasi Aset Level 2', dataIndex: 'kd_lvl2', width: 150, hidden: true, groupable: false, filter: {type: 'string'}},
                    {header: 'Kode Klasifikasi Aset Level 3', dataIndex: 'kd_lvl3', width: 150, hidden: true, groupable: false, filter: {type: 'string'}},
                    {header: 'Kode Klasifikasi Aset', dataIndex: 'kd_klasifikasi_aset', width: 150, hidden: true, groupable: false, filter: {type: 'string'}},
                    {header: 'Kode Lokasi', dataIndex: 'kd_lokasi', width: 150, hidden: true, groupable: false, filter: {type: 'string'}},
                    {header: 'Nama Barang', dataIndex: 'ur_sskel', width: 150, hidden: false, groupable: false, filter: {type: 'string'}},
                    {header: 'Kode Barang', dataIndex: 'kd_brg', width: 90, groupable: false, filter: {type: 'string'}},
                    {header: 'No Asset', dataIndex: 'no_aset', width: 60, groupable: false, filter: {type: 'numeric'}},
                    {header: 'Unit Kerja', dataIndex: 'nama_unker', width: 150, groupable: true, filter: {type: 'string'}},
                    {header: 'Unit Organisasi', dataIndex: 'nama_unor', width: 150, groupable: true, filter: {type: 'string'}},
                    {header: 'Lokasi Fisik', dataIndex: 'lok_fisik', width: 150, groupable: true, filter: {type: 'string'}},
                    {header: 'Image Url', dataIndex: 'image_url', width: 50, hidden: true, groupable: false, filter: {type: 'string'}},
                    {header: 'Document Url', dataIndex: 'document_url', width: 50, hidden: true, groupable: false, filter: {type: 'string'}},
                    {header: 'Rph Asset', dataIndex: 'rph_aset', width: 120, hidden: false, filter: {type: 'numeric'}},
                ]
            },
            search: {
                id: 'search_Luar'
            },
            toolbar: {
                id: 'toolbar_luar',
                prefix:'asset_luar', //semar
                add: {
                    id: 'button_add_Luar',
                    action: Luar.Action.add
                },
                edit: {
                    id: 'button_edit_Luar',
                    action: Luar.Action.edit
                },
                remove: {
                    id: 'button_remove_Luar',
                    action: Luar.Action.remove
                },
                print: {
                    id: 'button_pring_Luar',
                    action: Luar.Action.print
                }
            }
        };

        Luar.Grid.grid = Grid.inventarisGrid(setting, Luar.Data);

        var new_tabpanel_Asset = {
            id: 'luar_panel', title: 'Luar', iconCls: 'icon-luar_Luar', closable: true, border: false,layout:'border',
            items: [Region.filterPanelAset(Luar.Data,'luar'),Luar.Grid.grid]
        };

<?php

} else {
    echo "var new_tabpanel_MD = 'GAGAL';";
}
?>