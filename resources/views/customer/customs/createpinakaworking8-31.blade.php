<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>3D Model Editor</title>
  <style>
    body {
      margin: 0;
      overflow: hidden;
      font-family: 'Arial', sans-serif;
      background: linear-gradient(135deg, #1a2a6c, #b21f1f);
    }
    .container {
      display: flex;
      height: 100vh;
    }
    #threeContainer {
      width: 50%;
      height: 100%;
    }
    #fabricContainer {
      width: 50%;
      padding: 10px;
      background: rgba(255, 255, 255, 0.1);
      display: flex;
      flex-direction: column;
      align-items: center;
    }
    #fabricCanvas {
      border: 2px solid rgba(255, 255, 255, 0.3);
      border-radius: 8px;
      margin-top: 10px;
    }
    .editor-panel {
      position: absolute;
      top: 10px;
      left: 10px;
      background: rgba(0, 0, 0, 0.7);
      padding: 10px;
      border-radius: 8px;
      color: #fff;
      width: 220px;
    }
    .tab-buttons {
      display: flex;
      gap: 5px;
      margin-bottom: 10px;
    }
    .tab-button {
      padding: 6px 10px;
      cursor: pointer;
      background: rgba(255, 255, 255, 0.1);
      border-radius: 4px;
    }
    .tab-button.active {
      background: #3498db;
    }
    .tab-content {
      display: none;
    }
    .tab-content.active {
      display: block;
    }
    .control-group {
      margin-bottom: 10px;
    }
    .control-group h3 {
      font-size: 14px;
      margin: 5px 0;
    }
    input[type="range"] {
      width: 100%;
    }
    .slider-with-value {
      display: flex;
      align-items: center;
      gap: 10px;
    }
    .slider-with-value span {
      min-width: 30px;
    }
    .color-picker {
      display: flex;
      gap: 5px;
      align-items: center;
    }
    .color-preview {
      width: 20px;
      height: 20px;
      border-radius: 4px;
      border: 1px solid #fff;
    }
    button {
      padding: 6px 10px;
      border: none;
      border-radius: 4px;
      background: #3498db;
      color: #fff;
      cursor: pointer;
    }
    button:hover {
      background: #2980b9;
    }
    .icon-btn {
      padding: 6px;
      min-width: 30px;
    }
    .color-option {
      width: 20px;
      height: 20px;
      border-radius: 50%;
      cursor: pointer;
      border: 2px solid transparent;
    }
    .color-option.active {
      border-color: #fff;
    }
    #fabricTools {
      display: flex;
      gap: 5px;
      margin: 10px 0;
    }
    select {
      width: 100%;
      padding: 5px;
      border-radius: 4px;
      background: rgba(255, 255, 255, 0.1);
      color: #fff;
      border: 1px solid rgba(255, 255, 255, 0.3);
    }
  </style>
