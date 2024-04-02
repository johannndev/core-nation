angular.module('ariaApp.services')
.factory('Barcode', function($http, ariaGlobal, $location, $anchorScroll) {
var barcode = {}
//const barcodeDetector = new BarcodeDetector();
/*
barcode.start = function() {
  var videoElement = document.querySelector('video');
    barcodeDetector.detect(videoElement).then(function(result) {
      console.log(result)
    }).catch(function(err){
      console.log(err)
      alert(err)
    })
  navigator.mediaDevices.getUserMedia(constraints).then(function(stream) {
    videoElement.srcObject = stream;
  });
}

barcode.stop = function() {
  var videoElement = document.querySelector('video');
  videoElement.srcObject.getTracks().forEach(function(track) {
    console.log(track)
    track.stop();
  });
}
*/
return barcode;
})

const canvas = document.querySelector('canvas');
const context = canvas.getContext('2d');

function startBarcodeScanner() {
  runShapeDetectionApiDemo();
}

async function runShapeDetectionApiDemo() {
    const constraints = { video: { facingMode: 'environment', deviceId: '28b0746f9ee8ccb8bd72226e4c877b77cda1633d2e18fa122aa034f04cbb23c8' } };
    const mediaStream = await navigator.mediaDevices.getUserMedia(constraints);
console.log(navigator.mediaDevices.enumerateDevices)
    const video = document.getElementById('video');
    video.srcObject = mediaStream;
    video.autoplay = true;
    video.onloadedmetadata = () => {
        canvas.width = video.videoWidth;
        canvas.height = video.videoHeight;
    };

    let renderLocked = false;
    const barcodeDetector = new BarcodeDetector();

    function render() {
        if (!video.paused) {
            renderLocked = true;

            Promise.all([
                barcodeDetector.detect(video).catch((error) => console.log(error))
            ]).then(([detectedBarcodes = []]) => {

                context.strokeStyle = '#03A9F4';
                context.fillStyle = '#03A9F4';
                context.font = '16px Mononoki';

                detectedBarcodes.forEach((detectedBarcode) => {
                    const { top, left, width, height } = detectedBarcode.boundingBox;
                    const cornerPoints = detectedBarcode.cornerPoints;
                    if (cornerPoints && cornerPoints.length) {
                        const [{ x, y }] = cornerPoints;
                        context.beginPath();
                        context.moveTo(x, y);
                        for (let i = 1; i < cornerPoints.length; i++) {
                            context.lineTo(cornerPoints[i].x, cornerPoints[i].y);
                        }
                        context.closePath();
                    } else {
                        context.beginPath();
                        context.rect(left, top, width, height);
                    }
                    context.stroke();
                    context.fillText(detectedBarcode.rawValue, left, top + height + 16);
                });

                renderLocked = false;
            });
        }
    }
};

function stopCamera() {
  var videoElement = document.querySelector('video');
  videoElement.srcObject.getTracks().forEach(function(track) {
    console.log(track)
    track.stop();
  });
}

function displayFallbackMessage() {
    document.querySelector('.fallback-message').classList.remove('hidden');
    document.querySelector('canvas').classList.add('hidden');
    document.querySelector('.links').classList.add('hidden');
}
