<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Advanced 3D Model Editor</title>

  <!-- Three.js r128 + examples (global THREE namespace) -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r128/three.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/three@0.128.0/examples/js/controls/OrbitControls.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/three@0.128.0/examples/js/loaders/GLTFLoader.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/three@0.128.0/examples/js/exporters/GLTFExporter.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/three@0.128.0/examples/js/loaders/FontLoader.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/three@0.128.0/examples/js/geometries/TextGeometry.js"></script>

  <style>
    *{margin:0;padding:0;box-sizing:border-box;font-family:'Segoe UI', Tahoma, Geneva, Verdana, sans-serif}
    body{background:linear-gradient(135deg,#1a1a2e 0%,#16213e 100%);color:#fff;min-height:100vh;display:flex;flex-direction:column;overflow-x:hidden}
    header{padding:20px;text-align:center;background:rgba(0,0,0,.3);border-bottom:1px solid rgba(255,255,255,.1)}
    h1{font-size:2.5rem;margin-bottom:10px;background:linear-gradient(90deg,#4cc9f0,#4361ee);-webkit-background-clip:text;-webkit-text-fill-color:transparent}
    .container{display:flex;flex:1;padding:20px;gap:20px}
    @media (max-width:1024px){.container{flex-direction:column}}
    .viewer-container{flex:1;position:relative;border-radius:12px;overflow:hidden;box-shadow:0 10px 30px rgba(0,0,0,.5);background:#000;min-height:500px}
    #viewer{width:100%;height:100%;position:absolute;top:0;left:0}
    .loading{position:absolute;top:50%;left:50%;transform:translate(-50%,-50%);color:#fff;font-size:1.5rem;text-align:center;z-index:10}
    .spinner{border:5px solid rgba(255,255,255,.3);border-radius:50%;border-top:5px solid #4361ee;width:50px;height:50px;animation:spin 1s linear infinite;margin:0 auto 20px}
    @keyframes spin{0%{transform:rotate(0)}100%{transform:rotate(360deg)}}
    .editor-panel{width:360px;background:rgba(0,0,0,.3);border-radius:12px;padding:20px;box-shadow:0 5px 15px rgba(0,0,0,.3);overflow-y:auto;max-height:80vh}
    @media (max-width:1024px){.editor-panel{width:100%;max-height:none}}
    h2{margin-bottom:20px;color:#4cc9f0;font-size:1.5rem;padding-bottom:10px;border-bottom:1px solid rgba(255,255,255,.1)}
    .control-group{margin-bottom:20px;background:rgba(0,0,0,.2);padding:15px;border-radius:8px}
    .control-group h3{margin-bottom:15px;color:#4cc9f0;font-size:1.1rem}
    label{display:block;margin-bottom:8px;font-weight:500}
    input[type="range"]{width:100%;height:5px;-webkit-appearance:none;background:rgba(255,255,255,.2);border-radius:5px;outline:none}
    input[type="range"]::-webkit-slider-thumb{-webkit-appearance:none;width:20px;height:20px;border-radius:50%;background:#4361ee;cursor:pointer}
    button{background:linear-gradient(90deg,#4361ee,#4cc9f0);color:#fff;border:none;padding:12px 20px;border-radius:8px;cursor:pointer;font-weight:600;width:100%;margin:5px 0;transition:transform .2s,box-shadow .2s}
    button:hover{transform:translateY(-2px);box-shadow:0 5px 15px rgba(67,97,238,.4)}
    .btn-group{display:flex;gap:10px}.btn-group button{flex:1}
    footer{text-align:center;padding:20px;margin-top:auto;background:rgba(0,0,0,.3);border-top:1px solid rgba(255,255,255,.1)}
    .color-picker{display:flex;align-items:center;gap:10px;margin-bottom:15px}
    .color-preview{width:30px;height:30px;border-radius:50%;background:#4361ee;border:2px solid #fff}
    input[type="color"]{flex:1;height:35px;border:none;border-radius:5px;background:rgba(255,255,255,.1);cursor:pointer}
    select{width:100%;padding:10px;border-radius:8px;background:rgba(255,255,255,.1);color:#fff;border:1px solid rgba(255,255,255,.2);margin-bottom:15px}
    .slider-with-value{display:flex;align-items:center;gap:10px}
    .slider-with-value span{min-width:40px;text-align:right}
    .tab-buttons{display:flex;margin-bottom:20px;background:rgba(0,0,0,.2);border-radius:8px;overflow:hidden}
    .tab-button{flex:1;padding:10px;text-align:center;background:rgba(255,255,255,.1);cursor:pointer;transition:background .3s}
    .tab-button.active{background:#4361ee}
    .tab-content{display:none}.tab-content.active{display:block}
    .download-btn{background:linear-gradient(90deg,#00b09b,#96c93d);margin-top:20px}
    .reset-btn{background:linear-gradient(90deg,#ff5e62,#ff9966)}
    .error{color:#ff6b6b;text-align:center;margin-top:20px;display:none}
    .row{display:flex;gap:10px}
    .row > div{flex:1}
    .small{font-size:.85rem;opacity:.8}
    input[type="file"]{width:100%;padding:8px;border-radius:8px;background:rgba(255,255,255,.08);border:1px dashed rgba(255,255,255,.25);color:#fff}
  </style>
</head>
<body>
  <header>
    <h1>Advanced 3D Model Editor</h1>
    <p>Modify and customize your GLB model in real-time</p>
  </header>

  <div class="container">
    <div class="viewer-container">
      <div id="viewer"></div>
      <div class="loading" id="loading">
        <div class="spinner"></div>
        <p>Loading 3D Model...</p>
      </div>
      <div class="error" id="errorMessage">
        Failed to load the model. Check /models/hoody.glb or upload your own below.
      </div>
    </div>

    <div class="editor-panel">
      <div class="tab-buttons">
        <div class="tab-button active" data-tab="material">Material</div>
        <div class="tab-button" data-tab="transform">Transform</div>
        <div class="tab-button" data-tab="scene">Scene</div>
        <div class="tab-button" data-tab="file">File</div>
      </div>

      <!-- MATERIAL -->
      <div class="tab-content active" id="material-tab">
        <h2>Material Properties</h2>

        <div class="control-group">
          <h3>Color & Type</h3>
          <div class="color-picker">
            <div class="color-preview" id="colorPreview"></div>
            <input type="color" id="modelColor" value="#4361ee">
          </div>

          <label for="materialType">Material Type</label>
          <select id="materialType">
            <option value="standard">Standard (PBR)</option>
            <option value="physical">Physical (clearcoat)</option>
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

          <label for="emissive">Emissive Intensity</label>
          <div class="slider-with-value">
            <input type="range" id="emissive" min="0" max="3" step="0.01" value="0">
            <span id="emissiveValue">0</span>
          </div>

          <label for="opacity">Opacity</label>
          <div class="slider-with-value">
            <input type="range" id="opacity" min="0.05" max="1" step="0.01" value="1">
            <span id="opacityValue">1</span>
          </div>

          <div class="btn-group">
            <button id="toggleWireframe">Wireframe Off</button>
            <button id="toggleFlatShading">Smooth Shading</button>
          </div>
        </div>

        <div class="control-group">
          <h3>Texture</h3>
          <label class="small">Upload an image to use as a texture (optional)</label>
          <input type="file" id="textureFile" accept="image/*"/>
          <div class="row">
            <div>
              <label for="texRepeat">Repeat</label>
              <div class="slider-with-value">
                <input type="range" id="texRepeat" min="1" max="8" step="1" value="1">
                <span id="texRepeatValue">1</span>
              </div>
            </div>
            <div>
              <label for="texRotation">Rotation</label>
              <div class="slider-with-value">
                <input type="range" id="texRotation" min="0" max="360" step="1" value="0">
                <span id="texRotationValue">0°</span>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- TRANSFORM -->
      <div class="tab-content" id="transform-tab">
        <h2>Transform</h2>
        <div class="control-group">
          <h3>Position</h3>
          <label for="posX">X</label>
          <div class="slider-with-value"><input type="range" id="posX" min="-5" max="5" step="0.1" value="0"><span id="posXValue">0</span></div>
          <label for="posY">Y</label>
          <div class="slider-with-value"><input type="range" id="posY" min="-5" max="5" step="0.1" value="0"><span id="posYValue">0</span></div>
          <label for="posZ">Z</label>
          <div class="slider-with-value"><input type="range" id="posZ" min="-5" max="5" step="0.1" value="0"><span id="posZValue">0</span></div>
        </div>
        <div class="control-group">
          <h3>Rotation</h3>
          <label for="rotX">X</label>
          <div class="slider-with-value"><input type="range" id="rotX" min="-180" max="180" step="1" value="0"><span id="rotXValue">0°</span></div>
          <label for="rotY">Y</label>
          <div class="slider-with-value"><input type="range" id="rotY" min="-180" max="180" step="1" value="0"><span id="rotYValue">0°</span></div>
          <label for="rotZ">Z</label>
          <div class="slider-with-value"><input type="range" id="rotZ" min="-180" max="180" step="1" value="0"><span id="rotZValue">0°</span></div>
        </div>
        <div class="control-group">
          <h3>Scale</h3>
          <label for="scale">Uniform</label>
          <div class="slider-with-value"><input type="range" id="scale" min="0.1" max="3" step="0.1" value="1"><span id="scaleValue">1</span></div>
          <div class="btn-group">
            <button id="resetPosition">Reset Position</button>
            <button id="resetRotation">Reset Rotation</button>
          </div>
        </div>
      </div>

      <!-- SCENE -->
      <div class="tab-content" id="scene-tab">
        <h2>Scene Settings</h2>
        <div class="control-group">
          <h3>Lights</h3>
          <label for="lightIntensity">Directional Intensity</label>
          <div class="slider-with-value"><input type="range" id="lightIntensity" min="0" max="2" step="0.1" value="1"><span id="lightIntensityValue">1</span></div>
          <label for="ambientIntensity">Ambient Intensity</label>
          <div class="slider-with-value"><input type="range" id="ambientIntensity" min="0" max="1" step="0.1" value="0.4"><span id="ambientIntensityValue">0.4</span></div>
          <div class="btn-group">
            <button id="toggleAxes">Hide Axes</button>
            <button id="toggleHelpers">Hide Helpers</button>
          </div>
        </div>
        <div class="control-group">
          <h3>Environment</h3>
          <label for="bgColor">Background</label>
          <div class="color-picker">
            <div class="color-preview" id="bgColorPreview"></div>
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
        </div>
        <div class="control-group">
          <h3>Camera</h3>
          <label for="cameraAngle">Angle</label>
          <select id="cameraAngle">
            <option value="perspective">Perspective</option>
            <option value="front">Front</option>
            <option value="side">Side</option>
            <option value="top">Top</option>
            <option value="iso">Isometric</option>
          </select>
          <button id="resetCamera">Reset Camera</button>
        </div>
      </div>

      <!-- LABEL -->
      <div class="tab-content" id="label-tab">
        <h2>Name / Label</h2>
        <div class="control-group">
          <h3>Text</h3>
          <label for="labelText">Content</label>
          <input type="text" id="labelText" value="Your Name" />
          <label for="labelColor">Color</label>
          <div class="color-picker">
            <div class="color-preview" id="labelColorPreview" style="background:#ffffff"></div>
            <input type="color" id="labelColor" value="#ffffff">
          </div>
          <label for="labelSize">Size</label>
          <div class="slider-with-value">
            <input type="range" id="labelSize" min="0.02" max="0.5" step="0.01" value="0.1">
            <span id="labelSizeValue">0.10</span>
          </div>
          <div class="row">
            <div>
              <label for="labelPosX">X</label>
              <div class="slider-with-value"><input type="range" id="labelPosX" min="-1" max="1" step="0.01" value="0"><span id="labelPosXValue">0</span></div>
            </div>
            <div>
              <label for="labelPosY">Y</label>
              <div class="slider-with-value"><input type="range" id="labelPosY" min="-1" max="1" step="0.01" value="0.3"><span id="labelPosYValue">0.30</span></div>
            </div>
            <div>
              <label for="labelPosZ">Z</label>
              <div class="slider-with-value"><input type="range" id="labelPosZ" min="-1" max="1" step="0.01" value="0.2"><span id="labelPosZValue">0.20</span></div>
            </div>
          </div>
          <div class="btn-group">
            <button id="applyLabel">Apply/Update Label</button>
            <button id="toggleLabel">Hide Label</button>
          </div>
        </div>
      </div>

      <!-- FILE -->
      <div class="tab-content" id="file-tab">
        <h2>File</h2>
        <div class="control-group">
          <h3>Load Model</h3>
          <label class="small">Default path:</label>
          <code class="small">/models/hoody.glb</code>
          <div style="height:8px"></div>
          <input type="file" id="glbFile" accept=".glb,.gltf" />
        </div>
        <button class="download-btn" id="downloadBtn">Export GLB</button>
        <button class="reset-btn" id="resetAll">Reset All</button>
      </div>
    </div>
  </div>

  <footer><p>Powered by Three.js | GLTFLoader | OrbitControls | GLTFExporter</p></footer>

  <script>
    // ====== Globals ======
    let scene, camera, renderer, controls;
    let exportRoot;         // Group containing model + label (what we export)
    let model;              // Loaded model
    let labelMesh = null;   // Text mesh
    let font = null;        // Loaded font for TextGeometry
    let clock;
    let directionalLight, ambientLight, hemisphereLight;
    let axesHelper, gridHelper;
    let currentTexture = null;

    // ====== Init ======
    function init(){
      scene = new THREE.Scene();
      scene.background = new THREE.Color(0x1a1a2e);

      exportRoot = new THREE.Group();
      exportRoot.name = "ExportRoot";
      scene.add(exportRoot);

      camera = new THREE.PerspectiveCamera(75, window.innerWidth/window.innerHeight, 0.1, 1000);
      camera.position.set(2,2,5);

      renderer = new THREE.WebGLRenderer({ antialias:true });
      renderer.setSize(window.innerWidth, window.innerHeight);
      renderer.setPixelRatio(window.devicePixelRatio);
      renderer.shadowMap.enabled = true;
      document.getElementById('viewer').appendChild(renderer.domElement);

      controls = new THREE.OrbitControls(camera, renderer.domElement);
      controls.enableDamping = true;
      controls.dampingFactor = 0.05;

      // Lights
      ambientLight = new THREE.AmbientLight(0x404040, 0.4);
      scene.add(ambientLight);

      directionalLight = new THREE.DirectionalLight(0xffffff, 1);
      directionalLight.position.set(5,10,7.5);
      directionalLight.castShadow = true;
      scene.add(directionalLight);

      hemisphereLight = new THREE.HemisphereLight(0x4361ee, 0x4cc9f0, 0.5);
      scene.add(hemisphereLight);

      // Helpers
      axesHelper = new THREE.AxesHelper(3);
      scene.add(axesHelper);

      gridHelper = new THREE.GridHelper(10, 10, 0x666666, 0x333333);
      scene.add(gridHelper);

      // Clock
      clock = new THREE.Clock();

      // Load default font for text labels
      const fontLoader = new THREE.FontLoader();
      fontLoader.load(
        'https://threejs.org/examples/fonts/helvetiker_regular.typeface.json',
        (f)=>{ font = f; },
        undefined,
        (e)=>console.warn('Font load failed:', e)
      );

      // Load default model
      loadModel('/models/shirt_baked.glb');

      // Events
      window.addEventListener('resize', onWindowResize);

      // UI
      setupControls();

      animate();
    }

    // ====== Load Model (from path or File input) ======
    function loadModel(srcOrBlobURL){
      document.getElementById('loading').style.display = 'block';
      document.getElementById('errorMessage').style.display = 'none';

      // Clear existing model from exportRoot
      if(model){
        exportRoot.remove(model);
        model.traverse((c)=>{ if(c.isMesh){ c.geometry?.dispose?.(); c.material?.dispose?.(); } });
        model = null;
      }

      const loader = new THREE.GLTFLoader();
      loader.load(srcOrBlobURL, (gltf)=>{
        model = gltf.scene;
        exportRoot.add(model);

        // Center + fit camera
        centerAndFrame(model);

        // Store original materials
        initMaterialControls();

        // Apply current UI color immediately
        changeModelColor(document.getElementById('modelColor').value);

        // Hide loading
        document.getElementById('loading').style.display = 'none';
      }, undefined, (error)=>{
        console.error('Error loading model:', error);
        document.getElementById('loading').style.display = 'none';
        document.getElementById('errorMessage').style.display = 'block';
      });
    }

    function centerAndFrame(object){
      const box = new THREE.Box3().setFromObject(object);
      const center = box.getCenter(new THREE.Vector3());
      const size = box.getSize(new THREE.Vector3());

      object.position.x += (object.position.x - center.x);
      object.position.y += (object.position.y - center.y);
      object.position.z += (object.position.z - center.z);

      exportRoot.position.set(0,0,0); // keep the root centered

      const maxDim = Math.max(size.x, size.y, size.z);
      const fov = camera.fov * (Math.PI / 180);
      let cameraZ = Math.abs(maxDim / Math.sin(fov/2));
      cameraZ *= 1.5;

      camera.position.set(0, size.y*0.25, cameraZ);
      camera.lookAt(0,0,0);
      controls.update();
    }

    // ====== Material helpers ======
    function initMaterialControls(){
      if(!model) return;
      model.traverse((child)=>{
        if(child.isMesh){
          if(!child.userData.originalMaterial){
            child.userData.originalMaterial = child.material;
          }
          child.castShadow = true;
          child.receiveShadow = true;
        }
      });
    }

    function changeModelColor(color){
      if(!model) return;
      model.traverse((child)=>{
        if(child.isMesh){
          if(child.material && child.material.color){
            child.material.color.set(color);
          }
        }
      });
      document.getElementById('colorPreview').style.backgroundColor = color;
    }

    function changeMaterialType(type){
      if(!model) return;
      model.traverse((child)=>{
        if(!child.isMesh) return;
        const original = child.userData.originalMaterial || child.material;
        let paramsColor = (original.color && original.color.clone()) || new THREE.Color(document.getElementById('modelColor').value);

        let newMat;
        switch(type){
          case 'standard':
            newMat = new THREE.MeshStandardMaterial({
              color: paramsColor,
              roughness: child.material.roughness ?? 0.5,
              metalness: child.material.metalness ?? 0.5,
              map: child.material.map || null
            });
            break;
          case 'physical':
            newMat = new THREE.MeshPhysicalMaterial({
              color: paramsColor,
              roughness: child.material.roughness ?? 0.5,
              metalness: child.material.metalness ?? 0.5,
              clearcoat: 1.0,
              clearcoatRoughness: 0.1,
              map: child.material.map || null
            });
            break;
          case 'phong':
            newMat = new THREE.MeshPhongMaterial({
              color: paramsColor,
              shininess: 30,
              map: child.material.map || null
            });
            break;
          case 'matcap':
            newMat = new THREE.MeshMatcapMaterial({
              color: paramsColor
              // Note: For real matcap look, set .matcap texture too.
            });
            break;
          case 'toon':
            newMat = new THREE.MeshToonMaterial({
              color: paramsColor
              // You can add gradients via gradientMap for stronger toon steps
            });
            break;
          default:
            newMat = original.clone();
        }
        newMat.transparent = child.material.transparent;
        newMat.opacity = child.material.opacity;
        child.material = newMat;
      });
    }

    function applyStylePreset(preset){
      if(!model) return;
      model.traverse((child)=>{
        if(!child.isMesh || !child.material) return;
        const m = child.material;
        switch(preset){
          case 'matte':
            m.roughness = 0.9; m.metalness = 0.0; break;
          case 'glossy':
            m.roughness = 0.1; m.metalness = 0.0; break;
          case 'metallic':
            m.roughness = 0.2; m.metalness = 1.0; break;
          case 'toonPastel':
            changeMaterialType('toon');
            break;
          default: // none
            break;
        }
        m.needsUpdate = true;
      });

      // update sliders to reflect typical values (except toon)
      if(preset==='matte'){ setSlider('roughness',0.9); setSlider('metalness',0.0); }
      if(preset==='glossy'){ setSlider('roughness',0.1); setSlider('metalness',0.0); }
      if(preset==='metallic'){ setSlider('roughness',0.2); setSlider('metalness',1.0); }
    }

    function setSlider(id, value){
      const el = document.getElementById(id);
      el.value = value;
      document.getElementById(id+'Value').textContent = (id==='rotX'||id==='rotY'||id==='rotZ') ? (value+'°') : (''+Number(value).toFixed( (value%1===0)?0:2 ));
      el.dispatchEvent(new Event('input')); // trigger change handlers
    }

    // ====== Texture handling ======
    function applyTextureFromFile(file){
      if(!file || !model) return;
      const reader = new FileReader();
      reader.onload = function(e){
        const tex = new THREE.TextureLoader().load(e.target.result, ()=>{
          tex.wrapS = tex.wrapT = THREE.RepeatWrapping;
          const repeatVal = parseInt(document.getElementById('texRepeat').value, 10) || 1;
          tex.repeat.set(repeatVal, repeatVal);
          const rotDeg = parseInt(document.getElementById('texRotation').value, 10) || 0;
          tex.rotation = THREE.MathUtils.degToRad(rotDeg);
          tex.center.set(0.5,0.5);

          currentTexture = tex;

          model.traverse((child)=>{
            if(child.isMesh && child.material){
              child.material.map = tex;
              child.material.needsUpdate = true;
            }
          });
        });
      };
      reader.readAsDataURL(file);
    }

    function updateTextureRepeatRotation(){
      if(!currentTexture) return;
      const repeatVal = parseInt(document.getElementById('texRepeat').value, 10) || 1;
      const rotDeg = parseInt(document.getElementById('texRotation').value, 10) || 0;
      currentTexture.repeat.set(repeatVal, repeatVal);
      currentTexture.rotation = THREE.MathUtils.degToRad(rotDeg);
      currentTexture.center.set(0.5,0.5);
      document.getElementById('texRepeatValue').textContent = repeatVal;
      document.getElementById('texRotationValue').textContent = rotDeg + '°';
    }

    // ====== Label / Text ======
    function createOrUpdateLabel(){
      const text = document.getElementById('labelText').value || '';
      const color = document.getElementById('labelColor').value || '#ffffff';
      const size = parseFloat(document.getElementById('labelSize').value) || 0.1;
      const px = parseFloat(document.getElementById('labelPosX').value)||0;
      const py = parseFloat(document.getElementById('labelPosY').value)||0.3;
      const pz = parseFloat(document.getElementById('labelPosZ').value)||0.2;

      if(!font){
        alert('Font not loaded yet. Please try again in a moment.');
        return;
      }

      if(labelMesh){
        exportRoot.remove(labelMesh);
        labelMesh.geometry.dispose();
        labelMesh.material.dispose();
        labelMesh = null;
      }

      const geo = new THREE.TextGeometry(text, {
        font: font,
        size: size,
        height: Math.max(size*0.1, 0.01),
        curveSegments: 8,
        bevelEnabled: false
      });

      geo.computeBoundingBox();
      geo.center(); // center text

      const mat = new THREE.MeshStandardMaterial({ color: new THREE.Color(color), metalness: 0.1, roughness: 0.4 });
      labelMesh = new THREE.Mesh(geo, mat);
      labelMesh.position.set(px, py, pz);
      labelMesh.castShadow = true;
      exportRoot.add(labelMesh);

      document.getElementById('labelColorPreview').style.backgroundColor = color;
      document.getElementById('labelSizeValue').textContent = size.toFixed(2);
      document.getElementById('labelPosXValue').textContent = px.toFixed(2);
      document.getElementById('labelPosYValue').textContent = py.toFixed(2);
      document.getElementById('labelPosZValue').textContent = pz.toFixed(2);
    }

    // ====== Scene / Camera ======
    function onWindowResize(){
      camera.aspect = window.innerWidth/window.innerHeight;
      camera.updateProjectionMatrix();
      renderer.setSize(window.innerWidth, window.innerHeight);
    }

    function applyEnvPreset(preset){
      switch(preset){
        case 'bright':
          ambientLight.intensity = 0.7;
          directionalLight.intensity = 1.2;
          hemisphereLight.intensity = 0.7;
          scene.background = new THREE.Color('#dfe9f3');
          break;
        case 'sunset':
          ambientLight.intensity = 0.5;
          directionalLight.color.set('#ffd1a1');
          directionalLight.intensity = 1.0;
          hemisphereLight.color.set('#ffb37e');
          hemisphereLight.groundColor.set('#4d3a4a');
          hemisphereLight.intensity = 0.6;
          scene.background = new THREE.Color('#4b2e39');
          break;
        case 'night':
          ambientLight.intensity = 0.2;
          directionalLight.intensity = 0.6;
          hemisphereLight.intensity = 0.3;
          scene.background = new THREE.Color('#0b1020');
          break;
        case 'studio':
          ambientLight.intensity = 0.6;
          directionalLight.intensity = 1.1;
          hemisphereLight.color.set('#cde3ff');
          hemisphereLight.groundColor.set('#bcd1f0');
          hemisphereLight.intensity = 0.6;
          scene.background = new THREE.Color('#10131a');
          break;
        default: // default
          ambientLight.intensity = 0.4;
          directionalLight.color.set('#ffffff');
          directionalLight.intensity = 1.0;
          hemisphereLight.color.set('#4361ee');
          hemisphereLight.groundColor.set('#4cc9f0');
          hemisphereLight.intensity = 0.5;
          scene.background = new THREE.Color(document.getElementById('bgColor').value);
      }
    }

    function setCameraAngle(angle){
      const dist = 5;
      switch(angle){
        case 'front': camera.position.set(0, 1, dist); break;
        case 'side':  camera.position.set(dist, 1, 0); break;
        case 'top':   camera.position.set(0, dist, 0.001); break;
        case 'iso':   camera.position.set(3, 3, 3); break;
        default:      camera.position.set(2, 2, 5); break;
      }
      camera.lookAt(0,0,0);
      controls.update();
    }

    // ====== Export GLB ======
    function exportGLB(){
      const exporter = new THREE.GLTFExporter();
      exporter.parse(
        exportRoot,
        (result)=>{
          // result is an ArrayBuffer (binary) when {binary:true}
          const blob = new Blob([result], { type: 'model/gltf-binary' });
          const url = URL.createObjectURL(blob);
          const a = document.createElement('a');
          a.href = url;
          a.download = 'custom.glb';
          a.click();
          URL.revokeObjectURL(url);
        },
        { binary: true }
      );
    }

    // ====== Animate ======
    function animate(){
      requestAnimationFrame(animate);
      const delta = clock.getDelta();
      controls.update();
      renderer.render(scene, camera);
    }

    // ====== UI Wiring ======
    function setupControls(){
      // Tabs
      document.querySelectorAll('.tab-button').forEach(btn=>{
        btn.addEventListener('click', function(){
          document.querySelectorAll('.tab-button').forEach(b=>b.classList.remove('active'));
          document.querySelectorAll('.tab-content').forEach(c=>c.classList.remove('active'));
          this.classList.add('active');
          document.getElementById(this.getAttribute('data-tab')+'-tab').classList.add('active');
        });
      });

      // Color
      document.getElementById('modelColor').addEventListener('input', function(){ changeModelColor(this.value); });

      // Material type
      document.getElementById('materialType').addEventListener('change', function(){ changeMaterialType(this.value); });

      // Style preset
      document.getElementById('stylePreset').addEventListener('change', function(){ applyStylePreset(this.value); });

      // Material sliders
      document.getElementById('roughness').addEventListener('input', function(){
        const v = parseFloat(this.value);
        if(model){
          model.traverse((child)=>{ if(child.isMesh && child.material.roughness !== undefined){ child.material.roughness = v; } });
        }
        document.getElementById('roughnessValue').textContent = v.toFixed(2);
      });

      document.getElementById('metalness').addEventListener('input', function(){
        const v = parseFloat(this.value);
        if(model){
          model.traverse((child)=>{ if(child.isMesh && child.material.metalness !== undefined){ child.material.metalness = v; } });
        }
        document.getElementById('metalnessValue').textContent = v.toFixed(2);
      });

      document.getElementById('emissive').addEventListener('input', function(){
        const v = parseFloat(this.value);
        if(model){
          model.traverse((child)=>{ if(child.isMesh && child.material.emissive !== undefined){ child.material.emissiveIntensity = v; } });
        }
        document.getElementById('emissiveValue').textContent = v.toFixed(2);
      });

      document.getElementById('opacity').addEventListener('input', function(){
        const v = parseFloat(this.value);
        if(model){
          model.traverse((child)=>{ if(child.isMesh && child.material){ child.material.opacity = v; child.material.transparent = v < 1; } });
        }
        document.getElementById('opacityValue').textContent = v.toFixed(2);
      });

      // Wireframe / Flat shading
      document.getElementById('toggleWireframe').addEventListener('click', function(){
        if(!model) return;
        let on = false;
        model.traverse((child)=>{
          if(child.isMesh && child.material){
            child.material.wireframe = !child.material.wireframe;
            on = child.material.wireframe;
          }
        });
        this.textContent = on ? 'Wireframe On' : 'Wireframe Off';
      });

      document.getElementById('toggleFlatShading').addEventListener('click', function(){
        if(!model) return;
        let isFlat = false;
        model.traverse((child)=>{
          if(child.isMesh && child.material){
            child.material.flatShading = !child.material.flatShading;
            isFlat = child.material.flatShading;
            child.material.needsUpdate = true;
          }
        });
        this.textContent = isFlat ? 'Flat Shading' : 'Smooth Shading';
      });

      // Texture
      document.getElementById('textureFile').addEventListener('change', function(){
        if(this.files && this.files[0]) applyTextureFromFile(this.files[0]);
      });
      document.getElementById('texRepeat').addEventListener('input', updateTextureRepeatRotation);
      document.getElementById('texRotation').addEventListener('input', updateTextureRepeatRotation);

      // Transform
      document.getElementById('posX').addEventListener('input', function(){ if(model){ model.position.x = parseFloat(this.value); } document.getElementById('posXValue').textContent = (+this.value).toFixed(1);});
      document.getElementById('posY').addEventListener('input', function(){ if(model){ model.position.y = parseFloat(this.value); } document.getElementById('posYValue').textContent = (+this.value).toFixed(1);});
      document.getElementById('posZ').addEventListener('input', function(){ if(model){ model.position.z = parseFloat(this.value); } document.getElementById('posZValue').textContent = (+this.value).toFixed(1);});

      document.getElementById('rotX').addEventListener('input', function(){ if(model){ model.rotation.x = THREE.MathUtils.degToRad(parseFloat(this.value)); } document.getElementById('rotXValue').textContent = this.value+'°';});
      document.getElementById('rotY').addEventListener('input', function(){ if(model){ model.rotation.y = THREE.MathUtils.degToRad(parseFloat(this.value)); } document.getElementById('rotYValue').textContent = this.value+'°';});
      document.getElementById('rotZ').addEventListener('input', function(){ if(model){ model.rotation.z = THREE.MathUtils.degToRad(parseFloat(this.value)); } document.getElementById('rotZValue').textContent = this.value+'°';});

      document.getElementById('scale').addEventListener('input', function(){ const v=parseFloat(this.value); if(model){ model.scale.set(v,v,v); } document.getElementById('scaleValue').textContent = v.toFixed(1); });

      document.getElementById('resetPosition').addEventListener('click', function(){ if(model){ model.position.set(0,0,0);} setSlider('posX',0); setSlider('posY',0); setSlider('posZ',0); });
      document.getElementById('resetRotation').addEventListener('click', function(){ if(model){ model.rotation.set(0,0,0);} setSlider('rotX',0); setSlider('rotY',0); setSlider('rotZ',0); });

      // Scene
      document.getElementById('lightIntensity').addEventListener('input', function(){ const v=parseFloat(this.value); directionalLight.intensity=v; document.getElementById('lightIntensityValue').textContent=v.toFixed(1);});
      document.getElementById('ambientIntensity').addEventListener('input', function(){ const v=parseFloat(this.value); ambientLight.intensity=v; document.getElementById('ambientIntensityValue').textContent=v.toFixed(1);});
      document.getElementById('bgColor').addEventListener('input', function(){ scene.background = new THREE.Color(this.value); document.getElementById('bgColorPreview').style.backgroundColor = this.value; });
      document.getElementById('toggleAxes').addEventListener('click', function(){ axesHelper.visible=!axesHelper.visible; this.textContent = axesHelper.visible ? 'Hide Axes' : 'Show Axes';});
      document.getElementById('toggleHelpers').addEventListener('click', function(){ gridHelper.visible=!gridHelper.visible; this.textContent = gridHelper.visible ? 'Hide Helpers' : 'Show Helpers';});
      document.getElementById('envPreset').addEventListener('change', function(){ applyEnvPreset(this.value); });
      document.getElementById('cameraAngle').addEventListener('change', function(){ setCameraAngle(this.value); });
      document.getElementById('resetCamera').addEventListener('click', function(){ controls.reset(); camera.position.set(2,2,5); camera.lookAt(0,0,0); });

      // Label
      document.getElementById('applyLabel').addEventListener('click', createOrUpdateLabel);
      document.getElementById('toggleLabel').addEventListener('click', function(){
        if(!labelMesh) return;
        labelMesh.visible = !labelMesh.visible;
        this.textContent = labelMesh.visible ? 'Hide Label' : 'Show Label';
      });
      document.getElementById('labelColor').addEventListener('input', function(){ document.getElementById('labelColorPreview').style.backgroundColor = this.value; });

      // File (GLB upload)
      document.getElementById('glbFile').addEventListener('change', function(){
        const f = this.files && this.files[0];
        if(!f) return;
        const url = URL.createObjectURL(f);
        loadModel(url);
      });

      // Export / Reset All
      document.getElementById('downloadBtn').addEventListener('click', exportGLB);

      document.getElementById('resetAll').addEventListener('click', function(){
        // Reset materials
        if(model){
          model.traverse((child)=>{
            if(child.isMesh && child.userData.originalMaterial){
              child.material = child.userData.originalMaterial;
              child.material.transparent = false;
              child.material.opacity = 1;
              child.material.wireframe = false;
              child.material.flatShading = false;
              child.material.needsUpdate = true;
            }
          });
          // Reset transforms
          model.position.set(0,0,0);
          model.rotation.set(0,0,0);
          model.scale.set(1,1,1);
        }

        // Remove label
        if(labelMesh){
          exportRoot.remove(labelMesh);
          labelMesh.geometry.dispose(); labelMesh.material.dispose();
          labelMesh = null;
          document.getElementById('toggleLabel').textContent = 'Hide Label';
        }

        // Reset UI
        setSlider('roughness',0.5); setSlider('metalness',0.5); setSlider('emissive',0); setSlider('opacity',1);
        setSlider('posX',0); setSlider('posY',0); setSlider('posZ',0);
        setSlider('rotX',0); setSlider('rotY',0); setSlider('rotZ',0);
        setSlider('scale',1);
        document.getElementById('materialType').value='standard';
        document.getElementById('stylePreset').value='none';
        document.getElementById('modelColor').value='#4361ee'; changeModelColor('#4361ee');
        document.getElementById('colorPreview').style.backgroundColor='#4361ee';

        // Texture reset
        currentTexture = null;

        // Scene
        document.getElementById('bgColor').value = '#1a1a2e'; scene.background = new THREE.Color('#1a1a2e');
        document.getElementById('bgColorPreview').style.backgroundColor='#1a1a2e';
        axesHelper.visible = true; gridHelper.visible = true;
        ambientLight.intensity = 0.4; directionalLight.intensity = 1; hemisphereLight.intensity = 0.5;
        directionalLight.color.set('#ffffff'); hemisphereLight.color.set('#4361ee'); hemisphereLight.groundColor.set('#4cc9f0');
        document.getElementById('envPreset').value='default';

        // Camera
        controls.reset(); camera.position.set(2,2,5); camera.lookAt(0,0,0);
      });
    }

    // ====== Start ======
    window.addEventListener('load', init);
  </script>
</body>
</html>
