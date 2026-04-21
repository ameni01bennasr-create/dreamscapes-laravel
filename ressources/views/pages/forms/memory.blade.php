@extends('layouts.app')

@section('title', 'Générateur de Vidéo - Memory Journey')

@section('content')
<div class="container py-5">
    <div class="text-center mb-4">
        <h1 class="text-white"><i class="fas fa-video"></i> Générateur de Vidéo - Memory Journey</h1>
        <p class="text-purple">Revivez vos souvenirs à travers une expérience cinématique</p>
    </div>
    
    <div class="card bg-white rounded-4 shadow-lg">
        <div class="card-body p-4">
            <form id="videoForm" method="POST" action="{{ route('video.generate') }}">
                @csrf
                <input type="hidden" name="type" value="memory">
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="langue" class="form-label fw-bold">
                            <i class="fas fa-language text-primary"></i> Langue
                        </label>
                        <select id="langue" name="langue" class="form-select" required>
                            <option value="" disabled selected>Sélectionnez une langue</option>
                            <option value="fr">Français</option>
                            <option value="en">Anglais</option>
                            <option value="es">Espagnol</option>
                        </select>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label for="pays" class="form-label fw-bold">
                            <i class="fas fa-globe-europe text-primary"></i> Pays / Région
                        </label>
                        <select id="pays" name="pays" class="form-select">
                            <option value="" disabled selected>Sélectionnez un pays</option>
                            <option value="france">France</option>
                            <option value="japon">Japon</option>
                            <option value="usa">États-Unis</option>
                        </select>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="duree" class="form-label fw-bold">
                            <i class="fas fa-clock text-primary"></i> Durée (secondes)
                        </label>
                        <input type="range" id="duree" name="duree" class="form-range" min="15" max="300" value="60">
                        <span id="dureeValue" class="text-primary fw-bold">60 secondes</span>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label for="theme" class="form-label fw-bold">
                            <i class="fas fa-palette text-primary"></i> Thème
                        </label>
                        <select id="theme" name="theme" class="form-select" required>
                            <option value="" disabled selected>Sélectionnez un thème</option>
                            <option value="aventure">Aventure</option>
                            <option value="romance">Romance</option>
                            <option value="historique">Historique</option>
                        </select>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="style" class="form-label fw-bold">
                            <i class="fas fa-eye text-primary"></i> Style visuel
                        </label>
                        <select id="style" name="style" class="form-select" required>
                            <option value="" disabled selected>Sélectionnez un style</option>
                            <option value="realiste">Réaliste</option>
                            <option value="retro">Rétro / Vintage</option>
                            <option value="noir-blanc">Noir et blanc</option>
                        </select>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">
                            <i class="fas fa-images text-primary"></i> Photos à inclure
                        </label>
                        <div class="upload-area" id="photoUploadArea">
                            <i class="fas fa-cloud-upload-alt fa-3x text-primary"></i>
                            <h4 class="mt-2">Glissez-déposez vos photos</h4>
                            <input type="file" id="photos" name="photos[]" accept="image/*" multiple style="display:none">
                            <div class="file-info text-muted" id="photoInfo">Aucun fichier</div>
                        </div>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">
                            <i class="fas fa-film text-primary"></i> Vidéos à inclure
                        </label>
                        <div class="upload-area" id="videoUploadArea">
                            <i class="fas fa-file-video fa-3x text-primary"></i>
                            <h4 class="mt-2">Glissez-déposez vos vidéos</h4>
                            <input type="file" id="videos" name="videos[]" accept="video/*" multiple style="display:none">
                            <div class="file-info text-muted" id="videoInfo">Aucun fichier</div>
                        </div>
                    </div>
                </div>
                
                <div class="mb-3">
                    <label for="scenario" class="form-label fw-bold">
                        <i class="fas fa-scroll text-primary"></i> Description du souvenir
                    </label>
                    <textarea id="scenario" name="scenario" class="form-control" rows="5" 
                              placeholder="Décrivez le souvenir que vous souhaitez revivre..." required></textarea>
                </div>
                
                <div class="text-center mt-4">
                    <button type="submit" class="btn-submit">
                        <i class="fas fa-magic"></i> Générer la vidéo
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
.btn-submit {
    background: linear-gradient(to right, #4a00e0, #8e2de2);
    color: white;
    border: none;
    padding: 15px 40px;
    font-size: 1.2rem;
    font-weight: 600;
    border-radius: 50px;
    cursor: pointer;
    transition: transform 0.3s;
}
.btn-submit:hover { transform: translateY(-3px); }
.upload-area {
    border: 3px dashed #8e2de2;
    border-radius: 10px;
    padding: 30px;
    text-align: center;
    cursor: pointer;
    transition: all 0.3s;
    background-color: rgba(142, 45, 226, 0.05);
}
.upload-area:hover {
    background-color: rgba(142, 45, 226, 0.1);
    transform: translateY(-5px);
}
.text-purple { color: #c492e0; }
</style>
@endsection

@push('scripts')
<script>
const dureeSlider = document.getElementById('duree');
const dureeValue = document.getElementById('dureeValue');
dureeSlider.addEventListener('input', function() {
    dureeValue.textContent = this.value + ' secondes';
});

const photoArea = document.getElementById('photoUploadArea');
const photoInput = document.getElementById('photos');
const photoInfo = document.getElementById('photoInfo');
photoArea.addEventListener('click', () => photoInput.click());
photoInput.addEventListener('change', () => {
    photoInfo.textContent = photoInput.files.length + ' fichier(s)';
    photoInfo.style.color = '#4a00e0';
});

const videoArea = document.getElementById('videoUploadArea');
const videoInput = document.getElementById('videos');
const videoInfo = document.getElementById('videoInfo');
videoArea.addEventListener('click', () => videoInput.click());
videoInput.addEventListener('change', () => {
    videoInfo.textContent = videoInput.files.length + ' fichier(s)';
    videoInfo.style.color = '#4a00e0';
});
</script>
@endpush