document.addEventListener('DOMContentLoaded', () => {
    const canvas = document.getElementById('canvasprueba');
    if (!canvas) {
        return;
    }
    const ctx = canvas.getContext('2d');
    let waves = [];
    const numWaves = 5; // Número de ondas
    const waveSpeed = 0.005; // Velocidad de las ondas
    
    // Colores extraídos y armonizados con tu logo "TOTAL COLD"
    const brandColors = [
        '#00B5E2', // El azul cian de "COLD"
        '#0F3D6F', // El azul oscuro principal de "TOTAL"
        '#1A539B', // Un tono de azul de la montaña
        '#4E8ACD', // Otro tono de azul más claro de la montaña
        '#87CEEB'  // Un azul cielo claro para un toque de suavidad
    ];

    function resizeCanvas() {
        canvas.width = window.innerWidth;
        canvas.height = window.innerHeight;
    }

    class Wave {
        constructor(color, amplitude, frequency, offset) {
            this.color = color;
            this.amplitude = amplitude;
            this.frequency = frequency;
            this.offset = offset;
        }

        draw(time) {
            ctx.beginPath();
            ctx.lineWidth = 1;
            ctx.strokeStyle = this.color;
            ctx.globalAlpha = 0.3; // Opacidad sutil para que no sea demasiado dominante

            const xOffset = this.offset + time;
            for (let i = 0; i < canvas.width; i++) {
                const y = canvas.height / 2 + Math.sin(i * this.frequency + xOffset) * this.amplitude;
                if (i === 0) {
                    ctx.moveTo(i, y);
                } else {
                    ctx.lineTo(i, y);
                }
            }
            ctx.stroke();
        }
    }

    function init() {
        waves = [];
        for (let i = 0; i < numWaves; i++) {
            const color = brandColors[i % brandColors.length]; // Usa los colores de tu marca
            const amplitude = 50 + Math.random() * 30;
            const frequency = 0.005 + Math.random() * 0.005;
            const offset = Math.random() * 10;
            waves.push(new Wave(color, amplitude, frequency, offset));
        }
    }

    let time = 0;

    function animate() {
        ctx.clearRect(0, 0, canvas.width, canvas.height);
        
        waves.forEach(wave => {
            wave.draw(time);
        });

        time += waveSpeed;
        
        requestAnimationFrame(animate);
    }

    window.addEventListener('resize', () => {
        resizeCanvas();
        init(); 
    });
    
    resizeCanvas();
    init();
    animate();
});