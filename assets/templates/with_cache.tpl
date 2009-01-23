<p><?= time() ?></p>
<? if(!$cache->has('time', 60)) { ?>
    <? $cache->start() ?>
    <p><?= time() ?></p>
    <? $cache->saveAndPrint('time') ?>
<? } else { ?>
    <?= $cache->get('time') ?>
<? } ?>
