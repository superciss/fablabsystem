@extends('layouts.maincustomize')

@section('title', 'Customize')

@section('content')
    <div class="customize-container">
        <!-- Left Panel - 3D Preview (5 columns) -->
        <div class="preview-panel">
            <div class="preview-header">
                <div class="preview-title">3D Preview</div>
            </div>
            <div id="threeContainer"></div>
        </div>
        
        <!-- Center Panel - Editor (5 columns) -->
        <div class="editor-panel">
            <div class="editor-header">
                <div class="editor-title">Material Editor</div>
                <div class="editor-subtitle">Customize your 3D model properties</div>
                
                  <div class="d-flex justify-content-between align-items-start mt-3">

                    <!-- Left side -->
                    <div>
                        <h5>Actual Price</h5>
                        <p id="actualPrice" class="fw-bold fs-5">
                            ₱{{ number_format($product->price, 2) }}
                        </p>
                    </div>

                      <div>
                        <h5>Customize Price</h5>
                        <p id="estimatedPrice" class="fw-bold fs-5">₱0.00</p>
                    </div>

                      <div>
                        <label for="quantity" class="fw-bold">Quantity</label>
                        <input type="number" name="quantity" id="quantity" min="1" value="1" class="form-control w-auto d-inline-block">
                      </div>

                    <!-- Right side -->
                    <div class="text-end">
                        <div class="control-group">
                            <h5>Total Price</h5>
                        <p id="totalPrice" class="fw-bold fs-5">
                            ₱{{ number_format($product->price, 2) }}
                        </p>
                        </div>
                    </div>
                </div>

            </div>
                
            <div class="editor-content">
                <div id="editor-dynamic-content">
                    <div class="welcome-message">
                        <div id="fabricContainer">
                            <div id="fabricTools">
                                <button id="addText" class="icon-btn">T</button>
                                <button id="addRect" class="icon-btn">□</button>
                                <button id="addCircle" class="icon-btn">○</button>
                                <button id="addTriangle" class="icon-btn">△</button>
                                <button id="addLine" class="icon-btn">━</button>
                                <button id="addEllipse" class="icon-btn">⬭</button>
                                <button id="addPolygon" class="icon-btn">⬠</button>
                                <button id="addStar" class="icon-btn">★</button>
                                <button id="bringFront" class="secondary">bring front</button>
                                <button id="sendBack" class="secondary">send back</button>
                                <button id="removeObj" class="icon-btn danger">✕</button>
                                <button id="clearCanvas" class="secondary">Clear</button>
                            </div>

                            <div class="color-controls">
                                <label for="fillColor">Fill:</label>
                                <input type="color" id="fillColor" value="#ff0000">
                            </div>

                            <canvas id="fabricCanvas" width="700" height="500"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Right Panel - Tools (2 columns) -->
        <div class="tools-panel">
            <div class="tools-header">
                <div class="tools-title">Tools</div>
            </div>
            <div class="tools-content">
                <div class="toolbar">
                    <div class="tab-button active" data-tab="material">
                        <span>Material</span>
                    </div>
                    <div class="tab-button" data-tab="transform">
                        <span>Transform</span>
                    </div>
                    <div class="tab-button" data-tab="scene">
                        <span>Scene</span>
                    </div>
                    <div class="tab-button" data-tab="file">
                        <span>File</span>
                    </div>
                    <div class="tab-button" data-tab="texture">
                        <span>Texture</span>
                    </div>
                </div>
                
                <!-- Material Tools -->
                <div class="tool-content active" id="material-tool">
                    <div class="control-group">
                        <h3>Color & Type</h3>
                        <div class="color-picker">
                            <div class="color-preview" id="colorPreview" style="background: #4361ee;"></div>
                            <input type="color" id="modelColor" value="#4361ee">
                        </div>
                        <label for="materialType">Material Type</label>
                        <select id="materialType">
                            <option value="standard">Standard (PBR)</option>
                            <option value="physical">Physical</option>
                            <option value="phong">Phong</option>
                            <option value="matcap">MatCap</option>
                            <option value="toon">Toon</option>
                        </select>
                        <label for="stylePreset">Style Preset</label>
                        <select id="stylePreset">
                            <option value="none">None</option>
                            <option value="matte">Matte</option>
                            <option value="glossy">Glossy</option>
                            <option value="metallic">Metallic</option>
                            <option value="toonPastel">Toon Pastel</option>
                        </select>
                    </div>
                    <div class="control-group">
                        <h3>Material Controls</h3>
                        <label for="roughness">Roughness</label>
                        <div class="slider-with-value">
                            <input type="range" id="roughness" min="0" max="1" step="0.01" value="0.5">
                            <span id="roughnessValue">0.5</span>
                        </div>
                        <label for="metalness">Metalness</label>
                        <div class="slider-with-value">
                            <input type="range" id="metalness" min="0" max="1" step="0.01" value="0.5">
                            <span id="metalnessValue">0.5</span>
                        </div>
                        <label for="emissive">Emissive</label>
                        <div class="slider-with-value">
                            <input type="range" id="emissive" min="0" max="3" step="0.01" value="0">
                            <span id="emissiveValue">0</span>
                        </div>
                        <label for="opacity">Opacity</label>
                        <div class="slider-with-value">
                            <input type="range" id="opacity" min="0.05" max="1" step="0.01" value="1">
                            <span id="opacityValue">1</span>
                        </div>
                        <button id="resetMaterial" class="secondary">Reset Material</button>
                    </div>
                </div>

                <!-- Transform Tools -->
                <div class="tool-content" id="transform-tool">
                    <div class="control-group">
                        <h3>Transform Controls</h3>
                        <label for="rotateX">Rotation X</label>
                        <div class="slider-with-value">
                            <input type="range" id="rotateX" min="-180" max="180" value="0">
                            <span id="rotateXValue">0°</span>
                        </div>
                        <label for="rotateY">Rotation Y</label>
                        <div class="slider-with-value">
                            <input type="range" id="rotateY" min="-180" max="180" value="0">
                            <span id="rotateYValue">0°</span>
                        </div>
                        <label for="modelScale">Scale</label>
                        <div class="slider-with-value">
                            <input type="range" id="modelScale" min="0.1" max="2" step="0.1" value="1">
                            <span id="scaleValue">1.0</span>
                        </div>
                        <button id="resetModel" class="secondary">Reset</button>
                    </div>
                </div>

                <!-- Scene Tools -->
                <div class="tool-content" id="scene-tool">
                    <div class="control-group">
                        <h3>Scene Settings</h3>
                        <label for="bgColor">Background</label>
                        <div class="color-picker">
                            <div class="color-preview" id="bgColorPreview" style="background: #1a1a2e;"></div>
                            <input type="color" id="bgColor" value="#1a1a2e">
                        </div>
                        <label for="envPreset">Env Preset</label>
                        <select id="envPreset">
                            <option value="default">Default</option>
                            <option value="bright">Bright Studio</option>
                            <option value="sunset">Sunset</option>
                            <option value="night">Night</option>
                            <option value="studio">Cool Studio</option>
                        </select>
                        <label for="ambientLight">Ambient Light</label>
                        <div class="slider-with-value">
                            <input type="range" id="ambientLight" min="0" max="1" step="0.1" value="0.6">
                            <span id="ambientValue">0.6</span>
                        </div>
                        <button id="toggleGrid" class="secondary">Toggle Grid</button>
                    </div>
                </div>

                <!-- File Tools -->
                <div class="tool-content" id="file-tool">
                    <div class="control-group">
                        <h3>File</h3>
                        <label for="glbUpload">Upload GLB</label>
                        <input type="file" id="glbUpload" accept=".glb">
                        <button id="removeModel">Remove Model</button>
                        <button id="download3D">Download 3D PNG</button>
                    </div>
                </div>

                <!-- Texture Tools -->
                <div class="tool-content" id="texture-tool">
                    <div class="control-group">
                        <h3>Texture Controls</h3>
                        <label for="textureSelect">Texture</label>
                        <select id="textureSelect" class="form-control">
                            <option value="">-- Select Texture --</option>
                            @foreach ($textures as $texture)
                                <option value="{{ $texture->id }}" data-price="{{ $texture->price }}" data-image="{{ $texture->image }}">
                                    {{ $texture->name }}
                                </option>
                            @endforeach
                        </select>
                   
                        <ul id="selectedTextures"></ul>

                        <label for="textureUpload">Upload Texture</label>
                        <input type="file" id="textureUpload" accept="image/*">
                        <label for="textureScale">Texture Scale</label>
                        <div class="slider-with-value">
                            <input type="range" id="textureScale" min="0.1" max="5" step="0.1" value="1">
                            <span id="textureScaleValue">1.0</span>
                        </div>
                       
                        <button id="applyTextureBtn">Apply Texture</button>

                        <label for="customDescription">Description</label>
                        <textarea id="customDescription" rows="3" placeholder="Enter description..."></textarea>

                        <button id="saveCustomizeBtn" data-route="{{ route('customizations.store') }}"  data-product="{{ $product->id }}">Save front</button>
                       <button id="savebackBtn" data-route="{{ route('customizations.store1', ['id' => ':id']) }}">Save back</button>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/three@0.132.2/build/three.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fabric.js/5.3.1/fabric.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.datatables.net/1.13.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.5/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/three@0.132.2/examples/js/loaders/GLTFLoader.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/three@0.132.2/examples/js/controls/OrbitControls.js"></script>

    <script>
       document.addEventListener("DOMContentLoaded", function () {
    const materialType = document.getElementById("materialType");
    const textureSelect = document.getElementById("textureSelect");
    const selectedTexturesList = document.getElementById("selectedTextures");
    const applyBtn = document.getElementById("applyTextureBtn");
    const removeObjBtn = document.getElementById("removeObj");

   // --- 🔧 DOM Elements ---
const actualPriceEl = document.getElementById("actualPrice");
const estimatedPriceEl = document.getElementById("estimatedPrice");
const totalPriceEl = document.getElementById("totalPrice");
const quantityInput = document.getElementById("quantity");

// --- 🧩 State ---
let selectedTextures = []; // store {id, price, objRef, liRef}

// --- 💰 Update Total Price ---
function updateTotalPrice() {
    const actualPrice = parseFloat(actualPriceEl.textContent.replace(/[₱,]/g, "")) || 0;
    const estimatedPrice = parseFloat(estimatedPriceEl.textContent.replace(/[₱,]/g, "")) || 0;
    const quantity = parseInt(quantityInput.value) || 1;
    const total = (actualPrice + estimatedPrice) * quantity;
    totalPriceEl.textContent = "₱" + total.toFixed(2);
}

// --- 🧮 Update Estimated Price ---
function updateEstimatedPrice() {
    const localTotal = selectedTextures.reduce((sum, t) => sum + t.price, 0);
    estimatedPriceEl.textContent = "₱" + localTotal.toFixed(2);
    updateTotalPrice();
}

// --- 🖼️ Add Texture ---
applyBtn.addEventListener("click", () => {
    const selected = textureSelect.options[textureSelect.selectedIndex];
    if (!selected.value) {
        Swal.fire({
            icon: 'warning',
            title: 'Select a Texture',
            text: 'Please select a texture before applying.',
        });
        return;
    }

    const texId = String(selected.value);
    const texPrice = parseFloat(selected.dataset.price) || 0;
    const texImage = selected.dataset.image;
    const texSize = selected.dataset.size || "1x1";
    const scaleInput = parseFloat(document.getElementById("textureScale")?.value || 1);
    const baseSize = parseFloat(texSize.split("x")[0]) || 1;
    const pixelBase = 100;

    fabric.Image.fromURL(texImage, img => {
        const width = baseSize * pixelBase;
        const height = baseSize * pixelBase;

        img.set({
            left: fabricCanvas.width / 2,
            top: fabricCanvas.height / 2,
            originX: "center",
            originY: "center",
            scaleX: (width / img.width) * scaleInput,
            scaleY: (height / img.height) * scaleInput,
            selectable: true,
            evented: true,
            hasControls: true,
            hasBorders: true
        });

        img.textureId = texId;
        fabricCanvas.add(img);
        fabricCanvas.setActiveObject(img);
        fabricCanvas.renderAll();
        update3DTexture();

        // --- 🔸 Create list item ---
        const li = document.createElement("li");
        li.textContent = `${selected.text} (₱${texPrice.toFixed(2)}) [${texSize}]`;

        const removeBtn = document.createElement("button");
        removeBtn.textContent = "Remove";
        removeBtn.classList.add("btn", "btn-sm", "btn-outline-danger", "ms-2");

        // --- 🗑️ Remove handler ---
        removeBtn.addEventListener("click", () => {
            // Remove from canvas
            fabricCanvas.remove(img);
            fabricCanvas.renderAll();
            update3DTexture();

            // Remove from array
            selectedTextures = selectedTextures.filter(t => t.liRef !== li);

            // Remove from DOM
            li.remove();

            // Recalculate prices
            updateEstimatedPrice();
        });

        li.appendChild(removeBtn);
        selectedTexturesList.appendChild(li);

        // --- 🧱 Store texture instance ---
        selectedTextures.push({
            id: texId,
            price: texPrice,
            objRef: img,
            liRef: li
        });

        // --- 💰 Update prices ---
        updateEstimatedPrice();
    }, { crossOrigin: 'Anonymous' });
});

// --- 🔄 Quantity change ---
quantityInput.addEventListener("input", updateTotalPrice);




// 🔹 Remove Selected Object from Canvas
removeObjBtn.onclick = () => {
    const obj = fabricCanvas.getActiveObject();
    if (obj) {
        fabricCanvas.remove(obj);
        fabricCanvas.discardActiveObject();
        fabricCanvas.renderAll();
        update3DTexture();

        const match = selectedTextures.find(t => t.objRef === obj);
        if (match) match.liRef.remove();

        selectedTextures = selectedTextures.filter(t => t.objRef !== obj);
        updatePrice();
    } else {
        alert("No object selected to remove!");
    }
};

// 🔹 Update price on material change
materialType.addEventListener("change", updatePrice);

});

        // Tab switching functionality
        document.querySelectorAll('.tab-button').forEach(button => {
            button.addEventListener('click', () => {
                document.querySelectorAll('.tab-button').forEach(btn => {
                    btn.classList.remove('active');
                });
                button.classList.add('active');
                
                const tabName = button.getAttribute('data-tab');
                document.querySelectorAll('.tool-content').forEach(content => {
                    content.classList.remove('active');
                });
                document.getElementById(`${tabName}-tool`).classList.add('active');
                
                const editorTitle = document.querySelector('.editor-title');
                const editorSubtitle = document.querySelector('.editor-subtitle');
                
                switch(tabName) {
                    case 'material':
                        editorTitle.textContent = 'Material Editor';
                        editorSubtitle.textContent = 'Customize your 3D model materials';
                        break;
                    case 'transform':
                        editorTitle.textContent = 'Transform Editor';
                        editorSubtitle.textContent = 'Adjust position, rotation and scale';
                        break;
                    case 'scene':
                        editorTitle.textContent = 'Scene Editor';
                        editorSubtitle.textContent = 'Configure environment and lighting';
                        break;
                    case 'file':
                        editorTitle.textContent = 'File Operations';
                        editorSubtitle.textContent = 'Import and export 3D models';
                        break;
                    case 'texture':
                        editorTitle.textContent = 'Texture Editor';
                        editorSubtitle.textContent = 'Create and apply textures';
                        break;
                }
            });
        });

        // Update slider values in real-time
        const sliders = document.querySelectorAll('input[type="range"]');
        sliders.forEach(slider => {
            const valueDisplay = document.getElementById(`${slider.id}Value`);
            if (valueDisplay) {
                valueDisplay.textContent = slider.value + (slider.id.includes('rotate') ? '°' : '');
                slider.addEventListener('input', () => {
                    valueDisplay.textContent = slider.value + (slider.id.includes('rotate') ? '°' : '');
                });
            }
        });

        // Color picker preview update
        const colorPickers = document.querySelectorAll('input[type="color"]');
        colorPickers.forEach(picker => {
            const preview = document.getElementById(`${picker.id}Preview`);
            if (preview) {
                picker.addEventListener('input', () => {
                    preview.style.background = picker.value;
                });
            }
        });

        // Utility to debounce rapid updates
        function debounce(func, wait) {
            let timeout;
            return function (...args) {
                clearTimeout(timeout);
                timeout = setTimeout(() => func.apply(this, args), wait);
            };
        }


       let customizedProductId = null;

