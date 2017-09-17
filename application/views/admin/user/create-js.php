function setValue(ouid, txt) {
// console.log('clicked ' + ouid + '-' + txt);
$('#nodeUid').val(ouid);
$('#agencyName').text(txt);
}

$(function () {

$( "#form1" ).submit(function( event ) {
<!--    event.preventDefault();-->
});

$('#agencyName').click(function() {
$('#agencyModal').modal('show');
});

// $('#rootNode').click(function(uid, txt) {
//     console.log('clicked ' + uid + '-' + txt);
//     $('#nodeUid').val(uid);
//     $('#agencyName').text(txt);
//
// });

$('#tree').jstree({
'core' : {
'data' : {
'type' : 'POST',
'dataType': 'json',
'url' : "<?php echo base_url('agency/tree'); ?>",
'data' : function (node) {
return {
'id': node.id,
'operation': 'getNode'
};
},
},
'check_callback' : true,
'themes' : {
'responsive' : false
}
},
'types': {
'default': {
'icon': 'fa fa-folder'
}
},
'force_text' : true,
'plugins' : ['sort','types']
})
.on('changed.jstree', function(e, data) {
console.log('selected:'+data.selected);

$.ajax({
'url': "<?php echo base_url('agency/tree/getDetail'); ?>",
'type': 'POST',
'dataType': 'json',
'data': {
'id': data.node.id,
'parent': data.node.parent
},
'success': function(i) {
console.log(i);
$('#nodeUid').val(i.uid);
$('#agencyName').text(i.nama + ' (' + i.kod + ')');


// $('#updateNodeModal').modal('show');
},
'error': function() {
console.log('error');
}
});

});
});