</head>
<body>
  <div class="container">
    <div class="editor-panel">
      <div class="tab-buttons">
        <div class="tab-button active" data-tab="material">Material</div>
        <div class="tab-button" data-tab="transform">Transform</div>
        <div class="tab-button" data-tab="scene">Scene</div>
        <div class="tab-button" data-tab="file">File</div>
        <div class="tab-button" data-tab="texture">Texture</div>
      </div>

      <div class="tab-content active" id="material-tab">
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
        </div>
      </div>

      <div class="tab-content" id="transform-tab">
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
          <button id="resetModel">Reset</button>
        </div>
      </div>

      <div class="tab-content" id="scene-tab">
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
          <button id="toggleGrid">Toggle Grid</button>
        </div>
      </div>

      <div class="tab-content" id="file-tab">
        <div class="control-group">
          <h3>File</h3>
          <label for="glbUpload">Upload GLB</label>
          <input type="file" id="glbUpload" accept=".glb">
          <button id="removeModel">Remove Model</button>
          <button id="download3D">Download 3D PNG</button>
        </div>
      </div>

      <div class="tab-content" id="texture-tab">
        <div class="control-group">
          <h3>Texture Controls</h3>
          <label for="textureUpload">Upload Texture</label>
          <input type="file" id="textureUpload" accept="image/*">
          <label for="textureScale">Texture Scale</label>
          <div class="slider-with-value">
            <input type="range" id="textureScale" min="0.1" max="5" step="0.1" value="1">
            <span id="textureScaleValue">1.0</span>
          </div>
          <button id="clearTexture">Clear Texture</button>
          <button id="exportTexture">Export Texture</button>
          <button id="toggleFabric">Toggle Editor</button>
        </div>
      </div>
    </div>

    <div id="threeContainer"></div>
    <div id="fabricContainer">
      <div id="fabricTools">
        <button id="addText" class="icon-btn">T</button>
        <button id="addRect" class="icon-btn">□</button>
        <button id="addCircle" class="icon-btn">○</button>
        <button id="addTriangle" class="icon-btn">△</button>
        <button id="bringFront">↑</button>
        <button id="sendBack">↓</button>
        <button id="removeObj">✕</button>
        <button id="clearCanvas">Clear</button>
      </div>
      <div class="color-picker">
        <div class="color-option active" style="background: #ff0000;" data-color="#ff0000"></div>
        <div class="color-option" style="background: #00ff00;" data-color="#00ff00"></div>
        <div class="color-option" style="background: #0000ff;" data-color="#0000ff"></div>
        <div class="color-option" style="background: #ffff00;" data-color="#ffff00"></div>
      </div>
      <canvas id="fabricCanvas" width="512" height="512"></canvas>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/three@0.132.2/build/three.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/three@0.132.2/examples/js/loaders/GLTFLoader.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/three@0.132.2/examples/js/controls/OrbitControls.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/fabric.js/5.3.1/fabric.min.js"></script>
  <script>
    const threeContainer = document.getElementById('threeContainer');
    const fabricContainer = document.getElementById('fabricContainer');
    const scene = new THREE.Scene();
    scene.background = new THREE.Color(0x1a1a2e);
    const camera = new THREE.PerspectiveCamera(75, threeContainer.clientWidth / window.innerHeight, 0.1, 1000);
    const renderer = new THREE.WebGLRenderer({ antialias: true });
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
    let envPresets = {
      default: { ambient: 0.6, directional: 1, bgColor: 0x1a1a2e },
      bright: { ambient: 1, directional: 1.5, bgColor: 0xffffff },
      sunset: { ambient: 0.5, directional: 1, bgColor: 0xff4500 },
      night: { ambient: 0.2, directional: 0.5, bgColor: 0x000033 },
      studio: { ambient: 0.8, directional: 1.2, bgColor: 0x808080 }
    };

    const fabricCanvas = new fabric.Canvas('fabricCanvas', { backgroundColor: 'white' });
    let currentColor = '#ff0000';
    document.querySelectorAll('.color-option').forEach(opt => {
      opt.addEventListener('click', () => {
        document.querySelector('.color-option.active').classList.remove('active');
        opt.classList.add('active');
        currentColor = opt.getAttribute('data-color');
        const activeObj = fabricCanvas.getActiveObject();
        if (activeObj) {
          activeObj.set('fill', currentColor);
          fabricCanvas.renderAll();
        }
      });
    });

    canvasTexture = new THREE.CanvasTexture(fabricCanvas.getElement());
    canvasTexture.flipY = true;
    canvasTexture.minFilter = THREE.LinearFilter;
    canvasTexture.magFilter = THREE.LinearFilter;
    fabricCanvas.on('after:render', () => {
      if (canvasTexture) canvasTexture.needsUpdate = true;
    });

    document.querySelectorAll('.tab-button').forEach(tab => {
      tab.addEventListener('click', () => {
        document.querySelector('.tab-button.active').classList.remove('active');
        document.querySelector('.tab-content.active').classList.remove('active');
        tab.classList.add('active');
        document.getElementById(`${tab.dataset.tab}-tab`).classList.add('active');
      });
    });

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
        });
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
        });
      };
      reader.readAsDataURL(file);
    });

    document.getElementById('addText').onclick = () => {
      const text = new fabric.Textbox('Text', { left: 150, top: 150, fontSize: 24, fill: currentColor });
      fabricCanvas.add(text).setActiveObject(text);
    };
    document.getElementById('addRect').onclick = () => {
      const rect = new fabric.Rect({ left: 100, top: 100, width: 100, height: 60, fill: currentColor });
      fabricCanvas.add(rect).setActiveObject(rect);
    };
    document.getElementById('addCircle').onclick = () => {
      const circle = new fabric.Circle({ left: 150, top: 150, radius: 50, fill: currentColor });
      fabricCanvas.add(circle).setActiveObject(circle);
    };
    document.getElementById('addTriangle').onclick = () => {
      const tri = new fabric.Triangle({ left: 200, top: 200, width: 100, height: 100, fill: currentColor });
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
      fabricCanvas.clear().setBackgroundColor('white', fabricCanvas.renderAll.bind(fabricCanvas));
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
    document.getElementById('removeModel').onclick = () => {
      if (currentModel) {
        scene.remove(currentModel);
        currentModel = null;
        modelMeshes = [];
      }
    };
    document.getElementById('download3D').onclick = () => {
      const link = document.createElement('a');
      link.download = '3d_view.png';
      link.href = renderer.domElement.toDataURL('image/png');
      link.click();
    };
    document.getElementById('clearTexture').onclick = () => {
      modelMeshes.forEach(mesh => {
        if (mesh.material.map) {
          mesh.material.map = null;
          mesh.material.needsUpdate = true;
        }
      });
    };
    document.getElementById('exportTexture').onclick = () => {
      const link = document.createElement('a');
      link.download = 'texture.png';
      link.href = fabricCanvas.toDataURL({ format: 'png', quality: 1 });
      link.click();
    };
    document.getElementById('toggleGrid').onclick = () => {
      gridHelper.visible = !gridHelper.visible;
    };
    document.getElementById('toggleFabric').onclick = () => {
      if (fabricContainer.style.display === 'none') {
        fabricContainer.style.display = 'flex';
        threeContainer.style.width = '50%';
      } else {
        fabricContainer.style.display = 'none';
        threeContainer.style.width = '100%';
      }
      camera.aspect = threeContainer.clientWidth / window.innerHeight;
      camera.updateProjectionMatrix();
      renderer.setSize(threeContainer.clientWidth, window.innerHeight);
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
  </script>
</body>
</html>