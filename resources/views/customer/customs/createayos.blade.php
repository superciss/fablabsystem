<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Enhanced 3D Model Editor with Texture Mapping</title>
  <style>
    :root {
      --primary-color: #3498db;
      --secondary-color: #2ecc71;
      --dark-color: #2c3e50;
      --light-color: #ecf0f1;
      --accent-color: #e74c3c;
    }
    
    body {
      margin: 0;
      overflow: hidden;
      background: linear-gradient(135deg, #1a2a6c, #b21f1f, #fdbb2d);
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      color: var(--light-color);
    }
    
    .container {
      display: flex;
      height: 100vh;
    }
    
    #threeContainer {
      width: 50%;
      height: 100%;
      position: relative;
    }
    
    #fabricContainer {
      width: 50%;
      height: 100%;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: flex-start;
      padding: 10px;
      background: rgba(255, 255, 255, 0.1);
      backdrop-filter: blur(10px);
      border-left: 1px solid rgba(255, 255, 255, 0.2);
    }
    
    #fabricCanvas {
      border: 2px solid rgba(255, 255, 255, 0.3);
      border-radius: 8px;
      margin-top: 15px;
      box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
    }
    
    #controls {
      position: absolute;
      top: 15px;
      left: 15px;
      background: rgba(25, 25, 35, 0.8);
      padding: 15px;
      border-radius: 10px;
      z-index: 10;
      backdrop-filter: blur(5px);
      border: 1px solid rgba(255, 255, 255, 0.1);
      box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
      width: 250px;
    }
    
    .control-group {
      margin-bottom: 15px;
      padding-bottom: 10px;
      border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    }
    
    .control-group:last-child {
      border-bottom: none;
    }
    
    .control-group h3 {
      margin: 0 0 10px 0;
      font-size: 16px;
      color: var(--light-color);
      display: flex;
      align-items: center;
    }
    
    .control-group h3::before {
      content: "•";
      margin-right: 8px;
      color: var(--secondary-color);
    }
    
    #fabricTools {
      display: flex;
      gap: 8px;
      flex-wrap: wrap;
      margin-top: 5px;
      justify-content: center;
    }
    
    input[type="file"] {
      width: 100%;
      margin-bottom: 10px;
      background: rgba(255, 255, 255, 0.1);
      border-radius: 4px;
      padding: 8px;
      color: var(--light-color);
      border: 1px solid rgba(255, 255, 255, 0.2);
    }
    
    input[type="range"] {
      width: 100%;
      margin: 5px 0;
    }
    
    button {
      padding: 8px 12px;
      cursor: pointer;
      border: none;
      border-radius: 4px;
      background: var(--primary-color);
      color: white;
      font-weight: 500;
      transition: all 0.2s ease;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 5px;
    }
    
    button:hover {
      background: #2980b9;
      transform: translateY(-2px);
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    }
    
    button:active {
      transform: translateY(0);
    }
    
    .icon-btn {
      min-width: 40px;
      padding: 8px;
    }
    
    .color-picker {
      display: flex;
      gap: 5px;
      margin: 10px 0;
    }
    
    .color-option {
      width: 25px;
      height: 25px;
      border-radius: 50%;
      cursor: pointer;
      border: 2px solid transparent;
      transition: all 0.2s ease;
    }
    
    .color-option:hover {
      transform: scale(1.1);
    }
    
    .color-option.active {
      border-color: white;
    }
    
    .slider-container {
      display: flex;
      align-items: center;
      gap: 10px;
      margin: 8px 0;
    }
    
    .slider-container label {
      min-width: 80px;
      font-size: 14px;
    }
    
    .slider-value {
      min-width: 30px;
      text-align: center;
      background: rgba(255, 255, 255, 0.1);
      padding: 2px 5px;
      border-radius: 4px;
    }
    
    .tab-container {
      display: flex;
      margin-bottom: 10px;
    }
    
    .tab {
      padding: 8px 15px;
      background: rgba(255, 255, 255, 0.1);
      cursor: pointer;
      border-radius: 4px 4px 0 0;
      margin-right: 5px;
      font-size: 14px;
    }
    
    .tab.active {
      background: var(--primary-color);
    }
    
    .stats {
      position: absolute;
      bottom: 10px;
      left: 10px;
      background: rgba(0, 0, 0, 0.5);
      padding: 5px 10px;
      border-radius: 3px;
      font-size: 12px;
    }
  </style>
