<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>3D Shirt Texture Editor</title>
    <script src="https://cdn.jsdelivr.net/npm/three@0.132.2/build/three.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/three@0.132.2/examples/js/loaders/GLTFLoader.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/three@0.132.2/examples/js/controls/OrbitControls.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fabric.js/5.3.1/fabric.min.js"></script>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        body {
            background: linear-gradient(135deg, #1a2a3a 0%, #0d1b2a 100%);
            color: #e0e0e0;
            min-height: 100vh;
            overflow-x: hidden;
        }
        
        .container {
            display: flex;
            flex-direction: column;
            max-width: 1400px;
            margin: 0 auto;
            padding: 20px;
            gap: 20px;
        }
        
        header {
            text-align: center;
            padding: 10px 0;
        }
        
        h1 {
            font-size: 2.5rem;
            margin-bottom: 10px;
            background: linear-gradient(90deg, #4cc9f0, #4361ee);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        
        .subtitle {
            font-size: 1.1rem;
            color: #a0a0a0;
            max-width: 800px;
            margin: 0 auto;
        }
        
        .main-content {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
        }
        
        .panel {
            flex: 1;
            min-width: 300px;
            background: #1e293b;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.3);
            display: flex;
            flex-direction: column;
        }
        
        .panel-header {
            padding: 16px 20px;
            background: #2d3748;
            border-bottom: 1px solid #3c4658;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .panel-title {
            font-weight: 600;
            font-size: 18px;
            color: #4cc9f0;
        }
        
        .button {
            background: linear-gradient(90deg, #4361ee 0%, #3a0ca3 100%);
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 14px;
            transition: all 0.3s ease;
        }
        
        .button:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(67, 97, 238, 0.4);
        }
        
        .button:disabled {
            background: #3c4658;
            cursor: not-allowed;
            transform: none;
            box-shadow: none;
        }
        
        .panel-content {
            flex: 1;
            position: relative;
            overflow: hidden;
            min-height: 400px;
        }
        
        #preview-container {
            width: 100%;
            height: 100%;
        }
        
        #editor-container {
            width: 100%;
            height: 100%;
        }
        
        .upload-section {
            padding: 16px;
            border-top: 1px solid #3c4658;
            display: flex;
            flex-direction: column;
            gap: 12px;
        }
        
        .upload-button {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            background: #2d3748;
            color: white;
            padding: 12px;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .upload-button:hover {
            background: #3c4658;
            transform: translateY(-2px);
        }
        
        .upload-button input {
            display: none;
        }
        
        .texture-controls {
            padding: 16px;
            display: flex;
            flex-direction: column;
            gap: 12px;
        }
        
        .control-row {
            display: flex;
            gap: 12px;
            align-items: center;
        }
        
        .control-label {
            font-size: 14px;
            color: #a0a0a0;
            min-width: 100px;
        }
        
        .slider {
            flex: 1;
            -webkit-appearance: none;
            height: 6px;
            background: #3c4658;
            border-radius: 3px;
            outline: none;
        }
        
        .slider::-webkit-slider-thumb {
            -webkit-appearance: none;
            width: 18px;
            height: 18px;
            border-radius: 50%;
            background: #4361ee;
            cursor: pointer;
            transition: all 0.2s;
        }
        
        .slider::-webkit-slider-thumb:hover {
            background: #3a0ca3;
            transform: scale(1.2);
        }
        
        .color-picker {
            width: 40px;
            height: 40px;
            border: 2px solid #3c4658;
            border-radius: 6px;
            padding: 0;
            background: transparent;
            cursor: pointer;
        }
        
        .color-picker::-webkit-color-swatch {
            border-radius: 4px;
            border: none;
        }
        
        .instructions {
            padding: 16px;
            font-size: 14px;
            color: #a0a0a0;
            line-height: 1.6;
            background: #2d3748;
            border-radius: 8px;
            margin: 16px;
        }
        
        .instructions h3 {
            color: #4cc9f0;
            margin-bottom: 8px;
        }
        
        .instructions ul {
            padding-left: 20px;
        }
        
        .instructions li {
            margin-bottom: 6px;
        }
        
        .status-message {
            padding: 10px;
            text-align: center;
            color: #4cc9f0;
            font-size: 14px;
        }
        
        .error-message {
            padding: 10px;
            text-align: center;
            color: #f72585;
            font-size: 14px;
        }
        
        .export-options {
            display: flex;
            gap: 10px;
            margin-top: 10px;
        }
        
        .export-button {
            flex: 1;
            background: #2d3748;
            color: white;
            border: 1px solid #3c4658;
            padding: 8px 12px;
            border-radius: 6px;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .export-button:hover {
            background: #3c4658;
        }
        
        footer {
            text-align: center;
            padding: 20px;
            color: #a0a0a0;
            font-size: 0.9rem;
        }
        
        @media (max-width: 768px) {
            .main-content {
                flex-direction: column;
            }
            
            .panel {
                min-width: 100%;
            }
            
            .export-options {
                flex-direction: column;
            }
        }
        
        .loading-spinner {
            display: inline-block;
            width: 20px;
            height: 20px;
            border: 3px solid rgba(255,255,255,.3);
            border-radius: 50%;
            border-top-color: #4cc9f0;
            animation: spin 1s ease-in-out infinite;
        }
        
        @keyframes spin {
            to { transform: rotate(360deg); }
        }
        
        .highlighted {
            outline: 2px solid #4cc9f0 !important;
        }
    </style>
</head>
<body>
    <div class="container">
        <header>
            <h1>3D Shirt Texture Editor</h1>
            <p class="subtitle">Drag and drop textures on the right side and see them appear on the 3D model in real-time</p>
        </header>
        
        <div class="main-content">
            <div class="panel">
                <div class="panel-header">
                    <div class="panel-title">3D Preview</div>
                    <button class="button" id="reset-view">Reset View</button>
                </div>
                <div class="panel-content">
                    <div id="preview-container"></div>
                </div>
                <div class="status-message" id="model-status">Loading default model...</div>
                <div class="upload-section">
                    <label class="upload-button">
                        <input type="file" id="model-upload" accept=".glb,.gltf" />
                        Upload 3D Model
                    </label>
                </div>
            </div>
            
            <div class="panel">
                <div class="panel-header">
                    <div class="panel-title">Texture Editor</div>
                    <button class="button" id="add-text">Add Text</button>
                </div>
                <div class="panel-content">
                    <canvas id="editor-container"></canvas>
                </div>
                <div class="texture-controls">
                    <div class="control-row">
                        <div class="control-label">Texture Size</div>
                        <input type="range" min="50" max="200" value="100" class="slider" id="texture-scale">
                    </div>
                    <div class="control-row">
                        <div class="control-label">Background</div>
                        <input type="color" class="color-picker" id="bg-color" value="#2d3748">
                    </div>
                </div>
                <div class="upload-section">
                    <label class="upload-button">
                        <input type="file" id="texture-upload" accept="image/*" />
                        Upload Texture
                    </label>
                </div>
            </div>
        </div>
        
        <div class="instructions">
            <h3>How to Use This Editor</h3>
            <ul>
                <li>Drag and drop images or text onto the canvas</li>
                <li>Move, resize and rotate elements using controls</li>
                <li>Changes will automatically appear on the 3D model</li>
                <li>Use the slider to adjust texture size</li>
                <li>Upload your own 3D models or textures</li>
                <li>Rotate the 3D view by dragging with left mouse button</li>
                <li>Zoom in/out with scroll wheel</li>
                <li>For large models, wait for them to fully load</li>
            </ul>
        </div>
        
        <footer>
            <p>Created with Three.js and Fabric.js | 3D Texture Mapping Demo</p>
        </footer>
    </div>
<script>
    // Initialize variables
    let scene, camera, renderer, controls, shirt;
    let canvasEditor, texture, hiddenCanvas, hiddenCtx;

    // Initialize Three.js scene
    function initThreeJS() {
        scene = new THREE.Scene();
        scene.background = new THREE.Color(0x1e293b);

        camera = new THREE.PerspectiveCamera(45, 1, 0.1, 1000);
        camera.position.set(0, 0, 2.5);

        renderer = new THREE.WebGLRenderer({ antialias: true });
        const container = document.getElementById('preview-container');
        container.appendChild(renderer.domElement);

        // Lights
        const ambientLight = new THREE.AmbientLight(0xffffff, 0.6);
        scene.add(ambientLight);

        const directionalLight = new THREE.DirectionalLight(0xffffff, 0.8);
        directionalLight.position.set(1, 1, 1);
        scene.add(directionalLight);

        const backLight = new THREE.DirectionalLight(0xffffff, 0.4);
        backLight.position.set(-1, -1, -1);
        scene.add(backLight);

        controls = new THREE.OrbitControls(camera, renderer.domElement);
        controls.enableDamping = true;
        controls.dampingFactor = 0.05;

        loadModel('/models/shirt_baked.glb');

        animate();
        window.addEventListener('resize', onWindowResize);
        onWindowResize();
    }

    // Load 3D model
    function loadModel(url) {
        const loader = new THREE.GLTFLoader();
        const statusElement = document.getElementById('model-status');

        statusElement.textContent = 'Loading model...';

        loader.load(url, function (gltf) {
            if (shirt) scene.remove(shirt);

            shirt = gltf.scene;

            const box = new THREE.Box3().setFromObject(shirt);
            const size = box.getSize(new THREE.Vector3());
            const maxDim = Math.max(size.x, size.y, size.z);
            const scale = 1.5 / maxDim;

            shirt.scale.set(scale, scale, scale);

            box.setFromObject(shirt);
            const center = box.getCenter(new THREE.Vector3());
            shirt.position.x = -center.x;
            shirt.position.y = -center.y;
            shirt.position.z = -center.z;

            scene.add(shirt);
            statusElement.textContent = 'Model loaded successfully';

            shirt.traverse(function (child) {
                if (child.isMesh) {
                    child.material.map = texture;
                    child.material.needsUpdate = true;
                }
            });

            setTimeout(() => {
                statusElement.textContent = '';
            }, 2000);

        }, function (xhr) {
            const percentComplete = (xhr.loaded / xhr.total) * 100;
            statusElement.textContent = `Loading model: ${percentComplete.toFixed(2)}%`;
        }, function (error) {
            console.error('Error loading model:', error);
            statusElement.textContent = 'Error loading model. Using placeholder.';

            const geometry = new THREE.BoxGeometry(1, 1, 1);
            const material = new THREE.MeshStandardMaterial({
                color: 0x4361ee,
                roughness: 0.7,
                metalness: 0.1
            });
            shirt = new THREE.Mesh(geometry, material);
            shirt.material.map = texture;
            shirt.material.needsUpdate = true;
            scene.add(shirt);

            setTimeout(() => {
                statusElement.textContent = '';
            }, 3000);
        });
    }

    // Initialize Fabric.js editor
    function initFabricJS() {
        const container = document.getElementById('editor-container');
        const width = container.clientWidth;
        const height = container.clientHeight;

        canvasEditor = new fabric.Canvas('editor-container', {
            width: width,
            height: height,
            backgroundColor: '#2d3748'
        });

        // Create hidden canvas for clean texture rendering
        hiddenCanvas = document.createElement('canvas');
        hiddenCanvas.width = width;
        hiddenCanvas.height = height;
        hiddenCtx = hiddenCanvas.getContext('2d');

        // Use hiddenCanvas as the Three.js texture
        texture = new THREE.CanvasTexture(hiddenCanvas);
        texture.minFilter = THREE.LinearFilter;
        texture.magFilter = THREE.LinearFilter;

        // Update texture when objects are modified
        canvasEditor.on('object:modified', updateTexture);
        canvasEditor.on('object:added', updateTexture);
        canvasEditor.on('object:removed', updateTexture);
        canvasEditor.on('text:changed', updateTexture);

        addText();

        window.addEventListener('resize', function () {
            const container = document.getElementById('editor-container');
            canvasEditor.setWidth(container.clientWidth);
            canvasEditor.setHeight(container.clientHeight);

            hiddenCanvas.width = container.clientWidth;
            hiddenCanvas.height = container.clientHeight;

            canvasEditor.renderAll();
            updateTexture();
        });
    }

    // Update texture without handles
    function updateTexture() {
        // Hide selection handles in exported texture
        const active = canvasEditor.getActiveObject();
        if (active) active.hasControls = false;

        canvasEditor.discardActiveObject();
        canvasEditor.renderAll();

        hiddenCtx.clearRect(0, 0, hiddenCanvas.width, hiddenCanvas.height);
        hiddenCtx.drawImage(canvasEditor.lowerCanvasEl, 0, 0);

        texture.needsUpdate = true;

        if (active) active.hasControls = true; // restore handles in editor
    }

    // Add text to the editor
    function addText() {
        const text = new fabric.IText('Double click to edit', {
            left: canvasEditor.width / 2,
            top: canvasEditor.height / 2,
            fontFamily: 'Arial',
            fill: '#4cc9f0',
            fontSize: 40,
            originX: 'center',
            originY: 'center'
        });

        canvasEditor.add(text);
        canvasEditor.setActiveObject(text);
        updateTexture();
    }

    // Handle window resize
    function onWindowResize() {
        const container = document.getElementById('preview-container');
        const width = container.clientWidth;
        const height = container.clientHeight;

        camera.aspect = width / height;
        camera.updateProjectionMatrix();

        renderer.setSize(width, height);
    }

    // Animation loop
    function animate() {
        requestAnimationFrame(animate);
        controls.update();
        renderer.render(scene, camera);
    }

    // Initialize
    window.addEventListener('DOMContentLoaded', function () {
        initThreeJS();
        initFabricJS();

        document.getElementById('reset-view').addEventListener('click', function () {
            controls.reset();
        });

        document.getElementById('add-text').addEventListener('click', addText);

        document.getElementById('texture-scale').addEventListener('input', function (e) {
            const scale = e.target.value / 100;
            if (canvasEditor.getActiveObject()) {
                const obj = canvasEditor.getActiveObject();
                obj.scale(scale);
                canvasEditor.renderAll();
                updateTexture();
            }
        });

        document.getElementById('bg-color').addEventListener('input', function (e) {
            canvasEditor.backgroundColor = e.target.value;
            canvasEditor.renderAll();
            updateTexture();
        });

        document.getElementById('model-upload').addEventListener('change', function (e) {
            const file = e.target.files[0];
            if (!file) return;

            const statusElement = document.getElementById('model-status');
            statusElement.textContent = 'Uploading model...';

            if (file.size > 10 * 1024 * 1024) {
                statusElement.textContent = 'Model is large, please wait...';
            }

            const reader = new FileReader();
            reader.onload = function (event) {
                const blob = new Blob([event.target.result], { type: 'application/octet-stream' });
                const url = URL.createObjectURL(blob);
                loadModel(url);
            };
            reader.onerror = function () {
                statusElement.textContent = 'Error reading file';
            };
            reader.readAsArrayBuffer(file);
        });

        document.getElementById('texture-upload').addEventListener('change', function (e) {
            const file = e.target.files[0];
            if (!file) return;

            if (file.size > 5 * 1024 * 1024) {
                alert('Image is too large. Please use images under 5MB.');
                return;
            }

            const reader = new FileReader();
            reader.onload = function (event) {
                fabric.Image.fromURL(event.target.result, function (img) {
                    const maxSize = 1000;
                    if (img.width > maxSize || img.height > maxSize) {
                        const scale = maxSize / Math.max(img.width, img.height);
                        img.scale(scale);
                    }

                    img.set({
                        left: canvasEditor.width / 2,
                        top: canvasEditor.height / 2,
                        originX: 'center',
                        originY: 'center'
                    });

                    canvasEditor.add(img);
                    canvasEditor.setActiveObject(img);
                    updateTexture();
                });
            };
            reader.readAsDataURL(file);
        });
    });
</script>
</body>
</html>