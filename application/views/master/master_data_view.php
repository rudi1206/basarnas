<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<?php header("Content-Type: application/x-javascript"); ?>
	
<?php if(isset($jsscript) && $jsscript == TRUE){ ?>
<script>
////PANEL UTAMA MASTER DATA  -------------------------------------------- START

var West_MD = {
	title: 'PETUNJUK',
  region: 'west', collapsible: true,
  width: '25%', minSize: 100, maxSize: 350,
  split: true, iconCls: 'icon-help',
  items:[{
  	xtype : 'miframe', frame: false, height: '100%',
    src : BASE_URL + 'master_data/petunjuk'
  }]
};

var Tab_MD = Ext.createWidget('tabpanel', {
	id: 'Tab_MD', layout: 'fit', resizeTabs: true, enableTabScroll: false, deferredRender: true, border: false,
  defaults: {autoScroll:true},
  items: [{
      id: 'default_Tab_MD', 
      bodyPadding: 10,
      closable: false
  }]
});

var Center_MD = {
  region: 'center', layout: 'card', collapsible: false, margins: '0 0 0 0', width: '100%', border: true, autoScroll: true,
  items: [Tab_MD],
  tbar: Ext.create('Ext.toolbar.Toolbar', {
	  layout: {overflowHandler: 'Menu'},
		items: [
	  	{text: 'Unit Kerja', iconCls: 'icon-course', disabled: m_unit_kerja, handler: function(){Load_TabPage_MD('master_unit_kerja', BASE_URL + 'master_data/unit_kerja');}, tooltip: {text: 'Referensi Unit Kerja'}},
	  	{text: 'Jabatan', iconCls: 'icon-spam', disabled: m_jabatan, handler: function(){Load_TabPage_MD('master_jabatan', BASE_URL + 'master_data/jabatan');}, tooltip: {text: 'Referensi Jabatan'}},
	  	{text: 'Unit Organisasi', iconCls: 'icon-spell', disabled: m_unor, handler: function(){Load_TabPage_MD('master_unit_organisasi', BASE_URL + 'master_data/unit_organisasi');}, tooltip: {text: 'Referensi Unit Organisasi'}},
	  	{text: 'TTD', iconCls: 'icon-templates', disabled: m_ttd, handler: function(){Load_TabPage_MD('master_ttd', BASE_URL + 'master_data/ttd');}, tooltip: {text: 'Referensi Pejabat Penandatangan'}},
	  	{text: 'Provinsi', iconCls: 'icon-templates', disabled: m_prov, handler: function(){Load_TabPage_MD('master_prov', BASE_URL + 'master_data/prov');}, tooltip: {text: 'Referensi Provinsi'}},
	  	{text: 'Kabupaten', iconCls: 'icon-templates', disabled: m_kabkota, handler: function(){Load_TabPage_MD('master_kabkota', BASE_URL + 'master_data/kabkota');}, tooltip: {text: 'Referensi Kabupaten / Kota'}},
	  	{text: 'Kecamatan', iconCls: 'icon-templates', disabled: m_kec, handler: function(){Load_TabPage_MD('master_kec', BASE_URL + 'master_data/kec');}, tooltip: {text: 'Referensi Kecamatan'}},
	  	{text: 'Satuan', iconCls: 'icon-templates'},
	  ]
  })
};

var Container_MD = {
	xtype: 'container', region: 'center', layout: 'border', border: false,
  items: [Center_MD]
};

var new_tabpanel = {
	id: 'master_data', title: 'Referensi', iconCls: 'icon-gears', border: false, closable: true, 
	layout: 'fit', items: [Container_MD]
};
// PANEL UTAMA MASTER DATA  --------------------------------------------- END

function Load_TabPage_MD(tab_id,tab_url){
	Ext.getCmp('layout-body').body.mask("Loading...", "x-mask-loading");
	var new_tab_id = Ext.getCmp(tab_id);
	if(new_tab_id){
		Ext.getCmp('Tab_MD').setActiveTab(tab_id);
		Ext.getCmp('layout-body').body.unmask(); 
	}else{
		Ext.Ajax.timeout = Time_Out;
		Ext.Ajax.request({
  		url: tab_url, method: 'POST', params: {id_open: 1}, scripts: true, 
    	success: function(response){    	
    		var jsonData = response.responseText; var aHeadNode = document.getElementsByTagName('head')[0]; var aScript = document.createElement('script'); aScript.text = jsonData; aHeadNode.appendChild(aScript);
    		if(new_tabpanel_MD != "GAGAL"){
    			Ext.getCmp('Tab_MD').add(new_tabpanel_MD).show();
    		}else{
    			Ext.MessageBox.show({title:'Peringatan !', msg:'Anda tidak dapat mengakses ke halaman ini !', buttons: Ext.MessageBox.OK, icon: Ext.MessageBox.ERROR});    			
    		}
   		},
    	failure: function(response){ Ext.MessageBox.show({title:'Peringatan !', msg:'Gagal memuat dokumen !', buttons: Ext.MessageBox.OK, icon: Ext.MessageBox.ERROR}); }, 
    	callback: function(response){ Ext.getCmp('layout-body').body.unmask(); },
    	scope : this
		});
	}	
}
<?php }else{ echo "var new_tabpanel = 'GAGAL';"; } ?>