</head>
<body>
  <div class="container">
    <!-- 3D & Fabric Controls -->
    <div id="controls">
      <div class="tab-container">
        <div class="tab active" data-tab="model">Model</div>
        <div class="tab" data-tab="scene">Scene</div>
        <div class="tab" data-tab="texture">Texture</div>
      </div>
      
      <div class="control-group" id="model-controls">
        <h3>Model Controls</h3>
        <label for="glbUpload">Upload GLB:</label>
        <input type="file" id="glbUpload" accept=".glb">
        
        <div class="slider-container">
          <label>Rotation X:</label>
          <input type="range" id="rotateX" min="-180" max="180" value="0">
          <span class="slider-value" id="rotateXValue">0°</span>
        </div>
        
        <div class="slider-container">
          <label>Rotation Y:</label>
          <input type="range" id="rotateY" min="-180" max="180" value="0">
          <span class="slider-value" id="rotateYValue">0°</span>
        </div>
        
        <div class="slider-container">
          <label>Scale:</label>
          <input type="range" id="modelScale" min="0.1" max="2" step="0.1" value="1">
          <span class="slider-value" id="scaleValue">1.0</span>
        </div>
        
        <button id="fitModel">Fit Model</button>
        <button id="resetModel">Reset Transform</button>
      </div>
      
      <div class="control-group" id="scene-controls" style="display: none;">
        <h3>Scene Settings</h3>
        
        <div class="slider-container">
          <label>Ambient Light:</label>
          <input type="range" id="ambientLight" min="0" max="1" step="0.1" value="0.6">
          <span class="slider-value" id="ambientValue">0.6</span>
        </div>
        
        <div class="slider-container">
          <label>Directional Light:</label>
          <input type="range" id="directionalLight" min="0" max="2" step="0.1" value="1">
          <span class="slider-value" id="directionalValue">1.0</span>
        </div>
        
        <button id="toggleGrid">Toggle Grid</button>
        <button id="toggleAxes">Toggle Axes</button>
      </div>
      
      <div class="control-group" id="texture-controls" style="display: none;">
        <h3>Texture Controls</h3>
        <label for="textureUpload">Upload Texture:</label>
        <input type="file" id="textureUpload" accept="image/*">
        
        <div class="slider-container">
          <label>Texture Scale:</label>
          <input type="range" id="textureScale" min="0.1" max="5" step="0.1" value="1">
          <span class="slider-value" id="textureScaleValue">1.0</span>
        </div>
        
        <button id="clearTexture">Clear Texture</button>
        <button id="exportTexture">Export Texture</button>
      </div>
    </div>

    <div id="threeContainer"></div>
    <div id="fabricContainer">
      <div id="fabricTools">
        <button id="addText" class="icon-btn" title="Add Text">T</button>
        <button id="addRect" class="icon-btn" title="Rectangle">□</button>
        <button id="addCircle" class="icon-btn" title="Circle">○</button>
        <button id="addTriangle" class="icon-btn" title="Triangle">△</button>
        <button id="bringFront" title="Bring to Front">↑</button>
        <button id="sendBack" title="Send to Back">↓</button>
        <button id="removeObj" title="Remove Selected">✕</button>
        <button id="clearCanvas" title="Clear Canvas">Clear</button>
      </div>
      
      <div class="color-picker">
        <div class="color-option active" style="background-color: #ff0000;" data-color="#ff0000"></div>
        <div class="color-option" style="background-color: #00ff00;" data-color="#00ff00"></div>
        <div class="color-option" style="background-color: #0000ff;" data-color="#0000ff"></div>
        <div class="color-option" style="background-color: #ffff00;" data-color="#ffff00"></div>
        <div class="color-option" style="background-color: #ff00ff;" data-color="#ff00ff"></div>
        <div class="color-option" style="background-color: #000000;" data-color="#000000"></div>
        <div class="color-option" style="background-color: #ffffff;" data-color="#ffffff"></div>
      </div>
      
      <canvas id="fabricCanvas" width="512" height="512"></canvas>
    </div>
  </div>

  <div class="stats" id="stats"></div>

  <!-- Libraries -->
  <script src="https://cdn.jsdelivr.net/npm/three@0.132.2/build/three.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/three@0.132.2/examples/js/loaders/GLTFLoader.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/three@0.132.2/examples/js/controls/OrbitControls.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/fabric.js/5.3.1/fabric.min.js"></script>

  <script>
    // ------------------ THREE.JS -------------------
    const threeContainer = document.getElementById('threeContainer');
    const scene = new THREE.Scene();
    scene.background = new THREE.Color(0x1a1a23);
    const camera = new THREE.PerspectiveCamera(75, threeContainer.clientWidth / window.innerHeight, 0.1, 1000);
    const renderer = new THREE.WebGLRenderer({ antialias: true });
    renderer.setSize(threeContainer.clientWidth, window.innerHeight);
    renderer.shadowMap.enabled = true;
    threeContainer.appendChild(renderer.domElement);

    // Enhanced lighting
    const ambientLight = new THREE.AmbientLight(0xffffff, 0.6);
    scene.add(ambientLight);
    const directionalLight = new THREE.DirectionalLight(0xffffff, 1);
    directionalLight.position.set(5, 5, 5);
    directionalLight.castShadow = true;
    scene.add(directionalLight);

    // Add hemisphere light for more natural lighting
    const hemisphereLight = new THREE.HemisphereLight(0xffffbb, 0x080820, 0.5);
    scene.add(hemisphereLight);

    const controls = new THREE.OrbitControls(camera, renderer.domElement);
    controls.enableDamping = true;
    controls.dampingFactor = 0.05;
    camera.position.set(0, 2, 5);

    // Add grid helper
    const gridHelper = new THREE.GridHelper(10, 10, 0x888888, 0x444444);
    scene.add(gridHelper);
    
    // Add axes helper
    const axesHelper = new THREE.AxesHelper(3);
    scene.add(axesHelper);

    let modelMesh = null;
    let canvasTexture = null;
    let currentModel = null;

    // ------------------ FABRIC -------------------
    const fabricCanvas = new fabric.Canvas('fabricCanvas', {
      backgroundColor: 'white',
      preserveObjectStacking: true
    });

    // Set current color
    let currentColor = '#ff0000';
    document.querySelectorAll('.color-option').forEach(option => {
      option.addEventListener('click', function() {
        document.querySelector('.color-option.active').classList.remove('active');
        this.classList.add('active');
        currentColor = this.getAttribute('data-color');
        
        const activeObject = fabricCanvas.getActiveObject();
        if (activeObject) {
          activeObject.set('fill', currentColor);
          fabricCanvas.renderAll();
        }
      });
    });

    // CanvasTexture for Three.js
    canvasTexture = new THREE.CanvasTexture(fabricCanvas.getElement());
    canvasTexture.flipY = false;
    canvasTexture.minFilter = THREE.LinearFilter;
    canvasTexture.magFilter = THREE.LinearFilter;

    // Update texture on render
    fabricCanvas.on('after:render', function () {
      if (canvasTexture) canvasTexture.needsUpdate = true;
    });

    // ------------------ TAB SWITCHING -------------------
    document.querySelectorAll('.tab').forEach(tab => {
      tab.addEventListener('click', function() {
        document.querySelector('.tab.active').classList.remove('active');
        this.classList.add('active');
        
        const tabName = this.getAttribute('data-tab');
        document.querySelectorAll('.control-group').forEach(group => {
          group.style.display = 'none';
        });
        document.getElementById(`${tabName}-controls`).style.display = 'block';
      });
    });

    // ------------------ UPLOAD GLB -------------------
    document.getElementById('glbUpload').addEventListener('change', function (event) {
      const file = event.target.files[0];
      if (!file) return;

      // Remove previous model if exists
      if (currentModel) {
        scene.remove(currentModel);
      }

      const reader = new FileReader();
      reader.onload = function (e) {
        const loader = new THREE.GLTFLoader();
        loader.parse(e.target.result, '', function (gltf) {
          currentModel = gltf.scene;
          scene.add(currentModel);

          // Enable shadows for all meshes
          currentModel.traverse(function (child) {
            if (child.isMesh) {
              child.castShadow = true;
              child.receiveShadow = true;
              
              if (!modelMesh) {
                modelMesh = child;
                modelMesh.material.map = canvasTexture;
                modelMesh.material.needsUpdate = true;
              }
            }
          });

          fitModelToView(currentModel);
          updateStats();
        });
      };
      reader.readAsArrayBuffer(file);
    });

    // ------------------ UPLOAD IMAGE TO FABRIC -------------------
    document.getElementById('textureUpload').addEventListener('change', function (event) {
      const file = event.target.files[0];
      if (!file) return;

      const reader = new FileReader();
      reader.onload = function (e) {
        fabric.Image.fromURL(e.target.result, function (img) {
          img.set({
            left: fabricCanvas.width / 2,
            top: fabricCanvas.height / 2,
            originX: 'center',
            originY: 'center',
            scaleX: 0.5,
            scaleY: 0.5
          });
          fabricCanvas.add(img);
          fabricCanvas.setActiveObject(img);
        });
      };
      reader.readAsDataURL(file);
    });

    // ------------------ FABRIC TOOLS -------------------
    document.getElementById('addText').onclick = () => {
      const text = new fabric.Textbox("New Text", { 
        left: 150, 
        top: 150, 
        fontSize: 24, 
        fill: currentColor,
        fontFamily: 'Arial'
      });
      fabricCanvas.add(text).setActiveObject(text);
    };
    
    document.getElementById('addRect').onclick = () => {
      const rect = new fabric.Rect({ 
        left: 100, 
        top: 100, 
        width: 100, 
        height: 60, 
        fill: currentColor,
        stroke: '#000000',
        strokeWidth: 1
      });
      fabricCanvas.add(rect).setActiveObject(rect);
    };
    
    document.getElementById('addCircle').onclick = () => {
      const circle = new fabric.Circle({ 
        left: 150, 
        top: 150, 
        radius: 50, 
        fill: currentColor,
        stroke: '#000000',
        strokeWidth: 1
      });
      fabricCanvas.add(circle).setActiveObject(circle);
    };
    
    document.getElementById('addTriangle').onclick = () => {
      const tri = new fabric.Triangle({ 
        left: 200, 
        top: 200, 
        width: 100, 
        height: 100, 
        fill: currentColor,
        stroke: '#000000',
        strokeWidth: 1
      });
      fabricCanvas.add(tri).setActiveObject(tri);
    };
    
    document.getElementById('bringFront').onclick = () => {
      const obj = fabricCanvas.getActiveObject();
      if (obj) fabricCanvas.bringToFront(obj);
    };
    
    document.getElementById('sendBack').onclick = () => {
      const obj = fabricCanvas.getActiveObject();
      if (obj) fabricCanvas.sendToBack(obj);
    };
    
    document.getElementById('removeObj').onclick = () => {
      const obj = fabricCanvas.getActiveObject();
      if (obj) fabricCanvas.remove(obj);
    };
    
    document.getElementById('clearCanvas').onclick = () => {
      fabricCanvas.clear();
      fabricCanvas.setBackgroundColor('white', fabricCanvas.renderAll.bind(fabricCanvas));
    };

    // ------------------ 3D TOOLS -------------------
    document.getElementById('fitModel').onclick = () => {
      if (currentModel) fitModelToView(currentModel);
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
    
    document.getElementById('clearTexture').onclick = () => {
      if (modelMesh) {
        modelMesh.material.map = null;
        modelMesh.material.needsUpdate = true;
      }
    };
    
    document.getElementById('exportTexture').onclick = () => {
      const link = document.createElement('a');
      link.download = 'texture.png';
      link.href = fabricCanvas.toDataURL({
        format: 'png',
        quality: 1
      });
      link.click();
    };
    
    document.getElementById('toggleGrid').onclick = () => {
      gridHelper.visible = !gridHelper.visible;
    };
    
    document.getElementById('toggleAxes').onclick = () => {
      axesHelper.visible = !axesHelper.visible;
    };

    // Rotation sliders
    document.getElementById('rotateX').addEventListener('input', function() {
      if (currentModel) {
        currentModel.rotation.x = THREE.MathUtils.degToRad(this.value);
        document.getElementById('rotateXValue').textContent = `${this.value}°`;
      }
    });
    
    document.getElementById('rotateY').addEventListener('input', function() {
      if (currentModel) {
        currentModel.rotation.y = THREE.MathUtils.degToRad(this.value);
        document.getElementById('rotateYValue').textContent = `${this.value}°`;
      }
    });
    
    document.getElementById('modelScale').addEventListener('input', function() {
      if (currentModel) {
        const scale = parseFloat(this.value);
        currentModel.scale.set(scale, scale, scale);
        document.getElementById('scaleValue').textContent = scale.toFixed(1);
      }
    });
    
    document.getElementById('ambientLight').addEventListener('input', function() {
      ambientLight.intensity = parseFloat(this.value);
      document.getElementById('ambientValue').textContent = this.value;
    });
    
    document.getElementById('directionalLight').addEventListener('input', function() {
      directionalLight.intensity = parseFloat(this.value);
      document.getElementById('directionalValue').textContent = this.value;
    });
    
    document.getElementById('textureScale').addEventListener('input', function() {
      if (modelMesh && modelMesh.material.map) {
        const scale = parseFloat(this.value);
        modelMesh.material.map.repeat.set(scale, scale);
        modelMesh.material.needsUpdate = true;
        document.getElementById('textureScaleValue').textContent = scale.toFixed(1);
      }
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

      camera.position.copy(center);
      camera.position.z += size * 1.5;
      camera.lookAt(center);
    }
    
    function updateStats() {
      const statsElement = document.getElementById('stats');
      if (currentModel) {
        const box = new THREE.Box3().setFromObject(currentModel);
        const size = box.getSize(new THREE.Vector3());
        statsElement.textContent = `Size: ${size.x.toFixed(2)} x ${size.y.toFixed(2)} x ${size.z.toFixed(2)}`;
      } else {
        statsElement.textContent = 'No model loaded';
      }
    }

    // ------------------ RENDER LOOP -------------------
    function animate() {
      requestAnimationFrame(animate);
      controls.update();
      renderer.render(scene, camera);
    }
    animate();

    // ------------------ RESIZE -------------------
    window.addEventListener('resize', () => {
      camera.aspect = threeContainer.clientWidth / window.innerHeight;
      camera.updateProjectionMatrix();
      renderer.setSize(threeContainer.clientWidth, window.innerHeight);
    });
  </script>
</body>
</html>