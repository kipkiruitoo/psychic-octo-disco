<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use romanzipp\Seo\Structs\Link;
use romanzipp\Seo\Structs\Meta;
use romanzipp\Seo\Structs\Meta\OpenGraph;
use romanzipp\Seo\Structs\Meta\Twitter;
use romanzipp\Seo\Structs\Script;

class AddSeoDefaults
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        seo()->charset();
        seo()->viewport();

        seo()->title('Tea Movies');
        seo()->description('Watch full Movies and TV at anytime. On Tea Movies Watch or Download HD Movies and TV, no sign up. Tea Movies, Watch Full Movies Online; Tea Movies');

        // seo()->csrfToken();

        seo()->addMany([

            Meta::make()->name('copyright')->content('Tea Movies'),

            Meta::make()->name('mobile-web-app-capable')->content('yes'),
            Meta::make()->name('theme-color')->content('#f03a17'),

            Link::make()->rel('icon')->href('/logo.png'),

            OpenGraph::make()->property('title')->content('Tea Movies'),
            OpenGraph::make()->property('site_name')->content('Tea Movies'),
            OpenGraph::make()->property('locale')->content('en'),

            Twitter::make()->name('card')->content('summary_large_image'),
            // Twitter::make()->name('site')->content('@romanzipp'),
            // Twitter::make()->name('creator')->content('@tea'),
            Twitter::make()->name('image')->content('/logo.png', false)

        ]);

        seo('body')->add(
            Script::make()->attr('src', '/js/app.js')
        );
        
        return $next($request);
    }
}
