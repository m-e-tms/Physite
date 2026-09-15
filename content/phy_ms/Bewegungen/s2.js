const canvasST = document.getElementById("canvasST");
const ctxST = canvasST.getContext("2d");

function drawST() {
    const t = +tInput.value;
    const v = +vInput.value;
    const a = +aInput.value;

    function s(x){
        return v*x + 0.5*a*x*x;
    }

    const ox = 80;
    const oy = 420;
    const w  = 750;
    const h  = 350;

    const maxT = 10;
    const maxS = 2000;

    const sx = w / maxT;
    const sy = h / maxS;

    ctxST.fillStyle="#fff";
    ctxST.fillRect(0,0,canvasST.width,canvasST.height);

    // GRID
    ctxST.strokeStyle="#ddd";

    for(let x=0;x<=maxT;x++){
        let px=ox+x*sx;
        ctxST.beginPath();
        ctxST.moveTo(px,oy);
        ctxST.lineTo(px,oy-h);
        ctxST.stroke();
    }

    for(let y=0;y<=maxS;y+=200){
        let py=oy-y*sy;
        ctxST.beginPath();
        ctxST.moveTo(ox,py);
        ctxST.lineTo(ox+w,py);
        ctxST.stroke();
    }

    // ACHSEN
    ctxST.strokeStyle="#000";
    ctxST.lineWidth=2;

    ctxST.beginPath();
    ctxST.moveTo(ox,oy);
    ctxST.lineTo(ox+w,oy);
    ctxST.moveTo(ox,oy);
    ctxST.lineTo(ox,oy-h);
    ctxST.stroke();

    // BESCHRIFTUNG
    ctxST.fillStyle="#000";
    ctxST.fillText("t (s)", ox+w-40, oy+30);
    ctxST.fillText("s (m)", ox-60, oy-h+20);

    // KURVE
    ctxST.beginPath();
    ctxST.strokeStyle="#0077cc";

    for(let x=0;x<=maxT;x+=0.1){
        let px=ox+x*sx;
        let py=oy-s(x)*sy;
        if(x===0) ctxST.moveTo(px,py);
        else ctxST.lineTo(px,py);
    }
    ctxST.stroke();

    // PUNKT
    let px=ox+t*sx;
    let py=oy-s(t)*sy;

    ctxST.beginPath();
    ctxST.arc(px,py,6,0,Math.PI*2);
    ctxST.fillStyle="red";
    ctxST.fill();
}

function loopST(){
    drawST();
    requestAnimationFrame(loopST);
}
loopST();