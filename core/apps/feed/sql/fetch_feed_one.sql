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

SELECT * FROM `<?php echo($data['t_pubs']); ?>` 

	WHERE `status` = "active"

	AND `id` = <?php echo($data['id']); ?>

	AND `target` = "publication"

	AND `priv_wcs` = "everyone"

