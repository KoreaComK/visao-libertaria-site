<?php

/** @var array $historicoArtigoConfig */
$historicoArtigoConfig = $historicoArtigoConfig ?? [];
$jsonFlags            = JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT;

?>
<script>
	window.VL_HISTORICO_ARTIGO = <?= json_encode($historicoArtigoConfig, $jsonFlags); ?>;
</script>
<script src="<?= site_url('public/js/colaboradores-historico-artigo.js'); ?>"></script>
