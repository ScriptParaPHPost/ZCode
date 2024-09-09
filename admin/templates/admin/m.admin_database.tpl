<div class="boxy-title">
   <h3>Base de datos</h3>
</div>
<div id="res" class="boxy-content">
	{if $tsSave}<div class="empty empty-success">{$tsStatus}</div>{/if}
	{if $tsAct == ''}
   	<a href="{$tsConfig.url}/admin/database/backup" class="btn">Crear copia de seguridad</a>
   	<a href="{$tsConfig.url}/admin/database/lista" class="btn">Lista de backups</a>
   	<div style="overflow-x:auto;">
		   <table class="admin_table mt-3">
				<thead>
					<th class="body-bg"><input type="checkbox" class="up-checkbox" name="tables[all]" value="all"></th>
					<th>Tabla</th>
					<th>Motor</th>
					<th>Filas</th>
					<th>Tamaño</th>
					<th>Creado</th>
					<th>Actualizado</th>
					<th></th>
				</thead>
				<tbody>
					{foreach $tsTablesSQL key=t item=table}
						<tr data-id="{$table.id}">
							<td class="text-center">
								<input type="checkbox" class="up-checkbox" name="tables[{$table.name}]" value="{$table.name}">
							</td>
							<td>{$table.name} <small class="d-block">Caché <strong data-cache="{$table.id}">{if $table.cache === 0}Vacio{else}{$table.cache}{/if}</strong></small></td>
							<td>{$table.engine}</td>
							<td class="text-center">{$table.rows}</td>
							<td class="text-center">{$table.size}</td>
							<td class="text-center">{$table.create|fecha:'d/m/Y'}</td>
							<td class="text-center" data-update="{$table.id}">{$table.update|hace:true}</td>
							<td>
								<div class="drop-options text-center">
									<span role="button" class="actions mx-auto d-flex justify-content-center align-items-center" data-target="#option_{$table.name}">{uicon name="menu_vertical" class="pe-none"}</span>
									<div class="drop-box" id="option_{$table.name}">
										<span role="button" onclick="database.table_action('analyze', '{$table.name}')" title="Analizar {$table.name}">{uicon name="gauge" class="pe-none" size="1.325rem"} Analizar tabla</span>
										<span role="button" onclick="database.table_action('repair', '{$table.name}', {$table.id})" title="Reparar {$table.name}">{uicon name="nut" class="pe-none" size="1.325rem"} Reparar tabla</span>
										<span role="button" onclick="database.table_action('check', '{$table.name}')" title="Comprobar {$table.name}">{uicon name="search" class="pe-none" size="1.325rem"} Comprobar tabla</span>
										{if $table.cache != 0}
											<span role="button" data-remove="{$table.id}" onclick="database.table_action('optimize', '{$table.name}', {$table.id})" title="Limpiar {$table.name}">{uicon name="database" class="pe-none" size="1.325rem"} Vaciar caché</span>
										{/if}
									</div>
								</div>
							</td>
						</tr>
					{/foreach}
				</tbody>
			</table>
		</div>
		<h4>Solo las tablas seleccionadas</h4>
	   <div class="d-block d-lg-flex justify-content-start align-items-center column-gap-3">
	   	<span role="button" onclick="database.table_all('analyze')" class="btn d-block mb-3">Analizar</span>
	   	<span role="button" onclick="database.table_all('optimize')" class="btn d-block mb-3">Limpiar caché</span>
	   	<span role="button" onclick="database.table_all('repair')" class="btn d-block mb-3">Reparar</span>
	   	<span role="button" onclick="database.table_all('check')" class="btn d-block mb-3">Comprobar</span>
	   </div>
	{elseif $tsAct === 'backup'} 
		<h3>Crear copia de seguridad</h3>
		<p>Desde aquí podrás seleccionar para crear la copia de toda la base de datos o seleccionando algunas tablas que desees hacer el backup</p>

		<label for="todos">
			<input type="checkbox" class="up-checkbox" id="todos" name="tables[all]" value="all">
			<span>Todas las tablas</span>
		</label>
		<div class="row mb-3">
			{foreach $tsTablesSQL key=t item=table}
				<div class="col-12 col-lg-3">
					<label class="d-flex justify-content-start align-items-center column-gap-2 py-1" for="tabla_{$table.id}">
						<input type="checkbox" class="up-checkbox" id="tabla_{$table.id}" name="tables[{$table.name}]" value="{$table.name}">
						<span>{$table.name}</span>
					</label>
				</div>
			{/foreach}
		</div>
		<span role="button" onclick="database.create_backup()" class="btn">Crear backup</span>
		<a href="{$tsConfig.url}/admin/database/lista" class="btn">Lista de backups</a>
		<a href="{$tsConfig.url}/admin/database/" class="btn">Volver</a>
	{elseif $tsAct === 'lista'}
		<table class="admin_table mt-3">
			<thead>
				<th>#</th>
				<th>Nombre</th>
				<th>Tamaño</th>
				<th>Creado</th>
				<th>Acciones</th>
			</thead>
			<tbody>
				{foreach $tsBackupSQL key=t item=sql}
					<tr>
						<td>{$sql.id}</td>
						<td>{$sql.name}</td>
						<td class="text-center">{$sql.size}</td>
						<td class="text-center">{$sql.date|hace:true}</td>
						<td>
							<div class="admin_actions d-flex justify-content-center align-items-center column-gap-2">
								<a href="{$sql.file}" download="{$sql.code_name}.sql" class="text-decoration-none fw-semibold">Descargar</a>
							</div>
						</td>
					</tr>
				{foreachelse}
					<tr>
						<td colspan="5">
							<div class="empty">No hay backups realizados!</div>
						</td>
					</tr>
				{/foreach}
			</tbody>
		</table>
		<a href="{$tsConfig.url}/admin/database/backup" class="btn">Crear copia de seguridad</a>
		<a href="{$tsConfig.url}/admin/database/" class="btn">Volver</a>
	{/if}
</div>