/*
@*************************************************************************@
@ Software author: JOOJ Team (JOOJ.us)							  @
@ Author_url 1: https://jooj.us                       @
@ Author_url 2: http://jooj.us/twitter-clone                      @
@ Author E-mail: sales@jooj.us                                   @
@*************************************************************************@
@ JOOJ Talk - The Ultimate Modern Social Media Sharing Platform           @
@ Copyright (c) 2020 - 2021 JOOJ Talk. All rights reserved.               @
@*************************************************************************@
 */

SELECT posts.`id` as offset_id, posts.`publication_id`, posts.`type`, posts.`symbol_id`, posts.`comment_on`, posts.`time`
FROM `<?php echo($data['t_posts']); ?>` posts
INNER JOIN `<?php echo($data['t_pubs']); ?>` pubs ON posts.`publication_id` = pubs.`id`
WHERE posts.`symbol_id` = <?php echo($data['symbol_id']); ?>

AND CAST(posts.`time` AS UNSIGNED) >= UNIX_TIMESTAMP(NOW() - INTERVAL 1 DAY)

ORDER BY pubs.`replys_count` DESC, pubs.`reposts_count` DESC, pubs.`likes_count` DESC

<?php if(is_posnum($data['limit'])): ?>
	LIMIT <?php echo($data['limit']); ?>

	<?php if(not_empty($data['offset'])): ?>
		OFFSET <?php echo($data['offset']); ?>
	<?php endif; ?>

<?php endif; ?>