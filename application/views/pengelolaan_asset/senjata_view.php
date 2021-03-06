<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<?php header("Content-Type: application/x-javascript"); ?>

<?php if (isset($jsscript) && $jsscript == TRUE) { ?>
<script>
///////////
        var Params_M_Senjata = null;

        Ext.namespace('Senjata', 'Senjata.reader', 'Senjata.proxy', 'Senjata.Data', 'Senjata.Grid', 'Senjata.Window', 'Senjata.Form', 'Senjata.Action', 'Senjata.URL');

        Senjata.dataStorePemeliharaan = new Ext.create('Ext.data.Store', {
            model: MPemeliharaan, autoLoad: false, noCache: false,
            proxy: new Ext.data.AjaxProxy({
                url: BASE_URL + 'Pemeliharaan/getSpecificPemeliharaan', actionMethods: {read: 'POST'},
                reader: new Ext.data.JsonReader({
                    root: 'results', totalProperty: 'total', idProperty: 'id'})
            })
        });

        Senjata.URL = {
            read: BASE_URL + 'asset_Senjata/getAllData',
            createUpdate: BASE_URL + 'asset_Senjata/modifySenjata',
            remove: BASE_URL + 'asset_Senjata/deleteSenjata',
            createUpdatePemeliharaan: BASE_URL + 'Pemeliharaan/modifyPemeliharaan',
            removePemeliharaan: BASE_URL + 'Pemeliharaan/deletePemeliharaan'
        };

        Senjata.reader = new Ext.create('Ext.data.JsonReader', {
            id: 'Reader_Senjata', root: 'results', totalProperty: 'total', idProperty: 'id'
        });

        Senjata.proxy = new Ext.create('Ext.data.AjaxProxy', {
            id: 'Proxy_Senjata',
            url: Senjata.URL.read, actionMethods: {read: 'POST'}, extraParams: {id_open: '1'},
            reader: Senjata.reader,
            afterRequest: function(request, success) {
                Params_M_Senjata = request.operation.params;
                
                //USED FOR MAP SEARCH
                var paramsUnker = request.params.searchUnker;
                if(paramsUnker != null ||paramsUnker != undefined)
                {
                    Senjata.Data.clearFilter();
                    Senjata.Data.filter([{property: 'nama_unker', value: paramsUnker, anyMatch:true}]);
                }
            }
        });

        Senjata.Data = new Ext.create('Ext.data.Store', {
            id: 'Data_Senjata', storeId: 'DataSenjata', model: 'MSenjata', pageSize: 20, noCache: false, autoLoad: true,
            proxy: Senjata.proxy, groupField: 'tipe'
        });

        Senjata.Form.create = function(data, edit) {
            var form = Form.asset(Senjata.URL.createUpdate, Senjata.Data, edit);
            form.insert(0, Form.Component.unit(edit,form));
            form.insert(1, Form.Component.kode(edit));
            form.insert(2, Form.Component.klasifikasiAset(edit))
            form.insert(3, Form.Component.basicAsset(edit));
            form.insert(4, Form.Component.mechanical());
            form.insert(5, Form.Component.senjata());
            form.insert(6, Form.Component.fileUpload());
            if (data !== null)
            {
                form.getForm().setValues(data);
            }

            return form;
        };

        Senjata.Form.createPemeliharaan = function(data, kode, edit) {
            var setting = {
                url: Senjata.URL.createUpdatePemeliharaan,
                data: data,
                kode: kode,
                isEditing: edit,
                isPemeliharaanAssetInventaris: true,
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

            var form = Form.pemeliharaan(setting);

            if (data !== null)
            {
                form.getForm().setValues(data);
            }
            return form;
        };

        Senjata.Window.actionSidePanels = function() {
            var actions = {
                details: function() {
                    var _tab = Asset.Window.popupEdit.getComponent('asset-window-tab');
                    var tabpanels = _tab.getComponent('senjata-details');
                    if (tabpanels === undefined)
                    {
                        Senjata.Action.edit();
                    }
                },
                pengadaan: function() {
                    var _tab = Modal.assetEdit.getComponent('asset-window-tab');
                    var tabpanels = _tab.getComponent('senjata-pengadaan');
                    if (tabpanels === undefined)
                    {
                        Senjata.Action.detail_pengadaan();
                    }
                },
                pemeliharaan: function() {
                    var _tab = Modal.assetEdit.getComponent('asset-window-tab');
                    var tabpanels = _tab.getComponent('senjata-pemeliharaan');
                    if (tabpanels === undefined)
                    {
                        Senjata.Action.list_pemeliharaan();
                    }
                }
            };

            return actions;
        };

        Senjata.Action.detail_pengadaan = function() {
            var selected = Senjata.Grid.grid.getSelectionModel().getSelection();
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
                        Tab.addToForm(form, 'tanah-pengadaan', 'Pengadaan');
                        Modal.assetEdit.show();
                    }
                });
            }
        };

        Senjata.Action.edit_pemeliharaan = function() {
            var selected = Ext.getCmp('senjata_grid_pemeliharaan').getSelectionModel().getSelection();
            if (selected.length === 1)
            {
                var data = selected[0].data;
                delete data.nama_unker;
                var form = Sentaja.Form.createPemeliharaan(data, null, true);
                Tab.addToForm(form, 'senjata-edit-pemeliharaan', 'Ubah Pemeliharaan');
                Modal.assetEdit.show();
            }
        };

        Senjata.Action.remove_pemeliharaan = function() {
            var selected = Ext.getCmp('senjata_grid_pemeliharaan').getSelectionModel().getSelection();
            if (selected.length > 0)
            {
                var selectedData = selected[0].data;
                var dataStore = Senjata.dataStorePemeliharaan.load({params: {kd_lokasi: selectedData.kd_lokasi, kd_brg: selectedData.kd_brg, no_aset: selectedData.no_aset}});
                var arrayDeleted = [];
                _.each(selected, function(obj) {
                    var data = {
                        id: obj.data.id
                    };
                    arrayDeleted.push(data);
                });
                console.log(arrayDeleted);
                Modal.deleteAlert(arrayDeleted, Senjata.URL.removePemeliharaan, dataStore);
            }
        };


        Senjata.Action.add_pemeliharaan = function()
        {
            var selected = Senjata.Grid.grid.getSelectionModel().getSelection();
            var data = selected[0].data;

            var kode = {
                kd_lokasi: data.kd_lokasi,
                kd_brg: data.kd_brg,
                no_aset: data.no_aset
            };

            var form = Sentaja.Form.createPemeliharaan(null, kode, false);

            Tab.addToForm(form, 'senjata-add-pemeliharaan', 'Tambah Pemeliharaan');
            Modal.assetEdit.show();
        };

        Senjata.Action.list_pemeliharaan = function() {
            var selected = Senjata.Grid.grid.getSelectionModel().getSelection();
            if (selected.length === 1)
            {
                var data = selected[0].data;
                var dataStore = Senjata.dataStorePemeliharaan.load({params: {kd_lokasi: data.kd_lokasi, kd_brg: data.kd_brg, no_aset: data.no_aset}});
                var toolbarIDs = {};
                toolbarIDs.idGrid = "senjata_grid_pemeliharaan";
                toolbarIDs.add = Senjata.Action.add_pemeliharaan;
                toolbarIDs.remove = Senjata.Action.remove_pemeliharaan;
                toolbarIDs.edit = Senjata.Action.edit_pemeliharaan;
                var setting = {
                    data: data,
                    dataStore: dataStore,
                    toolbar: toolbarIDs,
                    isBangunan: false
                };

                var _senjataPemeliharaanGrid = Grid.pemeliharaanGrid(setting);
                Tab.addToForm(_senjataPemeliharaanGrid, 'senjata-pemeliharaan', 'Simak Pemeliharaan');
                Modal.assetEdit.show();
            }
        };

        Senjata.Action.add = function() {
            var _form = Senjata.Form.create(null, false);
            Modal.assetCreate.setTitle('Create Senjata');
            Modal.assetCreate.add(_form);
            Modal.assetCreate.show();
        };

        Senjata.Action.edit = function() {
            var selected = Senjata.Grid.grid.getSelectionModel().getSelection();
            if (selected.length === 1)
            {
                var data = selected[0].data;
                delete data.nama_unker;
                delete data.nama_unor;

                if (Modal.assetEdit.items.length === 0)
                {
                    Modal.assetEdit.setTitle('Edit Senjata');
                    Modal.assetEdit.add(Region.createSidePanel(Senjata.Window.actionSidePanels()));
                    Modal.assetEdit.add(Tab.create());
                }

                var _form = Senjata.Form.create(data, true);
                Tab.addToForm(_form, 'senjata-details', 'Simak Details');
                Modal.assetEdit.show();

            }
        };

        Senjata.Action.remove = function() {
            console.log('remove Senjata');
            var selected = Senjata.Grid.grid.getSelectionModel().getSelection();
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
            Asset.Window.createDeleteAlert(arrayDeleted, Senjata.URL.remove, Senjata.Data);
        };

        Senjata.Action.print = function() {
            var selected = Senjata.Grid.grid.getSelectionModel().getSelection();
            var selectedData = "";
            if (selected.length > 0)
            {
                for (var i = 0; i < selected.length; i++)
                {
                    selectedData += selected[i].data.kd_brg + "||" + selected[i].data.no_aset + "||" + selected[i].data.kd_lokasi + ",";
                }
            }
            var gridHeader = Senjata.Grid.grid.getView().getHeaderCt().getVisibleGridColumns();
            var gridHeaderList = "";
            //index starts at 2 to exclude the No. column
            for (var i = 2; i < gridHeader.length; i++)
            {
                if (gridHeader[i].dataIndex == undefined || gridHeader[i].dataIndex == "") //filter the action columns in grid
                {
                    //do nothing
                }
                else
                {
                    gridHeaderList += gridHeader[i].text + "&&" + gridHeader[i].dataIndex + "^^";
                }
            }
            var serverSideModelName = "Asset_Senjata_Model";
            var title = "Senjata";
            var primaryKeys = "kd_lokasi,kd_brg,no_aset";

            my_form = document.createElement('FORM');
            my_form.name = 'myForm';
            my_form.method = 'POST';
            my_form.action = BASE_URL + 'excel_management/exportToExcel/';

            my_tb = document.createElement('INPUT');
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
                id: 'grid_Senjata',
                title: 'DAFTAR ASSET Senjata',
                column: [
                    {header: 'No', xtype: 'rownumberer', width: 35, resizable: true, style: 'padding-top: .5px;'},
                    {header: 'Klasifikasi Aset', dataIndex: 'nama_klasifikasi_aset', width: 150, hidden: false, groupable: false, filter: {type: 'string'}},
                    {header: 'Kode Klasifikasi Aset', dataIndex: 'kd_klasifikasi_aset', width: 150, hidden: true, groupable: false, filter: {type: 'string'}},
                    {header: 'Kode Lokasi', dataIndex: 'kd_lokasi', width: 150, hidden: true, groupable: false, filter: {type: 'string'}},
                    {header: 'Kode Barang', dataIndex: 'kd_brg', width: 90, groupable: false, filter: {type: 'string'}},
                    {header: 'No Asset', dataIndex: 'no_aset', width: 60, groupable: false, filter: {type: 'numeric'}},
                    {header: 'Unit Kerja', dataIndex: 'nama_unker', width: 150, groupable: false, filter: {type: 'string'}},
                    {header: 'Unit Organisasi', dataIndex: 'nama_unor', width: 150, groupable: false, filter: {type: 'string'}},
                    {header: 'RPH Asset', dataIndex: 'rph_aset', width: 70, groupable: false, filter: {type: 'numeric'}},
                    {header: 'No KIB', dataIndex: 'no_kib', width: 50, groupable: false, filter: {type: 'numeric'}},
                    {header: 'Kuantitas', dataIndex: 'kuantitas', width: 65, groupable: false, filter: {type: 'numeric'}},
                    {header: 'Nama', dataIndex: 'nama', width: 180, groupable: false, filter: {type: 'string'}},
                    {header: 'Merk', dataIndex: 'merk', width: 100, groupable: false, filter: {type: 'string'}},
                    {header: 'Type', dataIndex: 'type', width: 120, groupable: false, filter: {type: 'string'}},
                    {header: 'Kaliber', dataIndex: 'kaliber', width: 90, groupable: false, hidden: true, filter: {type: 'string'}},
                    {header: 'No Pabrik', dataIndex: 'no_pabrik', width: 90, groupable: false, hidden: true, filter: {type: 'string'}},
                    {header: 'Tahun Buat', dataIndex: 'thn_buat', width: 120, hidden: true, filter: {type: 'string'}},
                    {header: 'Tangal Surat', dataIndex: 'tgl_surat', width: 90, hidden: true, filter: {type: 'string'}},
                    {header: 'Lengkap 1', dataIndex: 'lengkap1', width: 90, hidden: true, filter: {type: 'string'}},
                    {header: 'Lengkap 2', dataIndex: 'lengkap2', width: 90, hidden: true, filter: {type: 'string'}},
                    {header: 'Lengkap 3', dataIndex: 'lengkap3', width: 90, hidden: true, filter: {type: 'string'}},
                    {header: 'Jenis TRN', dataIndex: 'jns_trn', width: 90, hidden: true, filter: {type: 'string'}},
                    {header: 'Dari', dataIndex: 'dari', width: 150, hidden: false, filter: {type: 'string'}},
                    {header: 'Dasar Harga', dataIndex: 'dasar_hrg', width: 90, hidden: true, filter: {type: 'numeric'}},
                    {header: 'Sumber', dataIndex: 'sumber', width: 90, hidden: true, filter: {type: 'string'}},
                    {header: 'No Dana', dataIndex: 'no_dana', width: 90, hidden: true, filter: {type: 'string'}},
                    {header: 'Tanggal Dana', dataIndex: 'tgl_dana', width: 90, hidden: true, filter: {type: 'string'}},
                    {header: 'Unit PMK', dataIndex: 'unit_pmk', width: 90, hidden: true, filter: {type: 'string'}},
                    {header: 'Alamat PMK', dataIndex: 'alm_pmk', width: 90, hidden: true, filter: {type: 'string'}},
                    {header: 'Catatan', dataIndex: 'catatan', width: 90, hidden: true, filter: {type: 'string'}},
                    {header: 'Tanggal Buku', dataIndex: 'tgl_buku', width: 90, hidden: true, filter: {type: 'string'}},
                    {header: 'Harga Wajar', dataIndex: 'rph_wajar', width: 90, hidden: true, filter: {type: 'numeric'}},
                    {header: 'Status', dataIndex: 'status', width: 90, hidden: true, filter: {type: 'string'}},
                    {xtype: 'actioncolumn', width: 60, items: [{icon: '../basarnas/assets/images/icons/map1.png', tooltip: 'Map',
                                handler: function(grid, rowindex, colindex, obj) {
                                    var kodeWilayah = Senjata.Data.getAt(rowindex).data.kd_lokasi.substring(5, 9);
                                    console.log(kodeWilayah);
                                    Ext.getCmp('Content_Body_Tabs').setActiveTab('map_asset');
                                    applyItemQuery(kodeWilayah);
                                }}]},
                ]
            },
            search: {
                id: 'search_Senjata'
            },
            toolbar: {
                id: 'toolbar_senjata',
                add: {
                    id: 'button_add_Senjata',
                    action: Senjata.Action.add
                },
                edit: {
                    id: 'button_edit_Senjata',
                    action: Senjata.Action.edit
                },
                remove: {
                    id: 'button_remove_Senjata',
                    action: Senjata.Action.remove
                },
                print: {
                    id: 'button_pring_Senjata',
                    action: Senjata.Action.print
                }
            }
        }

        Senjata.Grid.grid = Grid.inventarisGrid(setting, Senjata.Data)


        var new_tabpanel_Asset = {
            id: 'senjata_panel', title: 'Senjata', iconCls: 'icon-tanah_Senjata', closable: true, border: false,layout:'border',
            items: [Region.filterPanelAset(Senjata.Data),Senjata.Grid.grid]
        }

<?php

} else {
    echo "var new_tabpanel_MD = 'GAGAL';";
}
?>