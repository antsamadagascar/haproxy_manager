<style>
.terminal-container {
    position: fixed;
    bottom: 0;
    left: 0;
    right: 0;
    z-index: 1030;
    height: 500px;
    background-color: #212529;
    border-top: 1px solid #444;
    transition: transform 0.3s ease-in-out;
    transform: translateY(100%);
}

.terminal-container.show {
    transform: translateY(0);
}

.terminal-header {
    padding: 0.5rem 1rem;
    background-color: #2c3034;
    border-bottom: 1px solid #444;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.terminal-content {
    height: calc(100% - 40px);
    padding: 1rem;
    overflow-y: auto;
    color: #fff;
    font-family: 'Consolas', 'Monaco', monospace;
}

.terminal-close {
    background: none;
    border: none;
    color: #fff;
    cursor: pointer;
}

.main-content {
    margin-bottom: 300px;
}
</style>

<div class="terminal-container" id="terminalContainer">
    <div class="terminal-header">
        <span class="text-light">haproxy log</span>
        <button class="terminal-close" id="terminalClose">
            <i class="bi bi-x-lg"></i>
        </button>
    </div>
    <div class="terminal-content" id="terminal">
        <!-- Le contenu du terminal sera ajouté ici -->
    </div>
</div>