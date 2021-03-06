<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<?php header("Content-Type: application/x-javascript"); ?>

<?php if (isset($jsscript) && $jsscript == TRUE) { ?>
    <script>
    ///////////
        Ext.namespace('Grid', 'ToolbarGrid');

        Grid.baseGrid = function(setting, data, feature_list) {

            var grid = new Ext.create('Ext.grid.Panel', {
                id: setting.grid.id,
                store: data,
                title: setting.grid.title,
                frame: true,
                region: 'center',
                border: true,
                loadMask: true,
                autoScroll:true,
                style: 'margin:0 auto;',
                height: '100%',
                selModel: feature_list.selmode,
                columns: setting.grid.column,
                columnLines: true,
                features: feature_list.filter,
                tbar: feature_list.toolbar,
                dockedItems: [{xtype: 'pagingtoolbar', store: data, dock: 'bottom', displayInfo: true}],
                listeners: {
                    itemdblclick: function(dataview, record, item, index, e) {
                        Ext.getCmp(setting.toolbar.edit.id).handler.call(Ext.getCmp(setting.toolbar.edit.id).scope);
                    }
                }
            });

            return grid;
        };

    // use in inventaris asset, 
        Grid.pemeliharaanGrid = function(setting) {
            if (setting.isBangunan)
            {
                var settingGrid = {
                    grid: {
                        id: setting.toolbar.idGrid,
                        title: 'Pemeliharaan',
                        column: [
                            {header: 'No', xtype: 'rownumberer', width: 35, resizable: true, style: 'padding-top: .5px;'},
                            {header: 'Jenis', dataIndex: 'jenis', width: 100, hidden: false, groupable: false, filter: {type: 'string'},
                                renderer: function(value) {
                                    if (value === '1')
                                    {
                                        return "PEMELIHARAAN";
                                    }
                                    else if (value === '2')
                                    {
                                        return "PERAWATAN";
                                    }
                                    else
                                    {
                                        return "";
                                    }
                                }
                            },
                            {header: ' SubJenis', dataIndex: 'subjenis', width: 100, hidden: false, groupable: false, filter: {type: 'string'},
                                renderer: function(value) {
                                    if (value === '1')
                                    {
                                        return "ARSITEKTURAL";
                                    }
                                    else if (value === '2')
                                    {
                                        return "STRUKTURAL";
                                    }
                                    else if (value === '3')
                                    {
                                        return "MEKANIKAL";
                                    }
                                    else if (value === '4')
                                    {
                                        return "ELEKTRIKAL";
                                    }
                                    else if (value === '5')
                                    {
                                        return "TATA RUANG LUAR";
                                    }
                                    else if (value === '6')
                                    {
                                        return "TATA GRAHA (HOUSE KEEPING)";
                                    }
                                    else if (value === '11')
                                    {
                                        return "REHABILITASI";
                                    }
                                    else if (value === '12')
                                    {
                                        return "RENOVASI";
                                    }
                                    else if (value === '13')
                                    {
                                        return "RESTORASI";
                                    }
                                    else if (value === '14')
                                    {
                                        return "PERAWATAN KERUSAKAN";
                                    }
                                    else
                                    {
                                        return "NOT YET IMPLEMENTED";
                                    }
                                }
                            },
                            {header: 'Pelaksana', dataIndex: 'pelaksana_nama', width: 120, hidden: false, groupable: false, filter: {type: 'string'}},
                            {header: 'Pelaksanaan Tgl Start', dataIndex: 'pelaksana_startdate', width: 120, groupable: false, filter: {type: 'string'}},
                            {header: 'Pelaksanaan Tgl End', dataIndex: 'pelaksana_endate', width: 120, groupable: false, filter: {type: 'string'}},
                            {header: 'Deskripsi', dataIndex: 'deskripsi', width: 150, hidden: false, groupable: false, filter: {type: 'string'}},
                            {header: 'Biaya', dataIndex: 'biaya', width: 100, hidden: false, groupable: false, filter: {type: 'string'}}
                        ]
                    },
                    search: {
                        id: 'search_pemeliharaan'
                    },
                    toolbar: {
                        id: 'toolbar_pemeliharaan',
                        add: {
                            id: 'button_add_pemeliharaan',
                            action: setting.toolbar.add
                        },
                        edit: {
                            id: 'button_edit_pemeliharaan',
                            action: setting.toolbar.edit
                        },
                        remove: {
                            id: 'button_remove_pemeliharaan',
                            action: setting.toolbar.remove
                        }
                    }
                };
            }
            else
            {

                var settingGrid = {
                    grid: {
                        id: setting.toolbar.idGrid,
                        title: 'Pemeliharaan',
                        column: [
                            {header: 'No', xtype: 'rownumberer', width: 35, resizable: true, style: 'padding-top: .5px;'},
                            {header: 'Jenis', dataIndex: 'jenis', width: 120, groupable: false, hidden: false, filter: {type: 'string'},
                                renderer: function(value){
                                    if (value === '1')
                                    {
                                        return "Predictive";
                                    }
                                    else if (value === '2')
                                    {
                                        return "Preventive";
                                    }
                                    else if (value === '3')
                                    {
                                        return "Corrective";
                                    }
                                }
                            },
                            {header: 'Tahun Anggaran', dataIndex: 'tahun_angaran', width: 120, groupable: false, hidden: false, filter: {type: 'string'}},
                            {header: 'Pelaksana Tanggal', dataIndex: 'pelaksana_tgl', width: 150, groupable: false, hidden: false, filter: {type: 'date'}},
                            {header: 'Pelaksana Nama', dataIndex: 'pelaksana_nama', width: 150, groupable: false, hidden: false, filter: {type: 'string'}},
                            {header: 'Kondisi', dataIndex: 'kondisi', width: 100, groupable: false, hidden: false, filter: {type: 'string'}},
                            {header: 'Deskripsi', dataIndex: 'deskripsi', width: 150, groupable: false, hidden: false, filter: {type: 'string'}},
                            {header: 'Harga', dataIndex: 'harga', width: 100, groupable: false, hidden: false, filter: {type: 'string'}},
                            {header: 'Kode Anggaran', dataIndex: 'kode_anggaran', width: 100, groupable: false, hidden: false, filter: {type: 'string'}},
                            {header: 'Frekuensi Waktu', dataIndex: 'freq_waktu', width: 120, groupable: false, hidden: false, filter: {type: 'string'}},
                            {header: 'Frekuensi Penggunaan', dataIndex: 'freq_penggunaan', width: 120, groupable: false, hidden: false, filter: {type: 'string'}},
                            {header: 'Status', dataIndex: 'status', width: 120, groupable: false, hidden: false, filter: {type: 'string'}},
                            {header: 'Durasi', dataIndex: 'durasi', width: 90, groupable: false, hidden: false, filter: {type: 'string'}},
                            {header: 'Rencana Waktu', dataIndex: 'rencana_waktu', width: 120, groupable: false, hidden: false, filter: {type: 'date'}},
                            {header: 'Rencana Penggunaan', dataIndex: 'rencana_penggunaan', width: 120, groupable: false, hidden: false, filter: {type: 'string'}},
                            {header: 'Rencana Keterangan', dataIndex: 'rencana_keterangan', width: 120, groupable: false, hidden: false, filter: {type: 'string'}},
                        ]
                    },
                    search: {
                        id: 'search_pemeliharaan'
                    },
                    toolbar: {
                        id: 'toolbar_pemeliharaan',
                        add: {
                            id: 'button_add_pemeliharaan',
                            action: setting.toolbar.add
                        },
                        edit: {
                            id: 'button_edit_pemeliharaan',
                            action: setting.toolbar.edit
                        },
                        remove: {
                            id: 'button_remove_pemeliharaan',
                            action: setting.toolbar.remove
                        }
                    }
                };
            }



            var filter = new Ext.create('Ext.ux.grid.filter.Filter', {
                ftype: 'filters', autoReload: true, local: true, encode: true
            });

            var search = new Ext.create('Ext.ux.form.SearchField', {
                id: settingGrid.search.id, store: setting.dataStore, width: 180
            });

            var selMode = new Ext.create('Ext.selection.CheckboxModel');

            var toolbar = new Ext.create('Ext.toolbar.Toolbar', {
                id: settingGrid.toolbar.id,
                items: [{
                        text: 'Tambah', id: settingGrid.toolbar.add.id, iconCls: 'icon-add', handler: function() {
                            settingGrid.toolbar.add.action();
                        }
                    }, '-', {
                        text: 'Ubah', id: settingGrid.toolbar.edit.id, iconCls: 'icon-edit', handler: function() {
                            settingGrid.toolbar.edit.action();
                        }
                    }, '-', {
                        text: 'Hapus', id: settingGrid.toolbar.remove.id, iconCls: 'icon-delete', handler: function() {
                            settingGrid.toolbar.remove.action();
                        }
                    }, '->', {
                        text: 'Clear Filter', iconCls: 'icon-filter_clear',
                        handler: function() {
                            _grid.filters.clearFilters();
                        }
                    }, search
                ]
            });


            var feature_list = {
                filter: filter,
                search: search,
                selmode: selMode,
                toolbar: toolbar
            };

            return Grid.baseGrid(settingGrid, setting.dataStore, feature_list);
        };
        
        
        Grid.inventarisGrid = function(setting, data) {
            if (setting === null)
            {
                console.log('setting is null');
                return;
            }

            var filter = new Ext.create('Ext.ux.grid.filter.Filter', {
                ftype: 'filters', autoReload: true, local: true, encode: true
            });

            var search = new Ext.create('Ext.ux.form.SearchField', {
                id: setting.search.id, store: data, width: 180
            });

            var selMode = new Ext.create('Ext.selection.CheckboxModel');

            var toolbar = new Ext.create('Ext.toolbar.Toolbar', {
                id: setting.toolbar.id,
                items: [{
                        text: 'Tambah', id: setting.toolbar.add.id, iconCls: 'icon-add', handler: function() {
                            setting.toolbar.add.action();
                        }
                    }, '-', {
                        text: 'Ubah', id: setting.toolbar.edit.id, iconCls: 'icon-edit', handler: function() {
                            setting.toolbar.edit.action();
                        }
                    }, '-', {
                        text: 'Hapus', id: setting.toolbar.remove.id, iconCls: 'icon-delete', handler: function() {
                            setting.toolbar.remove.action();
                        }
                    }, '-', {
                        text: 'Cetak', id: setting.toolbar.print.id, iconCls: 'icon-printer', handler: function() {
                            setting.toolbar.print.action();
                        }
                    }, '->', {
                        text: 'Clear Filter', iconCls: 'icon-filter_clear',
                        handler: function() {
                            _grid.filters.clearFilters();
                        }
                    }, search
                ]
            });

            var feature_list = {
                filter: filter,
                search: search,
                selmode: selMode,
                toolbar: toolbar
            };

            return Grid.baseGrid(setting, data, feature_list);
        };

        Grid.processGrid = function(setting, data) {
            if (setting === null)
            {
                console.log('setting is null');
                return;
            }

            var filter = new Ext.create('Ext.ux.grid.filter.Filter', {
                ftype: 'filters', autoReload: true, local: true, encode: true
            });

            var search = new Ext.create('Ext.ux.form.SearchField', {
                id: setting.search.id, store: data, width: 180
            });

            var selMode = new Ext.create('Ext.selection.CheckboxModel');

            var toolbar = new Ext.create('Ext.toolbar.Toolbar', {
                id: setting.toolbar.id,
                items: [{
                        text: 'Tambah', id: setting.toolbar.add.id, iconCls: 'icon-add', handler: function() {
                            setting.toolbar.add.action();
                        }
                    }, '-', {
                        text: 'Ubah', id: setting.toolbar.edit.id, iconCls: 'icon-edit', handler: function() {
                            setting.toolbar.edit.action();
                        }
                    }, '-', {
                        text: 'Hapus', id: setting.toolbar.remove.id, iconCls: 'icon-delete', handler: function() {
                            setting.toolbar.remove.action();
                        }
                    }, '-', {
                        text: 'Cetak', id: setting.toolbar.print.id, iconCls: 'icon-printer', handler: function() {
                            setting.toolbar.print.action();
                        }
                    }, '->', {
                        text: 'Clear Filter', iconCls: 'icon-filter_clear',
                        handler: function() {
                            _grid.filters.clearFilters();
                        }
                    }, search
                ]
            });

            var feature_list = {
                filter: filter,
                search: search,
                selmode: selMode,
                toolbar: toolbar
            };

            return Grid.baseGrid(setting, data, feature_list);

        };

        Grid.selectionAsset = function() {

            var data = new Ext.create('Ext.data.Store', {
                fields: ['nama', 'unker', 'kd_lokasi', 'kd_brg', 'no_aset', 'kd_gol', 'kd_bid', 'kd_kel', 'kd_skel', 'kd_sskel'], autoLoad: false,
                proxy: new Ext.data.AjaxProxy({
                    url: BASE_URL + 'asset_master/allAsset', actionMethods: {read: 'POST'},
                    extraParams: {id_open: 1, kd_lokasi: 0, kd_gol: 0, kd_bid: 0, kd_kel: 0, kd_skel: 0, kd_sskel: 0}
                })
            });

            var toolbar = ToolbarGrid.selection(true, data);

            var _grid = Ext.create('Ext.grid.Panel', {
                store: data,
                title: 'SELECT ASSET',
                frame: true,
                border: true,
                loadMask: true,
                style: 'margin:0 auto;',
                height: '100%',
                width: '100%',
                columnLines: true,
                tbar: toolbar,
                dockedItems: [
                    {xtype: 'pagingtoolbar', store: data, dock: 'bottom', displayInfo: true},
                ],
                listeners: {
                    itemdblclick: function(dataview, record, item, index, e) {
                        var data = record.data;
                        debugger;
                        if (data !== null)
                        {
                            var temp = Ext.getCmp('form-process');
                            if (temp !== null && temp != undefined)
                            {
                                var form = temp.getForm();
                                form.setValues(data);
                            }
                            Modal.assetSelection.close();
                        }
                    },
                },
                columns: [
                    {
                        text: 'Nama',
                        width: 200,
                        sortable: true,
                        dataIndex: 'nama',
                        filter: {type: 'string'}
                    },
                    {
                        text: 'Unit Kerja',
                        width: 200,
                        sortable: true,
                        dataIndex: 'unker',
                        filter: {type: 'string'}
                    },
                    {
                        text: 'Kode Lokasi',
                        width: 150,
                        sortable: true,
                        dataIndex: 'kd_lokasi',
                        filter: {type: 'string'}
                    },
                    {
                        text: 'Kode Barang',
                        width: 110,
                        sortable: true,
                        dataIndex: 'kd_brg',
                        filter: {type: 'string'}
                    },
                    {
                        text: 'No Asset',
                        width: 70,
                        sortable: true,
                        dataIndex: 'no_aset',
                        filter: {type: 'string'}
                    },
                ]
            });

            return _grid;
        };

        Grid.selectionReference = function() {

            var data = new Ext.create('Ext.data.Store', {
                fields: ['nama', 'kd_brg', 'kd_gol', 'kd_bid', 'kd_kel', 'kd_skel', 'kd_sskel'], autoLoad: false,
                proxy: new Ext.data.AjaxProxy({
                    url: BASE_URL + 'asset_master/allReference', actionMethods: {read: 'POST'},
                    extraParams: {id_open: 1, kd_gol: 0, kd_bid: 0, kd_kel: 0, kd_skel: 0, kd_sskel: 0}
                })
            });

            var toolbar = ToolbarGrid.selection(false, data);

            var _grid = Ext.create('Ext.grid.Panel', {
                store: data,
                title: 'SELECT RERENCE BARANG',
                frame: true,
                border: true,
                loadMask: true,
                style: 'margin:0 auto;',
                height: '100%',
                width: '100%',
                columnLines: true,
                tbar: toolbar,
                dockedItems: [
                    {xtype: 'pagingtoolbar', store: data, dock: 'bottom', displayInfo: true},
                ],
                listeners: {
                    itemdblclick: function(dataview, record, item, index, e) {
                        var data = record.data;
                        if (data !== null)
                        {
                            var temp = Ext.getCmp('form-create');
                            if (temp !== null)
                            {
                                var form = temp.getForm();
                                form.setValues(data);
                            }
                            Modal.assetSelection.close();
                        }
                    },
                },
                columns: [
                    {
                        text: 'Nama',
                        width: 200,
                        sortable: true,
                        dataIndex: 'nama',
                        filter: {type: 'string'}
                    },
                    {
                        text: 'Kode Barang',
                        width: 220,
                        sortable: true,
                        dataIndex: 'kd_brg',
                        filter: {type: 'string'}
                    },
                ]
            });

            return _grid;
        }

        ToolbarGrid.selection = function(WithLokasi, data) {
            var cmp = ToolbarGrid.component(WithLokasi, data);

            var toolbar = new Ext.create('Ext.toolbar.Toolbar', {
                items: cmp
            });


            return toolbar;
        };


        ToolbarGrid.component = function(WithLokasi, data)
        {
            var selectionParams = {};

            var component = [{
                    xtype: 'combo',
                    id: 'select_gol',
                    valueField: 'kd_gol',
                    displayField: 'ur_gol',
                    emptyText: 'Golongan',
                    valueNotFoundText: 'Golongan',
                    typeAhead: true,
                    width: 130,
                    store: Reference.Data.golongan,
                    listeners: {
                        change: function(obj, value) {
                            if (value !== null)
                            {
                                selectionParams['kd_gol'] = value;
                                var bidangField = Ext.getCmp('select_bidang');
                                if (bidangField !== null) {
                                    bidangField.enable();
                                    Reference.Data.bidang.changeParams({params: {
                                            id_open: 1,
                                            kd_gol: value}});
                                }
                                else {
                                    console.error('error couldnt find field or value');
                                }

                            }
                        }
                    }
                }, {
                    xtype: 'combo',
                    id: 'select_bidang',
                    valueField: 'kd_bid',
                    displayField: 'ur_bid',
                    emptyText: 'Bidang',
                    valueNotFoundText: 'Bidang',
                    typeAhead: true,
                    disabled: true,
                    width: 120,
                    store: Reference.Data.bidang,
                    listeners: {
                        change: function(obj, value) {
                            if (value !== null)
                            {
                                selectionParams['kd_bid'] = value;
                                var kelompokField = Ext.getCmp('select_kel');
                                var golonganField = Ext.getCmp('select_gol').getValue();
                                if (kelompokField !== null && golonganField !== null) {
                                    kelompokField.enable();
                                    Reference.Data.kelompok.changeParams({params: {
                                            id_open: 1,
                                            kd_gol: golonganField,
                                            kd_bid: value}});
                                }
                                else {
                                    console.error('error couldnt find field or value');
                                }
                            }
                        }
                    }
                }, {
                    xtype: 'combo',
                    id: 'select_kel',
                    valueField: 'kd_kel',
                    displayField: 'ur_kel',
                    emptyText: 'Kelompok',
                    valueNotFoundText: 'Kelompok',
                    typeAhead: true,
                    disabled: true,
                    width: 110,
                    store: Reference.Data.kelompok,
                    listeners: {
                        change: function(obj, value) {
                            if (value !== null)
                            {
                                selectionParams['kd_kel'] = value;
                                var golonganValue = Ext.getCmp('select_gol').getValue();
                                var bidangValue = Ext.getCmp('select_bidang').getValue();
                                var skelompokField = Ext.getCmp('select_skel');
                                if (skelompokField !== null && bidangValue !== null && golonganValue !== null) {
                                    skelompokField.enable();
                                    Reference.Data.subKelompok.changeParams({params: {
                                            id_open: 1,
                                            kd_gol: golonganValue,
                                            kd_bid: bidangValue,
                                            kd_kel: value}});
                                }
                                else {
                                    console.error('error couldnt find field or value');
                                }
                            }
                        }
                    }
                }, {
                    xtype: 'combo',
                    id: 'select_skel',
                    valueField: 'kd_skel',
                    displayField: 'ur_skel',
                    emptyText: 'SubKelompok',
                    valueNotFoundText: 'SubKelompok',
                    typeAhead: true,
                    disabled: true,
                    width: 110,
                    store: Reference.Data.subKelompok,
                    listeners: {
                        change: function(obj, value) {
                            if (value !== null)
                            {
                                selectionParams['kd_skel'] = value;
                                var golonganValue = Ext.getCmp('select_gol').getValue();
                                var bidangValue = Ext.getCmp('select_bidang').getValue();
                                var kelompokValue = Ext.getCmp('select_kel').getValue();
                                var sskelompokField = Ext.getCmp('select_sskel');
                                if (sskelompokField !== null && kelompokValue !== null && bidangValue !== null && golonganValue !== null) {
                                    sskelompokField.enable();
                                    Reference.Data.subSubKelompok.changeParams({params: {
                                            id_open: 1,
                                            kd_gol: golonganValue,
                                            kd_bid: bidangValue,
                                            kd_kel: kelompokValue,
                                            kd_skel: value
                                        }});
                                }
                                else {
                                    console.error('error couldnt find field or value');
                                }
                            }
                        }
                    }
                }, {
                    xtype: 'combo',
                    id: 'select_sskel',
                    valueField: 'kd_sskel',
                    displayField: 'ur_sskel',
                    emptyText: 'SSubKelompok',
                    valueNotFoundText: 'SSubKelompok',
                    typeAhead: true,
                    disabled: true,
                    width: 110,
                    store: Reference.Data.subSubKelompok,
                    listeners: {
                        change: function(obj, value) {
                            if (value !== null)
                            {
                                selectionParams['kd_sskel'] = value;
                            }
                        }
                    }
                }, '->', {
                    xtype: 'button',
                    text: 'search',
                    frame: true,
                    border: 1,
                    handler: function() {
                        if (WithLokasi)
                        {
                            data.changeParams({params: {
                                    kd_lokasi: selectionParams.kd_lokasi,
                                    kd_gol: selectionParams.kd_gol,
                                    kd_bid: selectionParams.kd_bid,
                                    kd_kel: selectionParams.kd_kel,
                                    kd_skel: selectionParams.kd_skel,
                                    kd_sskel: selectionParams.kd_sskel
                                }});
                        }
                        else
                        {
                            data.changeParams({params: {
                                    kd_gol: selectionParams.kd_gol,
                                    kd_bid: selectionParams.kd_bid,
                                    kd_kel: selectionParams.kd_kel,
                                    kd_skel: selectionParams.kd_skel,
                                    kd_sskel: selectionParams.kd_sskel
                                }});
                        }

                    }
                }];

            if (WithLokasi)
            {
                var lokasi = {
                    xtype: 'combo',
                    valueField: 'kdlok',
                    displayField: 'ur_upb',
                    emptyText: 'Unit Kerja',
                    valueNotFoundText: 'Unit Kerja',
                    typeAhead: true,
                    width: 190,
                    store: Reference.Data.unker,
                    listeners: {
                        change: function(obj, value) {
                            if (value !== null)
                            {
                                selectionParams['kd_lokasi'] = value;
                            }
                        }
                    }
                };

                component.splice(0, 0, lokasi);
            }

            return component;
        };

<?php } else {
    echo "var new_tabpanel_MD = 'GAGAL';";
} ?>