<?php

require 'config.php';
dol_include_once('/playlistabricot/class/playlistabricot.class.php');

if(empty($user->rights->playlistabricot->all->read)) accessforbidden();

$langs->load('abricot@abricot');
$langs->load('mymodule@mymodule');

$PDOdb = new TPDOdb;
$object = new TplaylistAbricot();

$hookmanager->initHooks(array('mymodulelist'));

/*
 * Actions
 */
$parameters=array();
$reshook=$hookmanager->executeHooks('doActions',$parameters,$object);    // Note that $action and $object may have been modified by some hooks
if ($reshook < 0) setEventMessages($hookmanager->error, $hookmanager->errors, 'errors');

if (empty($reshook))
{
	// do action from GETPOST ...
}

/*
 * View
 */

llxHeader('',$langs->trans('MyModuleList'),'','');

$sql = 'SELECT rowid, fk_author, title, author';
$sql.= ' FROM '.MAIN_DB_PREFIX.'playlistAbricot';


$formcore = new TFormCore($_SERVER['PHP_SELF'], 'form_list_mymodule', 'GET');

$nbLine = !empty($user->conf->MAIN_SIZE_LISTE_LIMIT) ? $user->conf->MAIN_SIZE_LISTE_LIMIT : $conf->global->MAIN_SIZE_LISTE_LIMIT;
$r = new TListviewTBS('playlistabricot');
echo $r->render($PDOdb, $sql, array(
		'view_type' => 'list' // default = [list], [raw], [chart]
		,'limit'=>array(
				'nbLine' => $nbLine
		)
		,'subQuery' => array()
		,'link' => array(
				'title' => '<a href="'.dol_buildpath('/playlistabricot/card_playlist.php', 1).'?id=@rowid@">@val@</a>',
				'author' => '<a href="'.dol_buildpath('/societe/card.php', 1).'?socid=@fk_author@">@val@</a>'
		)
		,'type' => array()
		,'search' => array(
// 			'author' => array('recherche' => true, 'table' => 't', 'field' => 'ref')
// 			,'title' => array('recherche' => true, 'table' => array('t', 't'), 'field' => array('label', 'description')) // input text de recherche sur plusieurs champs
				
		)
		,'translate' => array()
		,'hide' => array(
				'rowid',
				'fk_author'
		)
		,'liste' => array(
				'titre' => $langs->trans('TplayList')
				,'image' => img_picto('','title_generic.png', '', 0)
				,'picto_precedent' => '<'
				,'picto_suivant' => '>'
				,'noheader' => 0
				,'messageNothing' => $langs->trans('NoMyModule')
				,'picto_search' => img_picto('','search.png', '', 0)
		)
		,'title'=>array(
				'title' => $langs->trans('title')
				,'author' => $langs->trans('author')
		)
		,'eval'=>array(
				//'title' => 'TplaylistAbricot::getStaticNomUrl(@rowid@, 1)' // Si on a un fk_user dans notre requête
		)
));

$parameters=array('sql'=>$sql);
$reshook=$hookmanager->executeHooks('printFieldListFooter', $parameters, $object);    // Note that $action and $object may have been modified by hook
print $hookmanager->resPrint;

$formcore->end_form();

llxFooter('');

/**
 * TODO remove if unused
 */
function _getUserNomUrl($fk_user)
{
	global $db;
	
	$u = new User($db);
	if ($u->fetch($fk_user) > 0)
	{
		return $u->getNomUrl(1);
	}
	
	return '';
}