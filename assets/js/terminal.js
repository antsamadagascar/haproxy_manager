document.addEventListener('DOMContentLoaded', function() {
    const terminalToggle = document.getElementById('terminalToggle');
    const terminalContainer = document.getElementById('terminalContainer');
    const terminalClose = document.getElementById('terminalClose');

    terminalToggle.addEventListener('click', function(e) {
        e.preventDefault();
        terminalContainer.classList.toggle('show');
    });

    terminalClose.addEventListener('click', function() {
        terminalContainer.classList.remove('show');
    });
});

// assets/js/terminal.js
class DockerTerminal {
    constructor(terminalElement) {
        this.terminal = terminalElement;
        this.isRunning = false;
        this.updateInterval = null;
    }

    start() {
        if (this.isRunning) return;
        
        this.isRunning = true;
        this.updateLogs();
        // Mettre à jour les logs toutes les 5 secondes
        this.updateInterval = setInterval(() => this.updateLogs(), 5000);
    }

    stop() {
        this.isRunning = false;
        if (this.updateInterval) {
            clearInterval(this.updateInterval);
            this.updateInterval = null;
        }
    }

    async updateLogs() {
        try {
            const response = await fetch('http://localhost:81/semestre6/haproxy_manager/Docker_logs/get_logs', {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            const data = await response.json();

            if (data.success) {
                this.displayLogs(data.logs);
            } else {
                this.displayError(data.error);
            }
        } catch (error) {
            this.displayError('Erreur de connexion');
        }
    }

    displayLogs(logs) {
        this.terminal.innerHTML = logs
            .map(log => `<div class="log-line">${this.escapeHtml(log)}</div>`)
            .join('');
        
        // Scroll vers le bas
        this.terminal.scrollTop = this.terminal.scrollHeight;
    }

    displayError(message) {
        this.terminal.innerHTML += `<div class="log-error">${this.escapeHtml(message)}</div>`;
    }

    escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }
}

// Initialisation
document.addEventListener('DOMContentLoaded', function() {
    const terminalElement = document.getElementById('terminal');
    const terminal = new DockerTerminal(terminalElement);
    
    // Démarrer/arrêter le terminal quand il est affiché/caché
    document.getElementById('terminalToggle').addEventListener('click', function() {
        const container = document.getElementById('terminalContainer');
        if (container.classList.contains('show')) {
            terminal.start();
        } else {
            terminal.stop();
        }
    });

    document.getElementById('terminalClose').addEventListener('click', function() {
        terminal.stop();
    });
});