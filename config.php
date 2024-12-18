<?php

function konekDatabase()
{
  $host = "localhost";
  $port = "5432";
  $dbname = "db_client";
  $username = "postgres";
  $password = "merahputih";

  $koneksi = pg_connect("host=$host port=$port dbname=$dbname user=$username password=$password");

  if (!$koneksi) {
    die("Koneksi gagal: " . pg_last_error());
  }

  return $koneksi;
}

function konekRedis()
{
  $redis = new Redis();
  $redis->connect('127.0.0.1', 6379);
  return $redis;
}

function s3Config() {
  $sdk = new Sdk ([
    'region' => 'ap-southeast-1',
    'version' => 'latest',
    'credentials' => [
      'key' => '',
      'secret' => ''
    ]
  ]);

  return $sdk->createClient('s3');
}
 
?>