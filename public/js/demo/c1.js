document.addEventListener('DOMContentLoaded', () => {
    const canvas = document.getElementById('canvasprueba');
    if (!canvas) {
        return;
    }
    const ctx = canvas.getContext('2d');
    let points = [];
    const gridSize = 40; // Espaciado entre los puntos
    const connectDistance = 100; // Distancia máxima para conectar puntos
    const pointSize = 1;

    function resizeCanvas() {
        canvas.width = window.innerWidth;
        canvas.height = window.innerHeight;
    }

    class Point {
        constructor(x, y) {
            this.x = x;
            this.y = y;
            this.vx = (Math.random() - 0.5) * 0.2;
            this.vy = (Math.random() - 0.5) * 0.2;
            this.color = '#B0E0E6'; // Azul turquesa suave
        }

        update() {
            this.x += this.vx;
            this.y += this.vy;
            if (this.x < 0 || this.x > canvas.width) this.vx *= -1;
            if (this.y < 0 || this.y > canvas.height) this.vy *= -1;
        }

        draw() {
            ctx.beginPath();
            ctx.arc(this.x, this.y, pointSize, 0, Math.PI * 2);
            ctx.fillStyle = this.color;
            ctx.fill();
        }
    }

    function drawMesh() {
        ctx.strokeStyle = '#DCDCDC'; // Gris claro
        ctx.lineWidth = 0.2;
        ctx.globalAlpha = 0.6; // Opacidad sutil

        for (let i = 0; i < points.length; i++) {
            const p1 = points[i];

            // Conecta con los 8 puntos más cercanos
            for (let j = i + 1; j < points.length; j++) {
                const p2 = points[j];
                const dx = p1.x - p2.x;
                const dy = p1.y - p2.y;
                const distance = Math.sqrt(dx * dx + dy * dy);

                if (distance < connectDistance) {
                    ctx.beginPath();
                    ctx.moveTo(p1.x, p1.y);
                    ctx.lineTo(p2.x, p2.y);
                    ctx.stroke();
                }
            }
        }
    }

    function init() {
        points = [];
        const numX = Math.floor(canvas.width / gridSize);
        const numY = Math.floor(canvas.height / gridSize);
        for (let i = 0; i <= numX; i++) {
            for (let j = 0; j <= numY; j++) {
                points.push(new Point(i * gridSize, j * gridSize));
            }
        }
    }

    function animate() {
        ctx.clearRect(0, 0, canvas.width, canvas.height);
        drawMesh();
        points.forEach(point => {
            point.update();
            point.draw();
        });
        requestAnimationFrame(animate);
    }

    window.addEventListener('resize', () => {
        resizeCanvas();
        init(); // Reinicia la malla al redimensionar
    });
    
    resizeCanvas();
    init();
    animate();
});