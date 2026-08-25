const canvas = document.getElementById("space-canvas");
const ctx = canvas.getContext("2d");

let w, h;
function resize() {
  w = canvas.width = window.innerWidth;
  h = canvas.height = window.innerHeight;
}
window.addEventListener("resize", resize);
resize();

/* ================================
   STAR FIELD SETUP
================================ */
const STAR_COUNT = 6000;
const stars = [];

for (let i = 0; i < STAR_COUNT; i++) {
  stars.push({
    x: (Math.random() - 0.5) * w,
    y: (Math.random() - 0.5) * h,
    z: Math.random() * w
  });
}

/* ================================
   CAMERA STATE
================================ */
let mouseX = 0;
let mouseY = 0;

document.addEventListener("mousemove", e => {
  mouseX = (e.clientX / w - 0.5) * 2;
  mouseY = (e.clientY / h - 0.5) * 2;
});

/* ================================
   ANIMATION LOOP
================================ */
function animate() {
  ctx.fillStyle = "#05070c";
  ctx.fillRect(0, 0, w, h);

  for (const star of stars) {
    star.z -= 0.4; // camera forward motion

    if (star.z <= 0) {
      star.z = w;
      star.x = (Math.random() - 0.5) * w;
      star.y = (Math.random() - 0.5) * h;
    }

    const k = 128 / star.z;
    const x =
      star.x * k + w / 2 + mouseX * 40;
    const y =
      star.y * k + h / 2 + mouseY * 40;

    if (x < 0 || x > w || y < 0 || y > h) continue;

    const size = (1 - star.z / w) * 1.5;
    const alpha = 1 - star.z / w;

    ctx.fillStyle = `rgba(255,255,255,${alpha})`;
    ctx.fillRect(x, y, size, size);
  }

  requestAnimationFrame(animate);
}

animate();

