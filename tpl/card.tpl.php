<!-- Un début de <div> existe de par la fonction dol_fiche_head() -->
	<input type="hidden" name="action" value="[view.action]" />
	<table width="100%" class="border">
		<tbody>
		
			<tr class="label">
				<td width="25%">[langs.transnoentities(Label)]</td>
				<td>[view.showLabel;strconv=no]</td>
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
	<input type="submit" value="[langs.transnoentities(CreateDraft)]" class="button" />
	[onshow;block=end]
	
	<input type="button" onclick="javascript:history.go(-1)" value="[langs.transnoentities(Cancel)]" class="button">
	
</div>
[onshow;block=end]

[onshow;block=begin;when [view.mode]='create']
<div class="center">
	
	<table class="border" width="100%">
		<tbody>
			<tr>
				<td class="titlefieldcreate">
					<span id="TypeName" class="fieldrequired">
						<label for="title">ToNamePlaylist</label>
					</span>
				</td>
				<td colspan="3">
					<input type="text" class="minwidth300" maxlength="128" name="title" id="title" value="" autofocus="autofocus">
				</td>
			</tr>
			<tr id="name_alias">
				<td>
					<label for="author">AuthorPlaylist</label>
				</td>
				<td colspan="3">
					<input type="text" class="minwidth300" name="author" id="name_alias_input" value="">
				</td>
			</tr>
		</tbody>
	</table>
	
	[onshow;block=begin;when [object.getId()]=0]
	<input type="submit" value="[langs.transnoentities(CreatePlaylist)]" class="button" />
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