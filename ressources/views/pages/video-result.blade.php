@extends('layouts.app')

@section('title', 'Votre Vidéo est Prête')

@section('content')
<div class="container py-5">
    <div class="text-center mb-4">
        <h1 class="text-white"><i class="fas fa-film"></i> Votre Vidéo est Prête!</h1>
        <p class="text-purple">Votre vidéo personnalisée a été générée avec succès</p>
    </div>
    
    <div class="card bg-white rounded-4 shadow-lg">
        <div class="card-body p-4">
            <div class="video-section">
                <h2 class="mb-3"><i class="fas fa-play-circle text-primary"></i> Votre Vidéo Générée</h2>
                <div class="ratio ratio-16x9">
                    <video id="generatedVideo" controls poster="{{ asset('images/video-poster.jpg') }}">
                        <source src="{{ $videoUrl ?? asset('videos/sample.mp4') }}" type="video/mp4">
                        Votre navigateur ne supporte pas la lecture de vidéos.
                    </video>
                </div>
                <div class="video-controls mt-3 d-flex justify-content-center gap-2">
                    <button class="btn btn-primary" id="playBtn"><i class="fas fa-play"></i> Lire</button>
                    <button class="btn btn-secondary" id="pauseBtn"><i class="fas fa-pause"></i> Pause</button>
                    <button class="btn btn-info" id="fullscreenBtn"><i class="fas fa-expand"></i> Plein écran</button>
                </div>
            </div>
            
            <div class="actions-section mt-4 d-flex justify-content-center gap-3 flex-wrap">
                <button class="btn-submit" id="addToCartBtn">
                    <i class="fas fa-cart-plus"></i> Ajouter au Panier
                </button>
                <button class="btn-submit" id="newVideoBtn">
                    <i class="fas fa-plus-circle"></i> Créer une Nouvelle Vidéo
                </button>
                <button class="btn-submit btn-end" id="endBtn">
                    <i class="fas fa-home"></i> Accueil
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal choix format -->
<div id="format-modal" class="modal fade" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content bg-dark text-white border-purple">
            <div class="modal-header border-purple">
                <h5 class="modal-title">Choisissez votre format</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="format-option text-center p-3 rounded-3" data-format="Journal" data-price="19.99">
                            <i class="fas fa-book fa-3x text-purple"></i>
                            <h4 class="mt-2">Journal</h4>
                            <p class="price text-warning">19.99 €</p>
                            <small>Format PDF interactif</small>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="format-option text-center p-3 rounded-3" data-format="Vidéo" data-price="29.99">
                            <i class="fas fa-film fa-3x text-purple"></i>
                            <h4 class="mt-2">Vidéo</h4>
                            <p class="price text-warning">29.99 €</p>
                            <small>Vidéo HD avec effets</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.btn-submit {
    background: linear-gradient(to right, #4a00e0, #8e2de2);
    color: white;
    border: none;
    padding: 12px 30px;
    font-size: 1rem;
    font-weight: 600;
    border-radius: 50px;
    cursor: pointer;
    transition: transform 0.3s;
}
.btn-submit:hover { transform: translateY(-3px); }
.btn-end { background: linear-gradient(to right, #9b65bb, #c492e0); }
.border-purple { border-color: #C4A3FF !important; }
.text-purple { color: #C4A3FF; }
.format-option {
    border: 2px solid #C4A3FF;
    cursor: pointer;
    transition: all 0.3s;
}
.format-option:hover {
    background-color: rgba(196, 163, 255, 0.2);
    transform: translateY(-5px);
}
.price { font-size: 1.5rem; font-weight: bold; }
.text-purple { color: #c492e0; }
</style>
@endsection

@push('scripts')
<script>
const videoPlayer = document.getElementById('generatedVideo');
document.getElementById('playBtn').addEventListener('click', () => videoPlayer.play());
document.getElementById('pauseBtn').addEventListener('click', () => videoPlayer.pause());
document.getElementById('fullscreenBtn').addEventListener('click', () => videoPlayer.requestFullscreen());

let selectedFormat = null;

document.getElementById('addToCartBtn').addEventListener('click', () => {
    const modal = new bootstrap.Modal(document.getElementById('format-modal'));
    modal.show();
});

document.querySelectorAll('.format-option').forEach(option => {
    option.addEventListener('click', function() {
        const format = this.dataset.format;
        const price = this.dataset.price;
        
        fetch('{{ route("cart.add") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                name: '{{ $articleName ?? "Vidéo Personnalisée" }}',
                type: '{{ $articleType ?? "Vidéo" }}',
                format: format,
                price: parseFloat(price)
            })
        }).then(response => response.json())
          .then(data => {
              if (data.success) {
                  bootstrap.Modal.getInstance(document.getElementById('format-modal')).hide();
                  alert('Article ajouté au panier !');
                  location.reload();
              }
          });
    });
});

document.getElementById('newVideoBtn').addEventListener('click', () => {
    const formPage = '{{ session("last_form", route("form.cultural")) }}';
    window.location.href = formPage;
});

document.getElementById('endBtn').addEventListener('click', () => {
    window.location.href = '{{ route("home") }}';
});
</script>
@endpush