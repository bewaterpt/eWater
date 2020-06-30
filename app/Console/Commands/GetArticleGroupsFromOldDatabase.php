<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use DB;
use App\Models\DailyReport\ArticleGroup;
use App\Models\DailyReport\Article;

class GetArticleGroupsFromOldDatabase extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'outono:getArticleGroups';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command fetches and updates any new or existing article groups for use with the daily reports functionality';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->db = DB::connection('mysql');
        $this->outonoDb = DB::connection('outono');
        $this->pdo = $this->db->getPdo();
        $this->outonoPdo = $this->outonoDb->getPdo();
        parent::__construct();

    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $articleGroups = $this->outonoDb->select('select descricao from tbGrupos');

        $this->info('Inserting ' . Sizeof($articleGroups) . ' article groups');
        $this->output->progressStart(Sizeof($articleGroups));
        foreach($articleGroups as $articleGroup) {
            $articleGroupModel = new ArticleGroup();
            $articleGroupModel->description = $articleGroup->descricao;
            $articleGroupModel->save();
            $this->output->progressAdvance();
        }
        $this->output->progressFinish();
        $this->info('Done');
        // $this->info('');

        // $articles = $this->outonoDb->select('select * from tbArtigos');

        // $this->info('Inserting ' . Sizeof($articles) . ' articles');
        // $this->output->progressStart(Sizeof($articles));
        // foreach($articles as $article) {
        //     $articleModel = new Article();
        //     // $articleModel->id = $article->cod;
        //     $articleModel->description = $article->descricao;
        //     $articleModel->unit_price = $article->precoUnitario;
        //     $articleModel->fixed = $article->fixo;
        //     $articleModel->group()->associate($article->codGrupo);
        //     $articleModel->save();
        //     $this->output->progressAdvance();
        // }
        // $this->output->progressFinish();
        // $this->info('Done');
    }
}
