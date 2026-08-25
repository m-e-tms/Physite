document.addEventListener('DOMContentLoaded', () => {
    const canvas = document.getElementById('schwingungCanvas');
    const ctx = canvas.getContext('2d');

    const freqSlider = document.getElementById('freqSlider');
    const ampSlider = document.getElementById('ampSlider');
    const freqValue = document.getElementById('freqValue');
    const ampValue = document.getElementById('ampValue');

    const LOGICAL_WIDTH = 900;
    const LOGICAL_HEIGHT = 500;

    const x_start = 60;
    const x_end = 720;
    const y_center = 260;

    let freq = 1.0;
    let amp = 100.0;
    let lambda = 300.0;
    let phase = 0.0;
    let lastTime = 0;

    function updateValues() {
        freq = parseFloat(freqSlider.value);
        amp = parseFloat(ampSlider.value);
        
        freqValue.textContent = freq.toFixed(1);
        ampValue.textContent = amp;
        
        // Physics relation: lambda = v / f.
        // With v = 300 px/s, at f = 1 Hz we have lambda = 300 px.
        lambda = 300 / freq;
    }

    function resize() {
        const rect = canvas.getBoundingClientRect();
        const dpr = window.devicePixelRatio || 1;
        
        canvas.width = rect.width * dpr;
        canvas.height = rect.height * dpr;
        
        ctx.resetTransform();
        ctx.scale(canvas.width / LOGICAL_WIDTH, canvas.height / LOGICAL_HEIGHT);
    }

    function draw() {
        // Clear canvas
        ctx.fillStyle = '#0b0f19';
        ctx.fillRect(0, 0, LOGICAL_WIDTH, LOGICAL_HEIGHT);

        // 1. Draw Grid
        ctx.strokeStyle = '#1e293b';
        ctx.lineWidth = 1;
        
        // Horizontal grid lines
        for (let y = 60; y <= 460; y += 50) {
            ctx.beginPath();
            ctx.moveTo(x_start, y);
            ctx.lineTo(x_end, y);
            ctx.stroke();
        }
        
        // Vertical grid lines
        for (let x = 60; x <= 720; x += 60) {
            ctx.beginPath();
            ctx.moveTo(x, 60);
            ctx.lineTo(x, 460);
            ctx.stroke();
        }

        // 2. Draw Axes
        ctx.strokeStyle = '#475569';
        ctx.lineWidth = 2;
        
        // X-Axis (Baseline)
        ctx.beginPath();
        ctx.moveTo(x_start, y_center);
        ctx.lineTo(x_end, y_center);
        ctx.stroke();
        
        // Y-Axis
        ctx.beginPath();
        ctx.moveTo(x_start, 60);
        ctx.lineTo(x_start, 460);
        ctx.stroke();

        // Axis arrows
        ctx.fillStyle = '#475569';
        // X arrow
        ctx.beginPath();
        ctx.moveTo(x_end, y_center);
        ctx.lineTo(x_end - 10, y_center - 5);
        ctx.lineTo(x_end - 10, y_center + 5);
        ctx.fill();
        // Y arrow
        ctx.beginPath();
        ctx.moveTo(x_start, 60);
        ctx.lineTo(x_start - 5, 70);
        ctx.lineTo(x_start + 5, 70);
        ctx.fill();

        // Axis labels
        ctx.font = '500 14px Outfit, sans-serif';
        ctx.fillStyle = '#94a3b8';
        ctx.textAlign = 'left';
        ctx.fillText('x', x_end + 15, y_center + 5);
        ctx.textAlign = 'center';
        ctx.fillText('y', x_start, 45);

        // 3. Draw Amplitude Helper Lines (Dashed Guide Lines)
        ctx.save();
        ctx.strokeStyle = 'rgba(239, 68, 68, 0.25)';
        ctx.lineWidth = 1.5;
        ctx.setLineDash([4, 4]);
        // Top peak amplitude limit
        ctx.beginPath();
        ctx.moveTo(x_start, y_center - amp);
        ctx.lineTo(760, y_center - amp);
        ctx.stroke();
        // Bottom valley amplitude limit
        ctx.strokeStyle = 'rgba(239, 68, 68, 0.12)';
        ctx.beginPath();
        ctx.moveTo(x_start, y_center + amp);
        ctx.lineTo(760, y_center + amp);
        ctx.stroke();
        ctx.restore();

        // 4. Draw Wavelength Helper Lines (Dashed Verticals)
        const x_mid = (x_start + x_end) / 2; // 390
        const wl_start_x = x_mid - lambda / 2;
        const wl_end_x = x_mid + lambda / 2;
        
        ctx.save();
        ctx.strokeStyle = 'rgba(52, 168, 83, 0.4)';
        ctx.lineWidth = 1.5;
        ctx.setLineDash([4, 4]);
        
        if (wl_start_x >= x_start && wl_start_x <= x_end) {
            const y1 = y_center - amp * Math.sin(2 * Math.PI * (wl_start_x - x_start) / lambda - phase);
            ctx.beginPath();
            ctx.moveTo(wl_start_x, 40);
            ctx.lineTo(wl_start_x, y1);
            ctx.stroke();
        }
        
        if (wl_end_x >= x_start && wl_end_x <= x_end) {
            const y2 = y_center - amp * Math.sin(2 * Math.PI * (wl_end_x - x_start) / lambda - phase);
            ctx.beginPath();
            ctx.moveTo(wl_end_x, 40);
            ctx.lineTo(wl_end_x, y2);
            ctx.stroke();
        }
        ctx.restore();

        // 5. Draw Wavelength Bracket
        const bx1 = Math.max(x_start, wl_start_x);
        const bx2 = Math.min(x_end, wl_end_x);
        
        if (bx1 < bx2) {
            ctx.strokeStyle = '#34A853';
            ctx.lineWidth = 2;
            ctx.beginPath();
            ctx.moveTo(bx1, 40);
            ctx.lineTo(bx2, 40);
            ctx.stroke();
            
            // Start tick
            if (wl_start_x >= x_start) {
                ctx.beginPath();
                ctx.moveTo(wl_start_x, 32);
                ctx.lineTo(wl_start_x, 48);
                ctx.stroke();
            }
            
            // End tick
            if (wl_end_x <= x_end) {
                ctx.beginPath();
                ctx.moveTo(wl_end_x, 32);
                ctx.lineTo(wl_end_x, 48);
                ctx.stroke();
            }
        }

        // Wavelength Label Text
        ctx.font = '600 15px Outfit, sans-serif';
        ctx.fillStyle = '#34A853';
        ctx.textAlign = 'center';
        ctx.fillText('Wellenlänge: ' + Math.round(lambda) + ' px', x_mid, 25);

        // 6. Draw Wave (Sinuskurve)
        ctx.beginPath();
        for (let x = x_start; x <= x_end; x++) {
            const angle = 2 * Math.PI * (x - x_start) / lambda - phase;
            const y = y_center - amp * Math.sin(angle);
            if (x === x_start) {
                ctx.moveTo(x, y);
            } else {
                ctx.lineTo(x, y);
            }
        }
        
        ctx.save();
        // Outer glowing line
        ctx.strokeStyle = 'rgba(66, 133, 244, 0.25)';
        ctx.lineWidth = 6;
        ctx.stroke();
        // Inner core line
        ctx.strokeStyle = '#4285F4';
        ctx.lineWidth = 2.5;
        ctx.stroke();
        ctx.restore();

        // 7. Draw Amplitude Bracket
        ctx.strokeStyle = '#EA4335';
        ctx.lineWidth = 2;
        ctx.beginPath();
        ctx.moveTo(760, y_center);
        ctx.lineTo(760, y_center - amp);
        ctx.stroke();
        
        // Ticks
        ctx.beginPath();
        ctx.moveTo(752, y_center);
        ctx.lineTo(768, y_center);
        ctx.moveTo(752, y_center - amp);
        ctx.lineTo(768, y_center - amp);
        ctx.stroke();

        // Amplitude Label Text
        ctx.font = '600 15px Outfit, sans-serif';
        ctx.fillStyle = '#EA4335';
        ctx.textAlign = 'left';
        const ampTextY = y_center - amp / 2 + 5;
        ctx.fillText('Amplitude: ' + Math.round(amp) + ' px', 780, ampTextY);
    }

    function animate(timestamp) {
        if (lastTime === 0) {
            lastTime = timestamp;
        }
        const dt = (timestamp - lastTime) / 1000; // elapsed time in seconds
        lastTime = timestamp;

        // Phase accumulated smoothly based on frequency to avoid sudden jumps when dragging
        phase += 2 * Math.PI * freq * dt;
        phase = phase % (2 * Math.PI);

        draw();

        requestAnimationFrame(animate);
    }

    // Set up listeners
    freqSlider.addEventListener('input', updateValues);
    ampSlider.addEventListener('input', updateValues);

    // Initial setup
    updateValues();
    resize();
    
    // Start animation loop
    requestAnimationFrame(animate);

    // Watch for resize events
    window.addEventListener('resize', () => {
        resize();
        draw();
    });
});
