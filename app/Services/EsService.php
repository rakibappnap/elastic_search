<?php

namespace App\Services;

use App\Facades\EsClient;

class EsService
{
    private $esClient;

    public function __construct(EsClient $esClient)
    {
        $this->esClient = $esClient;
    }

    public function store($request)
    {
        $params = [
            'index' => 'theme_index_'.$request->id,
            'id'    => $request->id,
            'body'  => [
                'id'    => $request->id,
                'title' => $request->title,
                'userId' => $request->userId,
                'username' => $request->username,
                'category' => $request->category,
                'link' => $request->link,
                'caption' => $request->caption,
                'type' => $request->type,
            ]
        ];
        try {
            $this->esClient::index($params);
            return true;
        } catch (\Exception $exception) {
            return $exception->getMessage();
        }
    }

    public function update($request)
    {
        $params = [
            'index' => 'theme_index_'.$request->id,
            'id'    => $request->id,
            'body'  => [
                'id'    => $request->id,
                'title' => $request->title,
                'category' => $request->category,
                'caption' => $request->caption,
                'type' => $request->type,
            ]
        ];
        try {
            $this->esClient::index($params);
            return true;
        } catch (\Exception $exception) {
            return $exception->getMessage();
        }
    }

    public function search($keyword)
    {
        $params = [
            'size' => 20,
            'index' => '',
            'body'  => [
                'query' => [
                    'multi_match' => [
                        'fields' => [
                            'title',
                            'category',
                            'caption',
                            'type'
                        ],
                        'query' => $keyword,
                        'fuzziness' => 'AUTO',
                    ]
                ]
            ]
        ];
        $hits = $this->esClient::search($params);
        //dd($hits);
        if (count($hits['hits']['hits']) > 0) {
            $resultArr = array();
            foreach ($hits['hits']['hits'] as $res) {
                array_push($resultArr, $res['_source']['id']);
            }
            return $resultArr;
        } else {
            return false;
        }
    }

    public function delete($id)
    {
        $params = [
            'index' => 'theme_index_'.$id,
            'id'    => $id
        ];
        try {
            $this->esClient::delete($params);
            return true;
        } catch (\Exception $exception) {
            return $exception->getMessage();
        }
    }
}
