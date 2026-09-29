document.addEventListener('DOMContentLoaded', () => {
    const canvas = document.getElementById('interferenzCanvas');
    const ctx = canvas.getContext('2d');

    // ── Slider references ──────────────────────────────────────────────────
    const ampASlider    = document.getElementById('ampASlider');
    const lambdaASlider = document.getElementById('lambdaASlider');
    const ampBSlider    = document.getElementById('ampBSlider');
    const lambdaBSlider = document.getElementById('lambdaBSlider');
    const speedSlider   = document.getElementById('speedSlider');

    const ampAValue    = document.getElementById('ampAValue');
    const lambdaAValue = document.getElementById('lambdaAValue');
    const ampBValue    = document.getElementById('ampBValue');
    const lambdaBValue = document.getElementById('lambdaBValue');
    const speedValue   = document.getElementById('speedValue');

    // ── Logical canvas coordinates ─────────────────────────────────────────
    const LOGICAL_WIDTH  = 900;
    const LOGICAL_HEIGHT = 500;
    const x_start  = 60;
    const x_end    = 720;
    const y_center = 260;
    const x_mid    = (x_start + x_end) / 2; // 390

    // ── Parameters ─────────────────────────────────────────────────────────
    let ampA    = 100;
    let ampB    = 100;
    let lamA    = 200;
    let lamB    = 200;
    let speed   = 1.0;

    // ── Animation state ────────────────────────────────────────────────────
    // t goes from 0 → 1 over one full cycle.
    // FIX 1: The pulse width (halfLen = lambda/2) must be fully outside the
    // canvas before the cycle starts. We extend the travel range so each
    // pulse starts one full pulse-width beyond the visible edge and ends one
    // full pulse-width beyond the far edge.  This means the largest possible
    // pulse (lambda = 500, halfLen = 250) needs 250 px of extra margin on
    // each side.
    const MARGIN = 260; // px beyond x_start / x_end on each side
    // cxA travels from (x_start - MARGIN) to (x_end + MARGIN): left → right
    // cxB travels from (x_end + MARGIN)   to (x_start - MARGIN): right → left
    const travelFrom = x_start - MARGIN; //  -200
    const travelTo   = x_end   + MARGIN; //  980
    const travel     = travelTo - travelFrom; // 1180

    let t = 0;
    let lastTimestamp = 0;

    // ── Read sliders ───────────────────────────────────────────────────────
    function updateValues() {
        ampA  = parseFloat(ampASlider.value);
        ampB  = parseFloat(ampBSlider.value);
        lamA  = parseFloat(lambdaASlider.value);
        lamB  = parseFloat(lambdaBSlider.value);
        speed = parseFloat(speedSlider.value);

        ampAValue.textContent    = ampA;
        lambdaAValue.textContent = lamA;
        ampBValue.textContent    = ampB;
        lambdaBValue.textContent = lamB;
        speedValue.textContent   = speed.toFixed(1);
    }

    // ── Resize (DPR-aware) ─────────────────────────────────────────────────
    function resize() {
        const rect = canvas.getBoundingClientRect();
        const dpr  = window.devicePixelRatio || 1;
        canvas.width  = rect.width  * dpr;
        canvas.height = rect.height * dpr;
        ctx.resetTransform();
        ctx.scale(canvas.width / LOGICAL_WIDTH, canvas.height / LOGICAL_HEIGHT);
    }

    // ── Pulse shape ────────────────────────────────────────────────────────
    // FIX 2: Gaussian-bell shaped pulse that fades smoothly to zero and meets
    // the x-axis tangentially (zero derivative at the base).
    // sigma controls the spread – we set it so the visible "footprint" of the
    // pulse is approximately lambda wide: sigma = lambda / 5.
    // The pulse is truncated to zero beyond ±3*sigma (well into the tail).
    function pulse(x, cx, amp, lambda) {
        const sigma = lambda / 5;
        const cutoff = 3.2 * sigma; // beyond this: hard zero
        const dx = x - cx;
        if (Math.abs(dx) > cutoff) return 0;
        // Gaussian envelope
        return amp * Math.exp(-(dx * dx) / (2 * sigma * sigma));
    }

    // ── Draw ───────────────────────────────────────────────────────────────
    function draw() {
        // Clear
        ctx.fillStyle = '#0b0f19';
        ctx.fillRect(0, 0, LOGICAL_WIDTH, LOGICAL_HEIGHT);

        // Grid
        ctx.strokeStyle = '#1e293b';
        ctx.lineWidth = 1;
        for (let y = 60; y <= 460; y += 50) {
            ctx.beginPath(); ctx.moveTo(x_start, y); ctx.lineTo(x_end, y); ctx.stroke();
        }
        for (let x = 60; x <= 720; x += 60) {
            ctx.beginPath(); ctx.moveTo(x, 60); ctx.lineTo(x, 460); ctx.stroke();
        }

        // Axes
        ctx.strokeStyle = '#475569';
        ctx.lineWidth = 2;
        ctx.beginPath(); ctx.moveTo(x_start, y_center); ctx.lineTo(x_end, y_center); ctx.stroke();
        ctx.beginPath(); ctx.moveTo(x_start, 60); ctx.lineTo(x_start, 460); ctx.stroke();

        // Axis arrows
        ctx.fillStyle = '#475569';
        ctx.beginPath(); ctx.moveTo(x_end, y_center); ctx.lineTo(x_end - 10, y_center - 5); ctx.lineTo(x_end - 10, y_center + 5); ctx.fill();
        ctx.beginPath(); ctx.moveTo(x_start, 60); ctx.lineTo(x_start - 5, 70); ctx.lineTo(x_start + 5, 70); ctx.fill();

        // Axis labels
        ctx.font = '500 14px Outfit, sans-serif';
        ctx.fillStyle = '#94a3b8';
        ctx.textAlign = 'left';
        ctx.fillText('x', x_end + 15, y_center + 5);
        ctx.textAlign = 'center';
        ctx.fillText('y', x_start, 45);

        // Centre dashed guideline
        ctx.save();
        ctx.strokeStyle = 'rgba(148, 163, 184, 0.15)';
        ctx.lineWidth = 1;
        ctx.setLineDash([5, 7]);
        ctx.beginPath(); ctx.moveTo(x_mid, 60); ctx.lineTo(x_mid, 460); ctx.stroke();
        ctx.restore();

        // ── Compute pulse centres ──────────────────────────────────────────
        const cxA = travelFrom + t * travel;   // left → right
        const cxB = travelTo   - t * travel;   // right → left

        // ── Helper: draw a wave path ───────────────────────────────────────
        function drawWave(yFunc, coreColor, glowColor) {
            ctx.beginPath();
            let penDown = false;
            let drewAnything = false;
            for (let x = x_start; x <= x_end; x++) {
                const yVal = yFunc(x);
                if (yVal === null) {
                    // Lift the pen – next non-null point must use moveTo
                    penDown = false;
                    continue;
                }
                const py = y_center - yVal;
                if (!penDown) { ctx.moveTo(x, py); penDown = true; drewAnything = true; }
                else          { ctx.lineTo(x, py); }
            }
            if (!drewAnything) return;
            ctx.save();
            ctx.strokeStyle = glowColor;
            ctx.lineWidth = 6;
            ctx.stroke();
            ctx.strokeStyle = coreColor;
            ctx.lineWidth = 2;
            ctx.stroke();
            ctx.restore();
        }

        // ── Check if pulses overlap ────────────────────────────────────────
        // For the Gaussian, the effective footprint is ±3.2*sigma each side.
        const sigmaA = lamA / 5;
        const sigmaB = lamB / 5;
        const cutA   = 3.2 * sigmaA;
        const cutB   = 3.2 * sigmaB;

        const leftA  = cxA - cutA;
        const rightA = cxA + cutA;
        const leftB  = cxB - cutB;
        const rightB = cxB + cutB;

        const overlapLeft  = Math.max(leftA, leftB);
        const overlapRight = Math.min(rightA, rightB);
        const hasOverlap   = overlapLeft < overlapRight;

        if (hasOverlap) {
            // FIX 3: During overlap, show ONLY the interference (superposition) wave.
            // No individual waves, no dimmed ghosts.

            // Subtle backdrop in overlap region
            ctx.save();
            ctx.globalAlpha = 0.06;
            ctx.fillStyle = '#94a3b8';
            const olClamp = Math.max(x_start, overlapLeft);
            const orClamp = Math.min(x_end, overlapRight);
            ctx.fillRect(olClamp, 60, orClamp - olClamp, 400);
            ctx.restore();

            // Individual waves – only visible OUTSIDE the overlap zone
            drawWave(x => {
                if (x >= overlapLeft && x <= overlapRight) return null;
                const v = pulse(x, cxA, ampA, lamA);
                return v !== 0 ? v : null;
            }, '#4285F4', 'rgba(66, 133, 244, 0.25)');

            drawWave(x => {
                if (x >= overlapLeft && x <= overlapRight) return null;
                const v = pulse(x, cxB, ampB, lamB);
                return v !== 0 ? v : null;
            }, '#EA4335', 'rgba(234, 67, 53, 0.25)');

            // Interference wave – the ONLY thing drawn inside the overlap zone
            drawWave(x => {
                if (x < overlapLeft || x > overlapRight) return null;
                const sum = pulse(x, cxA, ampA, lamA) + pulse(x, cxB, ampB, lamB);
                return sum !== 0 ? sum : null;
            }, '#e2e8f0', 'rgba(226, 232, 240, 0.28)');

            // Label
            ctx.save();
            ctx.font = '600 13px Outfit, sans-serif';
            ctx.fillStyle = '#e2e8f0';
            ctx.textAlign = 'center';
            const labelX = (Math.max(x_start, overlapLeft) + Math.min(x_end, overlapRight)) / 2;
            ctx.fillText('Überlagerung', labelX, 48);
            ctx.restore();

        } else {
            // No overlap – draw both pulses at full opacity
            drawWave(x => {
                const v = pulse(x, cxA, ampA, lamA);
                return v !== 0 ? v : null;
            }, '#4285F4', 'rgba(66, 133, 244, 0.25)');
            drawWave(x => {
                const v = pulse(x, cxB, ampB, lamB);
                return v !== 0 ? v : null;
            }, '#EA4335', 'rgba(234, 67, 53, 0.25)');
        }

        // ── Wave labels ────────────────────────────────────────────────────
        ctx.save();
        ctx.font = '500 13px Outfit, sans-serif';
        ctx.textAlign = 'center';

        if (cxA >= x_start && cxA <= x_end && !hasOverlap) {
            ctx.fillStyle = '#60a5fa';
            const aLabelY = y_center - Math.abs(ampA) - 18;
            ctx.fillText('A →', cxA, Math.max(40, aLabelY));
        }
        if (cxB >= x_start && cxB <= x_end && !hasOverlap) {
            ctx.fillStyle = '#f87171';
            const bLabelY = y_center - Math.abs(ampB) - 18;
            ctx.fillText('← B', cxB, Math.max(40, bLabelY));
        }
        ctx.restore();

        // ── Amplitude brackets ─────────────────────────────────────────────
        if (ampA !== 0) {
            const bx = x_start - 15;
            ctx.strokeStyle = '#4285F4';
            ctx.lineWidth = 2;
            ctx.beginPath(); ctx.moveTo(bx, y_center); ctx.lineTo(bx, y_center - ampA); ctx.stroke();
            ctx.beginPath();
            ctx.moveTo(bx - 5, y_center); ctx.lineTo(bx + 5, y_center);
            ctx.moveTo(bx - 5, y_center - ampA); ctx.lineTo(bx + 5, y_center - ampA);
            ctx.stroke();
            ctx.font = '600 12px Outfit, sans-serif';
            ctx.fillStyle = '#60a5fa';
            ctx.textAlign = 'right';
            ctx.fillText('A: ' + ampA + ' px', bx - 10, y_center - ampA / 2 + 4);
        }

        if (ampB !== 0) {
            const bx = x_end + 15;
            ctx.strokeStyle = '#EA4335';
            ctx.lineWidth = 2;
            ctx.beginPath(); ctx.moveTo(bx, y_center); ctx.lineTo(bx, y_center - ampB); ctx.stroke();
            ctx.beginPath();
            ctx.moveTo(bx - 5, y_center); ctx.lineTo(bx + 5, y_center);
            ctx.moveTo(bx - 5, y_center - ampB); ctx.lineTo(bx + 5, y_center - ampB);
            ctx.stroke();
            ctx.font = '600 12px Outfit, sans-serif';
            ctx.fillStyle = '#fca5a5';
            ctx.textAlign = 'left';
            ctx.fillText('B: ' + ampB + ' px', bx + 10, y_center - ampB / 2 + 4);
        }
    }

    // ── Animation loop ─────────────────────────────────────────────────────
    function animate(timestamp) {
        if (lastTimestamp === 0) lastTimestamp = timestamp;
        const dt = (timestamp - lastTimestamp) / 1000;
        lastTimestamp = timestamp;

        const cycleDuration = 4 / speed;
        t += dt / cycleDuration;
        if (t >= 1) t = t % 1;

        draw();
        requestAnimationFrame(animate);
    }

    // ── Event listeners ────────────────────────────────────────────────────
    ampASlider.addEventListener('input', updateValues);
    lambdaASlider.addEventListener('input', updateValues);
    ampBSlider.addEventListener('input', updateValues);
    lambdaBSlider.addEventListener('input', updateValues);
    speedSlider.addEventListener('input', updateValues);

    // ── Initialise ─────────────────────────────────────────────────────────
    updateValues();
    resize();
    requestAnimationFrame(animate);

    window.addEventListener('resize', () => { resize(); draw(); });
});
