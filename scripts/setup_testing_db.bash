#!/usr/bin/env bash

if (( $# != 1 ))
then
    echo "$0 [db root pass]";
    exit 1;
fi

readonly dbRootPass=$1;

readonly dbUser='test';
readonly dbPass='password';
readonly dbName='doctrine_assert_test';

function execMysql {
    local query=$1;

    mysql -u root --password=${dbRootPass} -e "${query}";
}

function createDb {
    local dbName=$1;

    execMysql "DROP DATABASE IF EXISTS ${dbName}";
    execMysql "CREATE DATABASE ${dbName} CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci";
}

function createUser {
    local dbUser=$1;
    local dbPass=$2;
    local dbHost=$3;
    local dbName=$4;

    execMysql "DROP USER IF EXISTS '${dbUser}'@'${dbHost}'";
    execMysql "CREATE USER '${dbUser}'@'${dbHost}' IDENTIFIED BY '${dbPass}'";
    execMysql "GRANT ALL PRIVILEGES ON ${dbName} . * TO '${dbUser}'@'${dbHost}'";
    execMysql "FLUSH PRIVILEGES";
}

createDb "${dbName}";

createUser "${dbUser}" "${dbPass}" 'localhost' "${dbName}";
createUser "${dbUser}" "${dbPass}" '127.0.0.1' "${dbName}";
