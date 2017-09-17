<div class="card">
    <div class="card-block">
        <h4 class="card-title">Maklumat Pengguna</h4>
        <h6 class="card-subtitle mb-2 text-muted"><?php echo $title; ?> Pengguna</h6>
        <br>
        <?php echo form_open('admin/user/register',['id'=>'form1']); ?>
          <?php
            if(isset($user))
                echo form_hidden('uid', $user->uid);
          ?>
          <div class="main-content">
              <div class="form-group row">
                  <div class="form-group col-md-6">
                    <label for="username">ID Pengguna</label>
                    <input type="text" class="form-control validate" id="username" name="username" placeholder="No Kad Pengenalan" value="<?php if(isset($user)) echo $user->no_kp; ?>" data-rules="required|valid_email" >
                      <p class="help-block"></p>

                  </div>
                  <div class="form-group col-md-6">
                    <label for="password">Katalaluan</label>
                    <input type="password" class="form-control" id="password" name="password" <?php if(isset($user)) echo 'disabled'; ?> >
                  </div>
              </div>

              <div class="form-group row">
                  <div class="col-md-6">
                      <label for="agency">Agensi</label>
                      <div class="form-control">
                          <p id="agencyName">
                              <?php if(isset($user)) echo $user->agensi; else echo 'Sila Pilih'; ?>
                          </p>
                          <!-- <a href="#"  data-toggle="modal" data-target="#agencyModal"><i class="fa fa-sitemap"></i></a> -->
                          <input type="hidden" name="nodeUid" id="nodeUid" value="<?php if(isset($user))  echo $user->agensi_uid; ?>">
                      </div>
                  </div>
                  <div class="col-md-6">
                      <!-- <p class="lead">Peranan</p> -->
                      <label for="role">Peranan</label>
                      <div class="form-group">
                      <?php
                        $role = '';
                        if(isset($user))
                            $role = $user->peranan_uid;
                        $attr = 'class="form-control"';
                        echo form_dropdown('role', $roles, $role, $attr);
                       ?>
                      </div>
                  </div>
              </div>
          </div>
          <div class="modal-footer">
            <a href="<?php echo base_url('admin/config/user'); ?>" class="btn btn-secondary">Kembali</a>
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
