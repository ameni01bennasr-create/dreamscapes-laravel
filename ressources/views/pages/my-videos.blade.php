@extends('layouts.app')

@section('title', 'Mes Vidéos')

@section('content')
<div class="main-container py-5">
    <div class="text-center mb-5">
        <h1 class="text-purple" style="font-family: 'Brush Script MT', cursive; font-size: 3rem;">Mes Vidéos Générées</h1>
        <p class="text-white">Téléchargez et visualisez vos vidéos personnalisées</p>
    </div>
    
    @if($videos->isEmpty())
    <div class="empty-state text-center py-5">
        <i class="fas fa-film fa-4x text-purple opacity-50"></i>
        <h2 class="text-white mt-3">Aucune vidéo disponible</h2>
        <p class="text-white-50">Vous n'avez pas encore de vidéos générées ou achetées.</p>
        <button class="neon-btn mt-3" onclick="window.location.href='{{ route('home') }}'">
            <i class="fas fa-shopping-cart"></i> Explorer les produits
        </button>
    </div>
    @else
    <div class="videos-container">
        @foreach($videos as $video)
        <div class="video-card">
            <div class="video-thumbnail position-relative">
                <img src="{{ $video->thumbnail ?? asset('images/video-thumb.jpg') }}" alt="{{ $video->name }}">
                <div class="video-overlay">
                    <button class="play-btn" onclick="previewVideo('{{ $video->id }}')">
                        <i class="fas fa-play"></i>
                    </button>
                </div>
            </div>
            <div class="video-info p-3">
                <h3 class="video-title">{{ $video->name }}</h3>
                <div class="video-meta d-flex justify-content-between mb-2">
                    <span class="video-type">{{ $video->type ?? 'Vidéo' }}</span>
                    <span class="video-date">Acheté le {{ $video->created_at->format('d/m/Y') }}</span>
                </div>
                <p class="video-description">
                    Format: {{ $video->format }}<br>
                    Prix: {{ number_format($video->price, 2) }} €
                </p>
                <div class="video-actions d-flex gap-2">
                    <button class="action-btn download-btn" onclick="downloadVideo('{{ $video->id }}')">
                        <i class="fas fa-download"></i> Télécharger
                    </button>
                    <button class="action-btn preview-btn" onclick="previewVideo('{{ $video->id }}')">
                        <i class="fas fa-play"></i> Prévisualiser
                    </button>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @endif
</div>

<!-- Modal prévisualisation -->
<div id="video-modal" class="modal fade" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content bg-dark border-purple">
            <div class="modal-header border-purple">
                <h5 class="modal-title text-white" id="modal-video-title"></h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-0">
                <video id="modal-video-player" controls class="w-100" style="max-height: 70vh;">
                    Votre navigateur ne supporte pas la lecture de vidéos.
                </video>
            </div>
            <div class="modal-footer border-purple">
                <button class="btn btn-primary" onclick="downloadCurrentVideo()">
                    <i class="fas fa-download"></i> Télécharger
                </button>
            </div>
        </div>
    </div>
</div>

<style>
.videos-container {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
    gap: 30px;
}
.video-card {
    background: linear-gradient(145deg, rgba(196, 163, 255, 0.1), rgba(12, 2, 27, 0.8));
    border-radius: 15px;
    overflow: hidden;
    border: 1px solid rgba(196, 163, 255, 0.3);
    transition: all 0.3s;
}
.video-card:hover {
    transform: translateY(-10px);
    border-color: #C4A3FF;
    box-shadow: 0 10px 25px rgba(196, 163, 255, 0.4);
}
.video-thumbnail {
    position: relative;
    height: 200px;
    overflow: hidden;
}
.video-thumbnail img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}
.video-overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(12, 2, 27, 0.7);
    display: flex;
    align-items: center;
    justify-content: center;
    opacity: 0;
    transition: opacity 0.3s;
}
.video-card:hover .video-overlay { opacity: 1; }
.play-btn {
    background: rgba(196, 163, 255, 0.8);
    border: none;
    border-radius: 50%;
    width: 60px;
    height: 60px;
    transition: all 0.3s;
}
.play-btn:hover {
    background: #C4A3FF;
    transform: scale(1.1);
}
.play-btn i { color: #0C021B; font-size: 24px; margin-left: 5px; }
.video-title { font-size: 1.3rem; color: #F3E6FF; }
.action-btn {
    flex: 1;
    padding: 10px;
    border-radius: 8px;
    font-weight: 600;
    transition: all 0.3s;
}
.download-btn {
    background: linear-gradient(135deg, #9b59b6, #8e44ad);
    color: white;
}
.download-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(155, 89, 182, 0.4);
}
.preview-btn {
    background: rgba(196, 163, 255, 0.2);
    color: #C4A3FF;
    border: 1px solid #C4A3FF;
}
.border-purple { border-color: #C4A3FF !important; }
.text-purple { color: #C4A3FF; }
.neon-btn {
    padding: 10px 20px;
    font-size: 16px;
    color: #C4A3FF;
    background: transparent;
    border: 2px solid #C4A3FF;
    border-radius: 5px;
    cursor: pointer;
    transition: 0.3s;
}
.neon-btn:hover {
    color: #fff;
    background-color: #C4A3FF;
}
</style>
@endsection

@push('scripts')
<script>
let videosData = @json($videos);
let currentVideo = null;

function previewVideo(videoId) {
    const video = videosData.find(v => v.id == videoId);
    if (!video) return;
    
    currentVideo = video;
    const modal = new bootstrap.Modal(document.getElementById('video-modal'));
    const player = document.getElementById('modal-video-player');
    const title = document.getElementById('modal-video-title');
    
    player.src = video.video_url;
    title.textContent = video.name;
    modal.show();
    player.play();
}

function downloadVideo(videoId) {
    const video = videosData.find(v => v.id == videoId);
    if (!video) return;
    
    const link = document.createElement('a');
    link.href = video.video_url;
    link.download = video.file_name || `${video.name.replace(/[^a-z0-9]/gi, '_')}.mp4`;
    link.click();
}

function downloadCurrentVideo() {
    if (currentVideo) downloadVideo(currentVideo.id);
}
</script>
@endpush