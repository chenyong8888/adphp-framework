<?php
class imglib_lang{
	const imglib_source_image_required = "您必须在选项里指定源图片.";
	const imglib_gd_required = "此功能必须使用GD图形库.";
	const imglib_gd_required_for_props = "您的服务器必须支持GD图形库以获得此图片的属性.";
	const imglib_unsupported_imagecreate = "您的服务器的GD函数库无法处理此格式的图形文件.";
	const imglib_gif_not_supported = "由于许可限制，GIF文件通常不被支持. 您可以使用JPG或PNG格式的文件.";
	const imglib_jpg_not_supported = "不支持JPG文件.";
	const imglib_png_not_supported = "不支持PNG文件.";
	const imglib_jpg_or_png_required = "在设置中定义的图形尺寸的调整功能只能用于JPEG和PNG文件.";
	const imglib_copy_error = "替换文件发生错误.  请确认您的文件目录是可写的.";
	const imglib_rotate_unsupported = "服务器不支持图形旋转.";
	const imglib_libpath_invalid = "图形库的路径不正确.  请在设置中更正.";
	const imglib_image_process_failed = "图形处理失败.  请检查您的服务器是否支持选择的协议，且图形库的路径是否正确.";
	const imglib_rotation_angle_required = "旋转图形时必须指定旋转的角度.";
	const imglib_writing_failed_gif = "GIF 文件.";
	const imglib_invalid_path = "图形文件路径不正确.";
	const imglib_copy_failed = "图形复制程序失败.";
	const imglib_missing_font = "无法找到可用的字体.";
}
