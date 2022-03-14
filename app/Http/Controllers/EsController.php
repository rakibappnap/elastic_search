<?php

namespace App\Http\Controllers;

use App\Facades\EsClient;
use App\Models\Theme;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EsController extends Controller
{
    private $elasticsearch;

    public function __construct(EsClient $elasticsearch)
    {
        $this->elasticsearch = $elasticsearch;
    }

    public function index_entries()
    {
        $themes = Theme::with('user')->where('published', 1)
            ->orderBy('themeId', 'asc')
            ->get(['themeId', 'userId', 'title', 'link', 'caption', 'type']);
       // dd($themes);
        $count = 0;
        foreach ($themes as $theme) {
            $get_categories = DB::table('themeCategories as tc')
                ->join('categories as c', 'c.id', '=', 'tc.categoryId')
                ->where('tc.themeId', '=', $theme['themeId'])
                ->where('tc.status', '=', 1)
                ->select('c.name')
                ->get();
            $categories = $get_categories->count() > 0 ? implode(',', array_column($get_categories->toArray(), 'name')) : null;

           // dd($categories);

            if ($theme['type'] == 0) {
                $type = 'Free';
            } elseif ($theme['type'] == 1) {
                $type = 'Paid';
            } else {
                $type = 'Premium';
            }
            $params = [
                'index' => 'theme_index_'.$theme['themeId'],
                'id'    => $theme['themeId'],
                'body'  => [
                    'id'    => $theme['themeId'],
                    'title' => $theme['title'],
                    'userId' => $theme['userId'],
                    'username' => $theme->user->username,
                    'category' => $categories,
                    'link' => $theme['link'],
                    'caption' => $theme['caption'],
                    'type' => $type,
                ]
            ];
            if ($this->elasticsearch::index($params)) {
                $count++;
            }
        }
        dd($count);
    }

    public function search(Request $request)
    {
        $keyword = $request->query('keyword');
        $params = [

            'size' => 20,
            'index' => '',
            'body'  => [
                'query' => [
                    'multi_match' => [
                        'fields' => [
                            'title^5',
                            'category^5',
                            'caption'
                        ],
                        'query' => $keyword,
                        'fuzziness' => 'AUTO',
                    ]
                ]
            ]
        ];
        dd($this->elasticsearch::search($params));
    }

    public function index_all()
    {
        $themes = DB::connection('mysql2')
            ->table('themes as t')
            ->join('users as u', 'u.userId', '=', 't.userId')
            ->where('t.published', '=', 1)
            ->where('t.removed', '=', 0)
            ->where('t.isActive', '=', 1)
            ->select('t.themeId', 't.userId', 't.title', 't.caption', 't.link', 'u.username', DB::raw('CASE WHEN t.type = 0 then "Free" WHEN t.type = 1 then "Paid" ELSE "Premium" end as type'))
            ->get();
        $count = 0;
        foreach ($themes as $theme) {
            $get_categories = DB::connection('mysql2')
                ->table('themeCategories as tc')
                ->join('categories as c', 'c.id', '=', 'tc.categoryId')
                ->where('tc.themeId', '=', $theme->themeId)
                ->where('tc.status', '=', 1)
                ->select('c.name')
                ->get();
            $categories = $get_categories->count() > 0 ? implode(',', array_column($get_categories->toArray(), 'name')) : null;

            $params = [
                'index' => 'theme_index_'.$theme->themeId,
                'id'    => $theme->themeId,
                'body'  => [
                    'id'    => $theme->themeId,
                    'title' => $theme->title,
                    'userId' => $theme->userId,
                    'username' => $theme->username,
                    'category' => $categories,
                    'link' => $theme->link,
                    'caption' => $theme->caption,
                    'type' => $theme->type,
                ]
            ];
            if ($this->elasticsearch::index($params)) {
                $count++;
            }
        }
        dd($count);
    }
}