// Save Front
document.getElementById('saveCustomizeBtn').addEventListener('click', () => {
    const saveBtn = document.getElementById('saveCustomizeBtn');
    const productId = parseInt(saveBtn.dataset.product); // ✅ get product ID safely
    const description = document.getElementById('customDescription')?.value || '';
   
    const actualPrice = parseFloat(document.getElementById('actualPrice')?.textContent.replace('₱', '').replace(',', '')) || 0;
   const estimatedPrice = parseFloat(document.getElementById('estimatedPrice')?.textContent.replace('₱', '').replace(',', '')) || 0;
   const quantity = parseInt(document.getElementById('quantity')?.value) || 1;

// multiply full total (actual + estimated)
const totalPrice = (actualPrice + estimatedPrice) * quantity;

    const saveRoute = saveBtn.dataset.route;

    renderer.render(scene, camera);
    const pngDataUrl = renderer.domElement.toDataURL("image/png");

    fetch(saveRoute, {
        method: "POST",
        headers: {
            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content,
            "Content-Type": "application/json"
        },
        body: JSON.stringify({
            product_id: productId,
            front_image: pngDataUrl,
            description: description,
            total_price: totalPrice,
            quantity: quantity
        })
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            customizedProductId = data.data.id;
            Swal.fire({
                icon: 'success',
                title: 'Success',
                text: 'Customized product saved successfully!',
            });
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Error saving customization: ' + (data.message || 'Unknown error'),
            });
        }
    })
    .catch(err => {
        console.error("Save error:", err);
        Swal.fire({
            icon: 'error',
            title: 'Save Failed',
            text: err.message
        });
    });
});


