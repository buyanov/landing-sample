package main

import (
	"github.com/osamingo/jsonrpc/v2"
	"jrpc-server/models"
	"log"
	"net/http"
    "os"
)

func main() {

    var dsn string = os.Getenv("CLICKHOUSE_HOST")

    if dsn == "" {
        dsn = "tcp://host.docker.internal:9000"
    }

	err := models.InitClickHouse(dsn)
	if err != nil {
		log.Fatal(err)
	}

	mr := jsonrpc.NewMethodRepository()

	if err := mr.RegisterMethod("Main.Activity", ActivityHandler{}, ActivityParams{}, ActivityResult{}); err != nil {
		log.Fatalln(err)
	}

	if err := mr.RegisterMethod("Main.ActivityList", ActivityListHandler{}, ActivityListParams{}, ActivityListResult{}); err != nil {
		log.Fatalln(err)
	}

	http.Handle("/jrpc", mr)
	http.HandleFunc("/jrpc/debug", mr.ServeDebug)

	if err := http.ListenAndServe("0.0.0.0:8080", http.DefaultServeMux); err != nil {
		log.Fatalln(err)
	}
}
