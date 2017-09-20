<?php

require 'config.php';
require_once DOL_DOCUMENT_ROOT.'/core/lib/functions.lib.php';
require_once DOL_DOCUMENT_ROOT.'/core/class/html.form.class.php';
dol_include_once('/playlistabricot/class/playlistabricot.class.php');
dol_include_once('/playlistabricot/lib/playlistabricot.lib.php');

if(empty($user->rights->playlistabricot->all->read)) accessforbidden();

$langs->load('playlistabricot@playlistabricot');

$action = 		GETPOST('action');
$id = 			GETPOST('id', 'int');
$title = 		GETPOST('title');
$author = 		GETPOST('author');
$bitrate = 		GETPOST('bitrate');
$type = 		GETPOST('type');

$mode = 'view';
if (empty($user->rights->playlistabricot->all->write)) 	$mode = 'view'; // Force 'view' mode if can't edit object
else if ($action == 'create' || $action == 'edit') 		$mode = 'edit';

$PDOdb = new TPDOdb;
$object = new TplaylistAbricot;

if (!empty($id)) $object->load($PDOdb, $id);
elseif (!empty($ref)) $object->loadBy($PDOdb, $ref, 'ref');

$hookmanager->initHooks(array('playlistabricotcard', 'globalcard'));

/*
 * Actions
 */
$parameters = array('id' => $id, 'title' => $title, 'author' => $author, 'ref' => $ref, 'mode' => $mode);
$reshook = $hookmanager->executeHooks('doActions', $parameters, $object, $action); // Note that $action and $object may have been modified by some
if ($reshook < 0) setEventMessages($hookmanager->error, $hookmanager->errors, 'errors');

// Si vide alors le comportement n'est pas remplacé
if (empty($reshook))
{
	$error = 0;
	switch ($action) {
		case 'save':
			//get soc name
			require_once DOL_DOCUMENT_ROOT.'/societe/class/societe.class.php';
			$socObj = new Societe($db);
			$socObj->fetch($_REQUEST['fk_author']);
			$_REQUEST['author'] = $socObj->name;
			$object->set_values($_REQUEST); // Set standard attributes
			
			if ($error > 0)
			{
				$mode = 'edit';
				break;
			}
			
			$object->save($PDOdb, empty($object->ref));
			
			header('Location: '.dol_buildpath('/playlistabricot/card_playlist.php', 1).'?id='.$object->getId());
			exit;
			
			break;
		case 'delete':
			$object->delete($PDOdb);

			header('Location: '.dol_buildpath('/playlistabricot/list_playlist.php', 1));
			exit;
			
			break;
		case 'showTracks':
			$html = _liste($PDOdb, $id);			
			break;
	}
}


/**
 * View
 */

$title=$langs->trans("playlistabricot");
llxHeader('',$title);

if ($action == 'create' && $mode == 'edit')
{
	load_fiche_titre($langs->trans("NewplaylistAbricot"));
	dol_fiche_head();
}
else
{
	$head = playlistabricot_prepare_head($object);
	$picto = 'generic';
	if($action == 'showTracks')
	{
		dol_fiche_head($head, 'tracks', $langs->trans("playlistAbricot"), 0, $picto);
	}
	else{
		dol_fiche_head($head, 'card', $langs->trans("playlistAbricot"), 0, $picto);
	}
}

$formcore = new TFormCore;
$formcore->Set_typeaff($mode);

$form = new Form($db);

$formconfirm = getFormConfirm($PDOdb, $form, $object, $action);
if (!empty($formconfirm)) echo $formconfirm;

$TBS=new TTemplateTBS();
$TBS->TBS->protect=false;
$TBS->TBS->noerr=true;

if ($mode == 'edit') echo $formcore->begin_form($_SERVER['PHP_SELF'], 'form_playlistabricot');

$linkback = '<a href="'.dol_buildpath('/playlistabricot/list_playlist.php', 1).'">' . $langs->trans("BackToList") . '</a>';

$htmlDefault = $TBS->render('tpl/card_playlist.tpl.php'
		,array() // Block
		,array(
				'object'=>$object
				,'view' => array(
					'mode' => $mode
					,'action' => 'save'
					,'urlcard' => dol_buildpath('/playlistabricot/card_playlist.php', 1)
					,'urllist' => dol_buildpath('/playlistabricot/list_playlist.php', 1)
					//,'showRef' => ($action == 'create') ? $langs->trans('Draft') : $form->showrefnav($object->generic, 'ref', $linkback, 1, 'ref', 'ref', '')
					,'showTitle' => $formcore->texte('', 'title', $object->title, 80, 255)
					,'showAuthorSelect' => $form->select_thirdparty_list($object->fk_author,'fk_author')
					,'showAuthor' => $formcore->texte('', 'author', $object->author, 80, 255)
					//,'showStatus' => $object->getLibStatut(1)
				)
				,'langs' => $langs
				,'user' => $user
				,'conf' => $conf
				//,'TplaylistAbricot' => array(
				//	'STATUS_DRAFT' => TplaylistAbricot::STATUS_DRAFT
				//	,'STATUS_VALIDATED' => TplaylistAbricot::STATUS_VALIDATED
				//	,'STATUS_REFUSED' => TplaylistAbricot::STATUS_REFUSED
				//	,'STATUS_ACCEPTED' => TplaylistAbricot::STATUS_ACCEPTED
				//)
		)
	);

$html = _liste($PDOdb, $id);

if($action == 'showTracks')
{
	print $html;
}
else
{
	print $htmlDefault;
}
		
if ($mode == 'edit') echo $formcore->end_form();

//if ($mode == 'view' && $object->getId()) $somethingshown = $form->showLinkedObjectBlock($object->generic);

llxFooter();

//liste des tracks pour la ficher playlist
function _liste(&$PDOdb, $id) {
	global $conf, $langs;
	
	$l=new TListviewTBS('listWS');
	$sql= "SELECT rowid, title, author, type, bitrate FROM llx_trackAbricot WHERE fk_playlist = ". $id;

	$html = $l->render($PDOdb, $sql, array(
			
			'link'=>array(
					'title' => '<a href="'.dol_buildpath('/playlistabricot/card_track.php', 1).'?id=@rowid@">@val@</a>',
					'author' => '<a href="'.dol_buildpath('/societe/card.php', 1).'?socid=@rowid@">@val@</a>'
			)
			,'title'=>array(
					'title'=>"Titre",
					'author'=>"Auteur",
					'type'=>"Type",
					'bitrate'=>'Bitrate',
			)
			,'hide' => array(
					'rowid'
			)
			,'liste'=>array(
					'titre'=>'Liste des '.$langs->trans('TrackWord')
					//,'image'=>img_picto('','title.png', '', 0)
					//,'picto_precedent'=>img_picto('','back.png', '', 0)
					//,'picto_suivant'=>img_picto('','next.png', '', 0)
					//,'noheader'=> (int)isset($_REQUEST['fk_soc']) | (int)isset($_REQUEST['fk_product'])
					//,'messageNothing'=>"Il n'y a aucun ".$langs->trans('WorkStation')." à afficher"
					//,'picto_search'=>img_picto('','search.png', '', 0)
			)
			
	));
	return $html;	
}