// Save Back
document.getElementById('savebackBtn').addEventListener('click', () => {
    if (!customizedProductId) {
        Swal.fire({
            icon: 'warning',
            title: 'Wait',
            text: 'Save the front image first before saving back image!',
        });
        return;
    }

    const saveRoute = document.getElementById('savebackBtn').dataset.route.replace(':id', customizedProductId);

    renderer.render(scene, camera);
    const pngDataUrl = renderer.domElement.toDataURL("image/png");

    fetch(saveRoute, {
        method: "POST",
        headers: {
            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content,
            "Content-Type": "application/json"
        },
        body: JSON.stringify({
            back_img: pngDataUrl
        })
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            Swal.fire({
                icon: 'success',
                title: 'Success',
                text: 'Back image saved successfully!',
            });
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Error saving back image: ' + (data.message || 'Unknown error'),
            });
        }
    })
    .catch(err => {
        console.error("Save error:", err);
    });
});

        let selectedTexture = null;

        document.getElementById("textureSelect").addEventListener("change", function () {
            const selected = this.options[this.selectedIndex];
            selectedTexture = selected && selected.dataset.image
                ? selected.dataset.image.startsWith("data:image")
                    ? selected.dataset.image
                    : `data:image/png;base64,${selected.dataset.image}`
                : null;
        });

        document.getElementById("textureUpload").addEventListener("change", function (e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function (event) {
                    selectedTexture = event.target.result;
                    document.getElementById("textureSelect").value = "";
                };
                reader.readAsDataURL(file);
            }
        });

        document.getElementById("applyTextureBtn").addEventListener("click", () => {
            if (!selectedTexture) {
                alert("Please select or upload a texture!");
                return;
            }
            const scale = parseFloat(document.getElementById("textureScale").value);
            fabric.Image.fromURL(selectedTexture, img => {
                img.set({
                    left: fabricCanvas.width / 2,
                    top: fabricCanvas.height / 2,
                    originX: "center",
                    originY: "center",
                    scaleX: scale * 0.5,
                    scaleY: scale * 0.5,
                    selectable: true,
                    evented: true,
                    hasControls: true,
                    hasBorders: true
                });
                fabricCanvas.add(img).setActiveObject(img);
                fabricCanvas.renderAll();
                update3DTexture();
            }, { crossOrigin: 'Anonymous' });
        });

        const threeContainer = document.getElementById('threeContainer');
        const scene = new THREE.Scene();
        scene.background = new THREE.Color(0x1a1a2e);
        const camera = new THREE.PerspectiveCamera(75, threeContainer.clientWidth / window.innerHeight, 0.1, 1000);
        const renderer = new THREE.WebGLRenderer({ antialias: true, preserveDrawingBuffer: true });
        renderer.setSize(threeContainer.clientWidth, window.innerHeight);
        renderer.shadowMap.enabled = true;
        threeContainer.appendChild(renderer.domElement);

        const ambientLight = new THREE.AmbientLight(0xffffff, 0.6);
        scene.add(ambientLight);
        const directionalLight = new THREE.DirectionalLight(0xffffff, 1);
        directionalLight.position.set(5, 5, 5);
        directionalLight.castShadow = true;
        scene.add(directionalLight);

        const controls = new THREE.OrbitControls(camera, renderer.domElement);
        controls.enableDamping = true;
        camera.position.set(0, 2, 5);

        const gridHelper = new THREE.GridHelper(10, 10);
        scene.add(gridHelper);

        let modelMeshes = [], canvasTexture = null, currentModel = null;
        let materialType = 'standard';
        const envPresets = {
            default: { ambient: 0.6, directional: 1, bgColor: 0x1a1a2e },
            bright: { ambient: 1, directional: 1.5, bgColor: 0xffffff },
            sunset: { ambient: 0.5, directional: 1, bgColor: 0xff4500 },
            night: { ambient: 0.2, directional: 0.5, bgColor: 0x000033 },
            studio: { ambient: 0.8, directional: 1.2, bgColor: 0x808080 }
        };

        const fabricCanvas = new fabric.Canvas('fabricCanvas', { backgroundColor: 'white' });
        let currentColor = '#ff0000';
        canvasTexture = new THREE.CanvasTexture(fabricCanvas.getElement());
        canvasTexture.flipY = true;
        canvasTexture.minFilter = THREE.LinearFilter;
        canvasTexture.magFilter = THREE.LinearFilter;
        fabricCanvas.on('after:render', debounce(() => {
            if (canvasTexture) canvasTexture.needsUpdate = true;
        }, 100));

        function loadDefaultModel() {
            if (currentModel) scene.remove(currentModel);
            const geometry = new THREE.CylinderGeometry(1, 1.2, 1.5, 32);
            const material = new THREE.MeshStandardMaterial({ color: 0x4361ee });
            currentModel = new THREE.Mesh(geometry, material);
            scene.add(currentModel);
            modelMeshes = [currentModel];
            applyMaterial();
            fitModelToView(currentModel);

            new THREE.GLTFLoader().load('/models/free_t_shirt_design.glb', gltf => {
                currentModel = gltf.scene;
                scene.add(currentModel);
                modelMeshes = [];
                currentModel.traverse(child => {
                    if (child.isMesh) {
                        child.castShadow = true;
                        child.receiveShadow = true;
                        modelMeshes.push(child);
                    }
                });
                applyMaterial();
                fitModelToView(currentModel);
            }, undefined, err => console.error("Error loading default model:", err));
        }

        loadDefaultModel();

        document.getElementById('glbUpload').addEventListener('change', e => {
            const file = e.target.files[0];
            if (!file) return;
            if (currentModel) scene.remove(currentModel);
            const reader = new FileReader();
            reader.onload = e => {
                new THREE.GLTFLoader().parse(e.target.result, '', gltf => {
                    currentModel = gltf.scene;
                    scene.add(currentModel);
                    modelMeshes = [];
                    currentModel.traverse(child => {
                        if (child.isMesh) {
                            child.castShadow = true;
                            child.receiveShadow = true;
                            modelMeshes.push(child);
                        }
                    });
                    applyMaterial();
                    fitModelToView(currentModel);
                }, undefined, err => console.error("Error loading GLB:", err));
            };
            reader.readAsArrayBuffer(file);
        });

        document.getElementById('textureUpload').addEventListener('change', e => {
            const file = e.target.files[0];
            if (!file) return;
            const reader = new FileReader();
            reader.onload = e => {
                fabric.Image.fromURL(e.target.result, img => {
                    img.set({ left: fabricCanvas.width / 2, top: fabricCanvas.height / 2, originX: 'center', originY: 'center', scaleX: 0.5, scaleY: 0.5 });
                    fabricCanvas.add(img).setActiveObject(img);
                    fabricCanvas.renderAll();
                    update3DTexture();
                }, { crossOrigin: 'Anonymous' });
            };
            reader.readAsDataURL(file);
        });

        document.getElementById('addText').onclick = () => {
            const text = new fabric.Textbox('Text', { left: 150, top: 150, fontSize: 24, fill: currentColor });
            fabricCanvas.add(text).setActiveObject(text);
            fabricCanvas.renderAll();
            update3DTexture();
        };

        document.getElementById('addRect').onclick = () => {
            const rect = new fabric.Rect({ left: 100, top: 100, width: 100, height: 60, fill: currentColor });
            fabricCanvas.add(rect).setActiveObject(rect);
            fabricCanvas.renderAll();
            update3DTexture();
        };

        document.getElementById('addCircle').onclick = () => {
            const circle = new fabric.Circle({ left: 150, top: 150, radius: 50, fill: currentColor });
            fabricCanvas.add(circle).setActiveObject(circle);
            fabricCanvas.renderAll();
            update3DTexture();
        };

        document.getElementById('addTriangle').onclick = () => {
            const tri = new fabric.Triangle({ left: 200, top: 200, width: 100, height: 100, fill: currentColor });
            fabricCanvas.add(tri).setActiveObject(tri);
            fabricCanvas.renderAll();
            update3DTexture();
        };

        document.getElementById('addLine').onclick = () => {
            const line = new fabric.Line([50, 100, 200, 100], { stroke: currentColor, strokeWidth: 3 });
            fabricCanvas.add(line).setActiveObject(line);
            fabricCanvas.renderAll();
            update3DTexture();
        };

        document.getElementById('addEllipse').onclick = () => {
            const ellipse = new fabric.Ellipse({ left: 150, top: 150, rx: 80, ry: 40, fill: currentColor });
            fabricCanvas.add(ellipse).setActiveObject(ellipse);
            fabricCanvas.renderAll();
            update3DTexture();
        };

        document.getElementById('addPolygon').onclick = () => {
            const sides = 6, radius = 50;
            const points = [];
            for (let i = 0; i < sides; i++) {
                points.push({
                    x: radius * Math.cos((i * 2 * Math.PI) / sides),
                    y: radius * Math.sin((i * 2 * Math.PI) / sides)
                });
            }
            const polygon = new fabric.Polygon(points, { fill: currentColor, left: 200, top: 200, originX: 'center', originY: 'center' });
            fabricCanvas.add(polygon).setActiveObject(polygon);
            fabricCanvas.renderAll();
            update3DTexture();
        };

        document.getElementById('addStar').onclick = () => {
            const spikes = 5, outerRadius = 50, innerRadius = 20;
            const starPoints = [];
            for (let i = 0; i < spikes * 2; i++) {
                const radius = i % 2 === 0 ? outerRadius : innerRadius;
                const angle = (i * Math.PI) / spikes;
                starPoints.push({ x: radius * Math.cos(angle), y: radius * Math.sin(angle) });
            }
            const star = new fabric.Polygon(starPoints, { fill: currentColor, left: 250, top: 250, originX: 'center', originY: 'center' });
            fabricCanvas.add(star).setActiveObject(star);
            fabricCanvas.renderAll();
            update3DTexture();
        };

        document.getElementById('bringFront').onclick = () => {
            const obj = fabricCanvas.getActiveObject();
            if (obj) {
                fabricCanvas.bringToFront(obj);
                fabricCanvas.renderAll();
                update3DTexture();
            }
        };

        document.getElementById('sendBack').onclick = () => {
            const obj = fabricCanvas.getActiveObject();
            if (obj) {
                fabricCanvas.sendToBack(obj);
                fabricCanvas.renderAll();
                update3DTexture();
            }
        };

        document.getElementById('clearCanvas').onclick = () => {
            fabricCanvas.clear().setBackgroundColor('white', fabricCanvas.renderAll.bind(fabricCanvas));
            update3DTexture();
        };

        document.getElementById('resetModel').onclick = () => {
            if (currentModel) {
                currentModel.rotation.set(0, 0, 0);
                currentModel.scale.set(1, 1, 1);
                document.getElementById('rotateX').value = 0;
                document.getElementById('rotateY').value = 0;
                document.getElementById('modelScale').value = 1;
                document.getElementById('rotateXValue').textContent = '0°';
                document.getElementById('rotateYValue').textContent = '0°';
                document.getElementById('scaleValue').textContent = '1.0';
            }
        };

        document.getElementById('resetMaterial').onclick = () => {
            document.getElementById('modelColor').value = '#4361ee';
            document.getElementById('colorPreview').style.background = '#4361ee';
            document.getElementById('materialType').value = 'standard';
            document.getElementById('stylePreset').value = 'none';
            document.getElementById('roughness').value = 0.5;
            document.getElementById('roughnessValue').textContent = '0.5';
            document.getElementById('metalness').value = 0.5;
            document.getElementById('metalnessValue').textContent = '0.5';
            document.getElementById('emissive').value = 0;
            document.getElementById('emissiveValue').textContent = '0';
            document.getElementById('opacity').value = 1;
            document.getElementById('opacityValue').textContent = '1';
            applyMaterial();
        };

        document.getElementById('removeModel').onclick = () => {
            if (currentModel) {
                scene.remove(currentModel);
                currentModel = null;
                modelMeshes = [];
            }
        };

        document.getElementById('download3D').onclick = () => {
            renderer.render(scene, camera);
            try {
                const link = document.createElement('a');
                link.download = '3d_view.png';
                link.href = renderer.domElement.toDataURL('image/png');
                link.click();
            } catch (e) {
                console.error('Error downloading image:', e);
                alert('Error downloading image. Please try again.');
            }
        };

        document.getElementById('toggleGrid').onclick = () => {
            gridHelper.visible = !gridHelper.visible;
        };

        document.getElementById('rotateX').addEventListener('input', e => {
            if (currentModel) {
                currentModel.rotation.x = THREE.MathUtils.degToRad(e.target.value);
                document.getElementById('rotateXValue').textContent = `${e.target.value}°`;
            }
        });

        document.getElementById('rotateY').addEventListener('input', e => {
            if (currentModel) {
                currentModel.rotation.y = THREE.MathUtils.degToRad(e.target.value);
                document.getElementById('rotateYValue').textContent = `${e.target.value}°`;
            }
        });

        document.getElementById('modelScale').addEventListener('input', e => {
            if (currentModel) {
                const scale = parseFloat(e.target.value);
                currentModel.scale.set(scale, scale, scale);
                document.getElementById('scaleValue').textContent = scale.toFixed(1);
            }
        });

        document.getElementById('ambientLight').addEventListener('input', e => {
            ambientLight.intensity = parseFloat(e.target.value);
            document.getElementById('ambientValue').textContent = e.target.value;
        });

        document.getElementById('textureScale').addEventListener('input', e => {
            const scale = parseFloat(e.target.value);
            modelMeshes.forEach(mesh => {
                if (mesh.material.map) {
                    mesh.material.map.repeat.set(scale, scale);
                    mesh.material.needsUpdate = true;
                }
            });
            document.getElementById('textureScaleValue').textContent = scale.toFixed(1);
        });

        function applyMaterial() {
            if (modelMeshes.length === 0) return;
            const color = new THREE.Color(document.getElementById('modelColor').value);
            const roughness = parseFloat(document.getElementById('roughness').value);
            const metalness = parseFloat(document.getElementById('metalness').value);
            const emissiveIntensity = parseFloat(document.getElementById('emissive').value);
            const opacity = parseFloat(document.getElementById('opacity').value);
            const stylePreset = document.getElementById('stylePreset').value;

            let baseMaterial;
            switch (materialType) {
                case 'physical':
                    baseMaterial = new THREE.MeshPhysicalMaterial();
                    break;
                case 'phong':
                    baseMaterial = new THREE.MeshPhongMaterial({ shininess: 100 * (1 - roughness) });
                    break;
                case 'matcap':
                    baseMaterial = new THREE.MeshMatcapMaterial();
                    break;
                case 'toon':
                    baseMaterial = new THREE.MeshToonMaterial();
                    break;
                default:
                    baseMaterial = new THREE.MeshStandardMaterial();
            }

            baseMaterial.color = color;
            baseMaterial.roughness = roughness;
            baseMaterial.metalness = metalness;
            baseMaterial.emissive = color.clone().multiplyScalar(emissiveIntensity);
            baseMaterial.opacity = opacity;
            baseMaterial.transparent = opacity < 1;
            baseMaterial.map = canvasTexture;
            baseMaterial.needsUpdate = true;

            if (stylePreset === 'matte') {
                baseMaterial.roughness = 1;
                baseMaterial.metalness = 0;
            } else if (stylePreset === 'glossy') {
                baseMaterial.roughness = 0.2;
                baseMaterial.metalness = 0.5;
            } else if (stylePreset === 'metallic') {
                baseMaterial.roughness = 0.3;
                baseMaterial.metalness = 1;
            } else if (stylePreset === 'toonPastel') {
                baseMaterial = new THREE.MeshToonMaterial({ color: color.lerp(new THREE.Color(0xffffff), 0.5), opacity, transparent: opacity < 1, map: canvasTexture });
            }

            modelMeshes.forEach(mesh => {
                mesh.material = baseMaterial;
            });
        }

        document.getElementById('modelColor').addEventListener('input', e => {
            document.getElementById('colorPreview').style.background = e.target.value;
            applyMaterial();
        });

        document.getElementById('materialType').addEventListener('change', e => {
            materialType = e.target.value;
            applyMaterial();
        });

        document.getElementById('stylePreset').addEventListener('change', applyMaterial);

        document.getElementById('roughness').addEventListener('input', e => {
            document.getElementById('roughnessValue').textContent = e.target.value;
            applyMaterial();
        });

        document.getElementById('metalness').addEventListener('input', e => {
            document.getElementById('metalnessValue').textContent = e.target.value;
            applyMaterial();
        });

        document.getElementById('emissive').addEventListener('input', e => {
            document.getElementById('emissiveValue').textContent = e.target.value;
            applyMaterial();
        });

        document.getElementById('opacity').addEventListener('input', e => {
            document.getElementById('opacityValue').textContent = e.target.value;
            applyMaterial();
        });

        document.getElementById('bgColor').addEventListener('input', e => {
            scene.background = new THREE.Color(e.target.value);
            document.getElementById('bgColorPreview').style.background = e.target.value;
        });

        document.getElementById('envPreset').addEventListener('change', e => {
            const preset = envPresets[e.target.value];
            ambientLight.intensity = preset.ambient;
            directionalLight.intensity = preset.directional;
            scene.background = new THREE.Color(preset.bgColor);
            document.getElementById('bgColor').value = '#' + preset.bgColor.toString(16).padStart(6, '0');
            document.getElementById('bgColorPreview').style.background = document.getElementById('bgColor').value;
            document.getElementById('ambientValue').textContent = preset.ambient;
            document.getElementById('ambientLight').value = preset.ambient;
        });

        function fitModelToView(object) {
            const box = new THREE.Box3().setFromObject(object);
            const size = box.getSize(new THREE.Vector3()).length();
            const center = box.getCenter(new THREE.Vector3());
            controls.reset();
            object.position.sub(center);
            camera.near = size / 100;
            camera.far = size * 100;
            camera.updateProjectionMatrix();
            camera.position.z = size * 1.5;
            camera.lookAt(0, 0, 0);
        }

        const update3DTexture = debounce(() => {
            if (typeof THREE !== "undefined" && modelMeshes.length > 0) {
                const image = new Image();
                image.src = fabricCanvas.toDataURL("image/png");
                image.onload = () => {
                    const texture = new THREE.Texture(image);
                    texture.needsUpdate = true;
                    texture.wrapS = THREE.RepeatWrapping;
                    texture.wrapT = THREE.RepeatWrapping;
                    const scale = parseFloat(document.getElementById("textureScale").value);
                    texture.repeat.set(scale, scale);
                    modelMeshes.forEach(mesh => {
                        mesh.material.map = texture;
                        mesh.material.needsUpdate = true;
                    });
                };
                image.onerror = () => console.error("Error loading canvas texture");
            }
        }, 100);

        function animate() {
            requestAnimationFrame(animate);
            controls.update();
            renderer.render(scene, camera);
        }
        animate();

        window.addEventListener('resize', () => {
            camera.aspect = threeContainer.clientWidth / window.innerHeight;
            camera.updateProjectionMatrix();
            renderer.setSize(threeContainer.clientWidth, window.innerHeight);
        });

        let currentFill = "#ff0000";
        document.getElementById("fillColor").addEventListener("input", e => {
            currentFill = e.target.value;
            currentColor = currentFill;
            const activeObj = fabricCanvas.getActiveObject();
            if (activeObj) {
                activeObj.set("fill", currentFill);
                fabricCanvas.renderAll();
                update3DTexture();
            }
        });

        fabricCanvas.on('object:modified', update3DTexture);
    </script>
@endpush