<?php
require 'config.php';
require_once DOL_DOCUMENT_ROOT.'/core/class/html.form.class.php';

global $db;
$form = new Form($db);

//creer array key=>value de id_playlist -> playlist
$sql = 'SELECT rowid, title';
$sql.= ' FROM '.MAIN_DB_PREFIX.'playlistAbricot';

$result = $db->query($sql);
var_dump($result);
if(!empty($result)){
    foreach($result as $lines){
        var_dump($line);
        var_dump($line->rowid);
    }
}
else{
    $html = "<div id='toCreatePlaylist'><a href='#'>test</a></div>";
}

//appeler selectArray sur $form

//recuperer le html du form
//remplacer le html du extrafield par celui du form créé

