<html>
<head>
    <title></title>
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/style.css">
    <style type="text/css">
    </style>
</head>
<body>

<?php

if(isset($content))
{
    $this->load->view($content);
}
?>

</body>
</html>