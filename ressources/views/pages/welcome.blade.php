@extends('layouts.app')

@section('title', 'DreamScapes - Welcome')

@section('content')
<div id="roleSelectionModal" class="modal" onclick="closeRoleSelectionPopup(event)">
    <div class="modal-content" onclick="event.stopPropagation()">
        <h3>Are you ?</h3>
        <div class="modal-buttons">
            <button class="role-btn" onclick="redirectTo('{{ route('developer.login') }}')">Developer</button>
            <button class="role-btn" onclick="redirectTo('{{ route('login') }}')">Visitor</button>
        </div>
    </div>
</div>

<section id="welcome-section" class="section">
    <div class="left-content">
        <h1 class="signature-text">Welcome !!</h1>
        <button class="start-btn" onclick="scrollToSection('about')">Start</button>
    </div>
</section>

<section id="about" class="section">
    <div class="standard-section-content">
        <div class="left-content">
            <h2>Our Mission: The Cinematic Escape ✨</h2>
            <p style="font-size: 1.1rem; line-height: 1.6;">
                Our platform is a digital dream engine, designed to transform your desires and goals into immersive cinematic experiences.
            </p>
            <p style="font-size: 1.1rem; line-height: 1.6; margin-top: 20px;">
                Whether you are looking to relive memories, explore new virtual cultures, or visualize your future for motivation, we create the perfect, tailor-made scenario just for you.
            </p>
        </div>
        <div class="right-content">
            <div class="video-container">
                <video id="about-fast-video" muted loop autoplay 
                       src="{{ asset('videos/intro.mp4') }}" 
                       title="click to watch" 
                       onclick="openFullVideoPopup('{{ asset('videos/intro.mp4') }}')">
                </video>
            </div>
            <h2 class="video-title">Who Are You?!</h2>
        </div>
    </div>
</section>

<section id="products" class="section">
    <div class="standard-section-content">
        <h2 style="color: #F3E6FF; text-shadow: 0 0 5px #C4A3FF;">Our Products</h2>
        
        <div class="product-gallery-container hidden-on-scroll" id="productGallery">
            @foreach($products as $product)
            <div class="product-card clickable" 
                 data-article-name="{{ $product->name }} ({{ $product->type }})"
                 data-article-type="{{ $product->type }}"
                 data-price="{{ $product->price }}"
                 onclick="openSigninRequiredModal()">
                <div class="product-media-zone">
                    <video width="100%" height="100%" muted loop autoplay preload="auto" 
                           src="{{ asset($product->media_url) }}" 
                           title="{{ $product->name }} Clip">
                    </video>
                </div>
                <h3>{{ $product->name }}</h3>
                <div class="product-description-overlay">
                    <p style="font-weight: bold; margin-bottom: 10px;">{{ $product->name }} :</p>
                    <p style="font-size: 0.85rem; line-height: 1.4;">{{ $product->short_description }}</p>
                    @if($product->details)
                    <ul>
                        @foreach(explode("\n", $product->details) as $detail)
                        <li>{{ $detail }}</li>
                        @endforeach
                    </ul>
                    @endif
                    <p style="margin-top: 10px; font-size: 0.8rem; color: #C4A3FF;">Cliquez pour commander</p>
                </div>
            </div>
            @endforeach
        </div>
        
        <p style="text-align: center; margin-top: 50px; color: #F3E6FF;">
            ⏳ The Past, the Future, the Elsewhere: What scenario will you play today?
        </p>
    </div>
</section>

<section id="contact-section" class="section">
    <div class="standard-section-content">
        <h2 id="h22">Contactez-nous & Laissez Votre Empreinte Numérique 📧</h2>
        <div class="contact-form-container">
            <form class="contact-form" method="POST" action="{{ route('contact.send') }}">
                @csrf
                <input type="text" name="name" placeholder="Votre Nom Complet" required>
                <input type="email" name="email" placeholder="Votre Email Professionnel" required>
                <textarea name="message" placeholder="Décrivez votre scénario de rêve..." required></textarea>
                <button type="submit" class="neon-btn">Envoyer la Requête</button>
            </form>
            
            <div class="contact-info">
                <h3>Contact Direct</h3>
                <p><strong>Email Pro:</strong> contact@dreamscapes.com 📧</p>
                <p><strong>Instagram:</strong> @DreamScapesOff 📸</p>
                <p><strong>TikTok:</strong> @DreamScapesMagic 🎶</p>
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
function showRoleSelectionPopup() {
    document.getElementById('roleSelectionModal').style.display = 'block';
}

function closeRoleSelectionPopup(event) {
    if (event && event.target.id !== 'roleSelectionModal') return;
    document.getElementById('roleSelectionModal').style.display = 'none';
}

function redirectTo(url) {
    window.location.href = url;
}

function openSigninRequiredModal() {
    alert('Veuillez vous inscrire ou vous connecter pour commander un article.');
    showRoleSelectionPopup();
}

function scrollToSection(sectionId) {
    document.getElementById(sectionId).scrollIntoView({ behavior: 'smooth' });
}

function openFullVideoPopup(videoSrc) {
    const modal = document.getElementById('full-video-modal');
    const video = document.getElementById('full-video-player');
    if (modal && video) {
        video.src = videoSrc;
        modal.style.display = 'block';
        video.play();
    }
}
</script>
@endpush