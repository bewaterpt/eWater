<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Article;
use DB;

class DeleteAllArticles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'articles:nuke';

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
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        DB::statement("SET foreign_key_checks=0");
        Article::truncate();
        DB::statement("SET foreign_key_checks=1");
    }
}
