<div class="text-center mx-auto" style="width: 180px;height: 180px;">
	<img class="rounded object-fit-cover w-100 h-100 main-bg" src="{$tsGenerateNewQR}">
</div>
<input type="hidden" name="code_secret" id="code_secret" value="{$tsSecret}">
<div id="one_password_time" class="OPT__inputs">
   <input type="text" placeholder="0" inputmode="numeric" maxlength="1" />
   <input type="text" placeholder="0" inputmode="numeric" maxlength="1" />
   <input type="text" placeholder="0" inputmode="numeric" maxlength="1" />
   <input type="text" placeholder="0" inputmode="numeric" maxlength="1" />
   <input type="text" placeholder="0" inputmode="numeric" maxlength="1" />
   <input type="text" placeholder="0" inputmode="numeric" maxlength="1" />
</div>
<span role="button" class="btn btn-sm d-block text-center verify_2fa">Continuar</span>
<style>
.OPT__inputs {
	display: grid;
	column-gap: .5rem;
	grid-template-columns: repeat(6, 1fr);
	padding: 0.5rem;
	input {
		width: 100%;
		border: none;
		background-color: #CCC3;
		border-radius: var(--border-radius);
		border-bottom: 3px solid rgba(0, 0, 0, 0.5);
		text-align: center;
		font-size: 1.5rem;
		cursor: not-allowed;
		pointer-events: none;
		&:focus {
			border-bottom: 3px solid var(--main-bg);
   		outline: none;
		}
		&:nth-child(1) {
			cursor: pointer;
   		pointer-events: all;
		}
	}
}
</style>
<script src="{$tsConfig.js}/cuenta/verifiedOPT.js?{$smarty.now}"></script>