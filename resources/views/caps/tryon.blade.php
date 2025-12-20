@extends('layouts.app')

@section('title', 'AI Cap Try-On')

@section('content')
<div class="container py-4">
    @if($selectedProduct)
        <div class="alert alert-info mb-4">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <i class="bi bi-info-circle me-2"></i>
                    <strong>Now trying: {{ $selectedProduct->name }}</strong>
                    @if($selectedProduct->brand)
                        <span class="badge bg-primary ms-2">{{ $selectedProduct->brand }}</span>
                    @endif
                </div>
                <a href="/products/{{ $selectedProduct->id }}" class="btn btn-sm btn-outline-primary">
                    <i class="bi bi-arrow-left"></i> Back to Product
                </a>
            </div>
        </div>
    @endif
    
    <div class="text-center mb-4">
        <h2 class="fw-bold">AI Cap Try-On Experience</h2>
        <p class="text-muted">Try different cap styles, brands, and colors in real-time using your camera</p>
    </div>
    
    <div class="row">
        <div class="col-lg-8 mb-4">
            <div class="card shadow-sm">
                <div class="card-body p-0">
                    <div class="position-relative" style="max-width:100%;">
                        <video id="video" autoplay playsinline style="width:100%;border-radius:6px 6px 0 0;background:#000;min-height:400px"></video>
                        <canvas id="overlay" style="position:absolute;left:0;top:0;width:100%;height:100%;pointer-events:none"></canvas>
                    </div>
                    <div class="p-3 bg-light">
                        <div id="camNotice" class="alert alert-info mb-2 p-2" style="font-size:14px;display:none"></div>
                        <div id="camError" class="alert alert-warning mb-2 p-2" style="font-size:14px;display:none"></div>
                        <div class="d-flex gap-2">
                            <button id="takePhoto" class="btn btn-primary flex-grow-1">
                                <i class="bi bi-camera"></i> Capture Photo
                            </button>
                            <button id="toggleCam" class="btn btn-secondary">
                                <i class="bi bi-arrow-repeat"></i> Flip Camera
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <div class="card shadow-sm mb-3">
                <div class="card-header bg-info text-white">
                    <h6 class="mb-0"><i class="bi bi-palette"></i> Skin Tone Analysis</h6>
                </div>
                <div class="card-body p-3">
                    <div id="skinToneResult" class="text-center">
                        <div class="spinner-border spinner-border-sm" role="status">
                            <span class="visually-hidden">Analyzing...</span>
                        </div>
                        <p class="mt-2 mb-0">Analyzing skin tone...</p>
                    </div>
                    <div id="colorSuggestions" class="mt-3" style="display:none">
                        <h6>Recommended Colors:</h6>
                        <div id="suggestedColors" class="d-flex flex-wrap gap-2"></div>
                    </div>
                </div>
            </div>
            
            <div class="card shadow-sm">
                <div class="card-header bg-success text-white">
                    <h6 class="mb-0"><i class="bi bi-stars"></i> AI Recommendations</h6>
                </div>
                <div class="card-body p-2" style="max-height:400px;overflow-y:auto">
                    <div id="recommendations" class="list-group list-group-flush"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-4">
        <div class="card shadow-sm">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-grid"></i> Browse All Caps</h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <input type="text" id="capSearch" class="form-control" placeholder="Search caps by name, brand, or color...">
                </div>
                <div class="row g-3" id="capsGrid">
                    @foreach($caps as $c)
                        <div class="col-6 col-md-3 col-lg-2 cap-item" data-name="{{ strtolower($c->name) }}" data-brand="{{ strtolower($c->brand ?? '') }}" data-color="{{ strtolower($c->color ?? '') }}">
                            <div class="card h-100 cap-card" data-cap="{{ basename($c->image_url) }}" data-id="{{ $c->id }}" style="cursor:pointer">
                                <img src="{{ $c->image_url }}" class="card-img-top" style="height:100px;object-fit:contain;padding:10px" />
                                <div class="card-body p-2">
                                    <p class="mb-1" style="font-size:12px;font-weight:600">{{ $c->name }}</p>
                                    @if($c->brand)
                                        <p class="mb-1 text-muted" style="font-size:11px">{{ $c->brand }}</p>
                                    @endif
                                    <p class="mb-0 text-primary fw-bold" style="font-size:13px">₹{{ $c->price }}</p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                
                <!-- Pagination -->
                <div class="mt-4 d-flex justify-content-center">
                    {{ $caps->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/@mediapipe/face_mesh/face_mesh.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@mediapipe/camera_utils/camera_utils.js"></script>
<script>
// Improved MediaPipe face mesh-based cap overlay with smoothing, rotation and HiDPI canvas
const video = document.getElementById('video');
const overlay = document.getElementById('overlay');
const ctx = overlay.getContext('2d');
const camNotice = document.getElementById('camNotice');
const camError = document.getElementById('camError');
let camera = null;
let useFront = true;
let currentCapSvgText = null;

function setNotice(text) {
    camNotice.style.display = text ? 'block' : 'none';
    camNotice.textContent = text || '';
}
function setError(text) {
    camError.style.display = text ? 'block' : 'none';
    camError.textContent = text || '';
}

function resizeCanvas() {
    const dpr = window.devicePixelRatio || 1;
    const vw = video.videoWidth || video.clientWidth;
    const vh = video.videoHeight || video.clientHeight;
    overlay.width = Math.max(1, Math.round(vw * dpr));
    overlay.height = Math.max(1, Math.round(vh * dpr));
    overlay.style.width = (video.clientWidth || vw) + 'px';
    overlay.style.height = (video.clientHeight || vh) + 'px';
    ctx.setTransform(dpr,0,0,dpr,0,0);
}

async function loadCapSvg(name, color) {
    const res = await fetch('/images/caps/' + name);
    let text = await res.text();
    text = text.replace(/#COLOR#/g, color);
    currentCapSvgText = text;
    const blob = new Blob([text], {type: 'image/svg+xml'});
    const url = URL.createObjectURL(blob);
    const img = new Image();
    img.src = url;
    await new Promise(r => img.onload = r);
    URL.revokeObjectURL(url);
    return img;
}

let capImage = null;
let currentCapFile = 'cap1.svg';
let currentColor = '#1a73e8';
let skinToneDetected = false;
let detectedSkinTone = null;

async function updateCapImage(capFile, color) {
    if (capFile) currentCapFile = capFile;
    if (color) currentColor = color;
    try {
        capImage = await loadCapSvg(currentCapFile, currentColor);
    } catch (e) {
        console.error('Failed to load cap image', e);
        setError('Failed to load cap asset.');
    }
}

// Load first cap from the list
@if($caps->count() > 0)
const firstCapFile = '{{ basename($caps->first()->image_url) }}';
updateCapImage(firstCapFile);
@else
updateCapImage();
@endif

const faceMesh = new FaceMesh({ locateFile: (file) => `https://cdn.jsdelivr.net/npm/@mediapipe/face_mesh/${file}` });
faceMesh.setOptions({ maxNumFaces: 1, refineLandmarks: true, minDetectionConfidence: 0.5, minTrackingConfidence: 0.5 });

// smoothing state
const smooth = { x:0, y:0, w:0, rot:0, initialized:false };
const alpha = 0.6; // smoothing factor: closer to 1 = smoother/slower

function analyzeSkinTone(landmarks, vw, vh) {
    if (skinToneDetected) return;
    
    // Sample skin color from cheek area
    const cheekLeft = landmarks[116]; // Left cheek
    const cheekRight = landmarks[345]; // Right cheek
    const forehead = landmarks[10]; // Forehead
    
    if (!cheekLeft || !cheekRight || !forehead) return;
    
    // Create temporary canvas to sample pixels
    const tempCanvas = document.createElement('canvas');
    tempCanvas.width = vw;
    tempCanvas.height = vh;
    const tempCtx = tempCanvas.getContext('2d');
    tempCtx.drawImage(video, 0, 0, vw, vh);
    
    // Sample multiple points for better accuracy
    const samplePoints = [
        {x: cheekLeft.x * vw, y: cheekLeft.y * vh},
        {x: cheekRight.x * vw, y: cheekRight.y * vh},
        {x: forehead.x * vw, y: forehead.y * vh}
    ];
    
    let totalR = 0, totalG = 0, totalB = 0;
    let validSamples = 0;
    
    samplePoints.forEach(point => {
        const imageData = tempCtx.getImageData(point.x, point.y, 1, 1);
        const [r, g, b] = imageData.data;
        if (r > 50 && g > 50 && b > 50) { // Avoid dark pixels
            totalR += r;
            totalG += g;
            totalB += b;
            validSamples++;
        }
    });
    
    if (validSamples > 0) {
        const avgR = totalR / validSamples;
        const avgG = totalG / validSamples;
        const avgB = totalB / validSamples;
        
        detectedSkinTone = classifySkinTone(avgR, avgG, avgB);
        displaySkinToneResults(detectedSkinTone, avgR, avgG, avgB);
        skinToneDetected = true;
    }
}

function classifySkinTone(r, g, b) {
    const luminance = 0.299 * r + 0.587 * g + 0.114 * b;
    const yellowness = (r + g) / 2 - b;
    
    if (luminance > 200) return 'very-light';
    if (luminance > 170) return 'light';
    if (luminance > 130) return 'medium-light';
    if (luminance > 100) return 'medium';
    if (luminance > 70) return 'medium-dark';
    return 'dark';
}

function getSuggestedColors(skinTone) {
    const colorMap = {
        'very-light': ['#000000', '#2C3E50', '#8B4513', '#800080', '#006400'],
        'light': ['#000000', '#2C3E50', '#8B0000', '#4B0082', '#228B22'],
        'medium-light': ['#FFFFFF', '#000000', '#FF6347', '#4169E1', '#32CD32'],
        'medium': ['#FFFFFF', '#FFD700', '#FF4500', '#1E90FF', '#00CED1'],
        'medium-dark': ['#FFFFFF', '#FFD700', '#FF69B4', '#00BFFF', '#98FB98'],
        'dark': ['#FFFFFF', '#FFD700', '#FF1493', '#00FFFF', '#ADFF2F']
    };
    return colorMap[skinTone] || ['#000000', '#FFFFFF', '#FF0000', '#0000FF', '#00FF00'];
}

function displaySkinToneResults(skinTone, r, g, b) {
    const resultDiv = document.getElementById('skinToneResult');
    const suggestionsDiv = document.getElementById('colorSuggestions');
    const colorsDiv = document.getElementById('suggestedColors');
    
    resultDiv.innerHTML = `
        <div class="d-flex align-items-center justify-content-center mb-2">
            <div style="width:30px;height:30px;background:rgb(${r},${g},${b});border-radius:50%;border:2px solid #ddd;margin-right:10px"></div>
            <span><strong>${skinTone.replace('-', ' ').toUpperCase()}</strong></span>
        </div>
        <small class="text-muted">Skin tone detected successfully</small>
    `;
    
    const suggestedColors = getSuggestedColors(skinTone);
    colorsDiv.innerHTML = '';
    
    suggestedColors.forEach(color => {
        const colorBtn = document.createElement('button');
        colorBtn.className = 'btn btn-sm border';
        colorBtn.style.cssText = `background:${color};width:40px;height:40px;border-radius:50%;`;
        colorBtn.title = color;
        colorBtn.onclick = () => {
            updateCapImage(currentCapFile, color);
            document.querySelectorAll('#suggestedColors button').forEach(b => b.classList.remove('border-primary'));
            colorBtn.classList.add('border-primary');
        };
        colorsDiv.appendChild(colorBtn);
    });
    
    suggestionsDiv.style.display = 'block';
}

faceMesh.onResults(results => {
    if (!video.videoWidth || !video.videoHeight) return;
    resizeCanvas();
    ctx.clearRect(0,0,overlay.width, overlay.height);
    if (!results.multiFaceLandmarks || !results.multiFaceLandmarks.length) return;
    const landmarks = results.multiFaceLandmarks[0];
    const vw = video.videoWidth, vh = video.videoHeight;

    // Analyze skin tone
    analyzeSkinTone(landmarks, vw, vh);

    const pFore = landmarks[10];
    const pLeft = landmarks[127] || landmarks[130];
    const pRight = landmarks[356] || landmarks[359];
    if (!pFore || !pLeft || !pRight) return;

    const cx = pFore.x * vw;
    const cy = pFore.y * vh;
    const leftX = pLeft.x * vw;
    const leftY = pLeft.y * vh;
    const rightX = pRight.x * vw;
    const rightY = pRight.y * vh;

    // width based on temple distance, clamp for robustness
    let targetW = Math.max(60, Math.abs(rightX - leftX) * 1.65);
    let targetH = targetW * (capImage ? (capImage.height / capImage.width || 0.5) : 0.5);
    // rotation from temples vector
    const targetRot = Math.atan2(rightY - leftY, rightX - leftX);
    const targetX = cx - targetW/2;
    const targetY = cy - targetH * 0.62;

    if (!smooth.initialized) {
        smooth.x = targetX; smooth.y = targetY; smooth.w = targetW; smooth.rot = targetRot; smooth.initialized = true;
    } else {
        smooth.x = smooth.x * alpha + targetX * (1 - alpha);
        smooth.y = smooth.y * alpha + targetY * (1 - alpha);
        smooth.w = smooth.w * alpha + targetW * (1 - alpha);
        smooth.rot = smooth.rot * alpha + targetRot * (1 - alpha);
    }

    if (capImage) {
        // draw rotated + smoothed
        ctx.save();
        // draw in CSS pixel space (ctx transform already set for DPR)
        const cxDraw = smooth.x + smooth.w/2;
        const cyDraw = smooth.y + targetH/2;
        ctx.translate(cxDraw, cyDraw);
        ctx.rotate(smooth.rot);
        ctx.drawImage(capImage, -smooth.w/2, -targetH/2, smooth.w, targetH);
        ctx.restore();
    }
});

async function startCamera() {
    setError('');
    setNotice('Requesting camera permission...');
    try {
        const constraints = { video: { facingMode: useFront ? 'user' : 'environment' }, audio: false };
        const stream = await navigator.mediaDevices.getUserMedia(constraints);
        video.srcObject = stream;
        // give video metadata time to load
        await new Promise(r => video.onloadedmetadata = r);
        setNotice('Camera active');
        // create MediaPipe camera wrapper
        if (window.Camera) {
            if (camera) camera.stop();
            camera = new Camera(video, { onFrame: async () => { await faceMesh.send({image: video}); }, width: video.videoWidth, height: video.videoHeight });
            camera.start();
        } else {
            // fallback: poll frames
            (function loop(){ faceMesh.send({image: video}).catch(()=>{}); requestAnimationFrame(loop); })();
        }
    } catch (e) {
        console.error(e);
        setNotice('');
        setError('Camera access denied or not available. Use a modern browser and allow camera permissions.');
    }
}

document.getElementById('toggleCam').addEventListener('click', async () => {
    // toggle facing mode
    useFront = !useFront;
    if (camera) { camera.stop(); camera = null; }
    if (video.srcObject) {
        // stop tracks
        const tracks = video.srcObject.getTracks();
        tracks.forEach(t=>t.stop());
        video.srcObject = null;
    }
    await startCamera();
});

document.getElementById('takePhoto').addEventListener('click', () => {
    // capture at full video resolution for best quality
    const vw = video.videoWidth || video.clientWidth;
    const vh = video.videoHeight || video.clientHeight;
    const captureCanvas = document.createElement('canvas');
    captureCanvas.width = vw;
    captureCanvas.height = vh;
    const cctx = captureCanvas.getContext('2d');
    // draw video at full resolution
    cctx.drawImage(video, 0, 0, vw, vh);
    // overlay needs to be drawn in same coordinate space; use temporary canvas to rasterize current overlay
    const overlayCanvas = document.createElement('canvas');
    overlayCanvas.width = overlay.width; overlayCanvas.height = overlay.height;
    const octx = overlayCanvas.getContext('2d');
    // reset any transforms because overlay ctx was scaled for DPR
    octx.setTransform(1,0,0,1,0,0);
    octx.drawImage(overlay, 0, 0, overlayCanvas.width, overlayCanvas.height);
    // draw overlay onto capture canvas by scaling
    cctx.drawImage(overlayCanvas, 0, 0, vw, vh);
    const data = captureCanvas.toDataURL('image/png');
    const w = window.open('about:blank','_blank');
    w.document.write(`<img src="${data}" style="max-width:100%">`);
});

startCamera().catch(e => { console.error(e); setError('Failed to start camera.'); setNotice(''); });

// Cap card click to try on
document.querySelectorAll('.cap-card').forEach(card=>{
    card.addEventListener('click', ()=>{
        const capFile = card.dataset.cap;
        updateCapImage(capFile);
        window.scrollTo({top:0, behavior:'smooth'});
    });
});

@if($selectedProduct)
// Auto-load selected product
const selectedCapFile = '{{ basename($selectedProduct->image_url) }}';
if (selectedCapFile) {
    updateCapImage(selectedCapFile);
}
@endif

// Load recommendations
fetch('/api/caps/recommendations').then(r=>r.json()).then(json=>{
    const wrap = document.getElementById('recommendations');
    wrap.innerHTML = '';
    if (!json.data || json.data.length === 0) {
        wrap.innerHTML = '<div class="p-3 text-muted text-center">No recommendations found</div>';
        return;
    }
    (json.data||[]).forEach(p=>{
        const a = document.createElement('a');
        a.href = '/products/' + p.id;
        a.className = 'list-group-item list-group-item-action p-2';
        a.innerHTML = `<div class="d-flex align-items-center"><img src="${p.image_url}" style="width:48px;height:36px;object-fit:contain;margin-right:8px"> <div style="flex:1"><strong style="font-size:12px">${p.name}</strong>${p.brand ? '<div style="font-size:10px;color:#888">' + p.brand + '</div>' : ''}<div style="font-size:12px;color:#28a745;font-weight:600">₹${p.price}</div></div></div>`;
        wrap.appendChild(a);
    });
}).catch(()=>{});

// Search functionality
document.getElementById('capSearch').addEventListener('input', (e) => {
    const search = e.target.value.toLowerCase();
    document.querySelectorAll('.cap-item').forEach(item => {
        const name = item.dataset.name;
        const brand = item.dataset.brand;
        const color = item.dataset.color;
        const match = name.includes(search) || brand.includes(search) || color.includes(search);
        item.style.display = match ? '' : 'none';
    });
});
</script>
@endpush

@endsection
