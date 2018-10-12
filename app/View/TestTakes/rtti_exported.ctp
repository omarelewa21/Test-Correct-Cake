<div class="popup-head">
	Informatie
</div>

<div class="popup-content">

	<pre>
		<?php var_dump($debug); ?>
	</pre>

	<?php if(empty($errors)):?>
		<p>
			RTTI is verstuurd. U kunt dit venster sluiten.
		</p>
	<?php else: ?>
		<?php foreach($errors as $error): ?>
			<p class="error">
				<?= $error ?>
			</p>
		<?php endforeach; ?>
			<p>
				Los eerst bovenstaande fouten op voor dat de toets geexporteerd kan worden naar RTTI
			</p>
	<?php endif; ?>
</div>

<div class="popup-footer">
    <a href="#" class="btn grey mt5 mr5 pull-right" onclick="Popup.closeLast();">
        Sluiten
    </a>
</div>