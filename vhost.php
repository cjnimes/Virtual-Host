<?php
function update_apache($input)
{    
    // APACHE vhosts.conf    
    $file = 'c:/xampp/apache/conf/extra/httpd-vhosts.conf';
    
    $data = PHP_EOL . sprintf('
                    <VirtualHost *>
                            ServerName %s.localhost
                            ServerAlias %s.localhost
                            ServerAdmin admin@%s.localhost
                            DocumentRoot "%s/%s"
                            DirectoryIndex index.php
                    </VirtualHost>', 
                    $input['sname'], 
                    $input['sname'], 
                    $input['sname'],
                    $_SERVER['DOCUMENT_ROOT'],
                    $input['droot']);
    
    $backup = $file . '.BKP-' . date('YmdHis');

    if (!copy($file, $backup)) {
        throw new Exception('copy');
    }

    if (!file_put_contents($file, $data, FILE_APPEND)) {
        throw new Exception('put');
    }
}

function update_windows($input)
{
    // WINDOWS hosts    
    $file = 'c:/windows/system32/drivers/etc/hosts';
    
    $data = PHP_EOL . sprintf('127.0.0.1 %s.localhost', $input['sname']);

    $backup = $file . '.BKP-' . date('YmdHis');

    if (!copy($file, $backup)) {
        throw new Exception('copy');
    }

    if (!file_put_contents($file, $data, FILE_APPEND)) {
        throw new Exception('put');
    }
}

if (isset($_POST['btn-submit'])) {

    try {
    
        if ($_POST['droot'] == '' || $_POST['sname'] == '') {
            exit;
        }
        
        update_apache($_POST);
        update_windows($_POST);        
        
        print 'Virtual Host agregado exitosamente. Reinicie su servidor Apache.';

    } catch (Exception $e) {
        
        print 'Ha ocurrido un error ' . ($e->getMessage() == 'copy' ? 'haciendo el backup' : 'escribiendo el archivo');

    }

} else { ?>

<p><strong>Agregar Apache Virtual Host</strong></p>

<form action="<?php print $_SERVER['SCRIPT_NAME']?>" method="post">

    <label>
        <strong>DocumentRoot</strong>
	<br>
        <?php print $_SERVER['DOCUMENT_ROOT']?>/<input type="text" name="droot" autofocus="autofocus">
    </label>
    
    <br><br>
    
    <label>
        <strong>ServerName</strong>
	<br>
        http://<input type="text" name="sname">.localhost
    </label>
    
    <br><br>
    
    <input type="submit" name="btn-submit" value="Agregar">

</form>


<?php } ?>