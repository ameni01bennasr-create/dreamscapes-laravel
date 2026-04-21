<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\UserVideo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PageController extends Controller
{
    public function welcome()
    {
        $products = Product::where('is_active', true)->get();
        return view('pages.welcome', compact('products'));
    }
    
    public function home()
    {
        $products = Product::where('is_active', true)->get();
        return view('pages.home', compact('products'));
    }
    
    public function formulaireCultural()
    {
        session(['last_form' => route('form.cultural')]);
        return view('pages.forms.cultural');
    }
    
    public function formulaireMemory()
    {
        session(['last_form' => route('form.memory')]);
        return view('pages.forms.memory');
    }
    
    public function formulaireFuture()
    {
        session(['last_form' => route('form.future')]);
        return view('pages.forms.future');
    }
    
    public function videoResult(Request $request)
    {
        $formData = session('video_form_data', []);
        $articleData = json_decode(session('selected_article', '{}'), true);
        
        return view('pages.video-result', [
            'videoUrl' => asset('videos/sample.mp4'),
            'articleName' => $articleData['name'] ?? 'Vidéo Personnalisée',
            'articleType' => $articleData['type'] ?? 'Vidéo'
        ]);
    }
    
    public function myVideos()
    {
        $videos = UserVideo::where('user_id', Auth::id())->get();
        return view('pages.my-videos', compact('videos'));
    }
}