

    SELECT pubs.id, pubs.text, posts.comment_on, posts.type

    FROM `<?php echo($data['t_pubs']); ?>` pubs

    LEFT JOIN `<?php echo($data['t_posts']); ?>` posts ON pubs.time=posts.time

    WHERE pubs.text LIKE "%<?php echo($data['symbol_name']); ?>%"

    AND CAST(pubs.`time` AS UNSIGNED) >= UNIX_TIMESTAMP(NOW() - INTERVAL 1 DAY)

    <?php if($data['offset']): ?>
        AND pubs.id < <?php echo($data['offset']); ?>
    <?php endif; ?>

    ORDER BY pubs.likes_count DESC, pubs.id DESC

    <?php if($data['limit']): ?>
        LIMIT <?php echo($data['limit']); ?>;
    <?php endif; ?>

