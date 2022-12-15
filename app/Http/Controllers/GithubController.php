<?php

namespace App\Http\Controllers;

use App\Models\Stats;

class GithubController extends Controller
{

    /**
     * Display a listing of the resource.
     * Listando repositórios
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $gitHubClient = new \GithubClient();
        if(!$gitHubClient->isAuthentiqued()){
            return redirect()->route('authentication');
        }

        $repositories = $gitHubClient->repositories(session()->get('userData')->username);

        return view('index', ['repositories'=>$repositories]);
    }

    /**
     * Display the specified resource.
     * Exibe as datas e quantidades de commits de
     * acordo com o respositório informado como parametro
     *
     * @param  string  $repository
     * @return \Illuminate\Http\Response
     */
    public function show($repository)
    {
        $gitHubClient = new \GithubClient();
        if(!$gitHubClient->isAuthentiqued()){
            return redirect()->route('authentication');
        }

        $data = [];
        $dataChart = [];

        $dateSince = date('Y-m-d\T00:00:00\Z', strtotime('-90 days'));
        $dateUntil = date('Y-m-d\T23:59:59\Z');

        $commits = $gitHubClient->commits(session()
                                ->get('userData')->username, $repository, [
                                    'since'=>$dateSince,
                                    'until'=>$dateUntil
                                ]);


        if(!is_null($commits)) {
            krsort($commits);

            foreach ($commits as $commit) {
                $date = date('d/m/Y', strtotime($commit['commit']['committer']['date']));
                $data[$date] = isset($data[$date]) ? $data[$date] + 1 : 1;

                $stats = Stats::where([
                    'repository' => $repository,
                    'date' => date('Y-m-d', strtotime($commit['commit']['committer']['date']))
                ])->first();

                if ($stats) {
                    Stats::where('id', $stats->id)->first()->fill([
                        'quantity' => $data[$date]
                    ])->save();
                } else {
                    Stats::create([
                        'repository' => $repository,
                        'date' => date('Y-m-d', strtotime($commit['commit']['committer']['date'])),
                        'quantity' => $data[$date],
                    ]);
                }
            }

            foreach ($data as $k => $v) {
                $dataChart[] = ['x' => $k, 'y' => $v, 'repo' => $repository];
            }

            return view('show', ['repository'=>$repository, 'dataChart'=>json_encode($dataChart)]);

        }

        return view('show', ['repository'=>$repository]);
    }

    /**
     * Exibi dados mais detalhados de commits na data do repositório.
     *
     * @param $repository
     * @param $date
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function showDataCommit($repository, $date)
    {
        $gitHubClient = new \GithubClient();
        if(!$gitHubClient->isAuthentiqued()){
            return redirect()->route('authentication');
        }

        $dateSince = date('Y-m-d\T00:00:00\Z', strtotime($date));
        $dateUntil = date('Y-m-d\T23:59:59\Z', strtotime($date));

        $commits = $gitHubClient->commits(session()
            ->get('userData')->username, $repository, [
            'since'=>$dateSince,
            'until'=>$dateUntil
        ]);

        krsort($commits);

        foreach ($commits as $k => $commit){
            $commits[$k]['data'] = $gitHubClient->commit(session()->get('userData')->username, $repository, $commit['sha']);
        }

        return view('_data-commit', ['commits'=>$commits]);
    }


}
