<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Post;

use Artesaos\SEOTools\Facades\SEOTools;
use Artesaos\SEOTools\Facades\SEOMeta;

use Carbon\Carbon;

class ShowPost extends Component
{
    public $postId;
    public $post;

    public function mount($postId)
    {
        $this->postId = $postId;
        $this->post = Post::where('post_title_slug', $postId)->firstOrFail();
    }

    public function render()
    {
          // OR use single only SEOTools
        
        $title = ucfirst($this->post->post_title);
        SEOTools::setTitle($title);
       
        SEOTools::setDescription($this->post->meta_description);
        SEOTools::opengraph()->setUrl('https://www.smart-finance365.com/');
        SEOTools::setCanonical('https://www.smart-finance365.com/');
        SEOTools::opengraph()->addProperty('type', 'articles');
       
        SEOTools::jsonLd()->addImage('https://www.smart-finance365.com/img/logo.png');
        SEOMeta::addKeyword($this->post->meta_keywords);
        SEOMeta::addMeta('article:published_time', Carbon::parse($this->post->created_at)->format('F d, Y'), 'property');

        // OR use single only SEOTools

        return view('livewire.show-post')
            ->layout('layouts.guest-template'); // Establecer el layout para esta vista Livewire
    }
}
