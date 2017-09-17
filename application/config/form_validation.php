<?php


///////////////////////////
// Form based validation //
///////////////////////////

$config = array(

    'login' => array(
        array(
            'field' => 'username',
            'label' => 'Id Pengguna',
            'rules' => 'trim|required|max_length[15]',
            'errors' => array(
                'required' => 'Id Pengguna wajib diisi',
                'max_length' => 'Id Pengguna terlalu panjang benor'
            )
        ),
        array(
            'field' => 'password',
            'label' => 'Katalaluan',
            'rules' => 'trim|required|max_length[20]'
        )
    )

);


/////////////////////
// Error delimiter //
/////////////////////

// $config['error_prefix'] = '<div class="alert alert-warning alert-dismissible fade show" role="alert">';
// $config['error_suffix'] = '</div>';

$config['error_prefix'] = '<p><small>';
$config['error_suffix'] = '</small></p>';
