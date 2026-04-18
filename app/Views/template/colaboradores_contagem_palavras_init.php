<?php

/** @var array<string, mixed> $contagemPalavrasConfig */
$contagemPalavrasConfig = $contagemPalavrasConfig ?? [];
$jsonFlags            = JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT;

?>
<script>
	window.VL_CONTAGEM_PALAVRAS = <?= json_encode($contagemPalavrasConfig, $jsonFlags); ?>;
</script>
<script src="<?= site_url('public/js/colaboradores-contagem-palavras.js'); ?>"></script>
