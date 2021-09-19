package main

import (
	"context"
	"github.com/goccy/go-json"
	"github.com/osamingo/jsonrpc/v2"
	"jrpc-server/models"
)

type (
	ActivityHandler struct {}
	ActivityParams struct {
		Url string `json:"url"`
		UserAgent string `json:"user_agent"`
	}
	ActivityResult struct {
		Message string `json:"message"`
	}

	ActivityListHandler struct {}
	ActivityListParams struct {}
	ActivityListResult struct {
		Message []models.ActivityGroup `json:"message"`
	}
)

func (h ActivityHandler) ServeJSONRPC(c context.Context, params *json.RawMessage) (interface{}, *jsonrpc.Error) {

	var p ActivityParams
	if err := jsonrpc.Unmarshal(params, &p); err != nil {
		return nil, err
	}

	models.InsertNewActivity(p.Url, p.UserAgent)

	return ActivityResult{
		Message: "ok",
	}, nil
}

func (h ActivityListHandler) ServeJSONRPC(c context.Context, params *json.RawMessage) (interface{}, *jsonrpc.Error) {

	p := models.AllActivityGroup()

	return ActivityListResult{
		Message: p,
	}, nil
}