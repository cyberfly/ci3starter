<div class="card">
    <div class="card-block">
        <h4 class="card-title">Maklumat Pengguna</h4>
        <h6 class="card-subtitle mb-2 text-muted"><?php echo $title; ?> Pengguna</h6>

        <!-- success alert and validation error alert -->

        <?php $this->load->view('templates/notification'); ?>

        <br>
        <?php echo form_open('admin/user/create',['id'=>'form1']); ?>
          <?php
            if(isset($user))
                echo form_hidden('uid', $user->uid);
          ?>
          <div class="main-content">

              <div class="form-group col-md-6">
                  <label for="identity">ID Pengguna</label>
                  <input type="text" class="form-control <?php if(form_error('identity')){ echo 'is-invalid'; } ?> " id="identity" name="identity" placeholder="No Kad Pengenalan" value="<?php echo set_value('identity'); ?>"  >
                  <?php echo form_error('identity'); ?>
              </div>

              <div class="form-group col-md-6">
                  <label for="password">Katalaluan</label>
                  <input type="password" class="form-control <?php if(form_error('password')){ echo 'is-invalid'; } ?> " id="password" name="password"  >
                  <?php echo form_error('password'); ?>
              </div>

              <div class="form-group col-md-6">
                  <label for="first_name">Nama</label>
                  <input type="text" class="form-control <?php if(form_error('first_name')){ echo 'is-invalid'; } ?> " id="first_name" name="first_name" value="<?php echo set_value('first_name'); ?>" >
                  <?php echo form_error('first_name'); ?>
              </div>

              <div class="form-group col-md-6">
                  <label for="password">Email</label>
                  <input type="text" class="form-control <?php if(form_error('email')){ echo 'is-invalid'; } ?> " id="email" name="email" value="<?php echo set_value('email'); ?>" >
                  <?php echo form_error('email'); ?>
              </div>

          </div>
          <div class="modal-footer">
            <a href="<?php echo base_url('admin/user'); ?>" class="btn btn-secondary">Kembali</a>
            <button type="submit" class="btn btn-primary">Simpan</button>
          </div>
        <?php echo form_close(); ?>
    </div>
</div>


<!-- Modal -->
<div class="modal fade" id="agencyModal" tabindex="-1" role="dialog" aria-labelledby="agencyModal" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="agencyModal">Struktur Organisasi</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
            <a href="#" class="btn btn-link root-link" id="rootNode" onclick="setValue('<?php echo$org->ouid; ?>','<?php echo $org->oname; ?>');">
                <?php echo $org->oname . ' ('.$org->osname.')'; ?>
            </a>
            <div id="tree"></div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        </div>
    </div>
  </div>
</div>
