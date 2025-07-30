import * as fabric from 'fabric'

// faceShapes.js
export const faceShapes = {
  round: () => {
    return new fabric.Circle({
      radius: 60,
      fill: '#ffdca8',
      left: 150,
      top: 100,
      originX: 'center',
      originY: 'center',
    });
  },

  oval: () => {
    return new fabric.Ellipse({
      rx: 50,
      ry: 70,
      fill: '#ffdca8',
      left: 150,
      top: 100,
      originX: 'center',
      originY: 'center',
    });
  },

  square: () => {
    return new fabric.Rect({
      width: 100,
      height: 100,
      rx: 20,
      ry: 20,
      fill: '#ffdca8',
      left: 100,
      top: 100,
      originX: 'left',
      originY: 'top',
    });
  },

  // heart: () => {
  //   const path = new fabric.Path('M 75 80 C 65 60, 45 60, 45 80 C 45 100, 75 120, 75 140 C 75 120, 105 100, 105 80 C 105 60, 85 60, 75 80 Z');
  //   path.set({
  //     fill: '#ffdca8',
  //     left: 75,
  //     top: 60,
  //     originX: 'left',
  //     originY: 'top',
  //   });
  //   return path;
  // },

  // base: () => {
  //   const points = [
  //     { x: 75, y: 60 },   // top
  //     { x: 45, y: 100 },  // left cheek
  //     { x: 60, y: 140 },  // lower left jaw
  //     { x: 90, y: 140 },  // lower right jaw
  //     { x: 105, y: 100 }, // right cheek
  //   ];
  //   const polygon = new fabric.Polygon(points, {
  //     fill: '#ffdca8',
  //     left: 75,
  //     top: 60,
  //     originX: 'left',
  //     originY: 'top',
  //   });
  //   return polygon;
  // }
};

