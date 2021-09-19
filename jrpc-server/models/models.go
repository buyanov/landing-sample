package models

import (
	"database/sql"
	"log"
	"time"

	_ "github.com/ClickHouse/clickhouse-go"
)

var db *sql.DB

type ActivityGroup struct {
	PageUrl string
	Time time.Time
	Hits int
}

func InitClickHouse(dsn string) error  {
	var err error

	db, err = sql.Open("clickhouse", dsn)
	if err != nil {
		panic(err)
	}
	return db.Ping()
}

func InsertNewActivity(url string, userAgent string)  {

	tx, err := db.Begin()
	if err != nil {
		return
	}

	stmt, err := tx.Prepare(`
		INSERT INTO activity.stats (
		    activity_datetime,
	        activity_date,
	        page_url,
	        http_user_agent
	    )
	    VALUES (?, ?, ?, ?)
	`)

	_, err = stmt.Exec(time.Now(), time.Now(), url, userAgent)
	if err != nil {
		return
	}

	if err := tx.Commit(); err != nil {
		log.Fatal(err)
	}
}

func AllActivityGroup() []ActivityGroup {
	rows, err := db.Query(`
	 		SELECT 
	 		    s.page_url,
	 		    count() AS hits,
	 			max(s.activity_datetime) as last_activity
	 		FROM activity.stats AS s
	 		GROUP BY s.page_url
	 	`)
	if err != nil {
		log.Fatal(err)
	}

	defer rows.Close()

	var ags []ActivityGroup

	for rows.Next() {
		var ag ActivityGroup

		err := rows.Scan(&ag.PageUrl, &ag.Hits, &ag.Time)
		if err != nil {
			log.Fatal(err)
		}

		ags = append(ags, ag)
	}
	if err = rows.Err(); err != nil {
		log.Fatal(err)
	}

	return ags
}
