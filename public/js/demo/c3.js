document.addEventListener('DOMContentLoaded', () => {
    const canvas = document.getElementById('canvasprueba');
    if (!canvas) {
        return;
    }
    const ctx = canvas.getContext('2d');
    
    const numLines = 8;
    const speed = 0.003;
    const amplitude = 150;
    const frequency = 0.008;

    // Colores de tu marca para las líneas
    const brandColors = [
        '#00B5E2', // Azul cian
        '#0F3D6F', // Azul oscuro
        '#1A539B', // Azul medio
        '#4E8ACD'  // Azul claro
    ];

    let time = 0;
    
    function resizeCanvas() {
        canvas.width = window.innerWidth;
        canvas.height = window.innerHeight;
    }

    function draw() {
        ctx.clearRect(0, 0, canvas.width, canvas.height);
        
        for (let i = 0; i < numLines; i++) {
            ctx.beginPath();
            ctx.strokeStyle = brandColors[i % brandColors.length];
            ctx.lineWidth = 1.5;
            ctx.globalAlpha = 0.2; // Opacidad baja para un efecto sutil
            
            const lineOffset = i * (canvas.height / (numLines - 1));
            const yOffset = Math.sin(time + i) * 50; // Pequeño desplazamiento vertical para el movimiento

            for (let x = 0; x <= canvas.width; x++) {
                const y = Math.sin(x * frequency + time * 1.5) * amplitude + lineOffset + yOffset;
                if (x === 0) {
                    ctx.moveTo(x, y);
                } else {
                    ctx.lineTo(x, y);
                }
            }
            ctx.stroke();
        }
        
        time += speed;
        requestAnimationFrame(draw);
    }

    window.addEventListener('resize', () => {
        resizeCanvas();
        // No es necesario reiniciar el loop, solo la posición
    });
    
    resizeCanvas();
    draw();
});