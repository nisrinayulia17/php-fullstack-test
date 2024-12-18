<?php

require 'config.php';

function createClient($data)
{
  $koneksi = konekDatabase();

  $query = "INSERT INTO my_client (name, slug, is_project, self_capture, client_prefix, client_logo, address, phone_number, city, created_at, updated_at, deleted_at)
              VALUES ($1, $2, $3, $4, $5, $6, $7, $8, $9, NOW(), NOW(), NULL)";

  $result = pg_query_params($koneksi, $query, [
    $data['name'],
    $data['slug'],
    $data['is_project'],
    $data['self_capture'],
    $data['client_prefix'],
    $data['client_logo'],
    $data['address'],
    $data['phone_number'],
    $data['city']
  ]);

  if (!$result) {
    die("Query gagal: " . pg_last_error($koneksi));
  }

  pg_close($koneksi);

  return $result;
}

function updateClient($slug, $data)
{
  $koneksi = konekDatabase();

  $query = "UPDATE my_client SET
              name = $1,
              slug = $2,
              is_project = $3,
              self_capture = $4,
              client_prefix = $5,
              client_logo = $6,
              address = $7,
              phone_number = $8,
              city = $9,
              updated_at = NOW()
              WHERE slug = $10";

  $result = pg_query_params($koneksi, $query, [
    $data['name'],
    $data['slug'],
    $data['is_project'],
    $data['self_capture'],
    $data['client_prefix'],
    $data['client_logo'],
    $data['address'],
    $data['phone_number'],
    $data['city'],
    $slug
  ]);

  if (!$result) {
    die("Query gagal: " . pg_last_error($koneksi));
  }

  $redis  = konekRedis();
  $redis->del($slug);
  $redis->set($slug, json_encode($data));

  pg_close($koneksi);

  return $result;
}

function deleteClient($slug)
{
  $koneksi = konekDatabase();

  $query = "UPDATE my_client SET deleted_at = NOW() WHERE slug = $1";

  $result = pg_query_params($koneksi, $query, [$slug]);

  if (!$result) {
    die("Query gagal: " . pg_last_error($koneksi));
  }

  $redis  = konekRedis();
  $redis->del($slug);

  pg_close($koneksi);

  return $result;
}
