<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DailyReport\Article;
use App\Models\DailyReport\Report;
use Auth;

class DailyReportController extends Controller
{
    public function __contruct() {
        parent::__construct();
    }

    public function index() {
        $user = Auth::user();


        $reports = $user->reports()->get()->toArray();

        if ($this->statusModel->userCanApprove(2)) {
            $reports = array_merge($reports, Report::where('current_status', 2)->get()->toArray());
        }

        if ($this->statusModel->usercanApprove(3)) {
            $reports = array_merge($reports, Report::where('current_status', 3)->get()->toArray());
        }

        return view('daily_reports.index', ['reports' => $reports]);
    }

    public function create(Request $request) {

        if($request->method() === 'POST') {

        } else {
            $articles = Article::all()->pluck('cod', 'descricao');
            return view('daily_reports.create', ['articles' => $this->helper->sortArray($articles->toArray())]);
        }

    }

    public function getArticlePrice(Request $request) {
        echo json_encode(Article::getArticleById($request->input('article_id'))->precoUnitario);
        die;
    }
}
