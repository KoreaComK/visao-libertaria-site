<?php

/** @var array $comentariosConfig */
$comentariosConfig = $comentariosConfig ?? [];
$jsonFlags         = JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT;

?>
<script>
	window.VL_COMENTARIOS = <?= json_encode($comentariosConfig, $jsonFlags); ?>;
</script>
<script src="<?= site_url('public/js/colaboradores-comentarios.js'); ?>"></script>
