SELECT pubs.id, pubs.text
FROM `<?php echo($data['t_pubs']); ?>` pubs
WHERE pubs.text LIKE "%<?php echo($data['symbol_name']); ?>%"
<?php if($data['offset']): ?>
    AND pubs.id < <?php echo($data['offset']); ?>
<?php endif; ?>
ORDER BY pubs.id DESC
<?php if($data['limit']): ?>
    LIMIT <?php echo($data['limit']); ?>;
<?php endif; ?>

