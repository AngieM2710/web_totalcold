document.addEventListener('DOMContentLoaded', () => {
    const canvas = document.getElementById('canvasprueba');
    if (!canvas) {
        return;
    }
    const ctx = canvas.getContext('2d');
    let waves = [];
    const numWaves = 10; // Aumentamos el número de ondas para un mejor relleno
    const waveSpeed = 0.005; // Velocidad de las ondas
    
    // Colores extraídos y armonizados con tu logo "TOTAL COLD"
    const brandColors = [
        '#00B5E2', 
        '#0F3D6F', 
        '#1A539B', 
        '#4E8ACD', 
        '#87CEEB'
    ];

    function resizeCanvas() {
        canvas.width = window.innerWidth;
        canvas.height = window.innerHeight;
    }

    class Wave {
        constructor(color, amplitude, frequency, offset, startY) {
            this.color = color;
            this.amplitude = amplitude;
            this.frequency = frequency;
            this.offset = offset;
            this.startY = startY; // La posición vertical de inicio de la onda
        }

        draw(time) {
            ctx.beginPath();
            ctx.lineWidth = 1;
            ctx.strokeStyle = this.color;
            ctx.globalAlpha = 0.3; // Opacidad sutil

            const xOffset = this.offset + time;
            for (let i = 0; i < canvas.width; i++) {
                // Se dibuja la onda alrededor de su posición de inicio vertical
                const y = this.startY + Math.sin(i * this.frequency + xOffset) * this.amplitude;
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
        const waveSpacing = canvas.height / numWaves; // Espaciado uniforme entre cada onda
        for (let i = 0; i < numWaves; i++) {
            const color = brandColors[i % brandColors.length];
            const amplitude = 50 + Math.random() * 30;
            const frequency = 0.005 + Math.random() * 0.005;
            const offset = Math.random() * 10;
            const startY = i * waveSpacing; // Posiciona la onda de arriba a abajo
            waves.push(new Wave(color, amplitude, frequency, offset, startY));
        }
    }

    let time = 0;

    function animate() {
        ctx.clearRect(0, 0, canvas.width, canvas.height); // Borra el canvas en cada fotograma
        
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