<div class="boxy-title">
	<h3>Sistema de actualización</h3>
</div>
<div id="res" class="boxy-content">
	{if $tsSave}<div class="empty empty-success">Tu sistema ha sido actualizado correctamente.</div>{/if}
	{if $tsUpdated == ''}
		<div class="empty empty-success">Tu sistema ha sido actualizado correctamente.</div>
	{else}
	
		{if $tsAct == ''}
			{if is_array($tsLastCommit)}
				<span class="d-block mb-3">{$tsLastCommit.message}</span>
			{else}
				<small class="text-center d-block mb-3">
					SHA <strong>{$tsLastCommit}</strong> Último commit realizado
				</small>
				
				<div class="border p-2 rounded mb-3 d-block d-lg-flex justify-content-between align-items-center">
					<div>
						<span>Autor del commit <strong>{$tsLastCommitFiles.author.name}</strong></span>
						<time class="d-block fst-italic">{$tsLastCommitFiles.author.date|date_format:"d.m.Y H:i"}</time>
					</div>
					<div class="d-flex justify-content-end align-items-center">
						<span class="d-block text-center small" style="width:100px">
							<span class="d-block"><span class="fs-5">{$tsLastCommitFiles.stats.total|human}</span></span> Total
						</span>
						<span class="d-block text-center small" style="width:100px">
							<span class="d-block"><span class="fs-5">{$tsLastCommitFiles.stats.additions|human}</span></span> Adicionales
						</span>
						<span class="d-block text-center small" style="width:100px">
							<span class="d-block"><span class="fs-5">{$tsLastCommitFiles.stats.deletions|human}</span></span> Removidos
						</span>
					</div>
				</div>

				{if !empty($tsFilesStatus)}
					<div class="d-flex justify-content-between align-items-center">
						<h4>Archivos</h4>
						<a href="{$tsConfig.url}/admin/actualizacion?act=actualizar" class="btn">Actualizar</a>
					</div>
					<small>El proceso de actualización tardará dependiendo de la cantidad de archivos modificados, esto puede tomar varios minutos. También existe la actualización de forma manual!</small>
					<div style="overflow-x:auto;">
						<table class="admin_table mt-3">
							<thead>
								<th>Archivos</th>
								<th>Estado</th>
								<th>Nuevas</th>
								<th>Eliminadas</th>
								<th>Cambiadas</th>
								<th>Github</th>
								<th>Raw</th>
							</thead>
							<tbody>
								{foreach $tsFilesStatus key=f item=file}
									<tr>
										<td>{$file->filename}</td>
										<td class="text-center fw-semibold">{$file->status}</td>
										<td class="text-center fw-semibold">{$file->additions}</td>
										<td class="text-center fw-semibold">{$file->deletions}</td>
										<td class="text-center fw-semibold">{$file->changes}</td>
										<td class="text-center"><a href="{$file->blob_url}" class="text-decoration-none fw-semibold" target="_blank" title="ver código en github">{uicon name="github" folder="prime" class="pe-none avatar avatar-2"}</a></td>
										<td class="text-center"><a href="{$file->raw_url}" class="text-decoration-none fw-semibold" target="_blank" title="ver código">{uicon name="code" folder="prime" class="pe-none avatar avatar-2"}</a></td>
									</tr>
								{/foreach}
							</tbody>
						</table>
					</div>
				{else}
					<div class="empty">No hay archivos que necesiten actualización.</div>
				{/if}
			{/if}
		{elseif $tsAct === 'actualizar'}
			<div class="empty">Este proceso tardará varios minutos</div>
		{/if}
	
	{/if}
</div>