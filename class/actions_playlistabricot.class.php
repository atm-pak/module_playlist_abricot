<?php
/* <one line to give the program's name and a brief idea of what it does.>
 * Copyright (C) 2015 ATM Consulting <support@atm-consulting.fr>
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

/**
 * \file    class/actions_playlistabricot.class.php
 * \ingroup playlistabricot
 * \brief   This file is an example hook overload class file
 *          Put some comments here
 */

/**
 * Class ActionsplaylistAbricot
 */
class ActionsplaylistAbricot
{
	/**
	 * @var array Hook results. Propagated to $hookmanager->resArray for later reuse
	 */
	public $results = array();

	/**
	 * @var string String displayed by executeHook() immediately after return
	 */
	public $resprints;

	/**
	 * @var array Errors
	 */
	public $errors = array();

	/**
	 * Constructor
	 */
	public function __construct()
	{

	}

	/**
	 * Overloading the doActions function : replacing the parent's function with the one below
	 *
	 * @param   array()         $parameters     Hook metadatas (context, etc...)
	 * @param   CommonObject    &$object        The object to process (an invoice if you are in invoice module, a propale in propale's module, etc...)
	 * @param   string          &$action        Current action (if set). Generally create or edit or null
	 * @param   HookManager     $hookmanager    Hook manager propagated to allow calling another hook
	 * @return  int                             < 0 on error, 0 on success, 1 to replace standard code
	 */
	function formObjectOptions($parameters, &$object, &$action, $hookmanager)
	{
                global $db;
		$error = 0; // Error counter
		$myvalue = 'test'; // A result value

		//print_r($parameters);
		//echo "action: " . $action;
		//print_r($object);

		if (in_array('actioncard', explode(':', $parameters['context'])))
		{
                    /*
                    //creer array key=>value de id_playlist -> playlist
                    
                    //appeler selectArray sur $form
                    $form = new Form($db);
                    //recuperer le html du form
                    //remplacer le html du extrafield par celui du form créé
                    
                    ?>
                    <script type="text/javascript">
                            $(function() {
                                $.ajax({
                                url: '../../custom/playlistabricot/ajaxCall.php',
                                type: 'POST',
                                data: {toGet: 'selectInputOfPlaylist'},
                                dataType: 'jsonp',
                                success: function(data) {
                                   console.log(data);
                                   alert('ok');
                                },
                                
                                });
                             
                                $container_td = $('input[name="options_fk_playlist"]').closest('td');
                            });
                    </script>
                    <?php
                     */
		}

		if (! $error)
		{
			$this->results = array('myreturn' => $myvalue);
			$this->resprints = 'A text to show';
			return 0; // or return 1 to replace standard code
		}
		else
		{
			$this->errors[] = 'Error message';
			return -1;
		}
	}
}