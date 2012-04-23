$(function()
{
	$('.deadfile_detector_show_object').click(function()
	{
		file_id = $(this).attr('rel');
		$('#' + file_id).toggle();
	});

	$('.deadfile_detector_show_icon').click(function()
	{
		file_id = $(this).attr('rel');
		$('#' + file_id).toggle();
	});
});