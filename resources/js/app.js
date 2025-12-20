


import './bootstrap';
import * as bootstrap from 'bootstrap';

// Initialize all tooltips
var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
    return new bootstrap.Tooltip(tooltipTriggerEl)
})

// Initialize all popovers
var popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'))
var popoverList = popoverTriggerList.map(function (popoverTriggerEl) {
    return new bootstrap.Popover(popoverTriggerEl)
})

// AI Concierge functionality
if (document.getElementById('aiModal')) {
    const aiModal = new bootstrap.Modal(document.getElementById('aiModal'));
    
    // Open modal triggers
    document.querySelectorAll('[data-bs-toggle="ai-modal"]').forEach(button => {
        button.addEventListener('click', () => {
            aiModal.show();
        });
    });
}

// Chat functionality
window.sendMessage = async function() {
    const userInput = document.getElementById('userInput');
    const chatWindow = document.getElementById('chatWindow');
    const userText = userInput.value.trim();
    
    if (!userText) return;
    
    // Add user message
    const userMsg = document.createElement('div');
    userMsg.className = 'd-flex justify-content-end mb-3';
    userMsg.innerHTML = `
        <div class="bg-primary text-white rounded-4 p-3" style="max-width: 80%">
            ${userText}
        </div>
    `;
    chatWindow.appendChild(userMsg);
    
    // Clear input
    userInput.value = '';
    
    // Show loading
    const loadingMsg = document.createElement('div');
    loadingMsg.className = 'mb-3';
    loadingMsg.innerHTML = `
        <div class="bg-light rounded-4 p-3" style="max-width: 80%">
            <div class="spinner-border spinner-border-sm text-primary me-2" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            Aura is thinking...
        </div>
    `;
    chatWindow.appendChild(loadingMsg);
    chatWindow.scrollTop = chatWindow.scrollHeight;
    
    try {
        // Send to backend
        const response = await fetch('/ai/recommend', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ query: userText })
        });
        
        const data = await response.json();
        
        // Remove loading message
        chatWindow.removeChild(loadingMsg);
        
        // Add AI response
        const aiMsg = document.createElement('div');
        aiMsg.className = 'mb-3';
        aiMsg.innerHTML = `
            <div class="bg-light rounded-4 p-3" style="max-width: 80%">
                <strong>👑 Aura:</strong> ${data.analysis || "Here are my recommendations based on your query!"}
                ${data.products ? '<div class="mt-2"><small class="text-muted">Showing ' + data.products.length + ' matching items</small></div>' : ''}
            </div>
        `;
        chatWindow.appendChild(aiMsg);
        
        // Scroll to bottom
        chatWindow.scrollTop = chatWindow.scrollHeight;
        
    } catch (error) {
        console.error('Error:', error);
        chatWindow.removeChild(loadingMsg);
        
        const errorMsg = document.createElement('div');
        errorMsg.className = 'mb-3';
        errorMsg.innerHTML = `
            <div class="bg-danger text-white rounded-4 p-3" style="max-width: 80%">
                Sorry, I encountered an error. Please try again.
            </div>
        `;
        chatWindow.appendChild(errorMsg);
    }
};
