<template>
  <div>
    <h2 class="text-xl font-bold mb-4">顔エディタ</h2>

    <!-- 顔の形選択 -->
    <select v-model="selectedFace" @change="drawFace" class="border rounded px-2 py-1 mb-4">
      <option value="round">丸顔</option>
      <option value="oval">卵型</option>
      <option value="square">角顔</option>
    </select>

    <!-- canvas -->
    <canvas ref="canvasEl" width="300" height="300" class="border"></canvas>

    <!-- スライダー -->
    <div class="mt-4">
      <label for="faceWidth">顔の横幅: {{ faceWidth.toFixed(2) }}</label>
      <input
        id="faceWidth"
        type="range"
        min="0.8"
        max="1.3"
        step="0.01"
        v-model.number="faceWidth"
        @input="updateFaceTransform"
        class="w-full"
      />
    </div>

    <!-- EyeEditor（目のタイプ） -->
    <EyeEditor @change="drawEyes" />

    <EyebrowEditor :canvas="canvas" />

 <NoseSelector @update="(val) => { noseType.value = val }" />

  </div>
</template>

<script setup>
import { onMounted, ref } from 'vue'
import * as fabric from 'fabric'
import { faceShapes } from '@/lib/faceShapes.js'
import EyeEditor from './EyeEditor.vue'
import EyebrowEditor from './EyebrowEditor.vue' 
import NoseSelector from './NoseSelector.vue'


const canvasEl = ref(null)
const canvas = ref(null)

const selectedFace = ref('round')
const faceWidth = ref(1)

const face = ref(null)
let leftEye = null;
let rightEye = null;
let leftPupil = null;
let rightPupil = null;


const noseType = ref('normal')
let nose = null



onMounted(() => {
  canvas.value = new fabric.Canvas(canvasEl.value)
  drawFace()
  drawNose()
})

// 顔を描く
function drawFace() {
  if (!canvas.value) return
  canvas.value.clear()

  face.value = faceShapes[selectedFace.value]()
  face.value.set({
    left: 150,
    top: 150,
    originX: 'center',
    originY: 'center',
    scaleX: faceWidth.value,
  })

  canvas.value.add(face.value)

  // 顔描き直したら目も再描画する
  drawEyes('normal')
}

// 顔の横幅調整
function updateFaceTransform() {
  if (face.value) {
    face.value.scaleX = faceWidth.value
    canvas.value.renderAll()
  }
}

// 目を描く
function drawEyes(type) {
  if (!canvas.value) return;

  // 古い目を削除
  if (leftEye) canvas.value.remove(leftEye);
  if (rightEye) canvas.value.remove(rightEye);
  if (leftPupil) canvas.value.remove(leftPupil);
  if (rightPupil) canvas.value.remove(rightPupil);

  let angle = 0;
  let ry = 8;
  let rx = 16;
  let top = 140;

  const eyeScale = ref(1)

  if (type === 'tsuri') angle = 25;
  if (type === 'tare') angle = -25;
  if (type === 'jito') {
    ry = 8;
    top = 145;
  }

  // 目の白目部分（楕円 or 半楕円）
  leftEye = new fabric.Ellipse({
    rx, ry,
    fill: 'white',
    stroke: 'black',
    strokeWidth: 2,
    left: 120,
    top,
    angle,
    originX: 'center',
    originY: 'center'

  });

  rightEye = new fabric.Ellipse({
    rx, ry,
    fill: 'white',
    stroke: 'black',
    strokeWidth: 2,
    left: 180,
    top,
    angle: -angle,
    originX: 'center',
    originY: 'center',

  });

  // 黒目（固定サイズの円）
  const pupilRadius = 6;

  leftPupil = new fabric.Circle({
    radius: pupilRadius,
    fill: 'black',
    left: 120,
    top,
    originX: 'center',
    originY: 'center'
  });

  rightPupil = new fabric.Circle({
    radius: pupilRadius,
    fill: 'black',
    left: 180,
    top,
    originX: 'center',
    originY: 'center'
  });

  canvas.value.add(leftEye, rightEye, leftPupil, rightPupil);
  canvas.value.renderAll();
}



function drawNose() {
  if (!canvas.value) return
  if (nose) canvas.value.remove(nose)

  switch (noseType.value) {
    case 'dot':
      nose = new fabric.Circle({
        radius: 2,
        fill: 'black',
        left: 150,
        top: 160,
        originX: 'center',
        originY: 'center',
      })
      break
    case 'line':
      nose = new fabric.Line([150, 155, 150, 165], {
        stroke: 'black',
        strokeWidth: 2,
        originX: 'center',
        originY: 'center',
      })
      break
    case 'triangle':
      nose = new fabric.Triangle({
        width: 8,
        height: 6,
        fill: 'black',
        left: 150,
        top: 160,
        originX: 'center',
        originY: 'center',
      })
      break
    case 'none':
    default:
      nose = null
      return
  }

  if (nose) canvas.value.add(nose)
}

</script>

<style scoped>
canvas {
  display: block;
  margin: auto;
}
</style>
