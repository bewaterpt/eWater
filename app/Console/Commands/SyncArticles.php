<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use DB;
use App\Models\Article;
use App\Models\Connectors\OutonoArtigos as Artigos;
use App\Helpers\Helper;

class SyncArticles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'articles:sync';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->helper = new Helper();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $articlesToSync = Artigos::all()->sortBy('cod');
        Article::whereNotIn('id', $articlesToSync->pluck('cod'))->delete();

        if (Article::all()->count() === 0) {
            Article::insert($articlesToSync->map(function($article) {
                return [
                    'id' => $article->cod,
                    'designation' => $article->descricao,
                    'unit_price' => $article->precoUnitario,
                    'fixed' => $article->fixo,
                    'slug' => $this->helper->transliterate($article->descricao, 1),
                ];
            })->toArray());
        } else {
            foreach ($articlesToSync as $articleToSync) {
                $newArticle = Article::find($articleToSync->cod);
                if (!$newArticle) {
                    $newArticle = new Article();
                }

                $newArticle->designation = $articleToSync->descricao;
                $newArticle->unit_price = $articleToSync->precoUnitario;
                $newArticle->fixed = $articleToSync->fixo;
                $newArticle->slug = $this->helper->transliterate($articleToSync->descricao, 1);
                $newArticle->save;
            }
        }
    }
}
