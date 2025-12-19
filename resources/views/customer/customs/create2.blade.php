<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>3D Model Customizer</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r128/three.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/three@0.128.0/examples/js/controls/OrbitControls.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/three@0.128.0/examples/js/loaders/GLTFLoader.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/three@0.128.0/examples/js/exporters/GLTFExporter.js"></script>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        body {
            display: flex;
            flex-direction: column;
            height: 100vh;
            background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);
            color: #fff;
            overflow: hidden;
        }
        
        header {
            padding: 1rem;
            background: rgba(0, 0, 0, 0.3);
            text-align: center;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.5);
        }
        
        h1 {
            font-size: 2.2rem;
            margin-bottom: 0.5rem;
            background: linear-gradient(90deg, #4cc9f0, #4361ee);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        
        .container {
            display: flex;
            flex: 1;
            overflow: hidden;
        }
        
        .sidebar {
            width: 300px;
            background: rgba(30, 30, 50, 0.8);
            padding: 1rem;
            overflow-y: auto;
            border-right: 1px solid #4361ee;
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
        }
        
        .control-group {
            background: rgba(20, 20, 40, 0.6);
            padding: 1rem;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
        }
        
        h2 {
            font-size: 1.2rem;
            margin-bottom: 1rem;
            color: #4cc9f0;
            border-bottom: 1px solid #4361ee;
            padding-bottom: 0.5rem;
        }
        
        .button-group {
            display: flex;
            flex-direction: column;
            gap: 0.8rem;
        }
        
        button {
            padding: 0.8rem;
            background: linear-gradient(90deg, #4361ee, #3a0ca3);
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: all 0.3s ease;
            font-weight: 500;
        }
        
        button:hover {
            background: linear-gradient(90deg, #4cc9f0, #4361ee);
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(76, 201, 240, 0.3);
        }
        
        input[type="file"] {
            display: none;
        }
        
        .file-upload-label {
            display: block;
            padding: 0.8rem;
            background: linear-gradient(90deg, #7209b7, #3a0ca3);
            color: white;
            text-align: center;
            border-radius: 4px;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .file-upload-label:hover {
            background: linear-gradient(90deg, #b5179e, #7209b7);
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(181, 23, 158, 0.3);
        }
        
        .color-picker {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin: 0.8rem 0;
        }
        
        .color-picker label {
            flex: 1;
        }
        
        .color-picker input {
            width: 60px;
            height: 30px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        
        .viewer {
            flex: 1;
            position: relative;
            overflow: hidden;
        }
        
        #render-container {
            width: 100%;
            height: 100%;
        }
        
        .loading {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            font-size: 1.5rem;
            color: #4cc9f0;
            text-align: center;
            background: rgba(0, 0, 0, 0.7);
            padding: 2rem;
            border-radius: 8px;
            z-index: 10;
        }
        
        .hidden {
            display: none;
        }
        
        footer {
            padding: 1rem;
            text-align: center;
            background: rgba(0, 0, 0, 0.3);
            font-size: 0.9rem;
            color: #4cc9f0;
        }
        
        .model-info {
            margin-top: 1rem;
            padding: 0.8rem;
            background: rgba(20, 20, 40, 0.6);
            border-radius: 4px;
            font-size: 0.9rem;
        }
        
        .slider-control {
            margin: 0.8rem 0;
        }
        
        .slider-control label {
            display: block;
            margin-bottom: 0.5rem;
        }
        
        .slider-control input {
            width: 100%;
        }
        
        .hint {
            font-size: 0.8rem;
            color: #8d99ae;
            margin-top: 0.5rem;
        }
        
        .preview-models {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 0.5rem;
            margin-top: 1rem;
        }
        
        .preview-model {
            padding: 0.5rem;
            background: rgba(60, 60, 100, 0.5);
            border-radius: 4px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .preview-model:hover {
            background: rgba(76, 201, 240, 0.3);
        }
        
        .texture-controls {
            margin-top: 1rem;
            padding-top: 1rem;
            border-top: 1px solid #4361ee;
        }
        
        .texture-controls.hidden {
            display: none;
        }
        
        .texture-actions {
            display: flex;
            gap: 0.5rem;
            margin-top: 0.5rem;
        }
        
        .texture-actions button {
            flex: 1;
            padding: 0.5rem;
            font-size: 0.8rem;
        }
    </style>
</head>
<body>
    <header>
        <h1>3D Model Customizer</h1>
        <p>Upload, customize, and export 3D models with ease</p>
    </header>
    
    <div class="container">
        <div class="sidebar">
            <div class="control-group">
                <h2>Model Operations</h2>
                <div class="button-group">
                    <label for="model-upload" class="file-upload-label">
                        Upload GLB Model
                    </label>
                    <input type="file" id="model-upload" accept=".glb,.gltf">
                    
                    <label for="texture-upload" class="file-upload-label">
                        Upload Texture Image
                    </label>
                    <input type="file" id="texture-upload" accept="image/*">
                    
                    <button id="download-glb">Download GLB</button>
                    <button id="download-png">Download PNG Screenshot</button>
                    <button id="reset-camera">Reset Camera</button>
                </div>
                <p class="hint">Drag to orbit, scroll to zoom, right-drag to pan</p>
                
                <div class="preview-models">
                    <div class="preview-model" data-model="cube">Cube</div>
                    <div class="preview-model" data-model="sphere">Sphere</div>
                    <div class="preview-model" data-model="torus">Torus</div>
                    <div class="preview-model" data-model="cylinder">Cylinder</div>
                </div>
            </div>
            
            <div class="control-group">
                <h2>Material Customization</h2>
                <div class="color-picker">
                    <label>Base Color:</label>
                    <input type="color" id="base-color" value="#ff6b6b">
                </div>
                <div class="color-picker">
                    <label>Emissive Color:</label>
                    <input type="color" id="emissive-color" value="#000000">
                </div>
                
                <div class="slider-control">
                    <label for="roughness">Roughness: <span id="roughness-value">0.5</span></label>
                    <input type="range" id="roughness" min="0" max="1" step="0.01" value="0.5">
                </div>
                
                <div class="slider-control">
                    <label for="metalness">Metalness: <span id="metalness-value">0.5</span></label>
                    <input type="range" id="metalness" min="0" max="1" step="0.01" value="0.5">
                </div>
                
                <div class="texture-controls hidden" id="texture-controls">
                    <h3>Texture Controls</h3>
                    <div class="slider-control">
                        <label for="texture-offset-x">Offset X: <span id="offset-x-value">0</span></label>
                        <input type="range" id="texture-offset-x" min="-1" max="1" step="0.01" value="0">
                    </div>
                    <div class="slider-control">
                        <label for="texture-offset-y">Offset Y: <span id="offset-y-value">0</span></label>
                        <input type="range" id="texture-offset-y" min="-1" max="1" step="0.01" value="0">
                    </div>
                    <div class="slider-control">
                        <label for="texture-repeat-x">Repeat X: <span id="repeat-x-value">1</span></label>
                        <input type="range" id="texture-repeat-x" min="0.1" max="5" step="0.1" value="1">
                    </div>
                    <div class="slider-control">
                        <label for="texture-repeat-y">Repeat Y: <span id="repeat-y-value">1</span></label>
                        <input type="range" id="texture-repeat-y" min="0.1" max="5" step="0.1" value="1">
                    </div>
                    <div class="texture-actions">
                        <button id="apply-texture">Apply Texture</button>
                        <button id="remove-texture">Remove Texture</button>
                    </div>
                </div>
            </div>
            
            <div class="control-group">
                <h2>Model Information</h2>
                <div class="model-info">
                    <p>No model loaded</p>
                </div>
            </div>
        </div>
        
        <div class="viewer">
            <div id="render-container"></div>
            <div id="loading" class="loading hidden">Loading model...</div>
        </div>
    </div>
    
    <footer>
        <p>Three.js 3D Model Customizer | Created with ❤️ using Three.js</p>
    </footer>

    <script>
        // Initialize Three.js components
        let scene, camera, renderer, controls;
        let model = null;
        let currentTexture = null;
        let exporter = new THREE.GLTFExporter();
        
        // Initialize the application
        function init() {
            // Create scene
            scene = new THREE.Scene();
            scene.background = new THREE.Color(0x1a1a2e);
            scene.add(new THREE.AmbientLight(0xffffff, 0.5));
            
            // Create camera
            camera = new THREE.PerspectiveCamera(75, window.innerWidth / window.innerHeight, 0.1, 1000);
            camera.position.set(2, 2, 5);
            
            // Create renderer
            renderer = new THREE.WebGLRenderer({ antialias: true });
            renderer.setSize(window.innerWidth, window.innerHeight);
            renderer.setPixelRatio(window.devicePixelRatio);
            document.getElementById('render-container').appendChild(renderer.domElement);
            
            // Add orbit controls
            controls = new THREE.OrbitControls(camera, renderer.domElement);
            controls.enableDamping = true;
            controls.dampingFactor = 0.05;
            
            // Add lights
            const directionalLight = new THREE.DirectionalLight(0xffffff, 1);
            directionalLight.position.set(5, 5, 5);
            scene.add(directionalLight);
            
            const hemisphereLight = new THREE.HemisphereLight(0x443333, 0x222233, 0.6);
            scene.add(hemisphereLight);
            
            // Set up event listeners
            setupEventListeners();
            
            // Start animation loop
            animate();
        }
        
        // Set up UI event listeners
        function setupEventListeners() {
            // Model upload
            document.getElementById('model-upload').addEventListener('change', handleModelUpload);
            
            // Texture upload
            document.getElementById('texture-upload').addEventListener('change', handleTextureUpload);
            
            // Download handlers
            document.getElementById('download-glb').addEventListener('click', downloadGLB);
            document.getElementById('download-png').addEventListener('click', downloadPNG);
            document.getElementById('reset-camera').addEventListener('click', resetCamera);
            
            // Material controls
            document.getElementById('base-color').addEventListener('input', updateMaterial);
            document.getElementById('emissive-color').addEventListener('input', updateMaterial);
            document.getElementById('roughness').addEventListener('input', updateMaterial);
            document.getElementById('metalness').addEventListener('input', updateMaterial);
            
            // Texture controls
            document.getElementById('texture-offset-x').addEventListener('input', updateTextureControls);
            document.getElementById('texture-offset-y').addEventListener('input', updateTextureControls);
            document.getElementById('texture-repeat-x').addEventListener('input', updateTextureControls);
            document.getElementById('texture-repeat-y').addEventListener('input', updateTextureControls);
            
            // Texture actions
            document.getElementById('apply-texture').addEventListener('click', applyTexture);
            document.getElementById('remove-texture').addEventListener('click', removeTexture);
            
            // Update slider values
            document.getElementById('roughness').addEventListener('input', function() {
                document.getElementById('roughness-value').textContent = this.value;
            });
            
            document.getElementById('metalness').addEventListener('input', function() {
                document.getElementById('metalness-value').textContent = this.value;
            });
            
            document.getElementById('texture-offset-x').addEventListener('input', function() {
                document.getElementById('offset-x-value').textContent = this.value;
            });
            
            document.getElementById('texture-offset-y').addEventListener('input', function() {
                document.getElementById('offset-y-value').textContent = this.value;
            });
            
            document.getElementById('texture-repeat-x').addEventListener('input', function() {
                document.getElementById('repeat-x-value').textContent = this.value;
            });
            
            document.getElementById('texture-repeat-y').addEventListener('input', function() {
                document.getElementById('repeat-y-value').textContent = this.value;
            });
            
            // Preview models
            document.querySelectorAll('.preview-model').forEach(item => {
                item.addEventListener('click', function() {
                    createPreviewModel(this.dataset.model);
                });
            });
            
            // Window resize handler
            window.addEventListener('resize', onWindowResize);
        }
        
        // Create a preview model
        function createPreviewModel(type) {
            // Remove existing model
            if (model) {
                scene.remove(model);
            }
            
            let geometry;
            
            switch(type) {
                case 'cube':
                    geometry = new THREE.BoxGeometry(2, 2, 2);
                    break;
                case 'sphere':
                    geometry = new THREE.SphereGeometry(1, 32, 32);
                    break;
                case 'torus':
                    geometry = new THREE.TorusGeometry(1, 0.4, 16, 100);
                    break;
                case 'cylinder':
                    geometry = new THREE.CylinderGeometry(1, 1, 2, 32);
                    break;
                default:
                    geometry = new THREE.BoxGeometry(2, 2, 2);
            }
            
            const material = new THREE.MeshStandardMaterial({
                color: 0xff6b6b,
                roughness: 0.5,
                metalness: 0.5
            });
            
            model = new THREE.Mesh(geometry, material);
            scene.add(model);
            
            // Update model info
            updateModelInfo(model);
            
            // Center the model and adjust camera
            centerModel(model);
            
            // Hide texture controls if no texture is loaded
            if (!currentTexture) {
                document.getElementById('texture-controls').classList.add('hidden');
            }
        }
        
        // Handle model upload
        function handleModelUpload(event) {
            const file = event.target.files[0];
            if (!file) return;
            
            showLoading(true);
            
            const reader = new FileReader();
            reader.onload = function(e) {
                const loader = new THREE.GLTFLoader();
                loader.parse(e.target.result, '', function(gltf) {
                    // Remove existing model
                    if (model) {
                        scene.remove(model);
                    }
                    
                    model = gltf.scene;
                    scene.add(model);
                    
                    // Update model info
                    updateModelInfo(model);
                    
                    // Center the model and adjust camera
                    centerModel(model);
                    
                    showLoading(false);
                    
                    // Show texture controls if a texture is loaded
                    if (currentTexture) {
                        document.getElementById('texture-controls').classList.remove('hidden');
                    }
                }, function(error) {
                    console.error('Error loading model:', error);
                    alert('Error loading model. Please check the console for details.');
                    showLoading(false);
                });
            };
            
            reader.readAsArrayBuffer(file);
        }
        
        // Handle texture upload
        function handleTextureUpload(event) {
            const file = event.target.files[0];
            if (!file) return;
            
            const reader = new FileReader();
            reader.onload = function(e) {
                currentTexture = new THREE.TextureLoader().load(e.target.result);
                currentTexture.wrapS = THREE.RepeatWrapping;
                currentTexture.wrapT = THREE.RepeatWrapping;
                
                // Show texture controls
                document.getElementById('texture-controls').classList.remove('hidden');
                
                // Reset texture controls
                document.getElementById('texture-offset-x').value = 0;
                document.getElementById('texture-offset-y').value = 0;
                document.getElementById('texture-repeat-x').value = 1;
                document.getElementById('texture-repeat-y').value = 1;
                
                document.getElementById('offset-x-value').textContent = 0;
                document.getElementById('offset-y-value').textContent = 0;
                document.getElementById('repeat-x-value').textContent = 1;
                document.getElementById('repeat-y-value').textContent = 1;
            };
            
            reader.readAsDataURL(file);
        }
        
        // Update texture controls
        function updateTextureControls() {
            if (!currentTexture) return;
            
            const offsetX = parseFloat(document.getElementById('texture-offset-x').value);
            const offsetY = parseFloat(document.getElementById('texture-offset-y').value);
            const repeatX = parseFloat(document.getElementById('texture-repeat-x').value);
            const repeatY = parseFloat(document.getElementById('texture-repeat-y').value);
            
            currentTexture.offset.set(offsetX, offsetY);
            currentTexture.repeat.set(repeatX, repeatY);
            currentTexture.needsUpdate = true;
        }
        
        // Apply texture to model
        function applyTexture() {
            if (!model || !currentTexture) return;
            
            model.traverse(function(child) {
                if (child.isMesh) {
                    if (child.material) {
                        child.material.map = currentTexture;
                        child.material.needsUpdate = true;
                    }
                }
            });
        }
        
        // Remove texture from model
        function removeTexture() {
            if (!model) return;
            
            model.traverse(function(child) {
                if (child.isMesh) {
                    if (child.material) {
                        child.material.map = null;
                        child.material.needsUpdate = true;
                    }
                }
            });
        }
        
        // Update material based on UI controls
        function updateMaterial() {
            if (!model) return;
            
            const baseColor = document.getElementById('base-color').value;
            const emissiveColor = document.getElementById('emissive-color').value;
            const roughness = parseFloat(document.getElementById('roughness').value);
            const metalness = parseFloat(document.getElementById('metalness').value);
            
            model.traverse(function(child) {
                if (child.isMesh) {
                    if (child.material) {
                        child.material.color.setStyle(baseColor);
                        child.material.emissive.setStyle(emissiveColor);
                        child.material.roughness = roughness;
                        child.material.metalness = metalness;
                        child.material.needsUpdate = true;
                    }
                }
            });
        }
        
        // Download GLB file
        function downloadGLB() {
            if (!model) {
                alert('No model to export!');
                return;
            }
            
            exporter.parse(model, function(result) {
                const blob = new Blob([result], { type: 'model/gltf-binary' });
                const link = document.createElement('a');
                link.href = URL.createObjectURL(blob);
                link.download = 'model.glb';
                link.click();
            });
        }
        
        // Download PNG screenshot
        function downloadPNG() {
            if (!model) {
                alert('No model to capture!');
                return;
            }
            
            renderer.render(scene, camera);
            const dataURL = renderer.domElement.toDataURL('image/png');
            
            const link = document.createElement('a');
            link.href = dataURL;
            link.download = 'screenshot.png';
            link.click();
        }
        
        // Reset camera to default position
        function resetCamera() {
            if (!model) return;
            
            const box = new THREE.Box3().setFromObject(model);
            const center = box.getCenter(new THREE.Vector3());
            const size = box.getSize(new THREE.Vector3());
            
            const maxDim = Math.max(size.x, size.y, size.z);
            const fov = camera.fov * (Math.PI / 180);
            let cameraZ = Math.abs(maxDim / Math.sin(fov / 2));
            
            // Add some padding
            cameraZ *= 1.5;
            camera.position.set(cameraZ, cameraZ, cameraZ);
            camera.lookAt(center);
            
            controls.update();
        }
        
        // Center the model in the scene
        function centerModel(model) {
            const box = new THREE.Box3().setFromObject(model);
            const center = box.getCenter(new THREE.Vector3());
            const size = box.getSize(new THREE.Vector3());
            
            // Reposition model to center
            model.position.x += (model.position.x - center.x);
            model.position.y += (model.position.y - center.y);
            model.position.z += (model.position.z - center.z);
            
            // Adjust camera to fit the model
            resetCamera();
        }
        
        // Update model information panel
        function updateModelInfo(model) {
            let vertexCount = 0;
            let faceCount = 0;
            let meshCount = 0;
            
            model.traverse(function(child) {
                if (child.isMesh) {
                    meshCount++;
                    const geometry = child.geometry;
                    
                    if (geometry.index) {
                        faceCount += geometry.index.count / 3;
                    } else {
                        faceCount += geometry.attributes.position.count / 3;
                    }
                    
                    vertexCount += geometry.attributes.position.count;
                }
            });
            
            const infoPanel = document.querySelector('.model-info');
            infoPanel.innerHTML = `
                <p><strong>Meshes:</strong> ${meshCount}</p>
                <p><strong>Vertices:</strong> ${vertexCount.toLocaleString()}</p>
                <p><strong>Faces:</strong> ${faceCount.toLocaleString()}</p>
            `;
        }
        
        // Show/hide loading indicator
        function showLoading(show) {
            document.getElementById('loading').classList.toggle('hidden', !show);
        }
        
        // Handle window resize
        function onWindowResize() {
            camera.aspect = window.innerWidth / window.innerHeight;
            camera.updateProjectionMatrix();
            renderer.setSize(window.innerWidth, window.innerHeight);
        }
        
        // Animation loop
        function animate() {
            requestAnimationFrame(animate);
            controls.update();
            renderer.render(scene, camera);
        }
        
        // Initialize the application when the window loads
        window.addEventListener('load', init);
    </script>
</body>
</html>