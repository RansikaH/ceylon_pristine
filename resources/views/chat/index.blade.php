@extends('layouts.app')
@section('content')
<div class="chat-modern">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-9">
                <!-- Page Header -->
                <div class="chat-header mb-4">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center">
                            <div class="chat-header-icon">
                                <i class="bi bi-chat-dots-fill"></i>
                            </div>
                            <div>
                                <h1 class="chat-title mb-1">Chat with Admin</h1>
                                <p class="chat-subtitle mb-0">Get help and support from our team</p>
                            </div>
                        </div>
                        <a href="{{ route('dashboard') }}" class="btn-back-dash">
                            <i class="bi bi-arrow-left me-2"></i>Dashboard
                        </a>
                    </div>
                </div>

                <div class="chat-card">
                    <!-- Chat Messages Container -->
                    <div id="chat-messages" class="chat-messages">
                        @if($messages->isEmpty())
                            <div class="chat-empty-state">
                                <div class="empty-chat-icon">
                                    <i class="bi bi-chat-square-text"></i>
                                </div>
                                <h4 class="empty-chat-title">No messages yet</h4>
                                <p class="empty-chat-text">Start a conversation with our admin team!</p>
                            </div>
                        @else
                            @foreach($messages as $message)
                                <div class="message {{ $message->sender_id === Auth::id() ? 'sent' : 'received' }}" data-message-id="{{ $message->id }}">
                                    <div class="d-flex {{ $message->sender_id === Auth::id() ? 'justify-content-end' : 'justify-content-start' }}">
                                        <div class="message-bubble {{ $message->sender_id === Auth::id() ? 'sent-bubble' : 'received-bubble' }}">
                                            <p class="message-text">{{ $message->message }}</p>
                                            <div class="message-meta">
                                                <span class="message-time">{{ $message->created_at->format('H:i') }}</span>
                                                @if($message->sender_id === Auth::id() && $message->is_read)
                                                    <i class="bi bi-check-all ms-1"></i>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>
                    
                    <!-- Message Input Form -->
                    <div class="chat-input">
                        <form id="message-form">
                            @csrf
                            <div class="input-wrapper">
                                <input type="text" id="message-input" class="message-input-field" placeholder="Type your message..." maxlength="1000" required>
                                <button type="submit" class="btn-send" id="send-btn">
                                    <i class="bi bi-send-fill"></i>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
/* Chat Modern Styles */
.chat-modern {
    background: #f8f9fa;
    min-height: calc(100vh - 200px);
    padding-top: 2rem;
    padding-bottom: 2rem;
}

/* Page Header */
.chat-header {
    background: white;
    padding: 2rem;
    border-radius: 16px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
    border: 1px solid #e9ecef;
}

