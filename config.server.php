<?php
/**
 * NOTES : To allow certain IP address for accessing server folder, go to folder server/.htaccess and add 'Require ip' statement
 */

/**
 * [$serverConfig is variable for server configuration]
 * @var array
 */
$serverConfig = array();

$serverConfig['db.hostname'] = 'localhost';
$serverConfig['db.username'] = 'sikp';
$serverConfig['db.password'] = 'sikp';
$serverConfig['db.database_name'] = 'sikp_db';
$serverConfig['db.schema']   = 'sikp';
$serverConfig['db.port']     = 5444;
/**
 * cubrid, ibase, mssql, mysql, mysqli, oci8, odbc, pdo, postgre, sqlite, sqlite3, sqlsrv
 */
$serverConfig['db.driver'] = 'postgre';