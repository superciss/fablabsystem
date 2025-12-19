<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Advanced 3D Model Customizer</title>
    <script src="https://cdn.jsdelivr.net/npm/three@0.132.2/build/three.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/three@0.132.2/examples/js/loaders/GLTFLoader.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/three@0.132.2/examples/js/controls/OrbitControls.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/three@0.132.2/examples/js/geometries/DecalGeometry.js"></script>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        body {
            background: linear-gradient(135deg, #2c3e50, #4a6491);
            color: white;
            min-height: 100vh;
            padding: 20px;
        }
        
        .container {
            max-width: 1400px;
            margin: 0 auto;
        }
        
        header {
            text-align: center;
            padding: 20px 0;
            margin-bottom: 30px;
        }
        
        h1 {
            font-size: 2.8rem;
            margin-bottom: 10px;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
        }
        
        .subtitle {
            font-size: 1.2rem;
            opacity: 0.9;
        }
        
        .main-content {
            display: flex;
            flex-wrap: wrap;
            gap: 30px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 15px;
            padding: 25px;
            backdrop-filter: blur(10px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
        }
        
        .viewer-section {
            flex: 1.5;
            min-width: 300px;
        }
        
        .controls-section {
            flex: 1;
            min-width: 300px;
        }
        
        .viewer {
            width: 100%;
            height: 450px;
            background: rgba(0, 0, 0, 0.2);
            border-radius: 10px;
            overflow: hidden;
            position: relative;
        }
        
        #render-container {
            width: 100%;
            height: 100%;
        }
        
        .viewer-message {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            text-align: center;
            color: #ccc;
        }
        
        .control-group {
            margin-bottom: 25px;
            background: rgba(255, 255, 255, 0.08);
            padding: 20px;
            border-radius: 10px;
        }
        
        h2 {
            font-size: 1.5rem;
            margin-bottom: 15px;
            padding-bottom: 5px;
            border-bottom: 2px solid rgba(255, 255, 255, 0.2);
        }
        
        h3 {
            font-size: 1.2rem;
            margin-bottom: 15px;
            color: #6ab7ff;
        }
        
        .upload-btn {
            display: block;
            width: 100%;
            padding: 12px;
            background: #3498db;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-align: center;
            font-size: 1.1rem;
            transition: background 0.3s;
            margin-bottom: 15px;
        }
        
        .upload-btn:hover {
            background: #2980b9;
        }
        
        input[type="file"] {
            display: none;
        }
        
        .texture-upload-area {
            border: 2px dashed #6ab7ff;
            border-radius: 8px;
            padding: 20px;
            text-align: center;
            margin-top: 15px;
            background: rgba(106, 183, 255, 0.1);
            cursor: pointer;
            transition: background 0.3s;
        }
        
        .texture-upload-area:hover {
            background: rgba(106, 183, 255, 0.2);
        }
        
        .texture-upload-area p {
            margin-top: 10px;
            font-size: 0.9rem;
            color: #6ab7ff;
        }
        
        .texture-list {
            margin-top: 15px;
            max-height: 200px;
            overflow-y: auto;
        }
        
        .texture-item {
            display: flex;
            align-items: center;
            padding: 8px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 5px;
            margin-bottom: 8px;
            cursor: pointer;
            transition: background 0.3s;
        }
        
        .texture-item:hover {
            background: rgba(255, 255, 255, 0.15);
        }
        
        .texture-item.active {
            background: rgba(106, 183, 255, 0.3);
            border: 1px solid #6ab7ff;
        }
        
        .texture-preview {
            width: 40px;
            height: 40px;
            border-radius: 4px;
            margin-right: 10px;
            background-size: cover;
        }
        
        .texture-name {
            flex: 1;
            font-size: 0.9rem;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        
        .texture-actions {
            display: flex;
            gap: 5px;
        }
        
        .texture-btn {
            background: none;
            border: none;
            color: #6ab7ff;
            cursor: pointer;
            font-size: 1rem;
        }
        
        .color-picker {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-top: 10px;
        }
        
        .color-label {
            font-weight: bold;
        }
        
        input[type="color"] {
            width: 60px;
            height: 40px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            background: rgba(255, 255, 255, 0.1);
        }
        
        .drag-instructions {
            margin-top: 10px;
            padding: 10px;
            background: rgba(106, 183, 255, 0.1);
            border-radius: 5px;
            font-size: 0.9rem;
        }
        
        .download-options {
            display: flex;
            gap: 15px;
            margin-top: 20px;
        }
        
        .download-btn {
            flex: 1;
            padding: 12px;
            border: none;
            border-radius: 5px;
            color: white;
            font-weight: bold;
            cursor: pointer;
            transition: opacity 0.3s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }
        
        .download-btn:hover {
            opacity: 0.9;
        }
        
        .download-glb {
            background: #2196F3;
        }
        
        .download-png {
            background: #FF9800;
        }
        
        .icon {
            font-size: 1.2rem;
        }
        
        .instructions {
            margin-top: 30px;
            background: rgba(255, 255, 255, 0.08);
            padding: 20px;
            border-radius: 10px;
            font-size: 0.9rem;
            line-height: 1.6;
        }
        
        .instructions h3 {
            margin-bottom: 10px;
            color: #FF9800;
        }
        
        .instructions ul {
            padding-left: 20px;
        }
        
        .instructions li {
            margin-bottom: 8px;
        }
        
        @media (max-width: 768px) {
            .main-content {
                flex-direction: column;
            }
        }
        
        @media (max-width: 480px) {
            h1 {
                font-size: 2.2rem;
            }
            
            .download-options {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <header>
            <h1>Advanced 3D Model Customizer</h1>
            <p class="subtitle">Upload GLB models, add custom decals with interactive positioning, and download your creations</p>
        </header>
        
        <div class="main-content">
            <div class="viewer-section">
                <h2>3D Preview</h2>
                <div class="viewer">
                    <div id="render-container"></div>
                    <div class="viewer-message" id="viewer-message">
                        Upload a GLB file to begin customizing
                    </div>
                </div>
                
                <div class="control-group">
                    <h3>Model Controls</h3>
                    <label for="model-upload" class="upload-btn">
                        <span class="icon">📤</span> Upload GLB Model
                    </label>
                    <input type="file" id="model-upload" accept=".glb" />
                    <p style="text-align: center; margin-top: 10px; font-size: 0.9rem; opacity: 0.8;">
                        Use mouse to rotate, scroll to zoom, and right-click to pan
                    </p>
                </div>
            </div>
            
            <div class="controls-section">
                <div class="control-group">
                    <h3>Decal Management</h3>
                    <div class="texture-upload-area" id="texture-drop-area">
                        <div class="icon">📁</div>
                        <p>Drag & drop decal images here<br>or click to browse</p>
                    </div>
                    <input type="file" id="texture-upload" accept="image/*" multiple />
                    
                    <div class="texture-list" id="texture-list">
                        <!-- Decal items will be added here -->
                    </div>
                    
                    <div class="drag-instructions">
                        <p>Click on the model to place the decal</p>
                        <p>Drag the decal to move, drag green corner boxes to resize, or drag the red box to rotate</p>
                    </div>
                </div>
                
                <div class="control-group">
                    <h3>Material Properties</h3>
                    <div class="color-picker">
                        <span class="color-label">Base Color:</span>
                        <input type="color" id="color-picker" value="#4CAF50" />
                    </div>
                    
                    <div style="margin-top: 15px;">
                        <label for="roughness">Roughness: <span id="roughness-value">0.5</span></label>
                        <input type="range" id="roughness" min="0" max="1" step="0.01" value="0.5" style="width: 100%;">
                    </div>
                    
                    <div style="margin-top: 15px;">
                        <label for="metalness">Metalness: <span id="metalness-value">0.5</span></label>
                        <input type="range" id="metalness" min="0" max="1" step="0.01" value="0.5" style="width: 100%;">
                    </div>
                </div>
                
                <div class="control-group">
                    <h3>Download Options</h3>
                    <div class="download-options">
                        <button class="download-btn download-glb" id="download-glb">
                            <span class="icon">📥</span> Download GLB
                        </button>
                        <button class="download-btn download-png" id="download-png">
                            <span class="icon">📸</span> Download PNG
                        </button>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="instructions">
            <h3>How to use this editor:</h3>
            <ul>
                <li>Upload a GLB model file using the upload button</li>
                <li>Add decals by dragging and dropping image files onto the texture area</li>
                <li>Select a decal from the list to apply it to your model</li>
                <li>Click on the model to place the decal</li>
                <li>Drag the decal to move it, drag green corner boxes to resize, or drag the red box to rotate</li>
                <li>Adjust material properties using the color picker and sliders</li>
                <li>Download your customized model as GLB or an image as PNG</li>
            </ul>
        </div>
    </div>

    <script>
        // Initialize Three.js components
        let scene, camera, renderer, controls, loader, raycaster, mouse;
        let model = null;
        let textures = [];
        let activeTexture = null;
        let activeDecal = null;
        let decalHandles = [];
        let textureMapping = {
            position: new THREE.Vector3(),
            orientation: new THREE.Euler(),
            scale: 1
        };
        
        // Interaction state
        let interactionMode = 'none'; // 'none', 'move', 'resize', 'rotate'
        let selectedHandle = null;
        let dragStart = { x: 0, y: 0 };
        let textureStartPosition = new THREE.Vector3();
        let startScale = 1;
        let startOrientation = new THREE.Euler();
        
        // Initialize the application
        function init() {
            try {
                // Set up the scene
                scene = new THREE.Scene();
                scene.background = new THREE.Color(0x444444);
                
                // Set up the camera
                const container = document.getElementById('render-container');
                if (!container) throw new Error('Render container not found');
                camera = new THREE.PerspectiveCamera(75, container.offsetWidth / container.offsetHeight, 0.1, 1000);
                camera.position.z = 5;
                
                // Set up the renderer
                renderer = new THREE.WebGLRenderer({ antialias: true });
                renderer.setSize(container.offsetWidth, container.offsetHeight);
                renderer.setPixelRatio(window.devicePixelRatio);
                container.appendChild(renderer.domElement);
                
                // Add lights
                const ambientLight = new THREE.AmbientLight(0x404040, 1.5);
                scene.add(ambientLight);
                
                const directionalLight = new THREE.DirectionalLight(0xffffff, 1);
                directionalLight.position.set(1, 1, 1);
                scene.add(directionalLight);
                
                const directionalLight2 = new THREE.DirectionalLight(0xffffff, 0.5);
                directionalLight2.position.set(-1, -1, -1);
                scene.add(directionalLight2);
                
                // Set up controls
                controls = new THREE.OrbitControls(camera, renderer.domElement);
                controls.enableDamping = true;
                controls.dampingFactor = 0.05;
                
                // Initialize GLTF loader
                loader = new THREE.GLTFLoader();
                
                // Initialize raycaster and mouse
                raycaster = new THREE.Raycaster();
                mouse = new THREE.Vector2();
                
                // Handle window resize
                window.addEventListener('resize', onWindowResize);
                
                // Set up event listeners
                setupEventListeners();
                
                // Start animation loop
                animate();
            } catch (error) {
                console.error('Initialization error:', error);
                alert('Failed to initialize 3D viewer. Please check the console for details.');
            }
        }
        
        // Set up UI event listeners
        function setupEventListeners() {
            try {
                // Model upload
                const modelUpload = document.getElementById('model-upload');
                if (modelUpload) modelUpload.addEventListener('change', handleModelUpload);
                
                // Texture upload
                const textureUpload = document.getElementById('texture-upload');
                const textureDropArea = document.getElementById('texture-drop-area');
                
                if (textureDropArea) {
                    textureDropArea.addEventListener('click', () => {
                        textureUpload.click();
                    });
                }
                
                if (textureUpload) {
                    textureUpload.addEventListener('change', handleTextureUpload);
                }
                
                // Drag and drop for textures
                if (textureDropArea) {
                    textureDropArea.addEventListener('dragover', (e) => {
                        e.preventDefault();
                        textureDropArea.style.background = 'rgba(106, 183, 255, 0.3)';
                    });
                    
                    textureDropArea.addEventListener('dragleave', () => {
                        textureDropArea.style.background = 'rgba(106, 183, 255, 0.1)';
                    });
                    
                    textureDropArea.addEventListener('drop', (e) => {
                        e.preventDefault();
                        textureDropArea.style.background = 'rgba(106, 183, 255, 0.1)';
                        
                        if (e.dataTransfer.files.length > 0) {
                            handleTextureFiles(e.dataTransfer.files);
                        }
                    });
                }
                
                // Material properties
                const colorPicker = document.getElementById('color-picker');
                if (colorPicker) colorPicker.addEventListener('input', updateMaterial);
                
                const roughness = document.getElementById('roughness');
                if (roughness) {
                    roughness.addEventListener('input', function(e) {
                        document.getElementById('roughness-value').textContent = e.target.value;
                        updateMaterial();
                    });
                }
                
                const metalness = document.getElementById('metalness');
                if (metalness) {
                    metalness.addEventListener('input', function(e) {
                        document.getElementById('metalness-value').textContent = e.target.value;
                        updateMaterial();
                    });
                }
                
                // Set up decal interaction
                renderer.domElement.addEventListener('mousedown', onDecalInteractionStart);
                renderer.domElement.addEventListener('mousemove', onDecalInteraction);
                renderer.domElement.addEventListener('mouseup', onDecalInteractionEnd);
                renderer.domElement.addEventListener('wheel', onDecalScroll, { passive: false });
                
                // Download buttons
                const downloadGlb = document.getElementById('download-glb');
                if (downloadGlb) downloadGlb.addEventListener('click', downloadGLB);
                
                const downloadPng = document.getElementById('download-png');
                if (downloadPng) downloadPng.addEventListener('click', downloadPNG);
            } catch (error) {
                console.error('Error setting up event listeners:', error);
            }
        }
        
        // Handle model upload
        function handleModelUpload(event) {
            try {
                const file = event.target.files[0];
                if (!file) return;
                
                const reader = new FileReader();
                reader.onload = function(e) {
                    try {
                        const arrayBuffer = e.target.result;
                        
                        // Hide the upload message
                        const viewerMessage = document.getElementById('viewer-message');
                        if (viewerMessage) viewerMessage.style.display = 'none';
                        
                        // Clear previous model
                        if (model) {
                            scene.remove(model);
                            model = null;
                        }
                        
                        // Load the new model
                        loader.parse(arrayBuffer, '', function(gltf) {
                            model = gltf.scene;
                            scene.add(model);
                            
                            // Adjust camera to fit the model
                            fitCameraToObject(model);
                            
                            // Apply any active decal
                            if (activeTexture) {
                                applyDecal(activeTexture);
                            }
                        }, function(error) {
                            console.error('Error loading model:', error);
                            alert('Error loading model. Please make sure you uploaded a valid GLB file.');
                        });
                    } catch (error) {
                        console.error('Error processing model file:', error);
                        alert('Error processing model file.');
                    }
                };
                reader.readAsArrayBuffer(file);
            } catch (error) {
                console.error('Error in handleModelUpload:', error);
            }
        }
        
        // Handle texture upload
        function handleTextureUpload(event) {
            try {
                handleTextureFiles(event.target.files);
            } catch (error) {
                console.error('Error in handleTextureUpload:', error);
            }
        }
        
        // Process texture files
        function handleTextureFiles(files) {
            try {
                for (let i = 0; i < files.length; i++) {
                    const file = files[i];
                    if (!file.type.match('image.*')) continue;
                    
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        try {
                            const image = new Image();
                            image.onload = function() {
                                try {
                                    // Create texture
                                    const texture = new THREE.TextureLoader().load(e.target.result);
                                    texture.wrapS = THREE.ClampToEdgeWrapping;
                                    texture.wrapT = THREE.ClampToEdgeWrapping;
                                    
                                    // Add to textures list
                                    const textureData = {
                                        id: Date.now() + i,
                                        name: file.name,
                                        texture: texture,
                                        preview: e.target.result,
                                        aspectRatio: image.width / image.height
                                    };
                                    
                                    textures.push(textureData);
                                    addTextureToList(textureData);
                                } catch (error) {
                                    console.error('Error processing texture image:', error);
                                }
                            };
                            image.src = e.target.result;
                        } catch (error) {
                            console.error('Error reading texture file:', error);
                        }
                    };
                    reader.readAsDataURL(file);
                }
            } catch (error) {
                console.error('Error in handleTextureFiles:', error);
            }
        }
        
        // Add texture to the UI list
        function addTextureToList(textureData) {
            try {
                const textureList = document.getElementById('texture-list');
                if (!textureList) return;
                
                const textureItem = document.createElement('div');
                textureItem.className = 'texture-item';
                textureItem.dataset.id = textureData.id;
                
                textureItem.innerHTML = `
                    <div class="texture-preview" style="background-image: url('${textureData.preview}')"></div>
                    <div class="texture-name">${textureData.name}</div>
                    <div class="texture-actions">
                        <button class="texture-btn apply-btn">✓</button>
                        <button class="texture-btn delete-btn">✕</button>
                    </div>
                `;
                
                textureList.appendChild(textureItem);
                
                // Add event listeners
                textureItem.querySelector('.apply-btn').addEventListener('click', (e) => {
                    e.stopPropagation();
                    setActiveTexture(textureData);
                });
                
                textureItem.querySelector('.delete-btn').addEventListener('click', (e) => {
                    e.stopPropagation();
                    deleteTexture(textureData.id);
                });
                
                textureItem.addEventListener('click', () => {
                    setActiveTexture(textureData);
                });
            } catch (error) {
                console.error('Error in addTextureToList:', error);
            }
        }
        
        // Set active texture
        function setActiveTexture(textureData) {
            try {
                // Update UI
                document.querySelectorAll('.texture-item').forEach(item => {
                    item.classList.remove('active');
                });
                const textureItem = document.querySelector(`.texture-item[data-id="${textureData.id}"]`);
                if (textureItem) textureItem.classList.add('active');
                
                // Set active texture
                activeTexture = textureData;
                applyDecal(textureData);
            } catch (error) {
                console.error('Error in setActiveTexture:', error);
            }
        }
        
        // Create control handles for the decal
        function createControlHandles() {
            try {
                // Remove existing handles
                removeControlHandles();
                
                if (!activeDecal || !activeTexture) return;
                
                const handleSize = 0.1;
                const handleMaterial = new THREE.MeshBasicMaterial({ color: 0x00ff00 }); // Green for resize
                const rotationHandleMaterial = new THREE.MeshBasicMaterial({ color: 0xff0000 }); // Red for rotate
                
                // Calculate handle positions (relative to decal center)
                const scale = textureMapping.scale;
                const aspectRatio = activeTexture.aspectRatio;
                const width = scale;
                const height = scale / aspectRatio;
                
                // Corners for resizing
                const corners = [
                    new THREE.Vector3(-width / 2, height / 2, 0.01), // Top-left
                    new THREE.Vector3(width / 2, height / 2, 0.01),  // Top-right
                    new THREE.Vector3(-width / 2, -height / 2, 0.01), // Bottom-left
                    new THREE.Vector3(width / 2, -height / 2, 0.01)  // Bottom-right
                ];
                
                // Rotation handle (positioned above top center)
                const rotationHandlePos = new THREE.Vector3(0, height / 2 + 0.2, 0.01);
                
                // Create handle meshes as boxes
                corners.forEach((pos, index) => {
                    const geometry = new THREE.BoxGeometry(handleSize, handleSize, handleSize);
                    const handle = new THREE.Mesh(geometry, handleMaterial);
                    handle.userData = { type: 'resize', corner: index };
                    handle.position.copy(pos);
                    handle.position.applyEuler(textureMapping.orientation);
                    handle.position.add(textureMapping.position);
                    scene.add(handle);
                    decalHandles.push(handle);
                });
                
                // Create rotation handle as a box
                const rotationGeometry = new THREE.BoxGeometry(handleSize, handleSize, handleSize);
                const rotationHandle = new THREE.Mesh(rotationGeometry, rotationHandleMaterial);
                rotationHandle.userData = { type: 'rotate' };
                rotationHandle.position.copy(rotationHandlePos);
                rotationHandle.position.applyEuler(textureMapping.orientation);
                rotationHandle.position.add(textureMapping.position);
                scene.add(rotationHandle);
                decalHandles.push(rotationHandle);
            } catch (error) {
                console.error('Error in createControlHandles:', error);
            }
        }
        
        // Remove control handles
        function removeControlHandles() {
            try {
                decalHandles.forEach(handle => scene.remove(handle));
                decalHandles = [];
            } catch (error) {
                console.error('Error in removeControlHandles:', error);
            }
        }
        
        // Apply decal to the model
        function applyDecal(textureData) {
            try {
                if (!model) {
                    console.warn('No model loaded for decal application');
                    return;
                }
                
                // Remove existing decal
                if (activeDecal) {
                    scene.remove(activeDecal);
                    activeDecal = null;
                }
                
                removeControlHandles();
                
                // Update material properties
                const roughness = parseFloat(document.getElementById('roughness').value);
                const metalness = parseFloat(document.getElementById('metalness').value);
                const color = new THREE.Color(document.getElementById('color-picker').value);
                
                // Create material for decal
                const material = new THREE.MeshStandardMaterial({
                    map: textureData.texture,
                    transparent: true,
                    depthTest: true,
                    depthWrite: false,
                    polygonOffset: true,
                    polygonOffsetFactor: -4,
                    color: color,
                    roughness: roughness,
                    metalness: metalness
                });
                
                // Create decal
                const size = new THREE.Vector3(
                    textureMapping.scale,
                    textureMapping.scale / textureData.aspectRatio,
                    textureMapping.scale
                );
                const geometry = new THREE.DecalGeometry(model.children[0], textureMapping.position, textureMapping.orientation, size);
                activeDecal = new THREE.Mesh(geometry, material);
                activeDecal.userData = { type: 'decal' };
                scene.add(activeDecal);
                
                // Create control handles
                createControlHandles();
            } catch (error) {
                console.error('Error in applyDecal:', error);
            }
        }
        
        // Update decal mapping
        function updateDecalMapping() {
            try {
                if (!model || !activeTexture || !activeDecal) return;
                
                // Remove existing decal
                scene.remove(activeDecal);
                
                // Create new decal with updated parameters
                const size = new THREE.Vector3(
                    textureMapping.scale,
                    textureMapping.scale / activeTexture.aspectRatio,
                    textureMapping.scale
                );
                const geometry = new THREE.DecalGeometry(model.children[0], textureMapping.position, textureMapping.orientation, size);
                activeDecal = new THREE.Mesh(geometry, activeDecal.material);
                activeDecal.userData = { type: 'decal' };
                scene.add(activeDecal);
                
                // Update control handles
                createControlHandles();
            } catch (error) {
                console.error('Error in updateDecalMapping:', error);
            }
        }
        
        // Handle decal interaction start
        function onDecalInteractionStart(event) {
            try {
                if (!activeTexture || !model) return;
                
                // Only handle left mouse button without modifiers
                if (event.button === 0 && !event.ctrlKey && !event.altKey && !event.shiftKey) {
                    // Update mouse position
                    const rect = renderer.domElement.getBoundingClientRect();
                    mouse.x = ((event.clientX - rect.left) / rect.width) * 2 - 1;
                    mouse.y = -((event.clientY - rect.top) / rect.height) * 2 + 1;
                    
                    // Raycast to find intersection with handles or decal
                    raycaster.setFromCamera(mouse, camera);
                    const intersects = raycaster.intersectObjects([...decalHandles, activeDecal].filter(obj => obj), true);
                    
                    if (intersects.length > 0) {
                        const intersect = intersects[0];
                        const object = intersect.object;
                        
                        if (object.userData.type === 'resize') {
                            interactionMode = 'resize';
                            selectedHandle = object;
                        } else if (object.userData.type === 'rotate') {
                            interactionMode = 'rotate';
                            startOrientation.copy(textureMapping.orientation);
                        } else if (object.userData.type === 'decal') {
                            interactionMode = 'move';
                        }
                        
                        dragStart.x = event.clientX;
                        dragStart.y = event.clientY;
                        textureStartPosition.copy(textureMapping.position);
                        startScale = textureMapping.scale;
                        
                        controls.enabled = false;
                        event.preventDefault();
                    } else {
                        // Place new decal if clicking on model
                        const modelIntersects = raycaster.intersectObject(model, true);
                        if (modelIntersects.length > 0) {
                            const intersect = modelIntersects[0];
                            textureMapping.position.copy(intersect.point);
                            const normal = intersect.face.normal.clone().applyQuaternion(intersect.object.quaternion);
                            textureMapping.orientation.setFromQuaternion(new THREE.Quaternion().setFromUnitVectors(new THREE.Vector3(0, 0, 1), normal));
                            updateDecalMapping();
                            event.preventDefault();
                        }
                    }
                }
            } catch (error) {
                console.error('Error in onDecalInteractionStart:', error);
            }
        }
        
        // Handle decal interaction
        function onDecalInteraction(event) {
            try {
                if (interactionMode === 'none') return;
                
                // Update mouse position
                const rect = renderer.domElement.getBoundingClientRect();
                mouse.x = ((event.clientX - rect.left) / rect.width) * 2 - 1;
                mouse.y = -((event.clientY - rect.top) / rect.height) * 2 + 1;
                
                if (interactionMode === 'move') {
                    // Raycast to update position
                    raycaster.setFromCamera(mouse, camera);
                    const intersects = raycaster.intersectObject(model, true);
                    
                    if (intersects.length > 0) {
                        textureMapping.position.copy(intersects[0].point);
                        const normal = intersects[0].face.normal.clone().applyQuaternion(intersects[0].object.quaternion);
                        textureMapping.orientation.setFromQuaternion(new THREE.Quaternion().setFromUnitVectors(new THREE.Vector3(0, 0, 1), normal));
                        updateDecalMapping();
                    }
                } else if (interactionMode === 'resize') {
                    // Calculate scale based on mouse movement
                    const deltaX = (event.clientX - dragStart.x) * 0.005; // Reduced sensitivity for precise control
                    textureMapping.scale = Math.max(0.1, Math.min(5, startScale + deltaX));
                    updateDecalMapping();
                } else if (interactionMode === 'rotate') {
                    // Calculate rotation based on mouse movement
                    const deltaY = (event.clientY - dragStart.y) * 0.005; // Reduced sensitivity for precise control
                    textureMapping.orientation.z = startOrientation.z + deltaY;
                    updateDecalMapping();
                }
                
                event.preventDefault();
            } catch (error) {
                console.error('Error in onDecalInteraction:', error);
            }
        }
        
        // Handle decal interaction end
        function onDecalInteractionEnd() {
            try {
                if (interactionMode !== 'none') {
                    interactionMode = 'none';
                    selectedHandle = null;
                    controls.enabled = true;
                }
            } catch (error) {
                console.error('Error in onDecalInteractionEnd:', error);
            }
        }
        
        // Handle decal scroll (for scaling)
        function onDecalScroll(event) {
            try {
                if (!activeTexture || !activeDecal) return;
                
                event.preventDefault();
                
                // Adjust scale based on scroll direction
                const scaleDelta = event.deltaY > 0 ? -0.05 : 0.05; // Reduced sensitivity for scroll
                textureMapping.scale = Math.max(0.1, Math.min(5, textureMapping.scale + scaleDelta));
                
                // Update decal
                updateDecalMapping();
            } catch (error) {
                console.error('Error in onDecalScroll:', error);
            }
        }
        
        // Delete texture
        function deleteTexture(id) {
            try {
                // Remove from textures array
                textures = textures.filter(texture => texture.id != id);
                
                // Remove from UI
                const textureItem = document.querySelector(`.texture-item[data-id="${id}"]`);
                if (textureItem) {
                    textureItem.remove();
                }
                
                // If deleted texture was active, clear active texture and decal
                if (activeTexture && activeTexture.id == id) {
                    if (activeDecal) {
                        scene.remove(activeDecal);
                        activeDecal = null;
                    }
                    removeControlHandles();
                    activeTexture = null;
                }
            } catch (error) {
                console.error('Error in deleteTexture:', error);
            }
        }
        
        // Fit camera to object
        function fitCameraToObject(object) {
            try {
                const box = new THREE.Box3().setFromObject(object);
                const size = box.getSize(new THREE.Vector3()).length();
                const center = box.getCenter(new THREE.Vector3());
                
                // Update camera
                camera.near = size / 100;
                camera.far = size * 100;
                camera.updateProjectionMatrix();
                
                // Position camera
                camera.position.copy(center);
                camera.position.x += size / 2.0;
                camera.position.y += size / 5.0;
                camera.position.z += size / 2.0;
                camera.lookAt(center);
                
                // Update controls
                controls.target.copy(center);
                controls.update();
            } catch (error) {
                console.error('Error in fitCameraToObject:', error);
            }
        }
        
        // Update material properties
        function updateMaterial() {
            try {
                if (!model || !activeTexture) return;
                
                applyDecal(activeTexture);
            } catch (error) {
                console.error('Error in updateMaterial:', error);
            }
        }
        
        // Handle window resize
        function onWindowResize() {
            try {
                const container = document.getElementById('render-container');
                if (container) {
                    camera.aspect = container.offsetWidth / container.offsetHeight;
                    camera.updateProjectionMatrix();
                    renderer.setSize(container.offsetWidth, container.offsetHeight);
                }
            } catch (error) {
                console.error('Error in onWindowResize:', error);
            }
        }
        
        // Animation loop
        function animate() {
            try {
                requestAnimationFrame(animate);
                controls.update();
                renderer.render(scene, camera);
            } catch (error) {
                console.error('Error in animate:', error);
            }
        }
        
        // Download GLB (simulated)
        function downloadGLB() {
            try {
                if (!model) {
                    alert('Please upload a model first.');
                    return;
                }
                
                alert('In a full implementation, this would export your customized GLB file with decals. This is a frontend demo.');
            } catch (error) {
                console.error('Error in downloadGLB:', error);
            }
        }
        
        // Download PNG
        function downloadPNG() {
            try {
                if (!model) {
                    alert('Please upload a model first.');
                    return;
                }
                
                // Render the current scene to a data URL
                renderer.render(scene, camera);
                const dataURL = renderer.domElement.toDataURL('image/png');
                
                // Create a download link
                const link = document.createElement('a');
                link.href = dataURL;
                link.download = '3d-model-screenshot.png';
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);
            } catch (error) {
                console.error('Error in downloadPNG:', error);
            }
        }
        
        // Initialize the application when the page loads
        window.addEventListener('load', () => {
            try {
                init();
            } catch (error) {
                console.error('Error during initialization:', error);
                alert('Failed to initialize the application. Please check the console for details.');
            }
        });
    </script>
</body>
</html>