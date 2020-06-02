<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DailyReport\Article;
use App\Models\DailyReport\PendingReport;
use App\Models\DailyReport\ApprovedReport;
use Auth;

class DailyReportController extends Controller
{
    public function __contruct() {
        parent::__construct();
    }

    public function index() {
        $user = Auth::user();

        $reports = $user->pendingReports()->get();

        if ($user->roles->pluck('slug')->contains('1st_phase_approval')) {
            $reports = array_merge($reports, PendingReport::where('first_phase_approval', null));
        }

        if ($user->roles->pluck('slug')->contains('2nd_phase_approval')) {
            $reports = array_merge($reports, PendingReport::where('second_phase_approval', null));
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
