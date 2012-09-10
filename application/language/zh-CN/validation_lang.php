<?php
class validation_lang{
	const required 			= "%s 必须填写.";
	const issetcontent		= "%s 一定要有内容.";
	const valid_email		= "%s 必须是一个有效的电子邮箱地址.";
	const valid_emails 		= "%s 必须是有效的电子邮箱地址.";
	const valid_url 		= "%s 必须是有效的网址.";
	const valid_ip 			= "%s 必须是一个有效IP地址.";
	const min_length		= "%s 至少包含 %s 个字.";
	const max_length		= "%s 不能超过 %s 个字.";
	const exact_length		= "%s 必须刚好 %s 个字.";
	const alpha				= "%s 只能包含英文字母.";
	const alpha_numeric		= "%s 只能包含英文字母或数字.";
	const alpha_dash		= "%s 只能包含英文字母、数字、下划线、或破折号.";
	const numeric			= "%s 只能包含数字.";
	const is_numeric		= "%s 只能包含数字.";
	const integer			= "%s 只能包含整数.";
	const matches			= "%s 与 %s 不相符合.";
	const is_natural		= "%s 必须是自然数(非负整数).";
	const is_natural_no_zero= "%s 必须是大于零的自然数(非负整数).";
}