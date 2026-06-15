const canvasVT = document.getElementById("canvasVT");
const ctxVT = canvasVT.getContext("2d");

function drawVT() {
    const t  = +tInput.value;
    const v0 = +vInput.value;
    const a  = +aInput.value;
    let t1   = +t1Input.value;
    let t2   = +t2Input.value;

    if (t1 > t2) [t1, t2] = [t2, t1];

    function v(x){ return v0 + a*x; }

    // === Layout ===
    const ox = 80;
    const oy = 420;
    const w  = 750;
    const h  = 350;

    const maxT = 10;
    const maxV = 200;

    const sx = w / maxT;
    const sy = h / maxV;

    ctxVT.fillStyle="#fff";
    ctxVT.fillRect(0,0,canvasVT.width,canvasVT.height);

    // === GRID ===
    ctxVT.strokeStyle="#ddd";
    ctxVT.lineWidth=1;

    for(let x=0;x<=maxT;x++){
        let px=ox+x*sx;
        ctxVT.beginPath();
        ctxVT.moveTo(px,oy);
        ctxVT.lineTo(px,oy-h);
        ctxVT.stroke();
    }

    for(let y=0;y<=maxV;y+=20){
        let py=oy-y*sy;
        ctxVT.beginPath();
        ctxVT.moveTo(ox,py);
        ctxVT.lineTo(ox+w,py);
        ctxVT.stroke();
    }

    // === ACHSEN ===
    ctxVT.strokeStyle="#000";
    ctxVT.lineWidth=2;

    ctxVT.beginPath();
    ctxVT.moveTo(ox,oy);
    ctxVT.lineTo(ox+w,oy);
    ctxVT.moveTo(ox,oy);
    ctxVT.lineTo(ox,oy-h);
    ctxVT.stroke();

    // === BESCHRIFTUNG ===
    ctxVT.fillStyle="#000";
    ctxVT.font="14px Arial";

    ctxVT.fillText("t (s)", ox+w-40, oy+30);
    ctxVT.fillText("v (m/s)", ox-60, oy-h+20);

    // === KURVE ===
    ctxVT.beginPath();
    ctxVT.strokeStyle="#0077cc";

    for(let x=0;x<=maxT;x+=0.1){
        let px=ox+x*sx;
        let py=oy-v(x)*sy;
        if(x===0) ctxVT.moveTo(px,py);
        else ctxVT.lineTo(px,py);
    }
    ctxVT.stroke();

    // === FLÄCHE ===
    ctxVT.beginPath();
    ctxVT.moveTo(ox+t1*sx,oy);

    for(let x=t1;x<=t2;x+=0.05){
        ctxVT.lineTo(ox+x*sx, oy-v(x)*sy);
    }

    ctxVT.lineTo(ox+t2*sx,oy);
    ctxVT.fillStyle="rgba(0,150,255,0.3)";
    ctxVT.fill();

    // === PUNKT ===
    let px=ox+t*sx;
    let py=oy-v(t)*sy;

    ctxVT.beginPath();
    ctxVT.arc(px,py,6,0,Math.PI*2);
    ctxVT.fillStyle="red";
    ctxVT.fill();

    ctxVT.fillText(`v = ${v(t).toFixed(2)}`, 650, 50);
}

const tInput  = document.getElementById("t");
const vInput  = document.getElementById("v");
const aInput  = document.getElementById("a");
const t1Input = document.getElementById("t1");
const t2Input = document.getElementById("t2");

function loopVT(){
    drawVT();
    requestAnimationFrame(loopVT);
}
loopVT();