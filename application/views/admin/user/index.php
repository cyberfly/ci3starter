<!-- CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.15/css/dataTables.bootstrap4.min.css">

<!-- Datatables -->
<script src="https://cdn.datatables.net/1.10.15/js/jquery.dataTables.min.js" charset="utf-8"></script>
<!-- bootstrap -->
<script src="https://cdn.datatables.net/1.10.15/js/dataTables.bootstrap4.min.js" charset="utf-8"></script>

<!-- jstree -->
<link rel="stylesheet" href="<?php echo base_url('/assets/js/jstree/dist/themes/default/style.css'); ?>">
<script src="<?php echo base_url('/assets/js/jstree/dist/custom.jstree.js'); ?>" charset="utf-8"></script>

<style media="screen">
    .table thead th, .table tbody td {
        height: inherit !important;
    }
    .table th, .table td {
        vertical-align: middle !important;
    }
    .pagination {
        background-color: #ffffff !important;
    }
</style>

<div class="card">
    <div class="card-block">
        <!-- success alert and validation error alert -->

        <?php $this->load->view('templates/notification'); ?>

        <div class="row justify-content-between">
            <div class="flex ml-2">
                <h4 class="card-title">Senarai Pengguna</h4>
                <h6 class="card-subtitle mb-2 text-muted">Pengurusan Pengguna</h6>
            </div>
            <div class="mr-2">
                <!-- <button type="button" class="btn btn-default btn-sm" role="group"><i class="fa fa-plus"></i></button> -->
                <div class="btn-toolbar" role="toolbar" aria-label="Toolbar with button groups">
                  <div class="btn-group mr-2" role="group" aria-label="First group">
                    <!-- <button type="button" class="btn btn-secondary" data-toggle="modal" data-target="#addUserModal"><i class="material-icons md-18">person_add</i></button> -->
                    <a href="<?php echo base_url('admin/user/create'); ?>" class="btn btn-secondary" ><i class="material-icons md-18">person_add</i></a>
                  </div>
                </div>
            </div>
        </div>
        <br>
        <table id="userdt" class="table table-striped table-bordered" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th>Username</th>
                    <th>Nama</th>
                    <th>Emel</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
            <?php if(isset($users)){ foreach ($users as $user){ ?>
            <tr>
                <td>
                    <?php echo $user->username; ?>
                </td>
                <td>
                    <?php echo $user->first_name; ?>
                </td>
                <td>
                    <?php echo $user->email; ?>
                </td>
                <td>
                    <form action="<?php echo site_url("admin/user/delete/$user->id"); ?>" method="post">
                        <a href="<?php echo site_url("admin/user/edit/$user->id"); ?>" class="btn btn-primary ">Kemaskini</a>
                        <button type="button" class="btn btn-danger delete">Padam</button>
                    </form>
                </td>
            </tr>
            <?php } } ?>
            </tbody>
        </table>

    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="removeUserModal" tabindex="-1" role="dialog" aria-labelledby="removeUserModal" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
        <?php echo form_open('admin/user/delete'); ?>
          <div class="modal-header">
            <h5 class="modal-title" id="removeUserModal">Hapus Pengguna</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">

              <p>Anda pasti untuk menghapuskan rekod ini?</p>
               <p>Sila taip semula <code id="musername"></code> untuk mengesahkan tindakan anda</p>
              <div class="form-group">
                  <input type="text" name="uid" id="uid">
                  <input type="text" class="form-control" id="username" name="username" autofocus>
                  <input type="text" name="cusername" id="cusername">
              </div>

          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-danger">Hapus</button>
          </div>
        <?php echo form_close(); ?>
    </div>
  </div>
</div>

<script type="text/javascript">
    $(document).ready(function() {

        $('#userdt').DataTable({
            'processing': true,
            'serverSide': true,
            'ajax': {
                'url': "<?php echo base_url('admin/user/userList'); ?>",
                'type': "POST"
            },
            'columns': [
                { 'data': 'no_kp' },
                { 'data': 'katalaluan' },
                { 'data': 'btn' }
            ],
            'deferRender': true
        });

        $('#removeUserModal').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget)
            var username = button.data('username');
            var uid = button.data('uid');
            var modal = $(this);
            modal.find('#uid').val(uid);
            modal.find('#musername').text(username);
            modal.find('#cusername').val(username);
        });

    } );
</script>

<script type="text/javascript">

    $(function () {

        $('#tree').jstree({
            'core' : {
                'data' : {
                    'type' : 'POST',
                    'dataType': 'json',
                    'url' : "<?php echo base_url('tree'); ?>",
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
            'plugins' : ['dnd','sort','types']
        })
        .on('move_node.jstree', function (e, data) {
			$.post("<?php echo base_url('tree'); ?>", {
                'id' : data.node.id,
                'operation': 'moveNode',
                'parent' : data.parent,
                'position' : data.position
            }, 'json')
				.fail(function () {
					data.instance.refresh();
				});
		})
        .on('changed.jstree', function(e, data) {
            console.log('selected:'+data.selected);

            $.ajax({
                'url': "<?php echo base_url('tree/getDetail'); ?>",
                'type': 'POST',
                'dataType': 'json',
                'data': {
                    'id': data.node.id,
                    'parent': data.node.parent
                },
                'success': function(i) {

                    // tab1
                    $('#nodeId').val(i.id);
                    $('#nama').val(i.nama);
                    $('#singkatan').val(i.kod);
                    // tab2
                    $('#parentId').val(i.id);
                    $('#pid').val(i.parent);
                    $('#position').val(i.position);
                    // tab3
                    $('#rnodeId').val(i.id);
                    $("#rnama").attr("placeholder", i.kod);

                    $('#updateNodeModal').modal('show');
                },
                'error': function() {
                    console.log('error');
                }
            });

        });

        $("#btnUpdateNode").click(function() {
            $.post("<?php echo base_url('tree/updateDetail') ?>", $("#updateNodeForm").serialize())
                .done(function() {
                    $('#updateNodeModal').modal('hide');
                    $('#tree').jstree(true).refresh(false, true);
                });
        });

        $("#btnCreateNode").click(function() {

            $.post("<?php echo base_url('tree'); ?>", {
                    'id' : $('#parentId').val(),
                    'pid' : $('#pid').val(),
                    'operation': 'createNode',
                    'position' : $('#position').val(),
                    'text' : $('#cnama').val(),
                    'singkatan': $('#csingkatan').val()
                 }, 'json')
				.done(function (d) {
                    $('#cnama').val('');
                    $('#csingkatan').val('')
                    $('#updateNodeModal').modal('hide');
                    $('#tree').jstree(true).refresh(false, true);
				})
				.fail(function () {
                    $('#updateNodeModal').modal('hide');
                    $('#tree').jstree(true).refresh(false, true);
				});

        });

        $("#btnRemoveNode").click(function() {
            console.log('remove node' + $('#rnodeId').val());
            $.post("<?php echo base_url('tree'); ?>", {
                'id': $('#rnodeId').val(),
                'operation': 'deleteNode'
            }, 'json')
            .done(function() {
                $('#updateNodeModal').modal('hide');
                $('#tree').jstree(true).refresh(false, true);
            })
            .fail(function() {
                $('#tree').jstree(true).refresh(false, true);
            });
        });

    });

</script>
