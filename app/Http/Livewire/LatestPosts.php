<?php

namespace App\Http\Livewire;

use App\Models\Post;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Str;
use Carbon\Carbon;

use Artesaos\SEOTools\Facades\SEOTools;
use Artesaos\SEOTools\Facades\SEOMeta;

class LatestPosts extends Component
{
    use WithPagination;

    public function render()
    {

        
        // OR use single only SEOTools
        
        $title = ucfirst('Smart Finance 365 - Blog');
        SEOTools::setTitle($title);
       
        SEOTools::setDescription('Descubre las últimas noticias financieras y consejos de gestión de dinero en el blog Smart Finance 365. Mantente al día con las tendencias del mercado y mejora tu situación financiera.');
        SEOTools::opengraph()->setUrl('https://www.smart-finance365.com/');
        SEOTools::setCanonical('https://www.smart-finance365.com/');
        SEOTools::opengraph()->addProperty('type', 'articles');
       
        SEOTools::jsonLd()->addImage('https://www.smart-finance365.com/img/logo.png');
        SEOMeta::addKeyword('Smart Finance 365, Blog');
       
        // OR use single only SEOTools

        $latestPosts = Post::latest()->paginate(20);

        return view('livewire.latest-posts', ['latestPosts' => $latestPosts])
            ->layout('layouts.guest-template'); // Establecer el layout para esta vista Livewire
    }
}
