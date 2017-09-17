# CodeIgniter 3 Starter

**W-I-P** - Work In Progress document

## <a name="keperluan"></a>Keperluan  

* Apache
* PHP7
* MySQL 5.7
* CodeIgniter3 - https://codeigniter.com/  
* Bootstrap4 - https://v4-alpha.getbootstrap.com/  
* Material Theme - http://daemonite.github.io/material/index.html  

## <a name="pemasangan"></a>Pemasangan   

1. Muat turun kod sumber ```clone https://github.com/cyberfly/ci3starter.git ci3starter```  
1. Kemaskini ```application/config/config.php```  
1. Kemaskini ```application/config/database.php```  
1. Pastikan Apache ```mod_rewrite``` telah ```enabled```  
1. Kemaskini fail ```.htaccess``` seperti berikut:
    ```
    # Contoh konfigurasi .htaccess Apache
    <IfModule mod_rewrite.c>
        RewriteEngine On
        RewriteCond %{REQUEST_FILENAME} !-f
        RewriteCond %{REQUEST_FILENAME} !-d
        RewriteRule ^(.*)$ index.php/$1 [L]
    </IfModule>
    <IfModule !mod_rewrite.c>
        ErrorDocument 404 index.php
    </IfModule>
    ```  
1. Cipta pangkalan data ```ci3starter```    


