<!-- Un début de <div> existe de par la fonction dol_fiche_head() -->
	<!--<input type="hidden" name="action" value="[view.action]" />-->
	<table width="100%" class="border">
		<tbody>
			<tr class="label">
				<td width="25%">[langs.transnoentities(PlaylistTitle)]</td>
				<td><input size="45" type="text" name="title" value="" /></td>
			</tr>

			<tr class="status">
				<td width="25%">[langs.transnoentities(PlaylistAuthor)]</td>
				<td><input size="45" type="text" name="author" value="" /></td>
			</tr>
		</tbody>
	</table>

</div> <!-- Fin div de la fonction dol_fiche_head() -->

[onshow;block=begin;when [view.mode]='edit']
<div class="center">
	
	<!-- '+-' est l'équivalent d'un signe '>' (TBS oblige) -->
	[onshow;block=begin;when [object.getId()]+-0]
	<input type='hidden' name='id' value='[object.getId()]' />
	<input type="submit" value="[langs.transnoentities(Save)]" class="button" />
	[onshow;block=end]
	
	[onshow;block=begin;when [object.getId()]=0]
	<input type="submit" value="[langs.transnoentities(CreatePlaylist)]" class="button" />
	<input type="hidden" name="action" value="save"/>
	[onshow;block=end]
	
	<input type="button" onclick="javascript:history.go(-1)" value="[langs.transnoentities(Cancel)]" class="button">
	
</div>
[onshow;block=end]

[onshow;block=begin;when [view.mode]!='edit']
<div class="tabsAction">
	[onshow;block=begin;when [user.rights.playlistabricot.write;noerr]=1]
		
	[onshow;block=end]
</div>
[onshow;block=end]