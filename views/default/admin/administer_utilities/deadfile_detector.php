<?php

	if($dead_files = deadfile_detector_get_deadfiles()) {
		?>
		<label>
			<?php echo elgg_echo('deadfile_detector:dead_files'); ?>
		</label><br />
		<?php  
		$i = 1;
		$total_file_size = 0;
		foreach($dead_files as $reason => $file_ar) {
			?>
			<div class="float" style="width: 50%;"> 
				<h4><?php echo elgg_echo('deadfile_detector:reason:' . $reason); ?></h4>
				<ul class="deadfile_detector_file_list" style="width: 75%;">
				<?php 
				foreach($file_ar as $file) {
					?>
					<li>
						<?php
						if($reason == 'no_file') {
							?>
							<a class="deadfile_detector_show_object" rel="file_<?php echo $file['object']->getGUID(); ?>" href="javascript:void(0);"><?php echo $file['filename']; ?></a>
							<div class="hidden" id="file_<?php echo $file['object']->getGUID(); ?>">
								<?php echo elgg_view_entity($file['object']); ?>
							</div>
							<?php 
						} else {
							if(file_exists($file['filename'])) {
								if($file_handle = fopen($file['filename'], 'r')) {
									$file_content = fread($file_handle, filesize($file['filename']));
									?>
									<a class="deadfile_detector_show_icon" rel="file_icon_<?php echo $i; ?>" href="javascript:void(0);"><?php echo end(explode('/', $file['filename'])); ?></a> (<?php echo deadfile_detector_format_data_size(filesize($file['filename'])); ?>)
									<div class="hidden" id="file_icon_<?php echo $i; ?>">
										<?php echo $file['filename']; ?><br />
										<img class="deadfile_detector_view_icon" src="<?php echo elgg_get_site_url(); ?>mod/deadfile_detector/image.php?path=<?php echo urlencode($file['filename']); ?>" />
									</div>
									<?php
									 
									$i++;
									$total_file_size = ($total_file_size+filesize($file['filename']));
								}
							}
						}
						?>
					</li>
					<?php
				}
				?>
				</ul>
				<?php 
				if($reason == 'no_owner') {
					echo deadfile_detector_format_data_size($total_file_size);		
				}
				?>
			</div>
			<?php
		}
	} else {
		echo elgg_echo('deadfile_detector:nothing_found');
	}