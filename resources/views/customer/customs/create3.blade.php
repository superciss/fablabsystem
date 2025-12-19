<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Advanced 3D Model Customizer</title>
    <script src="https://cdn.jsdelivr.net/npm/three@0.132.2/build/three.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/three@0.132.2/examples/js/loaders/GLTFLoader.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/three@0.132.2/examples/js/controls/OrbitControls.js"></script>
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
            <p class="subtitle">Upload GLB models, add custom textures, and download your creations</p>
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
                    <h3>Texture Management</h3>
                    <div class="texture-upload-area" id="texture-drop-area">
                        <div class="icon">📁</div>
                        <p>Drag & drop texture images here<br>or click to browse</p>
                    </div>
                    <input type="file" id="texture-upload" accept="image/*" multiple />
                    
                    <div class="texture-list" id="texture-list">
                        <!-- Texture items will be added here -->
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
                <li>Add textures by dragging and dropping image files onto the texture area</li>
                <li>Select a texture from the list to apply it to your model</li>
                <li>Adjust material properties using the color picker and sliders</li>
                <li>Rotate and zoom the model by dragging with your mouse</li>
                <li>Download your customized model as GLB or an image as PNG</li>
            </ul>
        </div>
    </div>

    <script>
        // Initialize Three.js components
        let scene, camera, renderer, controls, loader;
        let model = null;
        let textures = [];
        let activeTexture = null;
        
        // Initialize the application
        function init() {
            // Set up the scene
            scene = new THREE.Scene();
            scene.background = new THREE.Color(0x444444);
            
            // Set up the camera
            const container = document.getElementById('render-container');
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
            
            // Handle window resize
            window.addEventListener('resize', onWindowResize);
            
            // Start animation loop
            animate();
            
            // Set up event listeners
            setupEventListeners();
        }
        
        // Set up UI event listeners
        function setupEventListeners() {
            // Model upload
            document.getElementById('model-upload').addEventListener('change', handleModelUpload);
            
            // Texture upload
            const textureUpload = document.getElementById('texture-upload');
            const textureDropArea = document.getElementById('texture-drop-area');
            
            textureDropArea.addEventListener('click', () => {
                textureUpload.click();
            });
            
            textureUpload.addEventListener('change', handleTextureUpload);
            
            // Drag and drop for textures
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
            
            // Material properties
            document.getElementById('color-picker').addEventListener('input', updateMaterial);
            document.getElementById('roughness').addEventListener('input', function(e) {
                document.getElementById('roughness-value').textContent = e.target.value;
                updateMaterial();
            });
            document.getElementById('metalness').addEventListener('input', function(e) {
                document.getElementById('metalness-value').textContent = e.target.value;
                updateMaterial();
            });
            
            // Download buttons
            document.getElementById('download-glb').addEventListener('click', downloadGLB);
            document.getElementById('download-png').addEventListener('click', downloadPNG);
        }
        
        // Handle model upload
        function handleModelUpload(event) {
            const file = event.target.files[0];
            if (!file) return;
            
            const reader = new FileReader();
            reader.onload = function(e) {
                const arrayBuffer = e.target.result;
                
                // Hide the upload message
                document.getElementById('viewer-message').style.display = 'none';
                
                // Clear previous model
                if (model) {
                    scene.remove(model);
                }
                
                // Load the new model
                loader.parse(arrayBuffer, '', function(gltf) {
                    model = gltf.scene;
                    scene.add(model);
                    
                    // Adjust camera to fit the model
                    fitCameraToObject(model);
                    
                    // Apply any active texture
                    if (activeTexture) {
                        applyTexture(activeTexture);
                    }
                }, function(error) {
                    console.error('Error loading model:', error);
                    alert('Error loading model. Please make sure you uploaded a valid GLB file.');
                });
            };
            
            reader.readAsArrayBuffer(file);
        }
        
        // Handle texture upload
        function handleTextureUpload(event) {
            handleTextureFiles(event.target.files);
        }
        
        // Process texture files
        function handleTextureFiles(files) {
            for (let i = 0; i < files.length; i++) {
                const file = files[i];
                if (!file.type.match('image.*')) continue;
                
                const reader = new FileReader();
                reader.onload = function(e) {
                    const image = new Image();
                    image.onload = function() {
                        // Create texture
                        const texture = new THREE.Texture();
                        texture.image = image;
                        texture.needsUpdate = true;
                        
                        // Add to textures list
                        const textureData = {
                            id: Date.now() + i,
                            name: file.name,
                            texture: texture,
                            preview: e.target.result
                        };
                        
                        textures.push(textureData);
                        addTextureToList(textureData);
                    };
                    image.src = e.target.result;
                };
                reader.readAsDataURL(file);
            }
        }
        
        // Add texture to the UI list
        function addTextureToList(textureData) {
            const textureList = document.getElementById('texture-list');
            
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
        }
        
        // Set active texture
        function setActiveTexture(textureData) {
            // Update UI
            document.querySelectorAll('.texture-item').forEach(item => {
                item.classList.remove('active');
            });
            document.querySelector(`.texture-item[data-id="${textureData.id}"]`).classList.add('active');
            
            // Apply texture
            activeTexture = textureData;
            applyTexture(textureData.texture);
        }
        
        // Apply texture to model
        function applyTexture(texture) {
            if (!model) return;
            
            const roughness = parseFloat(document.getElementById('roughness').value);
            const metalness = parseFloat(document.getElementById('metalness').value);
            const color = new THREE.Color(document.getElementById('color-picker').value);
            
            // Traverse the model and apply material
            model.traverse(function(child) {
                if (child.isMesh) {
                    // Create a material with the selected texture and properties
                    child.material = new THREE.MeshStandardMaterial({
                        map: texture,
                        color: color,
                        roughness: roughness,
                        metalness: metalness
                    });
                }
            });
        }
        
        // Update material properties
        function updateMaterial() {
            if (!model || !activeTexture) return;
            
            applyTexture(activeTexture.texture);
        }
        
        // Delete texture
        function deleteTexture(id) {
            // Remove from textures array
            textures = textures.filter(texture => texture.id != id);
            
            // Remove from UI
            const textureItem = document.querySelector(`.texture-item[data-id="${id}"]`);
            if (textureItem) {
                textureItem.remove();
            }
            
            // If deleted texture was active, clear active texture
            if (activeTexture && activeTexture.id == id) {
                activeTexture = null;
            }
        }
        
        // Fit camera to object
        function fitCameraToObject(object) {
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
        }
        
        // Handle window resize
        function onWindowResize() {
            const container = document.getElementById('render-container');
            camera.aspect = container.offsetWidth / container.offsetHeight;
            camera.updateProjectionMatrix();
            renderer.setSize(container.offsetWidth, container.offsetHeight);
        }
        
        // Animation loop
        function animate() {
            requestAnimationFrame(animate);
            controls.update();
            renderer.render(scene, camera);
        }
        
        // Download GLB (simulated)
        function downloadGLB() {
            if (!model) {
                alert('Please upload a model first.');
                return;
            }
            
            alert('In a full implementation, this would export your customized GLB file. This is a frontend demo.');
        }
        
        // Download PNG
        function downloadPNG() {
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
        }
        
        // Initialize the application when the page loads
        window.addEventListener('load', init);
    </script>
</body>
</html>