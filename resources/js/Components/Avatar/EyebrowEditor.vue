<template>
  <div class="mb-4">
    <label class="block mb-1 font-bold">眉毛のタイプ</label>
    <select v-model="eyebrowType" @change="drawEyebrows" class="border rounded px-2 py-1">
      <option value="normal">ノーマル</option>
      <option value="angry">怒り眉</option>
      <option value="sad">悲しげ眉</option>
    </select>
  </div>
</template>

<script setup>
import { ref, watch } from 'vue'
import * as fabric from 'fabric'

const props = defineProps({
  canvas: Object,
})

const eyebrowType = ref('normal')
let leftBrow = null
let rightBrow = null


function drawEyebrows() {
  if (!props.canvas) return;

  props.canvas.remove(leftBrow, rightBrow);

  const paths = getEyebrowPoints(eyebrowType.value);

  let leftAngle = 0;
  let rightAngle = 0;

  if (eyebrowType.value === null) {
    leftAngle = 0;
    rightAngle = 0;
  }
  if (eyebrowType.value === 'sad') {
    leftAngle = -20;
    rightAngle = 20;
  } else if (eyebrowType.value === 'angry') {
    leftAngle = 20;
    rightAngle = -20;
  }

  leftBrow = new fabric.Path(paths.left, {
    stroke: '#333',
    strokeWidth: 4,
    fill: '',
    left: 120,
    top: 100,
    originX: 'center',
    originY: 'center',
    angle: leftAngle,
  });

  rightBrow = new fabric.Path(paths.right, {
    stroke: '#333',
    strokeWidth: 4,
    fill: '',
    left: 180,
    top: 100,
    originX: 'center',
    originY: 'center',
    angle: rightAngle,
  });

  props.canvas.add(leftBrow, rightBrow);
}

function getEyebrowPoints(type) {
  // 形状は全タイプ共通。回転で表情を変える
  return {
    left: 'M -10 0 Q 0 -5 10 0',
    right: 'M -10 0 Q 0 -5 10 0',
  };
}


watch(eyebrowType, drawEyebrows)
</script>
