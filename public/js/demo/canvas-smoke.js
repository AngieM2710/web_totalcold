const canvas = document.getElementById("bubbleCanvas");
const ctx = canvas.getContext("2d");

canvas.width = window.innerWidth;
canvas.height = window.innerHeight;

let bubblesArray = [];

class Bubble {
    constructor(x, y, size, speedX, speedY) {
        this.x = x;
        this.y = y;
        this.size = size;
        this.speedX = speedX;
        this.speedY = speedY;
        this.alpha = Math.random() * 0.3 + 0.1; // Transparencia
    }

    update() {
        this.x += this.speedX;
        this.y += this.speedY;

        // Reciclaje cuando la burbuja sale por abajo
        if (this.y - this.size > canvas.height) {
            this.y = -this.size; // Posición arriba
            this.x = Math.random() * canvas.width; // Posición aleatoria
        }

        // Reciclaje cuando la burbuja sale por el lado
        if (this.x - this.size > canvas.width) {
            this.x = -this.size; // Posición aleatoria desde la izquierda
        }
    }

    draw() {
        ctx.fillStyle = `rgba(25, 255, 255, ${this.alpha})`; // Color blanco con transparencia
        ctx.beginPath();
        ctx.arc(this.x, this.y, this.size, 0, Math.PI * 2);
        ctx.fill();
    }
}

function init() {
    bubblesArray = [];
    for (let i = 0; i < 100; i++) {
        let size = Math.random() * 20 + 5; // Tamaño aleatorio para las burbujas
        let x = Math.random() * canvas.width;
        let y = Math.random() * canvas.height;
        let speedX = (Math.random() - 0.5) * 0.5;
        let speedY = Math.random() * 0.5 + 0.1;
        bubblesArray.push(new Bubble(x, y, size, speedX, speedY));
    }
}

function animate() {
    ctx.clearRect(0, 0, canvas.width, canvas.height); // Limpiar el canvas en cada ciclo

    for (let bubble of bubblesArray) {
        bubble.update();
        bubble.draw();
    }

    requestAnimationFrame(animate); // Llamar a la siguiente iteración de la animación
}

init();
animate();

window.addEventListener("resize", () => {
    canvas.width = window.innerWidth;
    canvas.height = window.innerHeight;
    init(); // Reiniciar las burbujas con las nuevas dimensiones
});