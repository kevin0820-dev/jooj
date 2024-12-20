/*
@*************************************************************************@
@ Software author: JOOJ Team (JOOJ.us)							  @
@ Author_url 1: https://jooj.us                       @
@ Author_url 2: http://jooj.us/twitter-clone                      @
@ Author E-mail: sales@jooj.us                                    @
@*************************************************************************@
@ JOOJ Talk - The Ultimate Modern Social Media Sharing Platform           @
@ Copyright (c) 2020 - 2023 JOOJ Talk. All rights reserved.               @
@*************************************************************************@
*/

SELECT * FROM `<?php echo($data['t_pubs']); ?>` pub

INNER JOIN (SELECT `type`, comment_on, publication_id from `<?php echo($data['t_posts']); ?>`
				union all 
				SELECT `type`, comment_on, publication_id from cl_posts_symbol)  AS merged_data on pub.id = merged_data.publication_id

	WHERE pub.`status` = "active"

	AND pub.`target` = "publication"

	AND pub.`priv_wcs` = "everyone"

    AND CAST(pub.`time` AS UNSIGNED) >= UNIX_TIMESTAMP(NOW() - INTERVAL 7 DAY)

	AND pub.`likes_count` > 0

	ORDER BY pub.`likes_count` DESC, pub.`replys_count` DESC, pub.`reposts_count` DESC, pub.`time` DESC

<?php if(is_posnum($data['limit'])): ?>
	LIMIT <?php echo($data['limit']); ?>

	<?php if(not_empty($data['offset'])): ?>
		OFFSET <?php echo($data['offset']); ?>
	<?php endif; ?>

<?php endif; ?>

