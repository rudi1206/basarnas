<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<?php header("Content-Type: application/x-javascript"); ?>

<?php if (isset($jsscript) && $jsscript == TRUE) { ?>
<script>
//////////////////
        var Params_M_Perlengkapan = null;

        Ext.namespace('Perlengkapan', 'Perlengkapan.reader', 'Perlengkapan.proxy', 'Perlengkapan.Data', 'Perlengkapan.Grid', 'Perlengkapan.Window', 'Perlengkapan.Form', 'Perlengkapan.Action',
                'Perlengkapan.URL');

        Perlengkapan.dataStorePemeliharaan = new Ext.create('Ext.data.Store', {
            model: 'MPemeliharaanPerlengkapan', autoLoad: false, noCache: false,
            proxy: new Ext.data.AjaxProxy({
                url: BASE_URL + 'Pemeliharaan_Perlengkapan/getSpecificPemeliharaanPerlengkapan', actionMethods: {read: 'POST'}
            })
        });

        Perlengkapan.URL = {
            read: BASE_URL + 'asset_perlengkapan/getAllData',
            createUpdate: BASE_URL + 'asset_perlengkapan/modifyPerlengkapan',
            remove: BASE_URL + 'asset_perlengkapan/deletePerlengkapan',
            createUpdatePemeliharaan: BASE_URL + 'Pemeliharaan_Perlengkapan/modifyPemeliharaanPerlengkapan',
            removePemeliharaan: BASE_URL + 'Pemeliharaan_Perlengkapan/deletePemeliharaanPerlengkapan'

        };

        Perlengkapan.reader = new Ext.create('Ext.data.JsonReader', {
            id: 'Reader_Perlengkapan', root: 'results', totalProperty: 'total', idProperty: 'id'
        });

        Perlengkapan.proxy = new Ext.create('Ext.data.AjaxProxy', {
            id: 'Proxy_Perlengkapan',
            url: Perlengkapan.URL.read, actionMethods: {read: 'POST'}, extraParams: {id_open: '1'},
            reader: Perlengkapan.reader,
            afterRequest: function(request, success) {
                Params_M_Perlengkapan = request.operation.params;
                
                //USED FOR MAP SEARCH
                var paramsUnker = request.params.searchUnker;
                if(paramsUnker != null ||paramsUnker != undefined)
                {
                    Perlengkapan.Data.clearFilter();
                    Perlengkapan.Data.filter([{property: 'nama_unker', value: paramsUnker, anyMatch:true}]);
                }
            }
        });

        Perlengkapan.Data = new Ext.create('Ext.data.Store', {
            id: 'Data_Perlengkapan', storeId: 'DataPerlengkapan', model: 'MPerlengkapan', pageSize: 20, noCache: false, autoLoad: true,
            proxy: Perlengkapan.proxy, groupField: 'tipe'
        });

        Perlengkapan.Form.create = function(data, edit) {
            var form = Form.asset(Perlengkapan.URL.createUpdate, Perlengkapan.Data, edit);
            
            form.insert(0, Form.Component.unit(edit));
//            form.insert(3, Form.Component.address());
//            form.insert(4, Form.Component.perlengkapan());
//            form.insert(5, Form.Component.tambahanPerlengkapanTanah());
            form.insert(1, Form.Component.klasifikasiAset(edit))
            form.insert(2, Form.Component.perlengkapan(edit));
            form.insert(3, Form.Component.fileUpload(edit));
            if (data !== null)
            {
                form.getForm().setValues(data);
            }

            return form;
        };

        Perlengkapan.Form.createPemeliharaan = function(dataGrid,dataForm,edit) {
            var setting = {
                url: Perlengkapan.URL.createUpdatePemeliharaan,
                data: dataGrid,
                isEditing: edit,
                isPerlengkapan: true,
                addBtn: {
                    isHidden: true,
                    text: '',
                    fn: null
                },
                selectionAsset: {
                    noAsetHidden: false
                }
            };

            var form = Form.pemeliharaanInAsset(setting);

            if (dataForm !== null)
            {
                form.getForm().setValues(dataForm);
            }
            return form;
        };

        Perlengkapan.Window.actionSidePanels = function() {
            var actions = {
                details: function() {
                    var _tab = Modal.assetEdit.getComponent('asset-window-tab');
                    var tabpanels = _tab.getComponent('perlengkapan-details');
                    if (tabpanels === undefined)
                    {
                        Perlengkapan.Action.edit();
                    }
                },
                pengadaan: function() {
                    var _tab = Modal.assetEdit.getComponent('asset-window-tab');
                    var tabpanels = _tab.getComponent('perlengkapan-pengadaan');
                    if (tabpanels === undefined)
                    {
                        Perlengkapan.Action.pengadaanEdit();
                    }
                },
                pemeliharaan: function() {
                    var _tab = Modal.assetEdit.getComponent('asset-window-tab');
                    var tabpanels = _tab.getComponent('perlengkapan-pemeliharaan');
                    if (tabpanels === undefined)
                    {
                        Perlengkapan.Action.pemeliharaanList();
                    }
                }
            };

            return actions;
        };

        Perlengkapan.Action.pengadaanEdit = function() {
            var selected = Perlengkapan.Grid.grid.getSelectionModel().getSelection();
            if (selected.length === 1)
            {
                var data = selected[0].data;
                var params = {
                                kd_lokasi : data.kd_lokasi,
                                kd_unor : data.kd_unor,
                                kd_brg : data.kd_brg,
                                no_aset : data.no_aset
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

                        var setting = {
                            url: BASE_URL + 'Pengadaan/modifyPengadaan',
                            data: null,
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
                        if (jsonData !== null || jsonData !== undefined)
                        {
                            form.getForm().setValues(jsonData);
                        }
                        Tab.addToForm(form, 'perlengkapan-pengadaan', 'Pengadaan');
                        Modal.assetEdit.show();
                    }
                });
            }
        };

        Perlengkapan.Action.pemeliharaanEdit = function() {
            var selected = Ext.getCmp('perlengkapan_grid_pemeliharaan').getSelectionModel().getSelection();
            if (selected.length === 1)
            {
                var dataForm = selected[0].data;
                var form = Perlengkapan.Form.createPemeliharaan(Perlengkapan.dataStorePemeliharaan, dataForm, true)
                Tab.addToForm(form, 'perlengkapan-edit-pemeliharaan', 'Edit Pemeliharaan');
            }
        };

        Perlengkapan.Action.pemeliharaanRemove = function() {
            var selected = Ext.getCmp('perlengkapan_grid_pemeliharaan').getSelectionModel().getSelection();
            if (selected.length > 0)
            {
                var arrayDeleted = [];
                _.each(selected, function(obj) {
                    var data = {
                        id: obj.data.id
                    };
                    arrayDeleted.push(data);
                });
                Modal.deleteAlert(arrayDeleted, Perlengkapan.URL.removePemeliharaan, Perlengkapan.dataStorePemeliharaan);
            }
        };


        Perlengkapan.Action.pemeliharaanAdd = function()
        {
            var selected = Perlengkapan.Grid.grid.getSelectionModel().getSelection();
            var data = selected[0].data;
            var dataForm = {
                kd_lokasi: data.kd_lokasi,
                kd_brg: data.kd_brg,
                no_aset: data.no_aset
            };
            var form = Perlengkapan.Form.createPemeliharaan(Perlengkapan.dataStorePemeliharaan, dataForm, false)
            Tab.addToForm(form, 'perlengkapan-add-pemeliharaan', 'Add Pemeliharaan');
        };


        Perlengkapan.Action.pemeliharaanList = function() {
            var selected = Perlengkapan.Grid.grid.getSelectionModel().getSelection();
            if (selected.length === 1)
            {
                var data = selected[0].data;
                Perlengkapan.dataStorePemeliharaan.getProxy().extraParams.kd_lokasi = data.kd_lokasi;
                Perlengkapan.dataStorePemeliharaan.getProxy().extraParams.kd_brg = data.kd_brg;
                Perlengkapan.dataStorePemeliharaan.getProxy().extraParams.no_aset = data.no_aset;
                Perlengkapan.dataStorePemeliharaan.load();
                var toolbarIDs = {};
                toolbarIDs.idGrid = "perlengkapan_grid_pemeliharaan";
                toolbarIDs.add = Perlengkapan.Action.pemeliharaanAdd;
                toolbarIDs.remove = Perlengkapan.Action.pemeliharaanRemove;
                toolbarIDs.edit = Perlengkapan.Action.pemeliharaanEdit;
                var setting = {
                    data: data,
                    dataStore: Perlengkapan.dataStorePemeliharaan,
                    toolbar: toolbarIDs,
                    isPerlengkapan: true
                };
                var _perlengkapanPemeliharaanGrid = Grid.pemeliharaanGrid(setting);
                Tab.addToForm(_perlengkapanPemeliharaanGrid, 'perlengkapan-pemeliharaan', 'Pemeliharaan');
                Modal.assetEdit.show();
            }
        };

        Perlengkapan.Action.add = function() {
            var _form = Perlengkapan.Form.create(null, false);
            Modal.assetCreate.setTitle('Create Perlengkapan');
            Modal.assetCreate.add(_form);
            Modal.assetCreate.show();
        };

        Perlengkapan.Action.edit = function() {
            var selected = Perlengkapan.Grid.grid.getSelectionModel().getSelection();
            if (selected.length === 1)
            {
                var data = selected[0].data;
                delete data.nama_unker;
                delete data.nama_unor;

                if (Modal.assetEdit.items.length <= 1)
                {
                    Modal.assetEdit.setTitle('Edit Perlengkapan');
                    Modal.assetEdit.insert(0, Region.createSidePanel(Perlengkapan.Window.actionSidePanels()));
                    Modal.assetEdit.add(Tab.create());
                }

                var _form = Perlengkapan.Form.create(data, true);
                Tab.addToForm(_form, 'perlengkapan-details', 'Simak Details');
                Modal.assetEdit.show();
            }
        };

        Perlengkapan.Action.remove = function() {
            console.log('remove Perlengkapan');
            var selected = Perlengkapan.Grid.grid.getSelectionModel().getSelection();
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
//            Asset.Window.createDeleteAlert(arrayDeleted, Perlengkapan.URL.remove, Perlengkapan.Data);
            Modal.deleteAlert(arrayDeleted,Perlengkapan.URL.remove,Perlengkapan.Data);
        };

        Perlengkapan.Action.print = function() {
            var selected = Perlengkapan.Grid.grid.getSelectionModel().getSelection();
            var selectedData = "";
            if (selected.length > 0)
            {
                for (var i = 0; i < selected.length; i++)
                {
                    selectedData += selected[i].data.kd_brg + "||" + selected[i].data.no_aset + "||" + selected[i].data.kd_lokasi + ",";
                }
            }
            var gridHeader = Perlengkapan.Grid.grid.getView().getHeaderCt().getVisibleGridColumns();
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
            var serverSideModelName = "Asset_Perlengkapan_Model";
            var title = "Perlengkapan";
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

            my_tb = document.createElement('INPUT');
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

        var setting = {
            grid: {
                id: 'grid_perlengkapan',
                title: 'DAFTAR ASSET PERLENGKAPAN',
                column: [
                    {header: 'No', xtype: 'rownumberer', width: 35, resizable: true, style: 'padding-top: .5px;'},
                    {header: 'Klasifikasi Aset', dataIndex: 'nama_klasifikasi_aset', width: 150, hidden: false, groupable: false, filter: {type: 'string'}},
                    {header: 'Kode Klasifikasi Aset', dataIndex: 'kd_klasifikasi_aset', width: 150, hidden: true, groupable: false, filter: {type: 'string'}},
                    {header: 'Id', dataIndex: 'id', width: 150, hidden: true, groupable: false, filter: {type: 'string'}},
                    {header: 'Serial Number', dataIndex: 'serial_number', width: 90, groupable: false, filter: {type: 'string'}},
                    {header: 'Kode Lokasi', dataIndex: 'kd_lokasi', width: 90, groupable: false, filter: {type: 'string'}},
                    {header: 'Kode Barang', dataIndex: 'kd_brg', width: 90, groupable: false, filter: {type: 'string'}},
                    {header: 'No Aset', dataIndex: 'no_aset', width: 90, groupable: false, filter: {type: 'string'}},
                    {header: 'Part Number', dataIndex: 'part_number', width: 90, groupable: false, filter: {type: 'string'}},
                    {header: 'Id Warehouse', dataIndex: 'warehouse_id', width: 150, groupable: false, filter: {type: 'string'}},
                    {header: 'Id Ruang', dataIndex: 'ruang_id', width: 150, groupable: false, filter: {type: 'string'}},
                    {header: 'Id Rak', dataIndex: 'rak_id', width: 150, groupable: false, filter: {type: 'string'}},
                    {header: 'Kondisi', dataIndex: 'kondisi', width: 90, hidden:true, groupable: false, filter: {type: 'string'}},
                    {header: 'Kuantitas', dataIndex: 'kuantitas', width: 90, hidden:true, groupable: false, filter: {type: 'string'}},
                    {header: 'Dari', dataIndex: 'dari', width: 90, groupable: false, hidden: true, filter: {type: 'string'}},
                    {header: 'Tanggal Perolehan', dataIndex: 'tanggal_perolehan', hidden: true, width: 60, groupable: false, filter: {type: 'numeric'}},
                    {header: 'No Dana', dataIndex: 'no_dana', width: 150,hidden: true, groupable: false, filter: {type: 'string'}},
                    {header: 'Penggunaan Waktu', dataIndex: 'penggunaan_waktu', width: 150, groupable: false, filter: {type: 'string'}},
                    {header: 'Penggunaan Freq', dataIndex: 'penggunaan_freq', width: 70, groupable: false, filter: {type: 'numeric'}},
                    {header: 'Unit Waktu', dataIndex: 'unit_waktu', width: 70, groupable: false, filter: {type: 'numeric'}},
                    {header: 'Unit Freq', dataIndex: 'unit_freq', width: 90, groupable: false, filter: {type: 'string'}},
                    {header: 'Disimpan', dataIndex: 'disimpan', width: 90, groupable: false, hidden: true, filter: {type: 'string'}},
                    {header: 'Dihapus', dataIndex: 'dihapus', width: 90, groupable: false, hidden: true, filter: {type: 'string'}},
                    {header: 'Image Url', dataIndex: 'image_url', width: 50, hidden: true, groupable: false, filter: {type: 'string'}},
                    {header: 'Document Url', dataIndex: 'document_url', width: 50, hidden: true, groupable: false, filter: {type: 'string'}},
                ]
            },
            search: {
                id: 'search_perlengkapan'
            },
            toolbar: {
                id: 'toolbar_perlengkapan',
                add: {
                    id: 'button_add_perlengkapan',
                    action: Perlengkapan.Action.add
                },
                edit: {
                    id: 'button_edit_perlengkapan',
                    action: Perlengkapan.Action.edit
                },
                remove: {
                    id: 'button_remove_perlengkapan',
                    action: Perlengkapan.Action.remove
                },
                print: {
                    id: 'button_pring_perlengkapan',
                    action: Perlengkapan.Action.print
                }
            }
        };

        Perlengkapan.Grid.grid = Grid.inventarisGrid(setting, Perlengkapan.Data);


        var new_tabpanel_Asset = {
            id: 'perlengkapan_panel', title: 'Perlengkapan', iconCls: 'icon-tanah_perlengkapan', closable: true, border: false,layout:'border',
            items: [Region.filterPanelAsetPerlengkapan(Perlengkapan.Data),Perlengkapan.Grid.grid]
        };

    <?php } else {
        echo "var new_tabpanel_MD = 'GAGAL';";
    } ?>