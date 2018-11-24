<?php
	include_once 'upload.php';

	$image	=	new Upload(
		array(
			'file' 			=> 'image',
			'allowed_size'	=>	1024 * 1024 * 1024
		)
	);

	echo $image->process();
