<?php
class db_lang{
	const db_invalid_connection_str = '用您给出的连接字符串无法获得数据库设置.';
	const db_unable_to_connect = '使用给出的设置无法连接数据库.';
	const db_unable_to_select = '无法选择指定的数据库: %s';
	const db_unable_to_create = '无法创建指定的数据库: %s';
	const db_invalid_query = '查询无效.';
	const db_must_set_table = '查询中必须设置数据库表.';
	const db_must_set_database = '数据库配置文件中必须设置数据库名.';
	const db_must_use_set = '必须使用"set"命令更新记录.';
	const db_must_use_where = '更新记录时必须包含"where"子句.';
	const db_del_must_use_where = '删除记录时必须包含"where"或"like"子句.';
	const db_field_param_missing = '必须提供表名参数才能取得字段信息.';
	const db_unsupported_function = '您使用的数据库不支持此功能.';
	const db_transaction_failure = '事务失败: 已回滚.';
	const db_unable_to_drop = '无法删除数据库.';
	const db_unsuported_feature = '数据库平台不支持此功能.';
	const db_unsuported_compression = '不支持此文件压缩格式.';
	const db_filepath_error = '您指定的文件路径无法写入数据.';
	const db_invalid_cache_path = '缓存路径无效或不可写.';
	const db_table_name_required = '必须提供表名.';
	const db_column_name_required = '必须提供字段名.';
	const db_column_definition_required = '必须提供字段定义.';
	const db_unable_to_set_charset = '无法设置客户端连接的字符集: %s';
	const db_error_heading = '发生了一个数据库错误';
}