.chat-header-icon {
    width: 70px;
    height: 70px;
    border-radius: 50%;
    background: linear-gradient(135deg, #50946c 0%, #3d7556 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 36px;
    margin-right: 1.5rem;
    box-shadow: 0 4px 12px rgba(80, 148, 108, 0.25);
}

.chat-title {
    font-size: 2rem;
    font-weight: 700;
    color: #2c3e50;
}

.chat-subtitle {
    color: #6c757d;
    font-size: 1rem;
}

.btn-back-dash {
    display: inline-flex;
    align-items: center;
    padding: 0.75rem 1.5rem;
    background: white;
    color: #6c757d;
    border: 2px solid #dee2e6;
    border-radius: 8px;
    font-weight: 600;
    font-size: 0.9375rem;
    text-decoration: none;
    transition: all 0.3s ease;
}

.btn-back-dash:hover {
    background: #f8f9fa;
    color: #495057;
    border-color: #adb5bd;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

/* Chat Card */
.chat-card {
    background: white;
    border-radius: 16px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
    border: 1px solid #e9ecef;
    overflow: hidden;
    display: flex;
    flex-direction: column;
    height: 600px;
}

/* Chat Messages */
.chat-messages {
    flex: 1;
    overflow-y: auto;
    padding: 2rem;
    background: linear-gradient(to bottom, #f8f9fa 0%, #e9ecef 100%);
}

/* Empty State */
.chat-empty-state {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    height: 100%;
    padding: 3rem 2rem;
}

.empty-chat-icon {
    width: 100px;
    height: 100px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 50px;
    margin-bottom: 1.5rem;
    box-shadow: 0 8px 20px rgba(102, 126, 234, 0.3);
}

.empty-chat-title {
    font-size: 1.5rem;
    font-weight: 700;
    color: #2c3e50;
    margin-bottom: 0.5rem;
}

.empty-chat-text {
    color: #6c757d;
    font-size: 1rem;
}

/* Message Bubbles */
.message {
    margin-bottom: 1rem;
    animation: fadeInUp 0.3s ease-out;
}

.message-bubble {
    max-width: 70%;
    padding: 1rem 1.25rem;
    border-radius: 16px;
    word-wrap: break-word;
    position: relative;
}

.sent-bubble {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border-bottom-right-radius: 4px;
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
}

.received-bubble {
    background: white;
    color: #2c3e50;
    border-bottom-left-radius: 4px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    border: 1px solid #e9ecef;
}

.message-text {
    margin: 0 0 0.5rem 0;
    font-size: 0.9375rem;
    line-height: 1.5;
}

.message-meta {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.sent-bubble .message-meta {
    color: rgba(255, 255, 255, 0.8);
}

.received-bubble .message-meta {
    color: #6c757d;
}

.message-time {
    font-size: 0.75rem;
    font-weight: 500;
}

/* Chat Input */
.chat-input {
    padding: 1.5rem;
    background: white;
    border-top: 1px solid #e9ecef;
}

.input-wrapper {
    display: flex;
    gap: 1rem;
    align-items: center;
}

.message-input-field {
    flex: 1;
    padding: 1rem 1.5rem;
    border: 2px solid #e9ecef;
    border-radius: 50px;
    font-size: 0.9375rem;
    transition: all 0.3s ease;
}

.message-input-field:focus {
    outline: none;
    border-color: #667eea;
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
}

.btn-send {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border: none;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.25rem;
    transition: all 0.3s ease;
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
    cursor: pointer;
}

.btn-send:hover {
    background: linear-gradient(135deg, #5568d3 0%, #63408b 100%);
    transform: scale(1.1);
    box-shadow: 0 6px 16px rgba(102, 126, 234, 0.4);
}

.btn-send:disabled {
    opacity: 0.6;
    cursor: not-allowed;
    transform: none;
}

/* Scrollbar Styling */
.chat-messages::-webkit-scrollbar {
    width: 8px;
}

.chat-messages::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 10px;
}

.chat-messages::-webkit-scrollbar-thumb {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 10px;
}

.chat-messages::-webkit-scrollbar-thumb:hover {
    background: linear-gradient(135deg, #5568d3 0%, #63408b 100%);
}

/* Typing Indicator */
.typing-indicator {
    display: none;
    padding: 0.75rem 1rem;
    background: white;
    border-radius: 16px;
    border-bottom-left-radius: 4px;
    margin: 1rem 0;
    width: fit-content;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.typing-indicator span {
    display: inline-block;
    width: 8px;
    height: 8px;
    border-radius: 50%;
    background: #667eea;
    margin: 0 2px;
    animation: typing 1.4s infinite;
}

.typing-indicator span:nth-child(2) {
    animation-delay: 0.2s;
}

.typing-indicator span:nth-child(3) {
    animation-delay: 0.4s;
}

@keyframes typing {
    0%, 60%, 100% {
        transform: translateY(0);
        opacity: 0.5;
    }
    30% {
        transform: translateY(-10px);
        opacity: 1;
    }
}

/* Animations */
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.chat-header,
.chat-card {
    animation: fadeInUp 0.5s ease-out;
}

.chat-card {
    animation-delay: 0.1s;
}

/* Responsive Design */
@media (max-width: 768px) {
    .chat-modern {
        padding-top: 1rem;
        padding-bottom: 1rem;
    }
    
    .chat-header {
        padding: 1.5rem;
    }
    
    .chat-header .d-flex.justify-content-between {
        flex-direction: column;
        gap: 1rem;
    }
    
    .chat-header-icon {
        width: 60px;
        height: 60px;
        font-size: 30px;
        margin-right: 1rem;
    }
    
    .chat-title {
        font-size: 1.5rem;
    }
    
    .btn-back-dash {
        align-self: flex-start;
    }
    
    .chat-card {
        height: 500px;
    }
    
    .chat-messages {
        padding: 1.5rem;
    }
    
    .message-bubble {
        max-width: 85%;
        padding: 0.875rem 1rem;
    }
    
    .chat-input {
        padding: 1rem;
    }
    
    .message-input-field {
        padding: 0.875rem 1.25rem;
        font-size: 0.875rem;
    }
    
    .btn-send {
        width: 45px;
        height: 45px;
        font-size: 1.125rem;
    }
}
</style>
@endpush

<script>
document.addEventListener('DOMContentLoaded', function() {
    const chatMessages = document.getElementById('chat-messages');
    const messageForm = document.getElementById('message-form');
    const messageInput = document.getElementById('message-input');
    const sendBtn = document.getElementById('send-btn');
    
    // Scroll to bottom on load
    scrollToBottom();
    
    // Handle message submission
    messageForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const message = messageInput.value.trim();
        if (!message) return;
        
        // Disable send button
        sendBtn.disabled = true;
        sendBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>Sending...';
        
        // Send message via AJAX
        fetch('{{ route("chat.send") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({
                message: message
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Add message to chat
                addMessageToChat(data.message, true);
                messageInput.value = '';
                scrollToBottom();
            } else {
                showToast('Error sending message', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showToast('Error sending message', 'error');
        })
        .finally(() => {
            // Re-enable send button
            sendBtn.disabled = false;
            sendBtn.innerHTML = '<i class="bi bi-send-fill"></i> Send';
            messageInput.focus();
        });
    });
    
    // Auto-refresh messages every 3 seconds
    setInterval(function() {
        fetchMessages();
    }, 3000);
    
    function fetchMessages() {
        fetch('{{ route("chat.messages") }}')
            .then(response => response.json())
            .then(data => {
                if (data.messages && data.messages.length > 0) {
                    updateChatMessages(data.messages);
                }
            })
            .catch(error => {
                console.error('Error fetching messages:', error);
            });
    }
    
    function addMessageToChat(message, isSent = false) {
        const messageHtml = `
            <div class="message ${isSent ? 'sent' : 'received'}" data-message-id="${message.id}">
                <div class="d-flex ${isSent ? 'justify-content-end' : 'justify-content-start'}">
                    <div class="message-bubble ${isSent ? 'sent-bubble' : 'received-bubble'}">
                        <p class="message-text">${message.message}</p>
                        <div class="message-meta">
                            <span class="message-time">${new Date().toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit', hour12: false })}</span>
                        </div>
                    </div>
                </div>
            </div>
        `;
        
        chatMessages.insertAdjacentHTML('beforeend', messageHtml);
    }
    
    function updateChatMessages(messages) {
        const existingMessageIds = Array.from(document.querySelectorAll('.message[data-message-id]'))
            .map(el => el.dataset.messageId)
            .filter(id => id !== undefined);
        
        messages.forEach(message => {
            if (!existingMessageIds.includes(message.id.toString())) {
                const isSent = message.sender_id === {{ Auth::id() }};
                addMessageToChat(message, isSent);
            }
        });
        
        scrollToBottom();
    }
    
    function scrollToBottom() {
        chatMessages.scrollTop = chatMessages.scrollHeight;
    }
    
    // Focus on input field
    messageInput.focus();
});
</script>
@endsection
