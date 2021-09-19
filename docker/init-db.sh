#!/bin/bash
set -e

clickhouse client -n <<-EOSQL
    CREATE DATABASE IF NOT EXISTS activity;
    CREATE TABLE IF NOT EXISTS activity.stats
    (
      activity_datetime DateTime,
      activity_date Date,
      page_url String,
      http_user_agent String
    )
    ENGINE = MergeTree(activity_date, (page_url, activity_date), 8192);
EOSQL
